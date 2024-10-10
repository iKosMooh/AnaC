<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistema de Clientes</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h2 class="text-center">Login</h2>
                <form action="autenticar.php" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" id="email" class="formcontrol" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha: </label>
                        <input type="password" name="senha" id="senha" class="formcontrol" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>
                        <div class="mt-3">
                            <p>Ainda não tem uma conta? <a href="cadastro_usuario.php">Cadastre-se aqui</a></p>
                        </div>
            </div>
        </div>
    </div>
</body>

</html>