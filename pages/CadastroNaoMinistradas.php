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

// Função para buscar aulas que o professor ministra
function buscarAulas($pdo, $id_professor)
{
    $sql = "
    SELECT 
        Au.ID_Aula,
        Au.ID_Materia,
        M.Nome AS Nome_Materia, 
        C.Nome AS Nome_Curso, 
        Au.Horario_Inicio, 
        Au.Horario_Termino 
    FROM 
        Aula Au
    INNER JOIN 
        Materias M ON Au.ID_Materia = M.ID_Materia
    INNER JOIN 
        CursoAtivo C ON M.ID_Curso = C.ID_Curso
    INNER JOIN 
        Professores_Cursos PC ON C.ID_Curso = PC.ID_Curso
    WHERE 
        PC.ID_Professor = :id_professor";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_professor', $id_professor, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verifique se o resultado está vazio
        if (empty($result)) {
            echo "<script>console.log('Nenhum registro encontrado para o professor com ID $id_professor');</script>";
        } else {
            //echo "<script>console.log(" . json_encode($result) . ");</script>";
        }
        
        return $result;
    } catch (PDOException $e) {
        // Exibe o erro específico no console
        echo "<script>console.log('Erro SQL: " . $e->getMessage() . "');</script>";
        return [];
    }
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
    <?php include_once 'header.php'; ?>
    <div class="container">
        <div class="formContainer">
            <h1>Cadastro de Aulas Não Ministradas</h1>

            <form id="reposicaoForm" method="post">
                <input type="hidden" id="id_professor" name="id_professor" value="<?php echo $id_professor; ?>">

                <?php if ($is_coordenador): ?>
                    <div class="pa">
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
                   <div class="pa"><label for="aulasNaoMinistradas">Dados da(s) aula(s) não ministrada(s)</label></div> 
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
                                <td class="ordem" data-label="Ordem">1</td>
                                <td data-label="Data da Aula Não Ministrada"><input type="date" name="dataAula[]"></td>
                                 <td data-label="Aula">
                                    <select name="id_aula[]" class="select-aula">
                                        <option value="">Selecione a aula</option>
                                        <?php foreach ($aulas as $aula): ?>
                                            <option value="<?php echo $aula['ID_Aula']; ?>"
                                                data-horario-inicio="<?php echo $aula['Horario_Inicio']; ?>"
                                                data-horario-termino="<?php echo $aula['Horario_Termino']; ?>"
                                                data-nome-disciplina="<?php echo $aula['Nome_Materia']; ?>"
                                                data-id-materia="<?php echo isset($aula['ID_Materia']) ? $aula['ID_Materia'] : ''; ?>">
                                                <?php echo $aula['Nome_Curso'] . ' - ' . $aula['Nome_Materia']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td data-label="Horário Início"><input type="text" class="horario-inicio" disabled></td>
                                <td data-label="Horário Término"><input type="text" class="horario-termino" disabled></td>
                                <td data-label="Disciplina"><input type="text" class="nome-disciplina" disabled></td>
                                <td data-label="Observação"><input type="text" name="observacaoAula[]" placeholder="Motivo da aula não ministrada"></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <div class="button.container"> 
                        <button type="button" id="adicionarAulaBtn">Adicionar Aula</button>
                    </div>

                <button type="submit" id="enviarAulasBtn">Enviar Aulas</button></div>
            </form>
        </div>
    </div>
    <?php include_once 'footer.php'; ?>

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

        $('#reposicaoForm').on('submit', function(e) {
        e.preventDefault(); // Impede o envio normal do formulário

        $.ajax({
            url: '../php/Nao_Ministrada_Cadastro.php', // URL do arquivo que processa o formulário
            type: 'POST',
            data: $(this).serialize(), // Serializa os dados do formulário
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message); // Mensagem de sucesso
                    // Aqui você pode também limpar o formulário ou redirecionar
                } else {
                    alert(response.message); // Mensagem de erro
                }
            },
            error: function() {
                alert('Ocorreu um erro ao enviar o formulário.'); // Mensagem de erro de AJAX
            }
        });
    });

    </script>
</body>
</html>