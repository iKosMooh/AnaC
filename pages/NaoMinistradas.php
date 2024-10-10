<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fatec Itapira - Plano de Reposição/Substituição de Aulas</title>
    <link rel="stylesheet" href="../css/form.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../css/footer.css">
</head>
<body>

    <div class="container">
        <div class="formContainer">
            <h1>Fatec Itapira “Ogari de Castro Pacheco”</h1>
            <h2>PLANO – REPOSIÇÃO/SUBSTITUIÇÃO DE AULAS</h2>
            <form id="reposicaoForm" enctype="multipart/form-data" action="/pages/HeaderP.html" method="post">
                <div class="inputlabel">
                    <label for="aulasNaoMinistradas">1) Dados da(s) aula(s) não ministrada(s)</label>
                    <table>
                        <thead>
                            <tr>
                                <th>Ordem</th>
                                <th>Data da Aula</th>
                                <th>Horário de Início e Término</th>
                                <th>Disciplina(s)</th>
                                <th>Semestre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01</td>
                                <td><input type="date" name="dataAula01"></td>
                                <td><input type="text" name="horarioAula01" placeholder="__:__ as __:__"></td>
                                <td><input type="text" name="disciplinaAula01"></td>
                                <td><input type="text" name="assinaturaAula01"></td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td><input type="date" name="dataAula02"></td>
                                <td><input type="text" name="horarioAula02" placeholder="__:__ as __:__"></td>
                                <td><input type="text" name="disciplinaAula02"></td>
                                <td><input type="text" name="assinaturaAula02"></td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td><input type="date" name="dataAula03"></td>
                                <td><input type="text" name="horarioAula03" placeholder="__:__ as __:__"></td>
                                <td><input type="text" name="disciplinaAula03"></td>
                                <td><input type="text" name="assinaturaAula03"></td>
                            </tr>
                            <tr>
                                <td>04</td>
                                <td><input type="date" name="dataAula04"></td>
                                <td><input type="text" name="horarioAula04" placeholder="__:__ as __:__"></td>
                                <td><input type="text" name="disciplinaAula04"></td>
                                <td><input type="text" name="assinaturaAula04"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                            <!-- Adicione mais linhas conforme necessário -->
                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2024 projetoproject.rf.gd. Todos os direitos reservados.</p>
            <ul class="footer-links">
                <li><a href="/">Inicio</a></li>
                <li><a href="#">Termos de Serviço</a></li>
                <li><a href="#">Política de Privacidade</a></li>
                <li><a href="#">Contato</a></li>
            </ul>
        </div>
    </footer>

    <script src="../js/FormsJustificativa.js"></script>
    <script src="../js/formProcess.js"></script>
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
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            width: 100%;
            background-color: #f5f5f5;
            color: #333;
            background-image: url("../img/formBG.jpg"); /* caminho ajustado conforme necessário */
            background-size: 250px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 20px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .formContainer {
            text-align: center;
        }

        .formContainer h1, .formContainer h2 {
            margin-bottom: 20px;
            color: #a31e22;
        }

        .inputlabel {
            margin-bottom: 15px;
            text-align: left;
        }

        .inputlabel label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .inputlabel input[type="text"],
        .inputlabel textarea,
        .inputlabel input[type="file"],
        .inputlabel input[type="date"],
        .inputlabel select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #a31e22;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #8b1a1d;
        }

        table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }
    </style>
</body>
</html>