<?php
session_start();

// Verifica se o usuário está logado e é coordenador
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'coordenador') {
    header('Location: login.php');
    exit();
}

require_once '../php/connect.php';

$id_coordenador = $_SESSION['id'];

// Consulta SQL para recuperar as justificativas e documentos de todos os professores
$sql = "
    SELECT aula_nao_ministrada.ID_Aula_Nao_Ministrada, aula_nao_ministrada.ID_Professor, aula_nao_ministrada.Justificado, aula_nao_ministrada.docs, aula_nao_ministrada.Observacao, aula_nao_ministrada.Date_Time, professores.Nome 
    FROM aula_nao_ministrada 
    INNER JOIN professores ON aula_nao_ministrada.ID_Professor = professores.ID_Professor
";

try {
    // Preparar e executar a consulta
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Buscar os resultados
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao recuperar as justificativas: " . $e->getMessage();
    exit();
}
?>

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
            color: #333;
            background-image: url("../img/formBG.jpg");
            background-size: auto; 
            background-repeat: repeat;
            background-position: center;
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
            border: 1px solid #000000;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #a31e22;
            color: rgb(250, 250, 250);
        }

        .no-docs {
            font-style: italic;
            color: #888;
        }

        /* Estilo do Modal */
        .modal {
            display: none;
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
            font-weight: bold;
        }

        .status-red {
            color: red;
            font-weight: bold;
        }

        .status-orange {
            color: orange;
            font-weight: bold;
        }

        .no-docs {
            color: gray;
            font-style: italic;
        }

        /* Esconder cabeçalhos em telas menores */
        @media (max-width: 1000px) {
            thead {
                display: none;
            }

            table, tbody, tr, td {
                display: block;
                width: 100%;
            }

            tbody tr {
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 10px;
            }

            tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0;
                border: none;
                border-bottom: 1px solid #ddd;
            }

            tbody td:before {
                content: attr(data-label); /* Mostra o cabeçalho antes do valor */
                font-weight: bold;
                color: #333;
                flex-basis: 40%;
                text-align: left;
                padding-right: 10px;
            }

            tbody td:last-child {
                border-bottom: none;
            }
        }

        /* Ajustes para telas ainda menores */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                max-width: 700px;
                margin-left: auto;
                margin-right: auto;
                margin-top: 20rem;
                padding: 10px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            tbody, tr {
                display: block;
                width: 100%;
                margin-bottom: 10px;
            }

            td {
                display: block;
                width: 100%;
                padding: 10px;
                text-align: left;
                border: none;
            }

            td:before {
                content: attr(data-label);
                font-weight: bold;
                margin-bottom: 5px;
            }

            td {
                border-bottom: none;
            }

            table, th, td {
                font-size: 14px;
            }
        }

    </style>
    <title>Justificativas Enviadas</title>
</head>

<body>
    <?php include_once 'header.php'; ?>
    <div class="container">
        <h1>Justificativas Enviadas</h1>
        
        <?php
            if ($resultados) {
                echo "<table>";
                echo "<thead>
                        <tr>
                            <th>Nome do Professor</th>
                            <th>ID Aula</th>
                            <th>Data da Aula</th>
                            <th>Justificado</th>
                            <th>Documento</th>
                            <th>Observação</th>
                        </tr>
                    </thead>";
                echo "<tbody>";
                foreach ($resultados as $row) {
                    echo "<tr>";
                    echo "<td data-label='Nome do Professor'>" . $row['Nome'] . "</td>";
                    echo "<td data-label='ID Aula'>" . $row['ID_Aula_Nao_Ministrada'] . "</td>";
                    echo "<td data-label='Data da Aula'>" . date('d/m/Y', strtotime($row['Date_Time'])) . "</td>";
                    echo "<td data-label='Justificado'>" . $row['Justificado'] . "</td>";
                    echo "<td data-label='Documento'>" . ($row['docs'] ? "<a href='../uploads/" . $row['docs'] . "' target='_blank'>Ver Documento</a>" : "Nenhum documento") . "</td>";
                    echo "<td data-label='Observação'>" . $row['Observacao'] . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>Não há justificativas registradas.</p>";
            }
        ?>
        
    </div>

    <!-- Modal para Aprovar/Negar -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="statusForm">
                <input type="hidden" id="ID_Atestado" name="ID_Atestado" value="">
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
    </script>
    <?php include_once 'footer.php'; ?>
</body>

</html>
