<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleção de Professor e Aulas</title>
    <link rel="stylesheet" href="../css/formC.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php include_once('header.php');?>
</head>
<body>
<div class="container">
    <label for="professores">Selecione um Professor:</label>
    <select name="professores" id="professores">
        <option value="disabled" disabled selected>Selecione um Professor</option>
        <?php
        require '../PHP/connect.php';
        $sql = "SELECT ID_Professor, Nome FROM Professores";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' . $row['ID_Professor'] . '">' . $row['Nome'] . '</option>';
        }
        ?>
    </select>

    <label for="aulas">Aulas Não Ministradas:</label>
    <select name="aulas" id="aulas">
        <option value="">Selecione uma aula</option>
    </select>

    <div id="formulario_reposicao"></div>
</div>

<?php include_once 'footer.php'; ?>

<script>
$(document).ready(function() {
    function preencherConteudoSimulado() {
        $('#aulas').append('<option value="1">2024-09-20 14:00 - Matemática - Curso A</option>');
        var formulario = `
            <label for="horario_inicio">Horário de Início:</label>
            <input type="text" id="horario_inicio" value="14:00" disabled><br><br>
            <label for="horario_final">Horário Final:</label>
            <input type="text" id="horario_final" value="16:00" disabled><br><br>
            <label for="data_reposicao">Escolha uma Data de Reposição:</label>
            <input type="date" id="data_reposicao" required><br><br>
            <label for="mensagem">Mensagem:</label>
            <textarea id="mensagem" rows="4" cols="50" required>Mensagem padrão</textarea><br><br>
            <button id="enviar_reposicao">Solicitar Reposição</button>
        `;
        $('#formulario_reposicao').html(formulario);
    }

    preencherConteudoSimulado();

    $('#professores').change(function() {
        var idProfessor = $(this).val();
        $('#aulas').empty().append('<option value="">Selecione uma aula</option>');
        $('#formulario_reposicao').empty();

        if (idProfessor) {
            $.ajax({
                url: '../php/buscar_aulas.php',
                type: 'GET',
                data: { id: idProfessor },
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        $.each(data, function(index, aula) {
                            $('#aulas').append('<option value="' + aula.ID_Aula + '">' + aula.Date_Time + ' - ' + aula.Nome_Materia + ' - ' + aula.Nome_Curso + '</option>');
                        });
                    }
                },
                error: function() {
                    alert('Erro ao buscar as aulas.');
                }
            });
        }
    });

    $('#aulas').change(function() {
        var idAula = $(this).val();
        $('#formulario_reposicao').empty();

        if (idAula) {
            $.ajax({
                url: '../php/buscar_horarios.php',
                type: 'GET',
                data: { id_aula: idAula },
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                    } else {
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

                        $('#enviar_reposicao').click(function() {
                            var dataReposicao = $('#data_reposicao').val();
                            var mensagem = $('#mensagem').val();

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
