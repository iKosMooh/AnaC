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
        PC.ID_Professor = :id_professor
        AND C.Nome = 'DSM 1º semestre 2024'";  // Filtro para o curso DSM 1º semestre 2024

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

// Verifica se os dados da aula são válidos
function verificarAulaExistente($pdo, $id_aula)
{
    $sql = "SELECT COUNT(*) FROM Aula WHERE ID_Aula = :id_aula";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_aula', $id_aula, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count > 0;
}

// Caso o formulário seja enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletar os dados enviados pelo formulário
    $dataAula = $_POST['dataAula'] ?? [];
    $idAula = $_POST['idAula'] ?? [];

    // Verificar se todas as aulas selecionadas existem
    foreach ($idAula as $aulaId) {
        if (!verificarAulaExistente($pdo, $aulaId)) {
            echo "<script>alert('Erro: A aula selecionada com ID $aulaId não existe no banco de dados.');</script>";
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Aula Não Ministrada</title>
    <link rel="stylesheet" href="../css/naoministrada.css">
    <link rel="stylesheet" href="../css/progressBar.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include_once 'header2.php'; ?>

    <div class="containerBar">
        <h1>1ª Etapa</h1>
        <div class="wrapperBar">
            <div class="progress-bar">
                <span class="progress-bar-fill" style="width: 25%;"></span>
            </div>
        </div>
    </div>

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
                                <th>Ação</th>
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
                                <td><input type="time" name="Horario_Inicio[]" class="horario-inicio"></td>
                                <td><input type="time" name="Horario_Termino[]" class="horario-termino"></td>
                                <td data-label="Ação">
                                    <button type="button" class="removerAulaBtn">Remover</button>
                                </td>
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
        function dataLimite() {
                // Define a data de hoje no formato yyyy-mm-dd
                const hoje = new Date();
                const ano = hoje.getFullYear();
                const mes = String(hoje.getMonth() + 1).padStart(2, '0'); // Mes é zero-indexado, por isso soma-se 1
                const dia = String(hoje.getDate()).padStart(2, '0'); // Preenche o dia com 0 à esquerda, se necessário
                const dataHoje = `${ano}-${mes}-${dia}`;

                // Aplica a data máxima em todos os campos de data
                $("input[type='date']").attr("max", dataHoje);

                // Define a data mínima (6 meses atrás)
                hoje.setMonth(hoje.getMonth() - 6);
                const anoPassado = hoje.getFullYear();
                const mesPassado = String(hoje.getMonth() + 1).padStart(2, '0');
                const diaPassado = String(hoje.getDate()).padStart(2, '0');
                const dataPassada = `${anoPassado}-${mesPassado}-${diaPassado}`;

                // Aplica a data mínima nos campos de data
                $("input[type='date']").attr("min", dataPassada);
            }
        $(document).ready(function() {
            dataLimite();
        });

        function validarDatas() {
            const hoje = new Date();
            const maxDate = hoje.toISOString().split('T')[0]; // Data de hoje
            hoje.setMonth(hoje.getMonth() - 6);
            const minDate = hoje.toISOString().split('T')[0]; // Data de 6 meses atrás

            let isValid = true;
            $("input[type='date']").each(function() {
                const data = $(this).val();

                if (data) {
                    if (data > maxDate || data < minDate) {
                        isValid = false;
                        alert("A data inserida deve estar dentro do intervalo de 6 meses atrás até hoje.");
                        return false; // Interrompe o loop
                    }
                }
            });
            return isValid;
        }

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
        <td><select name="id_materia[]" class="select-materia">
                <option value="">Selecione a disciplina</option>
            </select></td>
        <td><input type="time" name="Horario_Inicio[]" class="horario-inicio"></td>
        <td><input type="time" name="Horario_Termino[]" class="horario-termino"></td>
        <td><button type="button" class="removerAulaBtn">Remover</button></td>
        </tr>`;
            $('tbody').append(novaLinha);
            dataLimite();
        });


        // Função para remover uma linha
        $(document).on('click', '.removerAulaBtn', function(event) {
            event.preventDefault(); // Evita comportamento padrão, como submit acidental
            $(this).closest('tr').remove(); // Remove a linha correspondente

            // Atualiza a numeração das linhas restantes
            $('.aulaRow').each(function(index) {
                $(this).find('.ordem').text(index + 1);
            });
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
            let dataAulas = $("input[name='dataAula[]']");
            let cursos = $("select[name='id_curso[]']");
            let materias = $("select[name='id_materia[]']");

            dataAulas.each(function(index) {
                const data = $(this).val();
                const curso = cursos.eq(index).val();
                const materia = materias.eq(index).val();

                if (!data || !curso || !materia) {
                    alert(`Preencha todos os campos na linha ${index + 1}.`);
                    isValid = false;
                    return false; // Interrompe o loop
                }
            });

            return isValid;
        }


        $('#reposicaoForm').on('submit', function(e) {
            e.preventDefault(); // Evitar envio padrão

            if (!validarFormulario() && !validarDatas()) {
                return; // Não prosseguir se o formulário for inválido
            }

            const dataAula = [];
            const idAula = [];
            const horarioInicio = [];
            const horarioTermino = [];

            // Iterar pelas linhas e coletar os valores
            $('tr.aulaRow').each(function() {
                const data = $(this).find('input[name="dataAula[]"]').val();
                const idMateria = $(this).find('select[name="id_materia[]"]').val();
                const horarioIni = $(this).find('input[name="Horario_Inicio[]"]').val();
                const horarioTer = $(this).find('input[name="Horario_Termino[]"]').val();

                if (data && idMateria && horarioIni && horarioTer) {
                    dataAula.push(data);
                    idAula.push(idMateria);
                    horarioInicio.push(horarioIni);
                    horarioTermino.push(horarioTer);
                }
            });

            if (dataAula.length === 0) {
                alert("Preencha todas as linhas antes de enviar.");
                return;
            }

            const formData = {
                dataAula,
                idAula,
                Horario_Inicio: horarioInicio,
                Horario_Termino: horarioTermino,
                id_professor: $('#id_professor').val(),
            };

            $.ajax({
                url: '../php/Nao_Ministrada_Cadastro.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    if (response.status === 'success') {
                        $('#reposicaoForm')[0].reset();
                    }
                    window.location.replace('FormsJustificativa.php');
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Erro ao enviar os dados.');
                }
            });
        });
    </script>
</body>



</html>