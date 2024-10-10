<?php
include 'conexao.php';

if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];

    $clienteStmt = $conn->prepare("SELECT arquivo_pdf FROM clientes WHERE id = ?");
    $clienteStmt->execute([$id]);
    $cliente = $clienteStmt->fetch(PDO::FETCH_ASSOC);

    // Remove o arquivo PDF da pasta
    if (file_exists("uploads/" . $cliente['arquivo_pdf'])) {
        unlink("uploads/" . $cliente['arquivo_pdf']);
    }

    $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: index.php");
    exit;
}
?>
