<?php
session_start();
// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header('Location: login.php'); // Redireciona se não estiver logado
    exit();
}

$id_professor = $_SESSION['id'];
require_once '../php/connect.php';

function buscarPedidosReposicao($pdo, $id_professor) {
    $sql = "
        SELECT r.ID_Reposicao, a.Date_Time, r.DataReposicao, r.Mensagem, r.docs_plano_aula, r.Status_Pedido,r.Motivo, a.ID_Aula_Nao_Ministrada
        FROM reposicao r
        JOIN aula_nao_ministrada a ON r.ID_Aula_Nao_Ministrada = a.ID_Aula_Nao_Ministrada
        WHERE a.ID_Professor = :id_professor";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_professor', $id_professor, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao buscar pedidos de reposição: " . $e->getMessage();
        return [];
    }
}

$pedidos = buscarPedidosReposicao($pdo, $id_professor);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Pedidos de Reposição</title>
    <link rel="stylesheet" href="../css/status.css">
</head>

<body>
<?php include_once 'header.php'; ?>

    <div class="container">
        <h1>Pedidos de Reposição</h1>

        <?php if (empty($pedidos)): ?>
            <p id="mensagemNenhumaAula">Nenhum pedido de reposição encontrado.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Aula Não Ministrada</th>
                        <th>Data da Reposição</th>
                        <th>Mensagem</th>
                        <th>Documento do Plano de Aula</th>
                        <th>Status</th>
                        <th>Motivo</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo $pedido['ID_Reposicao']; ?></td>
                            <td><?php echo $pedido['Date_Time']; ?></td>
                            <td><?php echo $pedido['DataReposicao']; ?></td>
                            <td><?php echo htmlspecialchars($pedido['Mensagem']); ?></td>
                            <td>
                                <?php if ($pedido['docs_plano_aula']): ?>
                                    <a href="../uploads/<?php echo htmlspecialchars($pedido['docs_plano_aula']); ?>" target="_blank">Ver Documento</a>
                                <?php else: ?>
                                    Nenhum documento anexado
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($pedido['Status_Pedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['Motivo']); ?></td>
                            <td>
                                <?php if ($pedido['Status_Pedido'] == 'Rejeitado'): ?>
                                    <button onclick="openModal(<?php echo $pedido['ID_Reposicao']; ?>, '<?php echo htmlspecialchars($pedido['Mensagem']); ?>', '<?php echo $pedido['DataReposicao']; ?>', '<?php echo htmlspecialchars($pedido['docs_plano_aula']); ?>','<?php echo htmlspecialchars($pedido['ID_Aula_Nao_Ministrada']); ?>')">Editar</button>
                                <?php else: ?>
                                    <button disabled>Ação não permitida</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Modal para edição de pedidos -->
    <div id="myModal" class="modal">
        <div class="modal-content edit-container">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Editar Pedido de Reposição</h2>
            <form id="editForm" action="../php/processar_reposicao.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_aula_nao_ministrada" id="id_aula_nao_ministrada" value="">
                <input type="hidden" name="id_reposicao" id="id_reposicao" value="">

                <label for="data_reposicao">Data da Reposição:</label>
                <input type="date" id="data_reposicao" name="data_reposicao" required>
                <br><br>

                <label for="mensagem">Mensagem:</label>
                <textarea id="mensagem" name="mensagem" required></textarea>
                <br><br>

                <label for="docs_plano_aula">Documento do Plano de Aula:</label>
                <input type="file" id="docs_plano_aula" name="docs_plano_aula" accept=".pdf,.doc,.docx">
                <br><br>

                <input type="hidden" name="id_professor" id="id_professor" value="<?php echo $_SESSION['id']; ?>">

                <div class="submit-container">
                    <input type="submit" value="Atualizar Pedido">
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id, mensagem, dataReposicao, docs, idAulaNaoMinistrada) {
            document.getElementById("id_reposicao").value = id;
            document.getElementById("mensagem").value = mensagem;
            document.getElementById("data_reposicao").value = dataReposicao;
            document.getElementById("id_aula_nao_ministrada").value = idAulaNaoMinistrada;
            document.getElementById("myModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        // Fecha o modal se o usuário clicar fora dele
        window.onclick = function(event) {
            const modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
<?php include_once 'footer.php'; ?>

</body>

</html>
