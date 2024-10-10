<?php
session_start();
include 'conexao.php'; // Arquivo para conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Preparar a consulta para buscar o usuário
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar se o usuário existe e a senha está correta
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Armazenar informações do usuário na sessão
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['nome_usuario'] = $usuario['nome'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['nivel_acesso'] = $usuario['nivel_acesso'];

        // Redirecionar para a página de sucesso ou dashboard
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "E-mail ou senha incorretos.<br>";
        echo "<a href='login.php'>Clique aqui para Voltar</a>";
    }
}
?>