<?php
session_start();
// Verificar se o usuário está logado
if (!isset($_SESSION['tipo'])) {
    header('Location: login.php');
    exit();
}
if ($_SESSION['tipo'] != 'professor') {
    echo 'Você deve estar logado como professor para acessar esta página!';
    exit();
}

$id_professor = $_SESSION['id'];

require_once '../php/connect.php';

function buscarAulas($pdo, $id_professor)
{
    $sql = "
    SELECT 
        A.ID_Aula_Nao_Ministrada, 
        A.Date_Time AS Date_Aula_Nao_Ministrada, 
        M.Nome AS Nome_Materia, 
        C.Nome AS Nome_Curso, 
        A.Observacao, 
        A.Justificado 
    FROM 
        Aula_Nao_Ministrada A
    INNER JOIN 
        Aula Au ON A.ID_Aula = Au.ID_Aula
    INNER JOIN 
        Materias M ON A.ID_Materia = M.ID_Materia
    INNER JOIN 
        CursoAtivo C ON M.ID_Curso = C.ID_Curso
    INNER JOIN 
        Professores_Cursos PC ON C.ID_Curso = PC.ID_Curso
    WHERE 
        PC.ID_Professor = :id_professor
        AND A.Justificado != 'Justificado'";  // Filtra apenas os não justificados

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_professor', $id_professor, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


$aulas = buscarAulas($pdo, $id_professor);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Formulário de Justificativa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .upload-documento {
            display: none;
            /* Ocultar por padrão */
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="formContainer">
                <h1>Formulário de Justificativa</h1>
                <form id="reposicaoForm" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="id_professor" name="id_professor" value="<?php echo $id_professor; ?>">

                    <div class="inputlabel">
                        <label for="aulasNaoMinistradas">Dados da(s) aula(s) não ministrada(s)</label>
                        <table>
                            <thead>
                                <tr>
                                    <th>Ordem</th>
                                    <th>Aula</th>
                                    <th>Data da Aula Não Ministrada</th>
                                    <th>Disciplina</th>
                                    <th>Curso</th>
                                    <th>Justificativa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="aulaRow">
                                    <td class="ordem">1</td>
                                    <td>
                                        <select name="id_aula[]" class="select-aula" required>
                                            <option value="">Selecione a aula</option>
                                            <?php foreach ($aulas as $aula): ?>
                                                <option value="<?php echo $aula['ID_Aula_Nao_Ministrada']; ?>"
                                                    data-nome-disciplina="<?php echo $aula['Nome_Materia']; ?>"
                                                    data-nome-curso="<?php echo $aula['Nome_Curso']; ?>"
                                                    data-date-aula="<?php echo $aula['Date_Aula_Nao_Ministrada']; ?>">
                                                    <?php echo $aula['Date_Aula_Nao_Ministrada'] . ' - ' . $aula['Nome_Curso'] . ' - ' . $aula['Nome_Materia']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" name="date-aula[]" required readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="nome-disciplina" disabled>
                                    </td>
                                    <td>
                                        <input type="text" class="nome-curso" disabled>
                                    </td>
                                    <td>
                                        <select class="justificativa" name="justificativa[]" required>
                                            <option value="">Selecione a justificativa</option>

                                            <optgroup label="Licença Médica">
                                                <option value="Licença Médica - Falta Médica">Falta Médica</option>
                                                <option value="Licença Médica - Comparecimento ao Médico">Comparecimento ao Médico</option>
                                                <option value="Licença Médica - Licença para Tratamento de Saúde">Licença para Tratamento de Saúde</option>
                                                <option value="Licença Médica - Licença Maternidade">Licença Maternidade</option>
                                            </optgroup>

                                            <optgroup label="Falta Justificada">
                                                <option value="Falta Justificada - Falecimento de Cônjuge, Pai, Mãe, Filho">Falecimento de Cônjuge, Pai, Mãe, Filho</option>
                                                <option value="Falta Justificada - Falecimento de Ascendentes ou Descendentes">Falecimento de Ascendente ou Descendente</option>
                                                <option value="Falta Justificada - Casamento">Casamento</option>
                                                <option value="Falta Justificada - Nascimento de Filho">Nascimento de Filho (primeira semana)</option>
                                                <option value="Falta Justificada - Acompanhamento de Esposa ou Companheira Gestante">Acompanhamento de Esposa ou Companheira Gestante</option>
                                                <option value="Falta Justificada - Acompanhamento de Filho em Consulta Médica">Acompanhamento de Filho menor de 6 anos em consulta médica</option>
                                                <option value="Falta Justificada - Doação de Sangue">Doação Voluntária de Sangue</option>
                                            </optgroup>

                                            <optgroup label="Falta Injustificada">
                                                <option value="Falta Injustificada - Saída Antecipada">Saída Antecipada</option>
                                            </optgroup>

                                            <optgroup label="Faltas Previstas na Legislação">
                                                <option value="Faltas Previstas na Legislação - Alistamento Eleitoral">Alistamento Eleitoral</option>
                                                <option value="Faltas Previstas na Legislação - Convocação para Depoimento Judicial">Convocação para Depoimento Judicial</option>
                                                <option value="Faltas Previstas na Legislação - Comparecimento como Jurado no Tribunal do Júri">Comparecimento como Jurado no Tribunal do Júri</option>
                                                <option value="Faltas Previstas na Legislação - Convocação para Serviço Eleitoral">Convocação para Serviço Eleitoral</option>
                                                <option value="Faltas Previstas na Legislação - Dispensa para Composição de Mesas Eleitorais">Dispensa por Composição de Mesas Eleitorais</option>
                                            </optgroup>

                                            <optgroup label="Motivos Educacionais">
                                                <option value="Motivos Educacionais - Realização de Prova de Vestibular">Realização de Prova de Vestibular</option>
                                            </optgroup>

                                            <optgroup label="Outros Motivos">
                                                <option value="Outros Motivos - Comparecimento na Justiça do Trabalho">Comparecimento na Justiça do Trabalho</option>
                                                <option value="Outros Motivos - Atraso devido a Acidente de Transporte">Atraso Decorrente de Acidente de Transporte (com atestado da empresa)</option>
                                            </optgroup>

                                            <option value="Outra">Outra</option>
                                        </select>


                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" id="adicionarAulaBtn">Adicionar Aula</button>
                    </div>

                    <div class="upload-container">
                        <label for="documentoAula">Documento para todas as aulas:</label>
                        <input type="file" id="documentoAula" name="documentoAula" accept=".pdf, .doc, .docx, .jpg, .png" class="upload-documento">
                    </div>

                    <button type="submit" id="enviarAulasBtn">Enviar Aulas</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let ordem = 1;

        $('#adicionarAulaBtn').click(function() {
            ordem++;
            const novaLinha = `
            <tr class="aulaRow">
                <td class="ordem">` + ordem + `</td>
                <td>
                    <select name="id_aula[]" class="select-aula" required>
                        <option value="">Selecione a aula</option>
                        <?php foreach ($aulas as $aula): ?>
                            <option value="<?php echo $aula['ID_Aula_Nao_Ministrada']; ?>" 
                                    data-nome-disciplina="<?php echo $aula['Nome_Materia']; ?>"
                                    data-nome-curso="<?php echo $aula['Nome_Curso']; ?>"
                                    data-date-aula="<?php echo $aula['Date_Aula_Nao_Ministrada']; ?>">
                                <?php echo $aula['Date_Aula_Nao_Ministrada'] . ' - ' . $aula['Nome_Curso'] . ' - ' . $aula['Nome_Materia']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="date" name="date-aula[]" required>
                </td>
                <td>
                    <input type="text" class="nome-disciplina" disabled>
                </td>
                <td>
                    <input type="text" class="nome-curso" disabled>
                </td>
                <td>
                    <select class="justificativa" name="justificativa[]" required>
                        <option value="">Selecione a justificativa</option>
                        
                        <optgroup label="Licença Médica">
                            <option value="Licença Médica - Falta Médica">Falta Médica</option>
                            <option value="Licença Médica - Comparecimento ao Médico">Comparecimento ao Médico</option>
                            <option value="Licença Médica - Licença para Tratamento de Saúde">Licença para Tratamento de Saúde</option>
                            <option value="Licença Médica - Licença Maternidade">Licença Maternidade</option>
                        </optgroup>
                        
                        <optgroup label="Falta Justificada">
                            <option value="Falta Justificada - Falecimento de Cônjuge, Pai, Mãe, Filho">Falecimento de Cônjuge, Pai, Mãe, Filho</option>
                            <option value="Falta Justificada - Falecimento de Ascendentes ou Descendentes">Falecimento de Ascendente ou Descendente</option>
                            <option value="Falta Justificada - Casamento">Casamento</option>
                            <option value="Falta Justificada - Nascimento de Filho">Nascimento de Filho (primeira semana)</option>
                            <option value="Falta Justificada - Acompanhamento de Esposa ou Companheira Gestante">Acompanhamento de Esposa ou Companheira Gestante</option>
                            <option value="Falta Justificada - Acompanhamento de Filho em Consulta Médica">Acompanhamento de Filho menor de 6 anos em consulta médica</option>
                            <option value="Falta Justificada - Doação de Sangue">Doação Voluntária de Sangue</option>
                        </optgroup>

                        <optgroup label="Falta Injustificada">
                            <option value="Falta Injustificada - Saída Antecipada">Saída Antecipada</option>
                        </optgroup>
                        
                        <optgroup label="Faltas Previstas na Legislação">
                            <option value="Faltas Previstas na Legislação - Alistamento Eleitoral">Alistamento Eleitoral</option>
                            <option value="Faltas Previstas na Legislação - Convocação para Depoimento Judicial">Convocação para Depoimento Judicial</option>
                            <option value="Faltas Previstas na Legislação - Comparecimento como Jurado no Tribunal do Júri">Comparecimento como Jurado no Tribunal do Júri</option>
                            <option value="Faltas Previstas na Legislação - Convocação para Serviço Eleitoral">Convocação para Serviço Eleitoral</option>
                            <option value="Faltas Previstas na Legislação - Dispensa para Composição de Mesas Eleitorais">Dispensa por Composição de Mesas Eleitorais</option>
                        </optgroup>

                        <optgroup label="Motivos Educacionais">
                            <option value="Motivos Educacionais - Realização de Prova de Vestibular">Realização de Prova de Vestibular</option>
                        </optgroup>
                        
                        <optgroup label="Outros Motivos">
                            <option value="Outros Motivos - Comparecimento na Justiça do Trabalho">Comparecimento na Justiça do Trabalho</option>
                            <option value="Outros Motivos - Atraso devido a Acidente de Transporte">Atraso Decorrente de Acidente de Transporte (com atestado da empresa)</option>
                        </optgroup>

                        <option value="Outra">Outra</option>
                    </select>

                </td>
            </tr>`;
            $('tbody').append(novaLinha);
        });

        $(document).on('change', '.select-aula', function() {
            const selectedOption = $(this).find('option:selected');
            const row = $(this).closest('tr'); // Referência à linha correspondente

            // Preencher o campo de data automaticamente
            const dataAula = selectedOption.data('date-aula');
            row.find('input[name="date-aula[]"]').val(dataAula); // Atualiza o campo de data

            row.find('.nome-disciplina').val(selectedOption.data('nome-disciplina'));
            row.find('.nome-curso').val(selectedOption.data('nome-curso'));
        });

        $(document).on('change', '.justificativa', function() {
            const selectedJustificativa = $(this).val();
            const row = $(this).closest('tr'); // Referência à linha correspondente

            // Verificar se alguma das opções de Licença Médica está selecionada
            const hasLicencaMedica = $('.justificativa').toArray().some(function(select) {
                return $(select).val().includes('Licença Médica');
            });

            // Habilitar ou desabilitar o campo de upload de acordo com a seleção
            if (hasLicencaMedica) {
                $('.upload-documento').show(); // Mostrar campo de upload se Licença Médica estiver selecionada
                $('.upload-documento').prop('required', true); // Tornar o upload obrigatório
            } else {
                $('.upload-documento').hide().val(''); // Ocultar e limpar se Licença Médica não estiver selecionada
                $('.upload-documento').prop('required', false); // Remover obrigatoriedade do upload
            }
        });

        // Função para validar o formulário no envio
        $('#reposicaoForm').on('submit', function(event) {
            event.preventDefault(); // Previne o envio padrão do formulário

            const rows = $('.aulaRow');
            let allValid = true;

            // Criação de um objeto FormData para enviar dados do formulário
            let formData = new FormData(this);

            rows.each(function() {
                const inputs = $(this).find('input, select');
                inputs.each(function() {
                    if (!$(this).val()) {
                        alert('Por favor, preencha todos os campos obrigatórios.');
                        allValid = false; // Sair do loop
                        return false; // Para a iteração atual
                    }
                });
            });

            if (allValid) {
                $.ajax({
                    url: '../php/justificar.php', // URL do arquivo PHP que processará os dados
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert('Justificativa enviada com sucesso!');
                        console.log(response); // Para depuração
                        // Aqui você pode adicionar lógica adicional, como redirecionamento ou limpeza do formulário
                    },
                    error: function(xhr, status, error) {
                        alert('Ocorreu um erro ao enviar o formulário: ' + error);
                        console.error(xhr.responseText); // Para depuração
                    }
                });
            }
        });
    </script>
</body>

</html>