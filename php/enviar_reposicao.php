<?php
// Incluindo o arquivo de conexão
require 'connect.php';

if (isset($_POST['id_aula_nao_ministrada']) && isset($_POST['data_reposicao']) && isset($_POST['mensagem'])) {
    $idAulaNaoMinistrada = $_POST['id_aula_nao_ministrada'];
    $dataReposicao = $_POST['data_reposicao'];
    $mensagem = $_POST['mensagem'];
    $status = 'Aguardando';  // Definido como padrão
    $status_pedido = 'Pendente';  // Definido como padrão

    try {
        // Inserção na tabela Reposicao
        $sql = "INSERT INTO Reposicao (ID_Aula_Nao_Ministrada, DataReposicao, Mensagem, Status,Status_Pedido) 
                VALUES (:id_aula_nao_ministrada, :data_reposicao, :mensagem, :status, :status_pedido)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_aula_nao_ministrada', $idAulaNaoMinistrada);
        $stmt->bindParam(':data_reposicao', $dataReposicao);
        $stmt->bindParam(':mensagem', $mensagem);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':status_pedido', $status_pedido);
        $stmt->execute();

        echo "Pedido enviado com sucesso!";
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>
