<?php
session_start();
require_once '../php/connect.php';

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
        echo "Erro: ID de aula não ministrada não existe.";
        exit();
    }

    // Verificar se já existe um pedido de reposição para essa aula
    $sqlVerifica = "SELECT ID_Reposicao, Status_Pedido FROM reposicao WHERE ID_Aula_Nao_Ministrada = :id_aula_nao_ministrada";
    
    try {
        $stmtVerifica = $pdo->prepare($sqlVerifica);
        $stmtVerifica->bindParam(':id_aula_nao_ministrada', $id_aula_nao_ministrada, PDO::PARAM_INT);
        $stmtVerifica->execute();
        
        $pedidoExistente = $stmtVerifica->fetch(PDO::FETCH_ASSOC);

        // Se já existe um pedido
        if ($pedidoExistente) {
            if ($pedidoExistente['Status_Pedido'] == 'Pendente') {
                // Retornar mensagem se já houver um pedido pendente
                echo "Pedido já feito e está com status: " . $pedidoExistente['Status_Pedido'];
                exit(); // Interrompe a execução do script
            } elseif ($pedidoExistente['Status_Pedido'] == 'Rejeitado') {
                // Se o status for 'Rejeitado', atualizamos o pedido
                $id_reposicao = $pedidoExistente['ID_Reposicao'];

                // Verifica se o arquivo foi enviado
                $docs_plano_aula = null; // Inicializa como null
                if (isset($_FILES['docs_plano_aula']) && $_FILES['docs_plano_aula']['error'] == UPLOAD_ERR_OK) {
                    $docs_plano_aula = $_FILES['docs_plano_aula']['name'];
                    move_uploaded_file($_FILES['docs_plano_aula']['tmp_name'], "../uploads/$docs_plano_aula");
                }

                // Atualização do pedido no banco de dados
                $sqlUpdate = "UPDATE reposicao SET DataReposicao = :data_reposicao, 
                              docs_plano_aula = COALESCE(:docs_plano_aula, docs_plano_aula), Status_Pedido = 'Pendente' 
                              WHERE ID_Reposicao = :id_reposicao";

                $stmtUpdate = $pdo->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':data_reposicao', $data_reposicao);
                $stmtUpdate->bindParam(':docs_plano_aula', $docs_plano_aula, PDO::PARAM_STR | PDO::PARAM_NULL); // Permite null
                $stmtUpdate->bindParam(':id_reposicao', $id_reposicao, PDO::PARAM_INT);

                if ($stmtUpdate->execute()) {
                    echo "Pedido de reposição atualizado com sucesso!";
                } else {
                    echo "Erro ao atualizar o pedido de reposição.";
                }
            }
        } else {
            // Se não houver pedidos existentes, insere um novo
            // Verifica se o arquivo foi enviado
            if (isset($_FILES['docs_plano_aula']) && $_FILES['docs_plano_aula']['error'] == UPLOAD_ERR_OK) {
                $docs_plano_aula = $_FILES['docs_plano_aula']['name'];
                move_uploaded_file($_FILES['docs_plano_aula']['tmp_name'], "../uploads/$docs_plano_aula");
            } else {
                $docs_plano_aula = null; // Se não houver arquivo, manter como null
            }

            // Inserção no banco de dados
            $sqlInsert = "INSERT INTO reposicao (ID_Aula_Nao_Ministrada, DataReposicao, docs_plano_aula, Status_Pedido) 
                          VALUES (:id_aula_nao_ministrada, :data_reposicao, :docs_plano_aula, 'Pendente')";

            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->bindParam(':id_aula_nao_ministrada', $id_aula_nao_ministrada, PDO::PARAM_INT);
            $stmtInsert->bindParam(':data_reposicao', $data_reposicao);
            $stmtInsert->bindParam(':docs_plano_aula', $docs_plano_aula, PDO::PARAM_STR | PDO::PARAM_NULL); // Permite null

            if ($stmtInsert->execute()) {
                echo "Pedido de reposição criado com sucesso!";
            } else {
                echo "Erro ao enviar o pedido de reposição.";
            }
        }
    } catch (PDOException $e) {
        echo "Erro ao inserir dados: " . $e->getMessage();
    }
}
?>
