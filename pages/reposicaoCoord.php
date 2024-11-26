<?php
session_start();
require_once '../php/connect.php';

// Verifica se o usuário está autenticado
if ($_SESSION['tipo'] != 'coordenador') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT r.*, 
a.*, 
m.Nome AS Materia, 
p.Nome AS Professor
FROM reposicao r 
JOIN aula_nao_ministrada a ON r.ID_Aula_Nao_Ministrada = a.ID_Aula_Nao_Ministrada
JOIN aula al ON a.ID_Aula = al.ID_Aula  -- Relacionando a aula que não foi ministrada
JOIN materias m ON al.ID_Materia = m.ID_Materia  -- Encontrando a matéria pela aula
JOIN professores p ON a.ID_Professor = p.ID_Professor";


$stmt = $pdo->prepare($sql);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Gerenciar Pedidos de Reposição</title>
    <link rel="stylesheet" href="../css/reposicao.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* Estilos gerais */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background-color: #f0f4f8;
            color: #333;
            background-image: url("../img/formBG.jpg");
            background-repeat: repeat;
        }

        h1 {
            text-align: center;
            color: #a31e22;
            font-size: 28px;
            margin-bottom: 2rem;
        }

        .wrapper {
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
        }

        /* Estilos gerais */
        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000000;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #a31e22;
            color: #ffffff;
        }

        /* Estilo para o modal */
        #modal {
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fefefe;
            padding: 20px;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            position: relative;
        }

        .close-modal {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 28px;
            font-weight: bold;
        }

        .close-modal:hover,
        .close-modal:focus {
            color: black;
            cursor: pointer;
        }


        @media (max-width: 965px) {

            /* Ocultar cabeçalho da tabela */
            thead,
            thead tr,
            thead th {
                display: none !important;
            }

            /* Estrutura para exibir rótulos ao lado dos valores */
            .container {
                overflow-x: auto;
            }

            table,
            thead,
            tbody,
            th,
            td,
            tr {
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
                padding: 8px 10px;
                border: none;
                border-bottom: 1px solid #ddd;
            }

            tbody td:before {
                content: attr(data-label);
                font-weight: bold;
                flex-basis: 50%;
                text-align: left;
            }
        }

        @media (max-width: 480px) {

            /* Estilos específicos para telas muito pequenas */
            tbody tr {
                padding: 8px;
                margin-bottom: 10px;
            }

            tbody td {
                padding: 6px 8px;
                font-size: 14px;
            }

            .modal-content {
                width: 90%;
            }

            .modal-content h2 {
                font-size: 1.2em;
            }

            .abrir-modal {
                font-size: 14px;
                padding: 6px 10px;
            }

            /* Ajuste no botão e no formulário */
            button,
            select,
            input {
                font-size: 14px;
            }
        }

        @media (max-width: 352px) {

            /* Estilos específicos para telas com 352px ou menos */
            tbody tr {
                padding: 6px;
                margin-bottom: 8px;
            }

            tbody td {
                font-size: 12px;
                /* Tamanho da fonte reduzido */
                padding: 4px 6px;
                /* Reduzir o padding */
                display: block;
                /* Cada célula ocupar a linha inteira */
            }

            tbody td:before {
                flex-basis: 100%;
                /* Rótulo ocupa toda a linha */
                text-align: left;
                /* Alinhamento à esquerda */
                margin-bottom: 4px;
                /* Espaçamento entre rótulo e valor */
            }

            .modal-content {
                width: 95%;
                /* Reduzir a largura do modal para se adaptar a telas pequenas */
            }

            .modal-content h2 {
                font-size: 1.1em;
                /* Reduzir tamanho do título */
            }

            .abrir-modal {
                font-size: 12px;
                /* Tamanho do botão reduzido */
                padding: 4px 8px;
                /* Reduzir o padding do botão */
            }
        }

        .status-aprovado {
            color: green;
        }

        .status-rejeitado {
            color: red;
        }

        .status-pendente {
            color: orange;
        }
    </style>
</head>

