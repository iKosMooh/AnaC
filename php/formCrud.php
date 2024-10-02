<?php
// Inclui o arquivo de conexão ao banco de dados
require_once 'connect.php';

// Função para gerar variáveis dinamicamente com base nos dados do formulário
function generateVariables($postData) {
    $data = [];
    foreach ($postData as $key => $value) {
        $data[$key] = $value;  // Associa os campos dinamicamente
    }
    return $data;
}

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gera as variáveis dinamicamente a partir dos dados POST
    $data = generateVariables($_POST);
    // Faz um array_map em todos os campos do data, usando htmlspecialchars
    //$data = array_map('htmlspecialchars', $data);
    
    $operation = $data['operation']; // Operação CRUD (insert, read, update, delete)
    $tabela = $data['tabela']; // Nome da tabela (enviado pelo jQuery)
    //faz um array map em todos os campos do data, usando htmlspecialchars nesta linha crie isto
    
    // Função de conexão ao banco
    global $pdo;

    // Define as operações CRUD com base no valor de 'operation'
    switch ($operation) {
        case 'insert':
            // Cria dinamicamente a query de inserção baseada nos dados enviados
            $fields = array_keys($data);
            $values = array_values($data);

            // Remove as chaves 'operation' e 'tabela' da inserção
            unset($data['operation'], $data['tabela']);

            // Constrói dinamicamente a query
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            $columns = implode(', ', array_keys($data));
            $stmt = $pdo->prepare("INSERT INTO $tabela ($columns) VALUES ($placeholders)");

            if ($stmt->execute(array_values($data))) {
                echo "Registro criado com sucesso!";
            } else {
                echo "Erro ao criar o registro.";
            }
            break;

        case 'read':
            // Leitura de todos os registros da tabela
            $stmt = $pdo->prepare("SELECT * FROM $tabela");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($results); // Retorna os resultados em formato JSON
            break;

        case 'update':
            $inputIdentifierName = $data['inputIdentifier'];
            $inputIdentifier = $data[$inputIdentifierName]; // ID do registro para ser atualizado
            unset($data['inputIdentifier'], $data['operation'], $data['tabela']); // Remove chaves desnecessárias

            // Cria dinamicamente a query de atualização baseada nos dados enviados
            $setPart = implode(', ', array_map(function($key) { return "$key = ?"; }, array_keys($data)));
            $stmt = $pdo->prepare("UPDATE $tabela SET $setPart WHERE $inputIdentifier = ?");

            if ($stmt->execute([...array_values($data), $inputIdentifier])) {
                echo "Registro atualizado com sucesso!";
            } else {
                echo "Erro ao atualizar o registro.";
            }
            break;

        case 'delete':
            $inputIdentifierName = $data['inputIdentifier'];
            $inputIdentifier = $data[$inputIdentifierName]; // ID do registro para ser atualizado
            $stmt = $pdo->prepare("DELETE FROM $tabela WHERE $inputIdentifier = ?");
            $stmt->bindParam(1, $inputIdentifier);
            if ($stmt->execute()) {
                echo "Registro excluído com sucesso!";
            } else {
                echo "Erro ao excluir o registro.";
            }
            break;

        default:
            echo "Operação inválida.";
            break;
    }
}
