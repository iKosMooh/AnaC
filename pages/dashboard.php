<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php'); // Redireciona se não estiver logado
    exit();
}

// Exibir informações do usuário
echo "<h1>Bem-vindo, " . htmlspecialchars($_SESSION['nome']) . "</h1>";
echo "<p>Tipo de usuário: " . htmlspecialchars($_SESSION['tipo']) . "</p>";
echo "<p><a href='logout.php'>Sair</a></p>";
