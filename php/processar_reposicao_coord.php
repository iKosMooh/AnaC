<?php
session_start();
require_once '../php/connect.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_reposicao = $_POST['id_reposicao'];
    $status = $_POST['status'];
    $motivo = isset($_POST['motivo']) ? $_POST['motivo'] : null;

    // Atualiza o status e o motivo (se houver) do pedido de reposição
    $sqlUpdate = "UPDATE reposicao SET Status_Pedido = :status, motivo = :motivo WHERE ID_Reposicao = :id_reposicao";
    
    try {
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':status', $status);
        $stmtUpdate->bindParam(':id_reposicao', $id_reposicao, PDO::PARAM_INT);

        // Define o motivo somente se o status for "Rejeitado"
        if ($status === 'Rejeitado' && !empty($motivo)) {
            $stmtUpdate->bindParam(':motivo', $motivo, PDO::PARAM_STR);
        } else {
            $stmtUpdate->bindValue(':motivo', null, PDO::PARAM_NULL);
        }

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

