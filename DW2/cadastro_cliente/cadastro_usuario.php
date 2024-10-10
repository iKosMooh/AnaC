<?php
session_start();
include 'conexao.php'; // Conexão com banco de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a se-
    $sql = "INSERT INTO usuarios (nome, email, senha, nivel_acesso) VALUES (?,?,
?, 'COMUM')";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nome, $email, $senha]);
    echo "<div class='alert alert-success'>Usuário registrado com sucesso!</div>";
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Cadastro de Usuário</h2>
                        <form action="cadastro_usuario.php" method="post">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome:</label>
                                <input type="text" name="nome" id="nome"
                                    class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha:</label>
                                <input type="password" name="senha" id="senha" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alphal/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>