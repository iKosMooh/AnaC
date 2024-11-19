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
    C.ID_Curso,  -- Adicionando o ID_Curso
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
    <link rel="stylesheet" href="../css/naoministrada.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include_once 'header.php'; ?>
    <div class="container">
        <div class="formContainer">
            <h1>Cadastro de Aulas Não Ministradas</h1><br>

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
                                <th>Curso</th>
                                <th>Disciplina</th>
                                <th>Horário Início</th>
                                <th>Horário Término</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="aulaRow">
                                <td class="ordem" data-label="Ordem">1</td>
                                <td data-label="Data da Aula Não Ministrada"><input type="date" name="dataAula[]"></td>
                                <td data-label="Curso">
                                    <select name="id_curso[]" class="select-curso">
                                        <option value="">Selecione o curso</option>
                                        <?php
                                        // Array para armazenar os cursos já exibidos
                                        $exibidos = [];
                                        foreach ($aulas as $aula):
                                            if (!in_array($aula['ID_Curso'], $exibidos)):
                                                $exibidos[] = $aula['ID_Curso']; ?>
                                                <option value="<?php echo $aula['ID_Curso']; ?>"
                                                    data-nome-curso="<?php echo $aula['Nome_Curso']; ?>"
                                                    data-id-materia="<?php echo $aula['ID_Materia']; ?>"
                                                    data-horario-inicio="<?php echo $aula['Horario_Inicio']; ?>"
                                                    data-horario-termino="<?php echo $aula['Horario_Termino']; ?>">
                                                    <?php echo $aula['Nome_Curso']; ?>
                                                </option>
                                        <?php endif;
                                        endforeach; ?>
                                    </select>
                                </td>
                                <td data-label="Disciplina">
                                    <select name="id_materia[]" class="select-materia">
                                        <option value="">Selecione a disciplina</option>
                                    </select>
                                </td>
                                <td data-label="Horário Início"><input type="text" class="horario-inicio" disabled></td>
                                <td data-label="Horário Término"><input type="text" class="horario-termino" disabled></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="button" id="adicionarAulaBtn">Adicionar Aula</button>
                <button type="submit" id="enviarAulasBtn">Enviar Aulas</button>
        </div>
        </form>
    </div>
    </div>
    <?php include_once 'footer.php'; ?>

    <script>
        let ordem = 1;

        // Adicionar nova linha com dados de aula
        $('#adicionarAulaBtn').click(function() {
            ordem++;
            const novaLinha = `
            <tr class="aulaRow">
                <td class="ordem">${ordem}</td>
                <td><input type="date" name="dataAula[]"></td>
                <td>
                    <select name="id_curso[]" class="select-curso">
                        <option value="">Selecione o curso</option>
                        <?php
                        // Array para armazenar os cursos já exibidos
                        $exibidos = [];
                        foreach ($aulas as $aula):
                            if (!in_array($aula['ID_Curso'], $exibidos)):
                                $exibidos[] = $aula['ID_Curso']; ?>
                                <option value="<?php echo $aula['ID_Curso']; ?>"
                                        data-nome-curso="<?php echo $aula['Nome_Curso']; ?>"
                                        data-id-materia="<?php echo $aula['ID_Materia']; ?>"
                                        data-horario-inicio="<?php echo $aula['Horario_Inicio']; ?>"
                                        data-horario-termino="<?php echo $aula['Horario_Termino']; ?>">
                                    <?php echo $aula['Nome_Curso']; ?>
                                </option>
                        <?php endif;
                        endforeach; ?>
                    </select>
                </td>
                <td><input type="text" class="horario-inicio" disabled></td>
                <td><input type="text" class="horario-termino" disabled></td>
                <td><select name="id_materia[]" class="select-materia">
                        <option value="">Selecione a disciplina</option>
                    </select></td>
            </tr>`;
            $('tbody').append(novaLinha);
        });

        // Atualizar o select de disciplinas ao selecionar o curso
        $(document).on('change', '.select-curso', function() {
            const selectedOption = $(this).find('option:selected');
            const idCurso = selectedOption.val();
            const selectMateria = $(this).closest('tr').find('.select-materia');
            selectMateria.empty();
            selectMateria.append('<option value="">Selecione a disciplina</option>');

            // Preencher as disciplinas de acordo com o curso
            <?php foreach ($aulas as $aula): ?>
                if (<?php echo $aula['ID_Curso']; ?> == idCurso) {
                    selectMateria.append('<option value="<?php echo $aula['ID_Materia']; ?>" data-horario-inicio="<?php echo $aula['Horario_Inicio']; ?>" data-horario-termino="<?php echo $aula['Horario_Termino']; ?>"><?php echo $aula['Nome_Materia']; ?></option>');
                }
            <?php endforeach; ?>
        });

        // Preencher os horários ao selecionar a disciplina
        $(document).on('change', '.select-materia', function() {
            const selectedOption = $(this).find('option:selected');
            const horarioInicio = selectedOption.data('horario-inicio');
            const horarioTermino = selectedOption.data('horario-termino');
            const horarioInicioInput = $(this).closest('tr').find('.horario-inicio');
            const horarioTerminoInput = $(this).closest('tr').find('.horario-termino');

            horarioInicioInput.val(horarioInicio);
            horarioTerminoInput.val(horarioTermino);
        });

        // Função de validação do formulário
        function validarFormulario() {
            let isValid = true;
            const dataAulas = $("input[name='dataAula[]']");
            const idAulas = $("select[name='id_aula[]']");

            // Validar se todas as linhas têm a data da aula e a aula selecionada
            dataAulas.each(function(index) {
                const data = $(this).val();
                const idAula = idAulas.eq(index).val();

                // Verificar se a data está vazia
                if (!data) {
                    alert("Por favor, preencha a data da aula na linha " + (index + 1));
                    isValid = false;
                    return false; // Interrompe o loop
                }

                // Validar formato da data (não permitir datas no futuro)
                const today = new Date().toISOString().split('T')[0];
                if (data > today) {
                    alert("A data não pode ser no futuro (linha " + (index + 1) + ")");
                    isValid = false;
                    return false; // Interrompe o loop
                }
            });

            return isValid;
        }

        $('#reposicaoForm').on('submit', function(e) {
            e.preventDefault(); // Impede o envio normal do formulário

            if (!validarFormulario()) {
                return; // Se a validação falhar, não envia os dados
            }

            let dataAula = [];
            let idAula = [];

            // Coletar apenas os dados de ID_Aula e Data
            $('input[name="dataAula[]"]').each(function(index) {
                const data = $(this).val();
                const idCurso = $('select[name="id_curso[]"]').eq(index).val(); // ID_Curso
                const idMateria = $('select[name="id_materia[]"]').eq(index).val(); // ID_Materia

                // Verificar se a data está preenchida antes de enviar
                if (data && idCurso && idMateria) {
                    dataAula.push(data); // Adiciona a data ao array
                    idAula.push(idMateria); // Adiciona o ID da matéria ao array
                }
            });

            // Validar se temos dados para enviar
            if (dataAula.length === 0 || idAula.length === 0) {
                alert("Por favor, preencha todos os campos antes de enviar.");
                return;
            }

            // Preparar dados para envio
            const formData = {
                dataAula: dataAula,
                idAula: idAula,
                id_professor: $('#id_professor').val() // Enviar também o ID do professor
            };

            // Enviar via AJAX
            $.ajax({
                url: '../php/Nao_Ministrada_Cadastro.php', // URL do arquivo que processa o formulário
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    console.log('Resposta do servidor:', response);
                    if (response.status === 'success') {
                        alert(response.message);
                        $('#reposicaoForm')[0].reset(); // Limpa o formulário
                    } else {
                        alert(response.message); // Exibe mensagem de erro
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro de AJAX:', error);
                    var errorMessage = "Erro desconhecido.";
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch (e) {
                        errorMessage = "O servidor não retornou uma mensagem JSON válida.";
                    }
                    alert('Erro ao processar a solicitação: ' + errorMessage);
                }
            });
        });
    </script>
</body>

</html>