<?php
session_start();

// Verifica se o usuário está logado e é professor
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'professor') {
    header('Location: login.php');
    exit();
}

require_once 'connect.php';

$id_professor = $_SESSION['id'];

// Diretório para salvar o upload
$uploadDir = '../uploads/';
$success = true;

// Iterar pelas aulas enviadas no formulário
foreach ($_POST['id_aula'] as $index => $idAulaNaoMinistrada) {
    $justificativa = 'Justificado';
    $observacao = $_POST['observacao'];
    $file = $_FILES['upload_pdf']['name'][$index];

    // Verificar e processar o upload do PDF se houver um arquivo selecionado
    if ($file) {
        $fileTmpName = $_FILES['upload_pdf']['tmp_name'][$index];
        $fileName = basename($file);
        $uploadFilePath = $uploadDir . $fileName;

        if (!move_uploaded_file($fileTmpName, $uploadFilePath)) {
            echo "Erro ao fazer o upload do arquivo $fileName.";
            $success = false;
            continue;
        }
    } else {
        $fileName = null;
    }

    // Atualizar o registro da aula no banco de dados
    $sql = "
        UPDATE aula_nao_ministrada
        SET Justificado = :justificado, docs = :docs, Observacao = :observacao
        WHERE ID_Aula_Nao_Ministrada = :id_aula AND ID_Professor = :id_professor
    ";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':justificado', $justificativa);
        $stmt->bindParam(':docs', $fileName);
        $stmt->bindParam(':observacao', $observacao);
        $stmt->bindParam(':id_aula', $idAulaNaoMinistrada, PDO::PARAM_INT);
        $stmt->bindParam(':id_professor', $id_professor, PDO::PARAM_INT);
        
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Erro ao atualizar o banco de dados: " . $e->getMessage();
        $success = false;
    }
}

if ($success) {
    echo "Justificativa(s) salva(s) com sucesso!";
} else {
    echo "Erro ao salvar algumas justificativas.";
}
?>
