<?php
include 'conexao.php';
$clientes = $conn->query("SELECT * FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Lista de Clientes</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
<h1 class="mt-4">Sistema Shop</h1>
<?php
echo '<h2>Bem-vindo, Visitante!</h2>';
echo '<a href="login.php">Fazer login</a> ou <a href="cadastro_usuario.php">Criar conta</a>';
?>
</body>
</html>