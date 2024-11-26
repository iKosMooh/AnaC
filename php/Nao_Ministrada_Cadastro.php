<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['tipo'])) {
    header('Location: login.php');
    exit();
}

require '../PHP/connect.php'; // Incluindo a conexão com o banco

// Verifica se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $dataAula = isset($_POST['dataAula']) ? $_POST['dataAula'] : [];
    $idAula = isset($_POST['idAula']) ? $_POST['idAula'] : [];
    $idProfessor = isset($_POST['id_professor']) ? $_POST['id_professor'] : null;

    // Verifica se os dados são válidos
    if (empty($dataAula) || empty($idAula)) {
        echo json_encode(['status' => 'error', 'message' => 'Os dados não foram enviados corretamente.']);
        exit();
    }

    try {
        // Prepara a query para inserir as aulas não ministradas
        $sql = "INSERT INTO aula_nao_ministrada (ID_Professor, ID_Aula, Date_Time) VALUES (:id_professor, :id_aula, :data_aula)";
        $stmt = $pdo->prepare($sql);

        // Inicia a transação
        $pdo->beginTransaction();

        // Insere os dados
        foreach ($dataAula as $index => $data) {
            $stmt->bindParam(':id_professor', $idProfessor, PDO::PARAM_INT);
            $stmt->bindParam(':id_aula', $idAula[$index], PDO::PARAM_INT);
            $stmt->bindParam(':data_aula', $data, PDO::PARAM_STR);
            $stmt->execute();
        }


        // Commit da transação
        $pdo->commit();

        // Retorna sucesso
        echo json_encode(['status' => 'success', 'message' => 'Aulas não ministradas registradas com sucesso.']);
    } catch (PDOException $e) {
        // Em caso de erro, faz rollback
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Erro ao registrar as aulas: ' . $e->getMessage()]);
    }
}
