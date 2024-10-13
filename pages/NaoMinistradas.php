<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['tipo'])) {
    // Redirecionar para a página de login
    header('Location: login.php');
    exit();
}

// Verificar se o tipo de usuário é professor
if ($_SESSION['tipo'] != 'professor') {
    echo "Acesso negado. Apenas professores podem acessar esta página.";
    exit();
}

// Pegar o ID do professor da sessão
$id_professor = $_SESSION['id'];

//---------------------------------------------------------------------------------------------------------------

require '../PHP/connect.php'; // Arquivo de conexão com o banco de dados

// Consulta para buscar as aulas que o professor pode substituir
$sql = "SELECT 
    Aula.ID_Aula, 
    Aula.Horario_Inicio, 
    Aula.Horario_Termino,
    Aula.ID_Materia, 
    Materias.Nome AS Nome_Materia, 
    CursoAtivo.nome AS Nome_Curso 
FROM Aula
INNER JOIN Materias ON Aula.ID_Materia = Materias.ID_Materia
INNER JOIN CursoAtivo ON Materias.ID_Curso = CursoAtivo.ID_Curso
INNER JOIN Professores_Cursos ON CursoAtivo.ID_Curso = Professores_Cursos.ID_Curso
WHERE Professores_Cursos.ID_Professor = :id_professor
";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_professor', $id_professor, PDO::PARAM_INT);
$stmt->execute();
$aulas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Aula Não Ministrada</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="formContainer">
            <h1>Cadastro de Aulas Não Ministradas</h1>

            <form id="reposicaoForm" method="post">
                <input type="hidden" name="id_professor" value="<?php echo $id_professor; ?>">

                <div class="inputlabel">
                    <label for="aulasNaoMinistradas">Dados da(s) aula(s) não ministrada(s)</label>
                    <table>
                        <thead>
                            <tr>
                                <th>Ordem</th>
                                <th>Data da Aula Não Ministrada</th>
                                <th>Aula</th>
                                <th>Horário Início</th>
                                <th>Horário Término</th>
                                <th>Disciplina</th>
                                <th>Observação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="aulaRow">
                                <td class="ordem">1</td>
                                <td><input type="date" name="dataAula[]"></td>

                                <td>
                                    <select name="id_aula[]" class="select-aula">
                                        <option value="">Selecione a aula</option>
                                        <?php foreach ($aulas as $aula): ?>
                                            <option value="<?php echo $aula['ID_Aula']; ?>"
                                                data-horario-inicio="<?php echo $aula['Horario_Inicio']; ?>"
                                                data-horario-termino="<?php echo $aula['Horario_Termino']; ?>"
                                                data-nome-disciplina="<?php echo $aula['Nome_Materia']; ?>"
                                                data-id-materia="<?php echo $aula['ID_Materia']; ?>"> <!-- ID_Materia -->
                                                <?php echo $aula['Nome_Curso'] . ' - ' . $aula['Nome_Materia']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>

                                <td><input type="text" class="horario-inicio" disabled></td>
                                <td><input type="text" class="horario-termino" disabled></td>
                                <td><input type="text" class="nome-disciplina" disabled></td>
                                <td><input type="text" name="observacaoAula[]" placeholder="Motivo da aula não ministrada"></td>
                                <input type="hidden" id="ID_Materia" name="ID_Materia" value="ID_Materia">
                            </tr>
                        </tbody>
                    </table>

                    <button type="button" id="adicionarAulaBtn">Adicionar Aula</button>
                </div>

                <button type="button" id="enviarAulasBtn">Enviar Aulas</button>
            </form>
        </div>
    </div>

    <script>
        let ordem = 1;

        $('#adicionarAulaBtn').click(function() {
            ordem++;
            const novaLinha = `
                <tr class="aulaRow">
                    <td class="ordem">` + ordem + `</td>
                    <td><input type="date" name="dataAula[]"></td>
                    <td>
                        <select name="id_aula[]" class="select-aula">
                            <option value="">Selecione a aula</option>
                            <?php foreach ($aulas as $aula): ?>
                                <option value="<?php echo $aula['ID_Aula']; ?>" 
                                        data-horario-inicio="<?php echo $aula['Horario_Inicio']; ?>"
                                        data-horario-termino="<?php echo $aula['Horario_Termino']; ?>"
                                        data-nome-disciplina="<?php echo $aula['Nome_Materia']; ?>"
                                        data-id-materia="<?php echo $aula['ID_Materia']; ?>"> <!-- ID_Materia -->
                                    <?php echo $aula['Nome_Curso'] . ' - ' . $aula['Nome_Materia']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="text" class="horario-inicio" disabled></td>
                    <td><input type="text" class="horario-termino" disabled></td>
                    <td><input type="text" class="nome-disciplina" disabled></td>
                    <td><input type="text" name="observacaoAula[]" placeholder="Motivo da aula não ministrada"></td>
                </tr>`;
            $('tbody').append(novaLinha);
        });

        $(document).on('change', '.select-aula', function() {
            const selectedOption = $(this).find(':selected');
            const horarioInicio = selectedOption.data('horario-inicio');
            const horarioTermino = selectedOption.data('horario-termino');
            const nomeDisciplina = selectedOption.data('nome-disciplina');
            const idMateria = selectedOption.data('id-materia'); // Captura o ID da matéria

            const row = $(this).closest('.aulaRow');
            row.find('.horario-inicio').val(horarioInicio);
            row.find('.horario-termino').val(horarioTermino);
            row.find('.nome-disciplina').val(nomeDisciplina);
            row.data('id-materia', idMateria); // Armazena o ID da matéria na linha
        });

        $('#enviarAulasBtn').click(function() {
            $('.aulaRow').each(function() {
                const row = $(this);
                const dataAula = row.find('input[name="dataAula[]"]').val();
                const idAula = row.find('select[name="id_aula[]"]').val();
                const observacao = row.find('input[name="observacaoAula[]"]').val();
                const idMateria = row.data('id-materia'); // Recupera o ID da matéria da linha

                if (dataAula && idAula && observacao && idMateria) { // Verifica se idMateria não é undefined
                    $.ajax({
                        url: '../php/Nao_Ministrada_Cadastro.php',
                        type: 'POST',
                        data: {
                            id_professor: $('input[name="id_professor"]').val(),
                            dataAula: dataAula,
                            id_aula: idAula,
                            observacaoAula: observacao,
                            id_materia: idMateria // Enviando o ID_Materia
                        },
                        success: function(response) {
                            console.log(response); // Verifica o que está retornando
                            const jsonResponse = JSON.parse(response); // Tenta parsear a resposta
                            alert(jsonResponse.message); // Acessa a mensagem
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                } else {
                    alert('Por favor, preencha todos os campos obrigatórios.');
                }
            });
        });
    </script>
</body>

</html>