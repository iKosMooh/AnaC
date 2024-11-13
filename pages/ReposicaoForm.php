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

function buscarAulasNaoMinistradas($pdo, $id_professor) {
    $sql = "
        SELECT a.* 
        FROM aula_nao_ministrada a
        LEFT JOIN reposicao r ON a.ID_Aula_Nao_Ministrada = r.ID_Aula_Nao_Ministrada 
        WHERE a.ID_Professor = :id_professor 
        AND a.Aula_Reposta = 'Não'
        AND (r.Status_Pedido IS NULL OR r.Status_Pedido IN ('Aprovado', 'Reprovado'))";

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
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido de Reposição</title>
    <link rel="stylesheet" href="../css/reposi.css"> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <?php include_once 'header.php'; ?>

    <div class="container">
        <h1>Solicitar Reposição de Aula</h1>
        <?php if ($nenhumaAula): ?>
            <p id="mensagemNenhumaAula">Nenhuma aula não ministrada encontrada para justificar.</p>
        <?php else: ?>
            <form id="form-reposicao" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_professor" value="<?php echo htmlspecialchars($id_professor); ?>">

                <label for="id_aula_nao_ministrada">Selecione a Aula Não Ministrada:</label>
                <select id="id_aula_nao_ministrada" name="id_aula_nao_ministrada" required>
                    <option value="">-- Selecione uma aula --</option>
                    <?php foreach ($aulas as $aula): ?>
                        <option value="<?php echo htmlspecialchars($aula['ID_Aula_Nao_Ministrada']); ?>">
                            Aula em <?php echo htmlspecialchars($aula['Date_Time']); ?>: <?php echo htmlspecialchars($aula['Observacao']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="data_reposicao">Data da Reposição:</label>
                <input type="date" id="data_reposicao" name="data_reposicao" required>

                <label for="docs_plano_aula">Documento do Plano de Aula:</label>
                <input type="file" id="docs_plano_aula" name="docs_plano_aula" accept=".pdf,.doc,.docx">

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
                    success: function(response) {
                        alert(response); // Exibe a resposta na div
                    },
                    error: function() {
                        alert('Ocorreu um erro ao processar seu pedido.');
                    }
                });
            });
        });
    </script>
    <?php include_once 'footer.php'; ?>

</body>

</html>