<body>
    <?php include_once 'header2.php'; ?>

    <div class="wrapper">
        <div class="container">
            <h1>Gerenciar Pedidos de Reposição</h1>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data de Reposição</th>
                        <th>Observação</th>
                        <th>Arquivo</th>
                        <th>Status</th>
                        <th>Resposta do Coordenador</th>
                        <th>Matéria</th>
                        <th>Professor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td data-label="ID"><?php echo htmlspecialchars($pedido['ID_Reposicao']); ?></td>
                            <td data-label="Data de Reposição"><?php echo htmlspecialchars($pedido['DataReposicao']); ?></td>
                            <td data-label="Observacao"><?php echo htmlspecialchars($pedido['Observacao']); ?></td>
                            <td data-label="Arquivo">
                                <?php if ($pedido['docs_plano_aula']): ?>
                                    <a href="../uploads/<?php echo htmlspecialchars($pedido['docs_plano_aula']); ?>" target="_blank">Ver Arquivo</a>
                                <?php else: ?>
                                    Nenhum arquivo
                                <?php endif; ?>
                            </td>
                            <td data-label="Status">
                                <?php
                                $status = htmlspecialchars($pedido['Status_Pedido']);
                                $statusClass = '';

                                // Define a classe CSS com base no status
                                if ($status === 'Aprovado') {
                                    $statusClass = 'status-aprovado';
                                } elseif ($status === 'Rejeitado') {
                                    $statusClass = 'status-rejeitado';
                                } else {
                                    $statusClass = 'status-pendente';
                                }
                                ?>
                                <b class="<?php echo $statusClass; ?>"><?php echo $status; ?></b>
                            </td>

                            <td data-label="Resposta do Coordenador"><?php echo htmlspecialchars($pedido['Resposta_Coordenador']); ?></td>
                            <td data-label="Matéria"><?php echo htmlspecialchars($pedido['Materia']); ?></td>
                            <td data-label="Professor"><?php echo htmlspecialchars($pedido['Professor']); ?></td>
                            <td data-label="Ações">
                                <button class="abrir-modal btn" style="background-color:#b20000; color:white" type="button" data-id="<?php echo $pedido['ID_Reposicao']; ?>" data-status="<?php echo htmlspecialchars($pedido['Status_Pedido']); ?>">Alterar Status</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>


            </table>

            <!-- Modal -->
            <div id="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <h2>Alterar Status do Pedido</h2>
                    <form id="form-status">
                        <input type="hidden" name="id_reposicao" id="id_reposicao">
                        <label for="status">Gerenciar Reposição:</label>
                        <select name="status" id="status" required>
                            <option value="" disabled selected>Selecione uma opção</option>
                            <option value="Aprovado">Aprovado</option>
                            <option value="Rejeitado">Rejeitado</option>
                            <option value="Pendente">Pendente</option>
                        </select>

                        <!-- Campo para o motivo da reprovação -->
                        <div id="motivo-container" style="display: none;">
                            <label for="Resposta_Coordenador">Motivo da Rejeição:</label>
                            <div style="padding-top:5px; padding-bottom:5px; display:flex; justify-content:center">
                                <textarea style="width: 99%;" name="Resposta_Coordenador" id="Resposta_Coordenador" rows="5"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger">Atualizar</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <?php include_once 'footer.php'; ?>

    <script>
        $(document).ready(function() {
            // Abrir modal
            $('.abrir-modal').on('click', function() {
                var id = $(this).data('id');
                var status = $(this).data('status');
                $('#id_reposicao').val(id);
                $('#status').val(status === 'Pendente' ? 'Aprovado' : status);
                $('#modal').show();

                // Controlar exibição do campo "Motivo"
                toggleMotivoField();
            });

            // Monitorar mudanças no campo status
            $('#status').on('change', toggleMotivoField);

            // Função para exibir ou ocultar o campo "Motivo"
            function toggleMotivoField() {
                if ($('#status').val() === 'Rejeitado') {
                    $('#motivo-container').show();
                    $('#motivo').attr('required', true); // Tornar o campo obrigatório
                } else {
                    $('#motivo-container').hide();
                    $('#motivo').removeAttr('required');
                }
            }

            // Fechar modal
            $('.close-modal').on('click', function() {
                $('#modal').hide();
            });

            // Enviar o formulário via AJAX
            $('#form-status').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../php/processar_reposicao_coord.php',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success !== undefined) {
                            alert(response.message);
                            if (response.success) {
                                location.reload();
                            }
                        } else {
                            alert('Resposta inesperada do servidor.');
                        }
                    },
                    error: function() {
                        alert('Erro ao atualizar o status.');
                    }
                });
            });
        });
    </script>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>