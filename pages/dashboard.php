<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php'); // Redireciona se não estiver logado
    exit();
}

include_once 'header.php';
echo '<link rel="stylesheet" href="../css/dashboard.css">'; 

echo '<div class="dashboard-container">';
echo "<h1>Bem-vindo, " . htmlspecialchars($_SESSION['nome']) . "</h1>";

if ($_SESSION['tipo'] === 'professor') {
    echo "<h2>Acesso aos Formulários</h2>";
    echo '<div class="form-options">';
        echo '<div class="option">';
            echo '<a href="/pages/CadastroNaoMinistradas.php">';
                echo '<img src="../img/81088.png" alt="Formulário de Cadastro de Faltas">';
                echo '<span>Cadastro de Faltas</span>';
            echo '</a>';
            echo '<p>Neste forms você deve cadastrar uma aula que você não ministrou para justifica-la posteriormente</p>';
        echo '</div>';

        echo '<div class="option">';
            echo '<a href="/pages/FormsJustificativa.php">';
                echo '<img src="../img/1570754.png" alt="Formulário de Justificativa">';
                echo '<span>Formulário de Justificativa</span>';
            echo '</a>';
            echo '<p>Preencha o formulário para justificativa de faltas.</p>';
        echo '</div>';

        echo '<div class="option">';
            echo '<a href="/pages/ReposicaoForm.php">';
                echo '<img src="../img/repos.png" alt="Formulário de Reposição">';
                echo '<span>Formulário de Reposição</span>';
            echo '</a>';
            echo '<p>Preencha o formulário para marcar uma aula para repo-la.</p>';
        echo '</div>';

        echo '<div class="option">';
            echo '<a href="/pages/statusReposicaoProf.php">';
                echo '<img src="../img/status.png" alt="Ver Status dos pedidos de Reposições">';
                echo '<span>Ver Status dos pedidos de Reposições</span>';
            echo '</a>';
            echo '<p>Verifique o Status dos pedidos de Reposições.</p>';
        echo '</div>';
    echo '</div>'; 

} elseif ($_SESSION['tipo'] === 'coordenador') {
    echo "<h2>Acesso aos Formulários</h2>";
    echo '<div class="form-options">'; 
        echo '<div class="option top">'; 
            echo '<a href="/pages/reposicaoCoord.php">';
                echo '<img src="../img/81088.png" alt="Painel do Coordenador">';
                echo '<span>Painel do Coordenador</span>';
            echo '</a>';
            echo '<p>Visualize todas as reposições de aulas enviadas e aprove ou as reprove.</p>';
        echo '</div>';
    echo '</div>';
}
echo '</div>';

include_once 'footer.php';

