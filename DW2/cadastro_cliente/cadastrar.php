<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST ['nome'];
    $email = $_POST ['email']; 
    $arquivo = $_FILES['arquivo_pdf'];

    if ($arquivo['error'] === UPLOAD_ERR_OK) {
        $nomeArquivo = uniqid() . "." . $arquivo['name'];
        move_uploaded_file($arquivo ['tmp_name'], "uploads/$nomeArquivo");

        $stnt = $conn->prepare("INSERT INTO clientes (nome, email, arquivo_pdf) VALUES (?, ?, ?)");
        $stnt->execute([$nome, $email, $nomeArquivo]);

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Cadastrar Cliente</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" name="nome" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="arquivo_pdf">Arquivo PDF Assinado</label>
                <input type="file" class="form-control" name="arquivo_pdf" accept=".pdf" required>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>
</body>
</html>