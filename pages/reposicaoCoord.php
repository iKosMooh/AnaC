<?php
session_start();
require_once '../php/connect.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Recuperar pedidos de reposição com matéria e nome do professor
$sql = "SELECT r.ID_Reposicao, r.DataReposicao, r.Mensagem, r.docs_plano_aula, r.Status_Pedido, 
               a.ID_Aula_Nao_Ministrada, m.Nome AS Materia, p.Nome AS Professor
        FROM reposicao r 
        JOIN aula_nao_ministrada a ON r.ID_Aula_Nao_Ministrada = a.ID_Aula_Nao_Ministrada
        JOIN materias m ON a.ID_Materia = m.ID_Materia
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* Estilo para o modal */
        #modal {
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close-modal {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close-modal:hover,
        .close-modal:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include_once 'header.php'; ?>

    <div class="wrapper">
        <div class="container">
            <h1>Gerenciar Pedidos de Reposição</h1>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data de Reposição</th>
                        <th>Mensagem</th>
                        <th>Arquivo</th>
                        <th>Status</th>
                        <th>Matéria</th>
                        <th>Professor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['ID_Reposicao']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['DataReposicao']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['Mensagem']); ?></td>
                            <td>
                                <?php if ($pedido['docs_plano_aula']): ?>
                                    <a href="../uploads/<?php echo htmlspecialchars($pedido['docs_plano_aula']); ?>" target="_blank">Ver Arquivo</a>
                                <?php else: ?>
                                    Nenhum arquivo
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($pedido['Status_Pedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['Materia']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['Professor']); ?></td>
                            <td>
                                <button class="abrir-modal" data-id="<?php echo $pedido['ID_Reposicao']; ?>" data-status="<?php echo htmlspecialchars($pedido['Status_Pedido']); ?>">Alterar Status</button>
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
                        <label for="status">Novo Status:</label>
                        <select name="status" id="status" required>
                            <option value="Aprovado">Aprovado</option>
                            <option value="Rejeitado">Rejeitado</option>
                        </select>
                        <button type="submit">Atualizar</button>
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
            });

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
</html>

