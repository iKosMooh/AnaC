<?php

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Fatec Menu</title>
    <link rel="icon" href="/img/logo.png">
    <style>
        body{
            min-height: 100vh;
        }
    </style>
</head>

<body>
    <?php include_once './pages/header.php' ?>
    <div class="login-options">
        <div class="option">
            <a href="/pages/login.php">
                <img src="/img/login prof.png" alt="Login Professor">
                <span>Login Professor</span>
            </a>
            <p>Acesse o portal de login para Professores.</p>
        </div>
    </div>
    <?php include_once './pages/footer.php' ?>
</body>

</html>
