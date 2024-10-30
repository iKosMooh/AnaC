<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/modal.css">

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
            display: none;
            /* Ocultar o modal por padrão */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
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

        .status-orange {
            color: orange;
            font-weight: bold;
        }

        .no-docs {
            color: gray;
            /* Cor para mensagens de erro ou sem dados */
            font-style: italic;
            /* Estilo itálico */
        }
    </style>
    <title>Justificativas Enviadas</title>
</head>

<body>
    <?php include_once 'header.php';?>
    <div class="container">
        <h1>Justificativas Enviadas</h1>
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
                <!-- Cabeçalhos serão gerados dinamicamente -->
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/formCrud.js"></script>
    <script>
        // Função para abrir o modal e definir o ID do atestado
        function openModal(id, button, inputIdentifier) {
            const data = JSON.parse($(button).attr('data-atestado')); // Pega os dados armazenados no botão

            // Define o campo correto baseado no inputIdentifier
            $(`#${inputIdentifier}`).val(id); // Define o valor do ID no input correto

            const $form = $('#statusForm');
            $form.find('input, select, label').not(`#${inputIdentifier}`)
                .remove(); // Remove todos os inputs antigos, exceto o ID principal

            // Gera os inputs dinamicamente com base nas chaves da tabela
            tableKeys.forEach(key => {
                if (key !== inputIdentifier) { // Exclui o ID principal baseado no identificador
                    const label = `<label for="${key}">${key.replace(/_/g, ' ')}:</label>`;
                    let input;

                    if (key === 'Status') {
                        input = `
                                <select id="${key}" name="${key}">
                                    <option value="Aprovado" ${data[key] === 'Aprovado' ? 'selected' : ''}>Aprovado</option>
                                    <option value="Pendente" ${data[key] === 'Pendente' ? 'selected' : ''}>Pendente</option>
                                    <option value="Negado" ${data[key] === 'Negado' ? 'selected' : ''}>Negado</option>
                                </select>`;
                    } else if (typeof data[key] === 'string' && !isNaN(Date.parse(data[key]))) {
                        // Verifica se o valor é uma string que pode ser convertida para uma data válida
                        input = `<input type="date" id="${key}" name="${key}" value="${data[key]}">`;
                    } else {
                        input = `<input type="text" id="${key}" name="${key}" value="${data[key] || ''}">`;
                    }


                    $form.append(label + input); // Adiciona o label e o input ao formulário
                }
            });
            $form.append(
                `<button id='sendButton' type="button" onclick="submitForm('update', 'justificado', 'statusForm', 'ID_Atestado')">Confirmar</button>`
            )
            $('#myModal').show(); // Exibe o modal
        }

        // Evento para fechar o modal
        $('.close').on('click', function() {
            $('#myModal').hide();
            $('#sendButton').remove();
        });


        $(document).ready(function() {
            submitForm("read", "justificado", null, null)
                .then(data => {
                    VerDocsTable(data, 'atestadoTable');
                })
                .catch(error => {
                    console.error('Erro ao carregar atestados:', error);
                    const $tbody = $('#atestadoTable tbody');
                    $tbody.empty(); // Limpa o conteúdo atual da tabela
                    $tbody.append('<tr><td colspan="7" class="no-docs">Erro ao carregar dados.</td></tr>');
                });

            function VerDocsTable(data, tableID) {
                const $thead = $('#headerRow'); // Cabeçalho da tabela
                const $tbody = $("#" + tableID + " tbody");
                $tbody.empty(); // Limpa o conteúdo atual da tabela
                $thead.empty(); // Limpa o cabeçalho

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
                    const firstItem = data[0];
                    tableKeys = Object.keys(firstItem); // Armazena as chaves das colunas

                    // Gera os cabeçalhos dinamicamente
                    tableKeys.forEach(key => {
                        $thead.append(`<th>${key.replace(/_/g, ' ')}</th>`);
                    });

                    $thead.append('<th>Ações</th>'); // Adiciona coluna de ações

                    // Preenche os dados da tabela
                    data.forEach(atestado => {
                        const $row = $('<tr></tr>');

                        // Gera as células dinamicamente com base nas chaves
                        tableKeys.forEach(key => {
                            let cellContent = atestado[key];
                            if (key === 'Docs') {
                                cellContent =
                                    `<a href="${cellContent}" target="_blank">Ver Documento</a>`;
                            }
                            if (key === 'Status') {
                                const statusClass = cellContent === 'Aprovado' ? 'status-green' :
                                    cellContent === 'Negado' ? 'status-red' : 'status-orange';
                                cellContent = `<span class="${statusClass}">${cellContent}</span>`;
                            }
                            $row.append(`<td>${cellContent}</td>`);
                        });

                        // Adiciona botão de ação para abrir o modal
                        $row.append(`
                <td>
                    <button type="button" class="btn btn-approve" data-atestado='${JSON.stringify(atestado)}' onclick="openModal('${atestado.ID_Atestado}', this, 'ID_Atestado')">Editar</button>
                </td>
            `);

                        $tbody.append($row);
                    });
                } else {
                    $tbody.append(
                        '<tr><td colspan="7" class="no-docs">Nenhum dado para a tabela encontrado.</td></tr>');
                }
            }


        });
    </script>
    <?php include_once 'footer.php';?>
</body>

</html>
