    <?php
    session_start();
    // Verificar se o usuário está logado
    if (!isset($_SESSION['id'])) {
        header('Location: login.php'); // Redireciona se não estiver logado
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
                A.ID_Aula,
                M.Nome AS Nome_Materia,
                C.Nome AS Nome_Curso,
                A.Date_Time AS Date_Aula_Nao_Ministrada
            FROM 
                aula_nao_ministrada A
            INNER JOIN 
                Aula Au ON A.ID_Aula = Au.ID_Aula
            INNER JOIN 
                Materias M ON Au.ID_Materia = M.ID_Materia
            INNER JOIN 
                CursoAtivo C ON M.ID_Curso = C.ID_Curso
            WHERE 
                A.ID_Professor = :id_professor
                AND A.Justificado != 'Justificado';";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_professor', $id_professor, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Verifique se o resultado está vazio
            if (empty($result)) {
                echo "<script>console.log('Nenhum registro encontrado para o professor com ID $id_professor');</script>";
            } else {
                echo "<script>console.log(" . json_encode($result) . ");</script>";
            }

            return $result;
        } catch (PDOException $e) {
            // Exibe o erro específico no console
            echo "<script>console.log('Erro SQL: " . $e->getMessage() . "');</script>";
            return [];
        }
    }

    $aulas = buscarAulas($pdo, $id_professor);
    $nenhumaAula = empty($aulas);
    ?>

    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Formulário de Justificativa</title>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="../css/justificativa.css">
        <link rel="stylesheet" href="../css/progressBar.css">
    </head>

    <body>
        <?php include_once 'header2.php'; ?>

        <div class="wrapper">

            <div class="containerBar">
                <h1>2ª Etapa</h1>
                <div class="wrapperBar">
                    <div class="progress-bar">
                        <span class="progress-bar-fill" style="width: 50%;"></span>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="formContainer">
                    <h1>Formulário de Justificativa</h1>
                    <?php if ($nenhumaAula): ?>
                    <p id="mensagemNenhumaAula">Nenhuma aula não ministrada encontrada para justificar.</p>
                    <?php else: ?>
                    <form id="reposicaoForm" enctype="multipart/form-data">
                        <input type="hidden" id="id_professor" name="id_professor" value="<?php echo $id_professor; ?>">

                        <div class="inputlabel">
                            <label for="aulasNaoMinistradas">Dados da(s) aula(s) não ministrada(s)</label>
                            <label for="restante" class="restante">Aulas a justificar restantes: <b
                                    class="red"><?php echo count($aulas); ?></b></label>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Ordem</th>
                                        <th>Aula Não Ministrada</th>
                                        <th>Justificativa</th>
                                        <th>Upload PDF</th> <!-- Nova coluna -->
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
                                                    data-nome-curso="<?php echo $aula['Nome_Curso']; ?>"
                                                    data-nome-disciplina="<?php echo $aula['Nome_Materia']; ?>"
                                                    data-date-aula="<?php echo $aula['Date_Aula_Nao_Ministrada']; ?>">
                                                    <?php echo $aula['Date_Aula_Nao_Ministrada'] . $aula['Nome_Curso'] . ' - ' . $aula['Nome_Materia']; ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="justificativa" name="justificativa[]" required>
                                                <option value="">Selecione a justificativa</option>
                                                <optgroup label="Licença Médica">
                                                    <option value="Licença Médica - Falta Médica">Falta Médica</option>
                                                    <option value="Licença Médica - Comparecimento ao Médico">
                                                        Comparecimento
                                                        ao Médico</option>
                                                    <option value="Licença Médica - Licença para Tratamento de Saúde">
                                                        Licença para Tratamento de Saúde</option>
                                                    <option value="Licença Médica - Licença Maternidade">Licença
                                                        Maternidade
                                                    </option>
                                                </optgroup>
                                                <optgroup label="Outras Justificativas">
                                                    <option value="Falta Justificada">Falta Justificada</option>
                                                    <option value="Problemas Pessoais">Problemas Pessoais</option>
                                                    <option value="Problemas de Transporte">Problemas de Transporte
                                                    </option>
                                                    <option value="Outros">Outros</option>
                                                </optgroup>
                                            </select>
                                        <td>
                                            <input type="file" name="upload_pdf[]" class="upload-pdf hidden"
                                                accept=".pdf, .doc, .docx, .jpg, .png">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="inputlabel">
                            <label for="observacao">Observação</label>
                            <textarea name="observacao" id="observacao" rows="4"></textarea>
                        </div>

                        <button type="submit">Enviar Justificativa</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php include_once 'footer.php'; ?>

        <script>
        let ordem = 1;

        $('#adicionarAulaBtn').click(function() {
            ordem++;
            let novaRow = `
            <tr class="aulaRow">
                <td class="ordem">${ordem}</td>
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
                        <optgroup label="Outras Justificativas">
                            <option value="Falta Justificada">Falta Justificada</option>
                            <option value="Problemas Pessoais">Problemas Pessoais</option>
                            <option value="Problemas de Transporte">Problemas de Transporte</option>
                            <option value="Outros">Outros</option>
                        </optgroup>
                    </select>
                    <input type="file" name="atestado[]" class="upload-atestado hidden" accept=".pdf, .doc, .docx, .jpg, .png">
                </td>
                <td>
                    <input type="file" name="upload_pdf[]" class="upload-pdf hidden" accept=".pdf"> <!-- Novo campo -->
                </td>
                <td>
                    <button type="button" class="removerAulaBtn">Remover</button>
                </td>
            </tr>`;
            $('tbody').append(novaRow);
        });

        $(document).on('change', '.select-aula', function() {
            const selectedOption = $(this).find(':selected');
            let dateAula = selectedOption.data('date-aula');
            const nomeDisciplina = selectedOption.data('nome-disciplina');
            const nomeCurso = selectedOption.data('nome-curso');
            const selectedValue = $(this).val(); // Obter o valor selecionado

            // Ajusta o formato de dateAula para datetime-local caso necessário
            if (dateAula && dateAula.length === 10) {
                dateAula += 'T00:00'; // Adiciona uma hora padrão
            }

            $(this).closest('tr').find('input[name="date-aula[]"]').val(dateAula);
            $(this).closest('tr').find('.nome-disciplina').val(nomeDisciplina);
            $(this).closest('tr').find('.nome-curso').val(nomeCurso);

            // Verificar se a aula já foi selecionada em outra linha
            let duplicado = false;
            $('.select-aula').each(function() {
                if ($(this).val() === selectedValue && $(this).is(':not(:focus)')) {
                    duplicado = true;
                    return false; // Interrompe o loop
                }
            });

            if (duplicado) {
                alert('Aula já selecionada em outra linha! Selecione uma aula diferente.');
                $(this).val(''); // Limpa a seleção duplicada
            }
        });

        $(document).on('change', '.justificativa', function() {
            const justificativa = $(this).val();
            const atestadoInput = $(this).closest('td').find('.upload-atestado');
            const pdfInput = $(this).closest('tr').find('.upload-pdf');

            if (justificativa.includes('Licença Médica')) {
                atestadoInput.removeClass('hidden'); // Mostra o campo de upload
                pdfInput.removeClass('hidden'); // Mostra o campo PDF
            } else {
                atestadoInput.addClass('hidden'); // Oculta o campo
                atestadoInput.val(''); // Limpa o campo
                pdfInput.addClass('hidden'); // Oculta o campo PDF
                pdfInput.val(''); // Limpa o campo PDF
            }
        });

        $(document).on('click', '.removerAulaBtn', function() {
            $(this).closest('tr').remove(); // Remove a linha da tabela
            // Atualiza a ordem das aulas
            ordem--;
            $('.ordem').each(function(index) {
                $(this).text(index + 1); // Atualiza a numeração
            });
        });

        function enviarJustificativa() {
            var aulasCount = <?php echo count($aulas); ?>; // Número de aulas
            const form = $('#reposicaoForm')[0];
            const formData = new FormData(form); // Cria um FormData com o formulário

            $.ajax({
                url: '../php/justificar.php', // Arquivo que processará o envio do formulário
                type: 'POST',
                data: formData,
                contentType: false, // Necessário para o envio de arquivos
                processData: false,
                success: function(response) {
                    alert(response); // Exibe a resposta do servidor
                    console.log(response);

                    if (aulasCount > 1) {
                        // Pergunta ao usuário se deseja continuar justificando
                        var continuar = confirm('Ainda há aulas a justificar. Deseja continuar justificando? Se sim clique em OK');
                        if (!continuar) {
                            window.location.replace('ReposicaoForm.php'); // Redireciona para o formulário
                        } else {
                            alert('Você escolheu continuar justificando.');
                            location.reload();
                        }
                    } else {
                        alert('Todas as aulas foram justificadas!');
                        window.location.replace('ReposicaoForm.php');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Erro ao enviar o formulário: ' + xhr.responseText);
                    console.error(xhr.responseText);
                }
            });
        }



        // Captura o envio do formulário e previne o envio padrão
        $('#reposicaoForm').submit(function(e) {
            e.preventDefault(); // Impede o envio padrão do formulário
            enviarJustificativa(); // Chama a função AJAX
        });
        </script>



    </body>

    </html>