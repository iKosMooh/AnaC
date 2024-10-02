<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 800px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            color: #a31e22;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .no-docs {
            font-style: italic;
            color: #888;
        }
    </style>
    <title>Lista de Atestados</title>
</head>
<body>
    <div class="container">
        <h1>Lista de Atestados</h1>
        <table id="atestadoTable">
            <thead>
                <tr>
                    <th>ID Atestado</th>
                    <th>ID Professor</th>
                    <th>ID Aluno</th>
                    <th>Descrição</th>
                    <th>Data de Emissão</th>
                    <th>Documentos</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7" class="no-docs">Carregando dados...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/formCrud.js"></script>
    <script>
        $(document).ready(function() {
    submitForm("read", "atestado", null, null)
        .then(data => {
            processResponse(data);
        })
        .catch(error => {
            console.error('Erro ao carregar atestados:', error);
            const $tbody = $('#atestadoTable tbody');
            $tbody.empty(); // Limpa o conteúdo atual da tabela
            $tbody.append('<tr><td colspan="7" class="no-docs">Erro ao carregar dados.</td></tr>');
        });

    function processResponse(data) {
        const $tbody = $('#atestadoTable tbody');
        $tbody.empty(); // Limpa o conteúdo atual da tabela

        // Verifica se data é uma string e tenta parseá-la
        if (typeof data === 'string') {
            try {
                data = JSON.parse(data); // Converte a string JSON para um objeto
            } catch (e) {
                console.error('Erro ao parsear JSON:', e);
                $tbody.append('<tr><td colspan="7" class="no-docs">Erro ao carregar dados.</td></tr>');
                return; // Sai da função se ocorrer um erro
            }
        }

        // Verifica se data é um array
        if (Array.isArray(data) && data.length > 0) {
            data.forEach(atestado => {
                const $row = $('<tr></tr>');
                $row.append(`<td>${atestado.ID_Atestado}</td>`);
                $row.append(`<td>${atestado.ID_Professor}</td>`);
                $row.append(`<td>${atestado.ID_Aluno}</td>`);
                $row.append(`<td>${atestado.Descricao}</td>`);
                $row.append(`<td>${atestado.DataEmissao}</td>`);
                $row.append(`<td><a href="${atestado.Docs}" target="_blank">Ver Documento</a></td>`);
                $row.append(`<td>${atestado.status}</td>`);
                $tbody.append($row);
            });
        } else {
            $tbody.append('<tr><td colspan="7" class="no-docs">Nenhum atestado encontrado.</td></tr>');
        }
    }
});

    </script>
</body>
</html>
