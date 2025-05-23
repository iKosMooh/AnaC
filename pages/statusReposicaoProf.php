<?php
session_start();
// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header('Location: login.php'); // Redireciona se não estiver logado
    exit();
}

$id_professor = $_SESSION['id'];
require_once '../php/connect.php';

function buscarPedidosReposicao($pdo, $id_professor)
{
    $sql = "
        SELECT r.ID_Reposicao, a.Date_Time, r.DataReposicao, a.Observacao, a.docs, r.Status_Pedido, r.Resposta_Coordenador, a.ID_Aula_Nao_Ministrada
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
    <link rel="stylesheet" href="../css/progressBar.css">
</head>

<body>
    <?php include_once 'header2.php'; ?>

    <div class="containerBar">
        <h1>Ultima Etapa</h1>
        <div class="wrapperBar">
            <div class="progress-bar">
                <span class="progress-bar-fill" style="width: 100%;"></span>
            </div>
        </div>
    </div>
    <br>

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
                        <th>Observação Prof.</th>
                        <th>Documento</th>
                        <th>Status</th>
                        <th>Resposta Coordenador</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td data-label="ID"><?php echo $pedido['ID_Reposicao']; ?></td>
                            <td data-label="Aula Não Ministrada"><?php echo $pedido['Date_Time']; ?></td>
                            <td data-label="Data da Reposição"><?php echo $pedido['DataReposicao']; ?></td>
                            <td data-label="Observação Prof."><?php echo htmlspecialchars($pedido['Observacao']); ?></td>
                            <td data-label="Documento do Plano de Aula">
                                <?php if ($pedido['docs']): ?>
                                    <a href="../uploads/<?php echo htmlspecialchars($pedido['docs']); ?>" target="_blank">Ver Documento</a>
                                <?php else: ?>
                                        Nenhum documento anexado
                                    <?php endif; ?>
                                </td>
                            <td data-label="Status"><?php echo htmlspecialchars($pedido['Status_Pedido']); ?></td>
                            <td data-label="Resposta Coordenador"><?php echo htmlspecialchars($pedido['Resposta_Coordenador']); ?></td>
                            <td data-label="Ação">
                                <?php if ($pedido['Status_Pedido'] == 'Rejeitado'): ?>
                                    <button onclick="openModal(<?php echo $pedido['ID_Reposicao']; ?>, '<?php echo htmlspecialchars($pedido['Observacao']); ?>', '<?php echo $pedido['DataReposicao']; ?>', '<?php echo htmlspecialchars($pedido['docs']); ?>', '<?php echo htmlspecialchars($pedido['ID_Aula_Nao_Ministrada']); ?>')">Editar</button>
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
            <form id="editForm" action="../php/processar_reposicao.php" method="post" enctype="multipart/form-data" onsubmit="submitForm(event)">
                <input type="hidden" name="id_aula_nao_ministrada" id="id_aula_nao_ministrada" value="">
                <input type="hidden" name="id_reposicao" id="id_reposicao" value="">

                <label for="data_reposicao">Data da Reposição:</label>
                <input type="date" id="data_reposicao" name="data_reposicao" required>
                <br><br>

                <label for="Observacao">Observação:</label>
                <textarea id="Observacao" name="Observacao" required></textarea>
                <br><br>

                <input type="hidden" name="id_professor" id="id_professor" value="<?php echo $_SESSION['id']; ?>">

                <div class="submit-container">
                    <input type="submit" value="Atualizar Pedido">
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id, Observacao, dataReposicao, docs, idAulaNaoMinistrada) {
            document.getElementById("id_reposicao").value = id;
            document.getElementById("Observacao").value = Observacao;
            document.getElementById("data_reposicao").value = dataReposicao;
            document.getElementById("id_aula_nao_ministrada").value = idAulaNaoMinistrada;
            document.getElementById("myModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        // Função para impedir o envio padrão do formulário e processar via AJAX
        function submitForm(event) {
            event.preventDefault(); // Impede o envio normal do formulário

            // Cria um FormData com os dados do formulário
            var formData = new FormData(document.getElementById('editForm'));

            // Envia os dados via fetch (AJAX)
            fetch('../php/processar_reposicao.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    // Recarrega a página após o sucesso do envio
                    location.reload(); // Recarrega a página para mostrar as atualizações
                })
                .catch(error => {
                    console.error('Erro ao atualizar o pedido:', error);
                });
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