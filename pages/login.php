<?php
session_start();
include '../php/connect.php';

if (!isset($_SESSION['tipo'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usuario = $_POST['usuarioProfessor'];
        $senha = $_POST['senhaProfessor'];

        // Verificar se é um Professor
        $stmt = $pdo->prepare("
            SELECT * FROM Professores 
            WHERE (Usuario = :usuario OR Email = :usuario OR EmailInstitucional = :usuario) 
            AND Senha = :senha
        ");
        $stmt->execute(['usuario' => $usuario, 'senha' => $senha]);
        $professor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($professor) {
            $_SESSION['id'] = $professor['ID_Professor'];
            $_SESSION['nome'] = $professor['Nome'];
            $_SESSION['tipo'] = 'professor';
            header('Location: dashboard.php'); // Redireciona para a página do dashboard
            exit();
        }

        // Verificar se é um Coordenador
        $stmt = $pdo->prepare("
            SELECT * FROM Coordenadores 
            WHERE (Usuario = :usuario OR EmailInstitucional = :usuario OR Email = :usuario) 
            AND Senha = :senha
        ");
        $stmt->execute(['usuario' => $usuario, 'senha' => $senha]);
        $coordenador = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($coordenador) {
            $_SESSION['id'] = $coordenador['ID_Coordenador'];
            $_SESSION['nome'] = $coordenador['Nome'];
            $_SESSION['tipo'] = 'coordenador';
            header('Location: dashboard.php'); // Redireciona para a página do dashboard
            exit();
        }

        // Caso não encontre
        $error = "Usuário ou senha inválidos.";
    }
} else {
    header('Location: dashboard.php'); // Redireciona para a página do dashboard se o usuário já estiver logado
    exit();
}
?>

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
            <img src="/img/fatec.jpg" alt="Fatec">
        </div>
        <div class="container">
            <div id="tituloLogin">
                <h1>LOGIN</h1>
            </div>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form id="loginFormProfessor" method="POST">
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
</body>

</html>
