<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['setting'], $_POST['value'])) {
    $setting = $_POST['setting'];
    $value = $_POST['value'];

    // Salvar na sessão
    $_SESSION['accessibility'][$setting] = $value;

    echo json_encode(['status' => 'success', 'message' => 'Configuração salva']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
}
?>
