<?php
session_start();
require_once '../php/connect.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura o ID e o novo status
    $id_reposicao = $_POST['id_reposicao'];
    $status = $_POST['status'];

    // Atualiza o status do pedido de reposição
    $sqlUpdate = "UPDATE reposicao SET Status_Pedido = :status WHERE ID_Reposicao = :id_reposicao";
    
    try {
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':status', $status);
        $stmtUpdate->bindParam(':id_reposicao', $id_reposicao, PDO::PARAM_INT);

        if ($stmtUpdate->execute()) {
            echo json_encode(['success' => true, 'message' => 'Status atualizado com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o status.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
}
