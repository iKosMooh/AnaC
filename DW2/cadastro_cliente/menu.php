<!-- Barra de Navegação com Bootstrap -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Sistema</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Página Inicial</a>
                </li>
                <!-- Adicione mais links conforme necessário -->
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="nav-link">Bem-vindo, <?php echo $_SESSION['nome_usuario'];?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-danger" href="logout.php">Sair</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Inclua Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alphal/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alphal/dist/js/bootstrap.bundle.min.js"></script>