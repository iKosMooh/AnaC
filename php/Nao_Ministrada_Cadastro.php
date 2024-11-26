<?php
session_start();

// Verificar sessão
if (!isset($_SESSION['tipo'])) {
    header('Location: login.php');
    exit();
}

require '../PHP/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura e validação dos dados
    $dataAula = $_POST['dataAula'] ?? [];
    $Horario_Inicio = $_POST['Horario_Inicio'] ?? [];
    $Horario_Termino = $_POST['Horario_Termino'] ?? [];
    $idAula = $_POST['idAula'] ?? [];
    $idProfessor = $_POST['id_professor'] ?? null;

    if (empty($dataAula) || empty($idAula) || $idProfessor === null) {
        echo json_encode(['status' => 'error', 'message' => 'Os dados não foram enviados corretamente.']);
        exit();
    }

    // Verificar consistência dos dados
    if (count($dataAula) !== count($idAula) || count($dataAula) !== count($Horario_Inicio) || count($dataAula) !== count($Horario_Termino)) {
        echo json_encode(['status' => 'error', 'message' => 'Os arrays enviados possuem tamanhos inconsistentes.']);
        exit();
    }

    try {
        // Query SQL
        $sql = "INSERT INTO aula_nao_ministrada (ID_Professor, ID_Aula, Date_Time, Horario_Inicio, Horario_Termino) 
                VALUES (:id_professor, :id_aula, :data_aula, :Horario_Inicio, :Horario_Termino)";
        $stmt = $pdo->prepare($sql);

        // Iniciar transação
        $pdo->beginTransaction();

        // Iterar e inserir os dados
        foreach ($dataAula as $index => $data) {
            // Validar se os índices existem para evitar erros
            if (!isset($idAula[$index], $Horario_Inicio[$index], $Horario_Termino[$index])) {
                throw new Exception("Dados incompletos no índice $index.");
            }

            // Executar a query
            $stmt->execute([
                ':id_professor' => $idProfessor,
                ':id_aula' => $idAula[$index],
                ':data_aula' => $data,
                ':Horario_Inicio' => $Horario_Inicio[$index],
                ':Horario_Termino' => $Horario_Termino[$index],
            ]);
        }

        // Confirmar transação
        $pdo->commit();

        echo json_encode(['status' => 'success', 'message' => 'Aulas não ministradas registradas com sucesso.']);
    } catch (PDOException $e) {
        // Reverter transação em caso de erro
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Erro ao registrar as aulas: ' . $e->getMessage()]);
    } catch (Exception $e) {
        // Tratar outros erros
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
