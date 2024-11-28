<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$tipo = null;

if (isset($_SESSION['tipo'])) {
    if ($_SESSION['tipo'] == 'professor') {
        $tipo = 'Professor';
        // $exemplo = '<a href='/pagina/destino'>Nome do Campo</a>'
    } else if ($_SESSION['tipo'] == 'coordenador') {
        $tipo = 'Coordenador';
    }
}


// Aplicar configurações de acessibilidade
if (isset($_SESSION['accessibility'])) {
    $accessibility = $_SESSION['accessibility'];
    echo '<style>';
    if (isset($accessibility['fontSize']) && $accessibility['fontSize'] !== 'default') {
        echo "body { font-size: {$accessibility['fontSize']}px; }";
    }
    if (isset($accessibility['fontFamily'])) {
        echo "body { font-family: {$accessibility['fontFamily']}; }";
    }
    if (isset($accessibility['daltonismo'])) {
        if ($accessibility['daltonismo'] === 'protanopia') {
            echo "body { filter: grayscale(50%) hue-rotate(20deg); }";
        } elseif ($accessibility['daltonismo'] === 'deuteranopia') {
            echo "body { filter: grayscale(50%) hue-rotate(-20deg); }";
        } elseif ($accessibility['daltonismo'] === 'tritanopia') {
            echo "body { filter: grayscale(50%) hue-rotate(90deg); }";
        }
    }
    echo '</style>';
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal do Fatec</title>
    <link rel="icon" href="../img/logo.png">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/inclusivo.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
</head>

<body>
    <header class="header">
        <nav class="navbar">
            <a href="dashboard.php"><div style="padding:25px"><img width="70px" src="../img/voltar.svg" alt="Logo"></div></a>
            <ul class="nav-menu nav-menu-border">
                <li class="titulo">
                    <?php if (isset($_SESSION['tipo'])): ?>
                    <?php else: ?>
                        Portal FATEC
                    <?php endif; ?>
                </li>
                <li class="titulo1  saudacao">
                    <?php if (isset($_SESSION['nome'])): ?>
                        <h3><a href="#">Olá, <?= htmlspecialchars($_SESSION['nome']); ?></a></h3>
                    <?php endif; ?>
                </li>
                <!-- Exibir botão com base no estado de sessão -->
                <li class="nav-item sair">
                    <?php if (isset($_SESSION['tipo'])): ?>
                        <a class="user-link" href="logout.php">Sair</a>
                    <?php else: ?>
                        <a class="user-link" href="login.php">Login</a>
                    <?php endif; ?>
                </li>
            </ul>

        </nav>
    </header>

    <div class="accessibility-button" id="accessibilityButton">
        <span>☰</span>
    </div>

    <div class="accessibility-menu" id="accessibilityMenu">
        <h3>Acessibilidade</h3>
        <button class="accessibility-option" id="increaseFont">Aumentar Fonte</button>
        <button class="accessibility-option" id="decreaseFont">Diminuir Fonte</button>
        <button class="accessibility-option" id="resetFont">Fonte Padrão</button>
        <button class="accessibility-option" id="dislexiaFont">Fonte para Dislexia</button>
        <select class="accessibility-option" id="daltonismoType">
            <option value="">Selecione o Tipo de Daltonismo</option>
            <option value="">Nenhum</option>
            <option value="protanopia">Protanopia</option>
            <option value="deuteranopia">Deuteranopia</option>
            <option value="tritanopia">Tritanopia</option>
        </select>
    </div>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const hamburger = document.querySelector(".hamburguer");
            const navMenu = document.querySelector(".nav-menu");

            hamburger.addEventListener("click", () => {
                hamburger.classList.toggle('active');
                navMenu.classList.toggle('active');
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            const originalFontSize = $('body').css('font-size');
            let currentFontSize = parseFloat(originalFontSize);

            function saveAccessibilitySettings(setting, value) {
                $.ajax({
                    url: '../php/process_acessibilidade.php',
                    method: 'POST',
                    data: {
                        setting: setting,
                        value: value,
                    },
                    success: function(response) {
                        console.log('Configuração salva:', response);
                    },
                    error: function() {
                        console.error('Erro ao salvar configuração.');
                    },
                });
            }

            function adjustFontSize(delta) {
                currentFontSize += delta;
                const elementsToResize = $('body, p, th, td, h1, h2, h3, h4, h5, h6, span, a, li, label, button');
                elementsToResize.css('font-size', `${currentFontSize}px`);
                saveAccessibilitySettings('fontSize', currentFontSize);
            }

            // Lógica para abrir/fechar o menu de acessibilidade
            $('#accessibilityButton').click(function() {
                $('#accessibilityMenu').toggle(); // Alterna a exibição do menu
            });

            $('#increaseFont').click(function() {
                adjustFontSize(1); // Aumenta em 1px
            });

            $('#decreaseFont').click(function() {
                adjustFontSize(-1); // Diminui em 1px
            });

            $('#resetFont').click(function() {
                currentFontSize = parseFloat(originalFontSize); // Certifique-se de usar o valor inicial como número
                const elementsToReset = $('body, p, th, td, h1, h2, h3, h4, h5, h6, span, a, li, label, button');

                // Reseta o tamanho da fonte para o original
                elementsToReset.each(function() {
                    const defaultFontSize = $(this).css('font-size', '');
                });

                // Define a fonte padrão no body
                $('body').css('font-family', 'Ubuntu');

                // Salva a configuração como padrão na sessão
                saveAccessibilitySettings('fontSize', 'default');
                saveAccessibilitySettings('fontFamily', 'Ubuntu');
            });


            $('#dislexiaFont').click(function() {
                $('body').css('font-family', 'OpenDyslexic');
                saveAccessibilitySettings('fontFamily', 'OpenDyslexic');
                location.reload();
            });

            $('#daltonismoType').change(function() {
                const type = $(this).val();
                if (type === 'protanopia') {
                    $('body').css('filter', 'grayscale(50%) hue-rotate(20deg)');
                } else if (type === 'deuteranopia') {
                    $('body').css('filter', 'grayscale(50%) hue-rotate(-20deg)');
                } else if (type === 'tritanopia') {
                    $('body').css('filter', 'grayscale(50%) hue-rotate(90deg)');
                } else {
                    $('body').css('filter', 'none');
                }
                saveAccessibilitySettings('daltonismo', type);
            });
        });
    </script>


</body>

</html>