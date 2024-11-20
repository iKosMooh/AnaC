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
            header('Location: ../pages/dashboard.php'); // Redireciona para a página do dashboard
            exit();
        }

        // Caso não encontre
        $error = "Usuário ou senha inválidos.";
    }
} else {
    header('Location: ../pages/dashboard.php'); // Redireciona para a página do dashboard se o usuário já estiver logado
    exit();
}
?>
