<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['tipo'])) {
    header('Location: login.php');
    exit();
}

// Pegar o ID do professor da sessão
$id_professor = $_SESSION['id'];

// Conexão ao banco de dados
require '../PHP/connect.php';

// Captura de dados do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dataAula = $_POST['dataAula'];
    $id_aula = $_POST['id_aula'];
    $observacaoAula = $_POST['observacaoAula'];

    try {
        $pdo->beginTransaction(); // Inicia uma transação

        // Loop para inserir cada aula não ministrada
        foreach ($dataAula as $index => $data) {
            if (!empty($id_aula[$index])) { // Verifica se a aula foi selecionada
                // Obtendo o ID_Materia a partir da tabela Aula
                $sqlMateria = "SELECT ID_Materia FROM Aula WHERE ID_Aula = :id_aula";
                $stmtMateria = $pdo->prepare($sqlMateria);
                $stmtMateria->bindParam(':id_aula', $id_aula[$index]);
                $stmtMateria->execute();
                $materia = $stmtMateria->fetch(PDO::FETCH_ASSOC);

                if ($materia) {
                    $id_materia = $materia['ID_Materia'];

                    // Inserindo na tabela AulasNaoMinistradas
                    $sqlInsert = "INSERT INTO aula_nao_ministrada (Date_Time, Observacao, ID_Aula, ID_Professor, ID_Materia) 
                                  VALUES (:data, :observacao, :id_aula, :id_professor, :id_materia)";

                    $stmtInsert = $pdo->prepare($sqlInsert);
                    $stmtInsert->bindParam(':data', $data);
                    $stmtInsert->bindParam(':observacao', $observacaoAula[$index]);
                    $stmtInsert->bindParam(':id_aula', $id_aula[$index]);
                    $stmtInsert->bindParam(':id_professor', $id_professor);
                    $stmtInsert->bindParam(':id_materia', $id_materia);
                    
                    $stmtInsert->execute();
                }
            }
        }

        $pdo->commit(); // Comita a transação
        echo json_encode(['status' => 'success', 'message' => 'Aulas não ministradas registradas com sucesso!']);

    } catch (PDOException $e) {
        $pdo->rollBack(); // Desfaz a transação em caso de erro
        echo json_encode(['status' => 'error', 'message' => 'Erro ao registrar aulas: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido.']);
}
?>
