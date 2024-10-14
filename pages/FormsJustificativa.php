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
        AND A.Justificado != 'Justificado'";

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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* CSS personalizado integrado */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Roboto", sans-serif;
        }
        
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f5f5f5;
            color: #333;
            background-image: url("../img/formBG.jpg");
            background-size: auto; /* Ou um valor específico, como 100px 100px */
            background-repeat: repeat;
            background-position: center;
            min-height: 100vh;
        }
        
        .wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 100%;
        }
        
        .container {
            width: 100%;
            max-width: 1200px; /* Aumentando a largura máxima do container */
            margin: 20px auto; /* Centralizando o container */
            padding: 40px; /* Aumentado para melhor conforto */
            background: rgba(255, 255, 255, 0.9); /* Fundo com leve transparência */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* Sombra mais suave */
            border-radius: 10px; /* Borda mais suave */
        }
        
        
        .formContainer {
            text-align: center;
        }
        
        .formContainer h1 {
            margin-bottom: 20px; /* Reduzido para melhor alinhamento */
            color: #a31e22;
            font-size: 28px; /* Aumentado para melhor legibilidade */
        }
        
        .inputlabel {
            margin-bottom: 25px; /* Espaçamento aumentado */
            text-align: left;
        }
        
        .inputlabel label {
            display: block;
            margin-bottom: 10px; /* Melhor separação */
            font-weight: bold;
        }
        
        .inputlabel select,
        .inputlabel input[type="text"],
        .inputlabel textarea,
        .inputlabel input[type="file"],
        .inputlabel input[type="date"],
        .inputlabel input[type="time"] {
            width: 100%;
            padding: 12px; /* Ajustando o padding para uniformidade */
            margin-top: 0;
            border: 1px solid #ccc;
            border-radius: 5px; /* Borda mais suave */
            font-size: 16px; /* Diminuindo um pouco o tamanho da fonte */
            transition: border-color 0.3s; /* Transição suave para a borda */
        }
        
        .inputlabel select:focus,
        .inputlabel input[type="text"]:focus,
        .inputlabel textarea:focus,
        .inputlabel input[type="file"]:focus,
        .inputlabel input[type="date"]:focus,
        .inputlabel input[type="time"]:focus {
            border-color: #a31e22; /* Mudança de cor no foco */
            outline: none; /* Remover contorno padrão */
        }
        
        .justificativa {
            width: calc(100% - 40px); /* Ajustando a largura */
            padding: 10px; /* Melhorando o padding */
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        
        .upload-atestado {
            margin-top: 10px; /* Espaço acima do campo de upload */
            width: 100%;
        }
        
        button[type="submit"],
        button[type="button"] {
            width: 100%;
            padding: 15px; /* Aumentado para melhor acessibilidade */
            border: none;
            border-radius: 5px; /* Borda mais suave */
            background-color: #a31e22;
            color: #fff;
            font-size: 20px; /* Aumentado para melhor legibilidade */
            cursor: pointer;
            margin-top: 15px; /* Espaço entre os botões */
            transition: background-color 0.3s; /* Transição suave para o botão */
        }
        
        button[type="button"] {
            background-color: #8b1a1d; /* Mudando a cor do botão "Remover" */
        }
        
        button[type="button"]:hover {
            background-color: #8b1a1d; /* Cor do botão ao passar o mouse */
        }
        
        button[type="submit"]:hover,
        button[type="button"]:hover {
            background-color: #8b1a1d; /* Cor de fundo ao passar o mouse */
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0; /* Margem para a tabela */
            min-height: 200px; /* Aumentando a altura mínima da tabela */
        }
        
        th, td {
            padding: 15px 20px; /* Aumentado para mais conforto e visibilidade */
            text-align: left;
            border-bottom: 1px solid #ddd; /* Linha de separação */
            line-height: 1.5; /* Aumentando a altura das linhas */
        }
        
        th {
            background-color: #f2f2f2; /* Cor de fundo para cabeçalho */
            font-weight: bold; /* Negrito para o cabeçalho */
        }
        
        td {
            word-wrap: break-word; /* Quebrar palavras longas, se necessário */
        }
        
        .footer {
            width: 100%;
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            position: relative;
            bottom: 0; /* Fixar o rodapé */
        }
        
        .footer-links {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            display: inline;
            margin: 0 15px; /* Aumentado o espaço entre links */
        }
        
        .footer-links a {
            color: #fff;
            text-decoration: none;
        }
        
        .footer-links a:hover {
            text-decoration: underline;
        }
        
        .hidden {
            display: none;
        }
        
        /* Responsividade */
        @media (max-width: 600px) {
            th, td {
                padding: 10px; /* Menos padding em telas menores */
                font-size: 14px; /* Ajustando o tamanho da fonte */
            }
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
                                    <th>Upload PDF</th> <!-- Nova coluna -->
                                    <th>Ação</th>
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
                                            <optgroup label="Outras Justificativas">
                                                <option value="Falta Justificada">Falta Justificada</option>
                                                <option value="Problemas Pessoais">Problemas Pessoais</option>
                                                <option value="Problemas de Transporte">Problemas de Transporte</option>
                                                <option value="Outros">Outros</option>
                                            </optgroup>
                                        </select>
                                    <td>
                                        <input type="file" name="upload_pdf[]" class="upload-pdf hidden" accept=".pdf, .doc, .docx, .jpg, .png">
                                    </td>
                                    <td>
                                        <button type="button" class="removerAulaBtn">Remover</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" id="adicionarAulaBtn">Adicionar Aula</button>
                    </div>

                    <div class="inputlabel">
                        <label for="observacao">Observações</label>
                        <textarea name="observacao" id="observacao" rows="4"></textarea>
                    </div>

                    <button type="submit">Enviar Justificativa</button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer">
        <ul class="footer-links">
            <li><a href="#">Sobre</a></li>
            <li><a href="#">Contatos</a></li>
            <li><a href="#">Ajuda</a></li>
        </ul>
        <p>&copy; 2024 - Todos os direitos reservados.</p>
    </div>

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
            const dateAula = selectedOption.data('date-aula');
            const nomeDisciplina = selectedOption.data('nome-disciplina');
            const nomeCurso = selectedOption.data('nome-curso');

            $(this).closest('tr').find('input[name="date-aula[]"]').val(dateAula);
            $(this).closest('tr').find('.nome-disciplina').val(nomeDisciplina);
            $(this).closest('tr').find('.nome-curso').val(nomeCurso);
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
    </script>
</body>

</html>
