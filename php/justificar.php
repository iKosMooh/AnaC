<?php
session_start();
require_once 'connect.php'; // Certifique-se de que a conexão PDO está sendo feita corretamente

// Verificar se o usuário está logado e é um professor
if (!isset($_SESSION['tipo'])) {
    echo json_encode(['status' => 'error', 'message' => 'Acesso negado.']);
    exit();
}

// Recupera os dados do formulário
$id_professor = $_POST['id_professor'];
$data_emissao = date('Y-m-d'); // Data de emissão, você pode modificar se necessário
$docs = ''; // Inicializa a variável de documento

// Processamento do upload do arquivo
if (isset($_FILES['documentoAula']) && $_FILES['documentoAula']['error'] == UPLOAD_ERR_OK) {
    $uploads_dir = '../docs/uploads'; // Diretório para salvar o upload
    $tmp_name = $_FILES['documentoAula']['tmp_name'];
    $name = basename($_FILES['documentoAula']['name']);
    
    // Verifica se o diretório de uploads existe
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0777, true); // Cria o diretório se não existir
    }
    
    // Move o arquivo para o diretório de uploads
    if (move_uploaded_file($tmp_name, "$uploads_dir/$name")) {
        $docs = "$uploads_dir/$name"; // Caminho do documento salvo
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao mover o arquivo enviado.']);
        exit(); // Para a execução se o upload falhar
    }
}

// Variável para rastrear se houve algum erro durante a inserção
$messages = [];

// Prepara a consulta para inserir os dados
try {
    $sql = "INSERT INTO justificado (ID_Professor, Descricao, Docs, DateEmissao, Status) VALUES (?, ?, ?, ?, 'Pendente')";
    $stmt = $pdo->prepare($sql);

    // Prepara a consulta para atualizar o campo Justificado
    $updateSql = "UPDATE Aula_Nao_Ministrada SET Justificado = 'Justificado' WHERE ID_Aula_Nao_Ministrada = ?";
    $updateStmt = $pdo->prepare($updateSql);

    // Usar um loop para inserir cada aula
    foreach ($_POST['id_aula'] as $index => $id_aula) {
        // Combinar os dois selects com um hífen
        $justificativa = $_POST['justificativa'][$index]; // Primeiro select

        // Obtém a data da aula, ou usa null se não estiver definida
        $date_aula = $_POST['date-aula'][$index] ?? null;

        // Verifica se a data da aula está definida
        if ($date_aula) {
            // Bind dos parâmetros para inserção
            $stmt->bindParam(1, $id_professor);
            $stmt->bindParam(2, $justificativa);
            $stmt->bindParam(3, $docs);
            $stmt->bindParam(4, $date_aula);
            
            // Executa a inserção
            if (!$stmt->execute()) {
                $messages[] = "Erro ao inserir dados para a aula com ID $id_aula: " . implode(", ", $stmt->errorInfo());
            } else {
                // Atualiza o campo Justificado para "Justificado" na tabela Aula_Nao_Ministrada
                $updateStmt->bindParam(1, $id_aula, PDO::PARAM_INT);
                if (!$updateStmt->execute()) {
                    $messages[] = "Erro ao atualizar o status de justificação para a aula com ID $id_aula: " . implode(", ", $updateStmt->errorInfo());
                }
            }
        } else {
            // Adiciona mensagem de erro utilizando o ID da aula
            $messages[] = "A data da aula com ID $id_aula não está definida. A inserção não será realizada.";
        }
    }

    // Verificar se houve mensagens de erro
    if (!empty($messages)) {
        echo json_encode(['status' => 'error', 'message' => implode("<br>", $messages)]);
    } else {
        echo json_encode(['status' => 'success', 'message' => 'Dados enviados com sucesso!']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro na conexão ou execução: ' . $e->getMessage()]);
} finally {
    $stmt = null; // Libera o statement
    $pdo = null;  // Fecha a conexão
}
?>
