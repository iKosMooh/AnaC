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

        th,
        td {
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

        /* Estilo do Modal */
        .modal {
            display: none; /* Ocultar o modal por padrão */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .status-green {
            color: green;
            /* Cor verde para status aprovado */
            font-weight: bold;
            /* Opção de negrito */
        }

        .status-red {
            color: red;
            /* Cor vermelha para status negado */
            font-weight: bold;
            /* Opção de negrito */
        }

        .no-docs {
            color: gray;
            /* Cor para mensagens de erro ou sem dados */
            font-style: italic;
            /* Estilo itálico */
        }
    </style>
    <title>Lista de Atestados</title>
</head>

<body>
<div class="container">
        <h1>Lista de Atestados</h1>
        <table id="atestadoTable">
            <thead>
                <tr id="headerRow">
                    <!-- Cabeçalhos serão gerados dinamicamente -->
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7" class="no-docs">Carregando dados...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal para Aprovar/Negar -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="statusForm">
                <input type="hidden" id="ID_Atestado" name="ID_Atestado" value="">
                <label for="Status">Status:</label>
                <select id="Status" name="Status">
                    <option value="Aprovado">Aprovado</option>
                    <option value="Negado">Negado</option>
                </select>
                <button type="submit" onclick="submitForm('update', 'atestado', 'statusForm', 'ID_Atestado')">Confirmar</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/formCrud.js"></script>
    <script>

        // Função para abrir o modal e definir o ID do atestado
        function openModal(atestadoId) {
            $('#ID_Atestado').val(atestadoId);
            $('#myModal').show();
        }

        // Evento de fechamento do modal
        $('.close').on('click', function() {
            $('#myModal').hide();
        });

        $(document).ready(function() {
            submitForm("read", "atestado", null, null)
                .then(data => {
                    VerDocsTable(data, 'atestadoTable');
                })
                .catch(error => {
                    console.error('Erro ao carregar atestados:', error);
                    const $tbody = $('#atestadoTable tbody');
                    $tbody.empty(); // Limpa o conteúdo atual da tabela
                    $tbody.append('<tr><td colspan="7" class="no-docs">Erro ao carregar dados.</td></tr>');
                });

            function VerDocsTable(data, tableID) /*ESTE TABLE ID NÃO É O NOME DA TABELA NO BANCO SIM NO HTML*/ {

                const $thead = $('#headerRow'); // tr ou cabeçalho
                const $tbody = $("#" + tableID + " tbody");
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
                    // Gera os cabeçalhos dinamicamente com base nas chaves do primeiro objeto
                    const firstItem = data[0];
                    const keys = Object.keys(firstItem);

                    // Adiciona os cabeçalhos à tabela com base nas colunas // MDS VOU CHORAR AKI JÁ NÃO AGUENTO MAIS KKKK Lamentos a quem leu isso
                    keys.forEach(key => {
                        $thead.append(`<th>${key.replace(/_/g, ' ')}</th>`);
                    });

                    $thead.append('<th>Ações</th>'); // ADICIONA A COLUNA EXTRA

                    // Preenche os dados da tabela
                    data.forEach(atestado => {
                        const $row = $('<tr></tr>');

                        // Gera as células dinamicamente com base nas chaves
                        keys.forEach(key => {
                            let cellContent = atestado[key];
                            if (key === 'Docs') {
                                cellContent = `<a href="${cellContent}" target="_blank">Ver Documento</a>`;
                            }
                            if (key === 'Status') {
                                // Aplique cores com base no status
                                const statusClass = cellContent === 'Aprovado' ? 'status-green' : cellContent === 'Negado' ? 'status-red' : '';
                                cellContent = `<span class="${statusClass}">${cellContent}</span>`; // Envolve o conteúdo em um <span> com a classe correspondente
                            }
                            $row.append(`<td>${cellContent}</td>`);
                        });

                        // Adiciona as ações de Aprovar/Negar /// AKI NESTA BOSTA É CRIADO O FORM COM OS INPUTS HIDDEN PQ O HTML NÃOOOO QUER GERAR O FORM NA ROW INTEIRA APENAS DENTRO DO TD NÃO SEI POR QUE, CORINGANDO AKI JÁ 👿 
                        // DEVE SER PERSONALIZADO PARA CADA TABELA GERADA INFELIZMENTE
                        $row.append(`
                            <td>
                                <button type="button" class="btn btn-approve" onclick="openModal('${atestado.ID_Atestado}')">Editar</button>
                            </td>
                        `);

                        $tbody.append($row);
                    });
                } else {
                    $tbody.append('<tr><td colspan="7" class="no-docs">Nenhum dado para a tabela encontrado.</td></tr>');
                }
            }

        });

        function setStatusAndSubmit(id, status) {
            $('#Status-'+id).val(status); // Define o valor do campo input
            submitForm('update', 'atestado', id, 'ID_Atestado'); // Chama a função com o status atualizado
        }
    
    </script>
</body>

</html>
