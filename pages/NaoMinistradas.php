<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['tipo'])) {
    header('Location: login.php');
    exit();
}

// Pegar o ID do professor da sessão
$id_professor = $_SESSION['id'];
$is_coordenador = ($_SESSION['tipo'] == 'coordenador');

// Conexão ao banco de dados
require '../PHP/connect.php';

// Função para buscar aulas
function buscarAulas($pdo, $id_professor)
{
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
    WHERE Professores_Cursos.ID_Professor = :id_professor";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_professor', $id_professor, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$aulas = buscarAulas($pdo, $id_professor); // Corrigido para usar $id_professor corretamente

// Consulta para buscar todos os professores, se o usuário for coordenador
if ($is_coordenador) {
    $sql_professores = "SELECT ID_Professor, Nome FROM Professores";
    $stmt_professores = $pdo->prepare($sql_professores);
    $stmt_professores->execute();
    $professores = $stmt_professores->fetchAll(PDO::FETCH_ASSOC);
} else {
    $aulas = buscarAulas($pdo, $id_professor);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Aula Não Ministrada</title>
    <link rel="stylesheet" href="../css/naoministrada.css"> <!-- CSS para estilização -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="formContainer">
            <h1>Cadastro de Aulas Não Ministradas</h1>

            <form id="reposicaoForm" method="post">
                <input type="hidden" id="id_professor" name="id_professor" value="<?php echo $id_professor; ?>">

                <?php if ($is_coordenador): ?>
                    <div>
                        <label for="selectProfessor">Selecione o Professor:</label>
                        <select id="selectProfessor" name="id_professor">
                            <option value="">Selecione um professor</option>
                            <?php foreach ($professores as $professor): ?>
                                <option value="<?php echo $professor['ID_Professor']; ?>"><?php echo $professor['Nome']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

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
                                                data-id-materia="<?php echo $aula['ID_Materia']; ?>">
                                                <?php echo $aula['Nome_Curso'] . ' - ' . $aula['Nome_Materia']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input type="text" class="horario-inicio" disabled></td>
                                <td><input type="text" class="horario-termino" disabled></td>
                                <td><input type="text" class="nome-disciplina" disabled></td>
                                <td><input type="text" name="observacaoAula[]" placeholder="Motivo da aula não ministrada"></td>
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
                    <td class="ordem">${ordem}</td>
                    <td><input type="date" name="dataAula[]"></td>
                    <td>
                        <select name="id_aula[]" class="select-aula">
                            <option value="">Selecione a aula</option>
                            <?php foreach ($aulas as $aula): ?>
                                <option value="<?php echo $aula['ID_Aula']; ?>" 
                                        data-horario-inicio="<?php echo $aula['Horario_Inicio']; ?>"
                                        data-horario-termino="<?php echo $aula['Horario_Termino']; ?>"
                                        data-nome-disciplina="<?php echo $aula['Nome_Materia']; ?>"
                                        data-id-materia="<?php echo $aula['ID_Materia']; ?>">
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
            const selectedOption = $(this).find('option:selected');
            $(this).closest('tr').find('.horario-inicio').val(selectedOption.data('horario-inicio'));
            $(this).closest('tr').find('.horario-termino').val(selectedOption.data('horario-termino'));
            $(this).closest('tr').find('.nome-disciplina').val(selectedOption.data('nome-disciplina'));
        });

        // Atualizar aulas ao mudar o professor
        $('#selectProfessor').change(function() {
            const idProfessorSelecionado = $(this).val();
            $('#id_professor').val(idProfessorSelecionado);
            if (idProfessorSelecionado) {
                $.ajax({
                    url: '../php/buscar_aulas.php', // Aponte para o script que retorna as aulas do professor selecionado
                    type: 'POST',
                    data: {
                        id_professor: idProfessorSelecionado
                    },
                    dataType: 'json', // Adiciona o tipo de dado esperado
                    success: function(response) {
                        // Limpar o select de aulas
                        $('.select-aula').empty().append('<option value="">Selecione a aula</option>');
                        // Adicionar as novas opções de aulas
                        response.forEach(function(aula) {
                            $('.select-aula').append('<option value="' + aula.ID_Aula + '" data-horario-inicio="' + aula.Horario_Inicio + '" data-horario-termino="' + aula.Horario_Termino + '" data-nome-disciplina="' + aula.Nome_Materia + '" data-id-materia="' + aula.ID_Materia + '">' + aula.Nome_Curso + ' - ' + aula.Nome_Materia + '</option>');
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Erro ao buscar aulas:', textStatus, errorThrown);
                    }
                });
            }
        });

        // Envio do formulário
        $('#enviarAulasBtn').click(function() {
            $('#reposicaoForm').submit();
        });
    </script>
</body>
</html>
