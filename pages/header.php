<?php
if (isset($_SESSION['tipo'])){
session_start();
}

$tipo = null;

if (isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'professor') {
        $tipo = 'Professor';
        // $exemplo = '<a href='/pagina/destino'>Nome do Campo</a>'
    } 
    else if ($_SESSION['tipo'] == 'coordenador') {
        $tipo = 'Coordenador';
    }
} 

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal do Coordenador</title>
    <link rel="icon" href="../img/logo.png">
    <link rel="stylesheet" href="../css/header.css"> 
</head>

<body>
    <header class="header">
        <nav class="navbar">
            <a href="#" class="logo"><img src="../img/Logo-Fatec-1200x800-1-removebg-preview.png" alt="Logo"></a>
            <ul class="nav-menu nav-menu-border">
                <li class="titulo">Portal FATEC</li>
                <!-- Exibir botão com base no estado de sessão -->
                <li class="nav-item sair">
                    <?php if (isset($_SESSION['tipo'])): ?>
                        <a class="user-link" href="/pages/logout.php">Sair</a>
                    <?php else: ?>
                        <a class="user-link" href="/pages/login.php">Login</a>
                    <?php endif; ?>
                </li>
            </ul>
            <div class="hamburguer">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </nav>
    </header>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const hamburger = document.querySelector(".hamburguer");
            const navMenu = document.querySelector(".nav-menu");

            hamburger.addEventListener("click", () => {
                hamburger.classList.toggle('active');
                navMenu.classList.toggle('active');
            });
        });
    </script>
</body>

</html>

