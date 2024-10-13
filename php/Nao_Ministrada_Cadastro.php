<?php
session_start();
require '../PHP/connect.php'; // Inclua sua conexão com o banco de dados

// Verificar se o usuário está logado e é um professor
if (!isset($_SESSION['tipo'])) {
    echo json_encode(['status' => 'error', 'message' => 'Acesso negado.']);
    exit();
}

// Pegar dados do POST
$id_professor = $_POST['id_professor'];
$dataAula = $_POST['dataAula'];
$id_aula = $_POST['id_aula'];
$observacaoAula = $_POST['observacaoAula'];
$id_materia = $_POST['id_materia']; // Aqui você deve acessar id_materia

// Verificando se o ID_Materia foi enviado
if (empty($id_materia)) {
    echo json_encode(['status' => 'error', 'message' => 'ID da matéria não foi fornecido.']);
    exit();
}

try {
    // Prepare a consulta para inserir a aula não ministrada
    $sql = "INSERT INTO Aula_Nao_Ministrada (ID_Professor, Date_Time, ID_Aula, Observacao, ID_Materia) VALUES (:id_professor, :dataAula, :id_aula, :observacao, :id_materia)";
    $stmt = $pdo->prepare($sql);

    // Bind de parâmetros
    $stmt->bindParam(':id_professor', $id_professor);
    $stmt->bindParam(':dataAula', $dataAula);
    $stmt->bindParam(':id_aula', $id_aula);
    $stmt->bindParam(':observacao', $observacaoAula);
    $stmt->bindParam(':id_materia', $id_materia); // Bind do ID_Materia

    // Execute a inserção
    $stmt->execute();

    // Retorno de sucesso
    echo json_encode(['status' => 'success', 'message' => 'Aula não ministrada registrada com sucesso.']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao registrar aula: ' . $e->getMessage()]);
}
?>
