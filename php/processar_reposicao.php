<?php
session_start();
require_once '../php/connect.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_aula_nao_ministrada = $_POST['id_aula_nao_ministrada'];
    $data_reposicao = $_POST['data_reposicao'];
    $id_professor = $_POST['id_professor'];

    // Verificar se o ID da aula não ministrada existe
    $sqlVerificaAula = "SELECT COUNT(*) FROM aula_nao_ministrada WHERE ID_Aula_Nao_Ministrada = :id_aula_nao_ministrada";
    $stmtVerificaAula = $pdo->prepare($sqlVerificaAula);
    $stmtVerificaAula->bindParam(':id_aula_nao_ministrada', $id_aula_nao_ministrada, PDO::PARAM_INT);
    $stmtVerificaAula->execute();

    if ($stmtVerificaAula->fetchColumn() == 0) {
        $response['message'] = "Erro: ID de aula não ministrada não existe.";
        echo json_encode($response);
        exit();
    }

    // Verificar se já existe um pedido de reposição para essa aula
    $sqlVerifica = "SELECT ID_Reposicao, Status_Pedido FROM reposicao WHERE ID_Aula_Nao_Ministrada = :id_aula_nao_ministrada";
    
    try {
        $stmtVerifica = $pdo->prepare($sqlVerifica);
        $stmtVerifica->bindParam(':id_aula_nao_ministrada', $id_aula_nao_ministrada, PDO::PARAM_INT);
        $stmtVerifica->execute();
        
        $pedidoExistente = $stmtVerifica->fetch(PDO::FETCH_ASSOC);

        if ($pedidoExistente) {
            if ($pedidoExistente['Status_Pedido'] == 'Pendente') {
                $response['message'] = "Pedido já feito e está com status: " . $pedidoExistente['Status_Pedido'];
                echo json_encode($response);
                exit();
            } elseif ($pedidoExistente['Status_Pedido'] == 'Rejeitado') {
                $id_reposicao = $pedidoExistente['ID_Reposicao'];

                // Atualização do pedido no banco de dados
                $sqlUpdate = "UPDATE reposicao SET DataReposicao = :data_reposicao, 
                              Status_Pedido = 'Pendente' 
                              WHERE ID_Reposicao = :id_reposicao";

                $stmtUpdate = $pdo->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':data_reposicao', $data_reposicao);
                $stmtUpdate->bindParam(':id_reposicao', $id_reposicao, PDO::PARAM_INT);

                if ($stmtUpdate->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Pedido de reposição atualizado com sucesso!";
                } else {
                    $response['message'] = "Erro ao atualizar o pedido de reposição.";
                }
            }
        } else {
            // Se não houver pedidos existentes, insere um novo


            // Inserção no banco de dados
            $sqlInsert = "INSERT INTO reposicao (ID_Aula_Nao_Ministrada, DataReposicao, Status_Pedido) 
                          VALUES (:id_aula_nao_ministrada, :data_reposicao, 'Pendente')";

            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->bindParam(':id_aula_nao_ministrada', $id_aula_nao_ministrada, PDO::PARAM_INT);
            $stmtInsert->bindParam(':data_reposicao', $data_reposicao);

            if ($stmtInsert->execute()) {
                $response['success'] = true;
                $response['message'] = "Pedido de reposição criado com sucesso!";
            } else {
                $response['message'] = "Erro ao enviar o pedido de reposição.";
            }
        }
    } catch (PDOException $e) {
        $response['message'] = "Erro ao inserir dados: " . $e->getMessage();
    }
}

echo json_encode($response);
?>
