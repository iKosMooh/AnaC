<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header('Location: login.php'); // Redireciona se não estiver logado
    exit();
}
if ($_SESSION['tipo'] != 'professor') {
    echo 'Você deve estar logado como professor para acessar esta página!';
    exit();
}

$id_professor = $_SESSION['id'];

require_once '../php/connect.php';

function buscarAulasNaoMinistradas($pdo, $id_professor)
{
    $sql = "
        SELECT a.* 
        FROM aula_nao_ministrada a
        LEFT JOIN reposicao r ON a.ID_Aula_Nao_Ministrada = r.ID_Aula_Nao_Ministrada 
        WHERE a.ID_Professor = :id_professor 
        AND a.Aula_Reposta = 'Não'
        AND (r.Status_Pedido IS NULL OR r.Status_Pedido IN ('Aprovado', 'Reprovado')) 
        AND a.Justificado = 'Justificado'";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_professor', $id_professor, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<script>console.log('Erro SQL: " . $e->getMessage() . "');</script>";
        return [];
    }
}

$aulas = buscarAulasNaoMinistradas($pdo, $id_professor);
$nenhumaAula = empty($aulas);

date_default_timezone_set('America/Sao_Paulo');
$datahoje = date('Y-m-d', time());
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido de Reposição</title>
    <link rel="stylesheet" href="../css/reposi.css">
    <link rel="stylesheet" href="../css/progressBar.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <?php include_once 'header2.php'; ?>

    <div class="containerBar">
        <h1>3ª Etapa</h1>
        <div class="wrapperBar">
            <div class="progress-bar">
                <span class="progress-bar-fill" style="width: 75%;"></span>
            </div>
        </div>
    </div>
    <br>

    <div class="container">
        <h1>Apresentar Reposição de Aula</h1>
        <?php if ($nenhumaAula): ?>
            <p id="mensagemNenhumaAula">Nenhuma aula não ministrada, JUSTIFICADA para criar uma reposição .</p>
        <?php else: ?>
            <form id="form-reposicao" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_professor" value="<?php echo htmlspecialchars($id_professor); ?>">

                <label for="id_aula_nao_ministrada">Selecione a Aula Não Ministrada:</label>
                <select id="id_aula_nao_ministrada" name="id_aula_nao_ministrada" required>
                    <option value="">-- Selecione uma aula --</option>
                    <?php foreach ($aulas as $aula): ?>
                        <option value="<?php echo htmlspecialchars($aula['ID_Aula_Nao_Ministrada']); ?>">
                            Aula perdida em <?php echo htmlspecialchars($aula['Date_Time']); ?>: <?php echo htmlspecialchars($aula['Observacao']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="data_reposicao">Data da Reposição:</label>
                <input type="date" id="agend" name="data_reposicao" value="<?php echo $datahoje; ?>" min="<?php echo $datahoje; ?>" required>

                <input type="submit" value="Enviar Pedido de Reposição">
            </form>
            <div id="resultado"></div>
        <?php endif; ?>
    </div>

    <script>
        $(document).ready(function() {
            $('#form-reposicao').on('submit', function(e) {
                e.preventDefault(); // Impede o envio padrão do formulário

                var formData = new FormData(this); // Coleta todos os dados do formulário

                $.ajax({
                    url: '../php/processar_reposicao.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json', // Declara que espera um JSON como resposta
                    success: function(response) {
                        if (response && response.message) {
                            alert(response.message); // Exibe apenas a mensagem
                        } else {
                            alert('Resposta inesperada do servidor.');
                        }
                        window.location.replace('statusReposicaoProf.php');
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro no AJAX:', error);
                        alert('Ocorreu um erro ao processar seu pedido. Tente novamente mais tarde.');
                    }
                });
            });
        });
    </script>
    <?php include_once 'footer.php'; ?>

</body>

</html>