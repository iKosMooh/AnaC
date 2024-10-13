<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Seleção de Professor e Aulas</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <label for="professores">Selecione um Professor:</label>
    <select name="professores" id="professores">
        <option value="disabled" disabled selected>Selecione um Professor</option>
        <!-- As opções do professor serão geradas aqui -->
        <?php
        // Incluindo o arquivo de conexão
        require '../PHP/connect.php';

        // Preparar a consulta para obter professores
        $sql = "SELECT ID_Professor, Nome FROM Professores";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' . $row['ID_Professor'] . '">' . $row['Nome'] . '</option>';
        }
        ?>
    </select>

    <br><br>

    <label for="aulas">Aulas Não Ministradas:</label>
    <select name="aulas" id="aulas">
        <option value="">Selecione uma aula</option>
        <!-- As opções de aula serão geradas aqui -->
    </select>

    <br><br>

    <!-- Os campos de horário e formulário serão inseridos aqui -->
    <div id="formulario_reposicao"></div>

    <script>
    $(document).ready(function() {
        // Quando selecionar o professor, buscar as aulas
        $('#professores').change(function() {
            var idProfessor = $(this).val();

            // Limpar as aulas anteriores e formulário
            $('#aulas').empty().append('<option value="">Selecione uma aula</option>');
            $('#formulario_reposicao').empty();

            if (idProfessor) {
                // Fazer a requisição AJAX para buscar aulas
                $.ajax({
                    url: '../php/buscar_aulas.php',
                    type: 'GET',
                    data: { id: idProfessor },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            alert(data.error); // Mostrar erro se houver
                        } else {
                            // Adicionar as aulas ao select
                            $.each(data, function(index, aula) {
                                $('#aulas').append('<option value="' + aula.ID_Aula + '">' + aula.Data_Time + ' - ' + aula.Nome_Materia + ' - ' + aula.Nome_Curso + '</option>');
                            });
                        }
                    },
                    error: function() {
                        alert('Erro ao buscar as aulas.');
                    }
                });
            }
        });

        // Quando selecionar a aula, buscar os horários e exibir o formulário
        $('#aulas').change(function() {
            var idAula = $(this).val();

            // Limpar o formulário anterior
            $('#formulario_reposicao').empty();

            if (idAula) {
                // Fazer a requisição AJAX para buscar horários e gerar o formulário
                $.ajax({
                    url: '../php/buscar_horarios.php',
                    type: 'GET',
                    data: { id_aula: idAula },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            alert(data.error); // Mostrar erro se houver
                        } else {
                            // Gerar o formulário com os horários e campos para reposição
                            var formulario = `
                                <label for="horario_inicio">Horário de Início:</label>
                                <input type="text" id="horario_inicio" value="${data.Horario_Inicio}" disabled><br><br>

                                <label for="horario_final">Horário Final:</label>
                                <input type="text" id="horario_final" value="${data.Horario_Termino}" disabled><br><br>

                                <label for="data_reposicao">Escolha uma Data de Reposição:</label>
                                <input type="date" id="data_reposicao" required><br><br>

                                <label for="mensagem">Mensagem:</label>
                                <textarea id="mensagem" rows="4" cols="50" required></textarea><br><br>

                                <button id="enviar_reposicao">Solicitar Reposição</button>
                            `;
                            $('#formulario_reposicao').html(formulario);

                            // Adicionar evento para envio do formulário
                            $('#enviar_reposicao').click(function() {
                                var dataReposicao = $('#data_reposicao').val();
                                var mensagem = $('#mensagem').val();

                                // Fazer a requisição AJAX para enviar a reposição
                                $.ajax({
                                    url: '../php/enviar_reposicao.php',
                                    type: 'POST',
                                    data: {
                                        id_aula_nao_ministrada: idAula,
                                        data_reposicao: dataReposicao,
                                        mensagem: mensagem
                                    },
                                    success: function(response) {
                                        alert('Reposição enviada com sucesso!');
                                    },
                                    error: function() {
                                        alert('Erro ao enviar a reposição.');
                                    }
                                });
                            });
                        }
                    },
                    error: function() {
                        alert('Erro ao buscar os horários.');
                    }
                });
            }
        });
    });
    </script>

</body>
</html>
