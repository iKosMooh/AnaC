<?php

if (isset($_SESSION)){
session_start();
}

include '../php/connect.php';
if (!isset($_SESSION['tipo'])){
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Verificar se é um Professor
    $stmt = $pdo->prepare("
        SELECT * FROM Professores 
        WHERE (Usuario = :usuario OR Email = :usuario OR EmailInstitucional = :usuario) 
        AND Senha = :senha
    ");
    $stmt->execute(['usuario' => $usuario, 'senha' => $senha]);
    $professor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($professor) {
        $_SESSION['id'] = $professor['ID_Professor'];
        $_SESSION['nome'] = $professor['Nome'];
        $_SESSION['tipo'] = 'professor';
        header('Location: dashboard.php'); // Redireciona para a página do dashboard
        exit();
    }

    // Verificar se é um Coordenador
    $stmt = $pdo->prepare("
        SELECT * FROM Coordenadores 
        WHERE (Usuario = :usuario OR EmailInstitucional = :usuario) 
        AND Senha = :senha
    ");
    $stmt->execute(['usuario' => $usuario, 'senha' => $senha]);
    $coordenador = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($coordenador) {
        $_SESSION['id'] = $coordenador['ID_Coordenador'];
        $_SESSION['nome'] = $coordenador['Nome'];
        $_SESSION['tipo'] = 'coordenador';
        header('Location: dashboard.php'); // Redireciona para a página do dashboard
        exit();
    }

    // Caso não encontre
    $error = "Usuário ou senha inválidos.";
}
} else{
    header('Location: dashboard.php'); // Redireciona para a página do dashboard
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <?php include_once 'header.php' ?>
    <h1>Login</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="usuario" placeholder="Usuário, Email Comum ou Email Institucional" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
