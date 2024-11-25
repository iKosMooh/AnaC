<?php
session_start();
include '../php/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuarioProfessor'];
    $senha = $_POST['senhaProfessor'];

    // Verificar se é um Professor
    $stmt = $pdo->prepare("SELECT * FROM Professores WHERE (Usuario = :usuario OR Email = :usuario OR EmailInstitucional = :usuario) AND Senha = :senha");
    $stmt->execute(['usuario' => $usuario, 'senha' => $senha]);
    $professor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($professor) {
        $_SESSION['id'] = $professor['ID_Professor'];
        $_SESSION['nome'] = $professor['Nome'];
        $_SESSION['tipo'] = 'professor';
        header('Location: ../pages/dashboard.php');
        exit();
    }

    // Verificar se é um Coordenador
    $stmt = $pdo->prepare("SELECT * FROM Coordenadores WHERE (Usuario = :usuario OR EmailInstitucional = :usuario OR Email = :usuario) AND Senha = :senha");
    $stmt->execute(['usuario' => $usuario, 'senha' => $senha]);
    $coordenador = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($coordenador) {
        $_SESSION['id'] = $coordenador['ID_Coordenador'];
        $_SESSION['nome'] = $coordenador['Nome'];
        $_SESSION['tipo'] = 'coordenador';
        header('Location: ../pages/dashboard.php');
        exit();
    }

    // Caso não encontre, redirecionar com mensagem de erro
    $_SESSION['erro_login'] = "E-mail ou senha incorretos";
    header('Location: ../php/login_verify.php'); // Redireciona para a página de erro
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro de Login</title>
</head>
<body>
    <h1>Erro no Login</h1>

    <?php
    // Exibir a mensagem de erro se existir
    if (isset($_SESSION['erro_login'])) {
        echo '<p style="color: red;">' . $_SESSION['erro_login'] . '</p>';
        unset($_SESSION['erro_login']); // Limpar a mensagem após exibição
    }
    ?>

    <p><a href="../pages/login.php">Voltar para a página de login</a></p>
</body>
</html>
