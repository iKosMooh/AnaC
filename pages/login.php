<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/login.css">
    <title>Login</title>
</head>

<body>
    <?php include_once 'header.php'; ?>
    <div class="flex-container">
        <div class="imagem">
            <img src="../img/fatec.jpg" alt="Fatec">
        </div>
        <div class="container">
            <div id="tituloLogin">
                <h1>LOGIN</h1>
            </div>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form id="loginFormProfessor" method="POST" action="../php/login_verify.php">
                <div class="inputlabel">
                    <label for="usuarioProfessor">E-mail:</label>
                    <input type="text" name="usuarioProfessor" id="usuarioProfessor" required
                        placeholder="Digite seu e-mail" />
                </div>
                <br>
                <div class="inputlabel">
                    <label for="senhaProfessor">Senha:</label>
                    <input type="password" name="senhaProfessor" id="senhaProfessor" required
                        placeholder="Digite sua senha" />
                </div>
                <br>
                <button type="submit">Entrar</button>
            </form>
        </div>
    </div>
    <?php include_once 'footer.php'; ?>
</body>

</html>
