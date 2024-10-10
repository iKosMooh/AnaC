<?php
include 'conexao.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $clienteStmt = $conn->prepare("SELECT * FROM clientes WHERE id = ?");
    $clienteStmt->execute([$id]);
    $cliente = $clienteStmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $arquivo = $_FILES['arquivo_pdf'];
    
    if ($arquivo['error'] === UPLOAD_ERR_NO_FILE) {

        $stmt = $conn->prepare("UPDATE clientes SET nome = ?, email = ? WHERE id = ?");
        $stmt->execute([$nome, $email, $id]);
    } else {

        $nomeArquivo = uniqid() . "." . pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        move_uploaded_file($arquivo['tmp_name'], "uploads/$nomeArquivo");

        if (file_exists("uploads/" . $cliente['arquivo_pdf'])) {
            unlink("uploads/" . $cliente['arquivo_pdf']);
        }

        $stmt = $conn->prepare("UPDATE clientes SET nome = ?, email = ?, arquivo_pdf = ? WHERE id = ?");
        $stmt->execute([$nome, $email, $nomeArquivo, $id]);
    }

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Editar Cliente</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($cliente['nome']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="arquivo_pdf">Arquivo PDF Assinado (deixe em branco para manter o atual)</label>
                <input type="file" class="form-control" name="arquivo_pdf" accept=".pdf">
            </div>
            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
    </div>
</body>
</html>
