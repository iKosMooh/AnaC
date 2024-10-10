<?php
session_start();
require_once 'conexao.php';
include 'menu.php'; // Incluir o menu (navbar) no topo do arquivo
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle Administrador</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Painel de Controle</h1>
        <div class="mt-4">
            <button id="btnClientes" class="btn btn-primary" onclick="showSection('clientes')">Lista de Clientes</button>
            <?php if ($_SESSION['nivel_acesso'] === 'admin' || $_SESSION['nivel_acesso'] === 'ADMIN'): ?>
                <button id="btnUsuarios" class="btn btn-secondary" onclick="showSection('usuarios')">Lista de Usuários</button>
            <?php endif; ?>
        </div>

        <div id="clientes" class="mt-4">
            <h2>Lista de Clientes:</h2>
            <?php
            // Exemplo de consulta para exibir a lista de clientes
            $clientes = $conn->query("SELECT * FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
            if (count($clientes) > 0) {
                echo "<table class='table table-bordered'>";
                echo "<thead><tr><th>Nome</th><th>Email</th><th>Arquivo PDF</th><th>Ações</th></tr></thead><tbody>";
                foreach ($clientes as $cliente) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($cliente['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($cliente['email']) . "</td>";
                    echo "<td><a href='uploads/" . htmlspecialchars($cliente['arquivo_pdf']) . "' target='_blank'>Ver PDF</a></td>";
                    echo "<td>
                            <a href='editar.php?id=" . $cliente['id'] . "' class='btn btn-warning'>Editar</a>
                            <a href='excluir.php?id=" . $cliente['id'] . "' class='btn btn-danger' onclick=\"return confirm('A exclusão será permanente! Tem certeza disso?')\">Excluir</a>
                          </td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>Não há clientes cadastrados.</p>";
            }
            ?>
        </div>

        <div id="usuarios" class="mt-4 hidden">
            <h2>Lista de Usuários:</h2>
            <?php
            // Exemplo de consulta para exibir a lista de usuários
            $usuarios = $conn->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
            if (count($usuarios) > 0) {
                echo "<table class='table table-bordered'>";
                echo "<thead><tr><th>Nome</th><th>Email</th><th>Nível de Acesso</th><th>Ações</th></tr></thead><tbody>";
                foreach ($usuarios as $usuario) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($usuario['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($usuario['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($usuario['nivel_acesso']) . "</td>";
                    echo "<td>
                            <a href='editar_usuario.php?id=" . $usuario['id'] . "' class='btn btn-warning'>Editar</a>
                            <a href='excluir_usuario.php?id=" . $usuario['id'] . "' class='btn btn-danger' onclick=\"return confirm('A exclusão será permanente! Tem certeza disso?')\">Excluir</a>
                          </td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>Não há usuários cadastrados.</p>";
            }
            ?>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function showSection(section) {
            // Ocultar todas as seções
            document.getElementById('clientes').classList.add('hidden');
            document.getElementById('usuarios').classList.add('hidden');
            
            // Mostrar a seção selecionada
            document.getElementById(section).classList.remove('hidden');
        }
    </script>
</body>
</html>
