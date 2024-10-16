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
$descricao = $_POST['justificativaPersonalizada']; // ou outra descrição necessária
$data_emissao = date('Y-m-d'); // Data de emissão, você pode modificar se necessário
$docs = ''; // Inicializa a variável de documento

// Processamento do upload do arquivo
if (isset($_FILES['documentoAula']) && $_FILES['documentoAula']['error'] == UPLOAD_ERR_OK) {
    $uploads_dir = 'uploads'; // Diretório para salvar o upload
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
        exit; // Para a execução se o upload falhar
    }
}

// Variável para rastrear se houve algum erro durante a inserção
$allValid = true;
$messages = [];

// Prepara a consulta para inserir os dados
try {
    $sql = "INSERT INTO justificado (ID_Professor, Descricao, Docs, DateEmissao, Status) VALUES (?, ?, ?, ?, 'Pendente')";
    $stmt = $pdo->prepare($sql);

    foreach ($_POST['id_aula'] as $index => $id_aula) {
        $justificativa = $_POST['justificativa'][$index];

        // Verifique se a data da aula está definida
        if (isset($_POST['date-aula'][$index]) && !empty($_POST['date-aula'][$index])) {
            $date_aula = $_POST['date-aula'][$index];

            // Bind dos parâmetros
            $stmt->bindParam(1, $id_professor);
            $stmt->bindParam(2, $justificativa);
            $stmt->bindParam(3, $docs);
            $stmt->bindParam(4, $date_aula);
            
            // Executa a inserção
            if (!$stmt->execute()) {
                $messages[] = "Erro ao inserir dados para o índice $index: " . implode(", ", $stmt->errorInfo());
                $allValid = false; // Indica que houve um erro
            }
        } else {
            // Adiciona mensagem de erro apenas se a data não estiver definida
            $messages[] = "A data da aula não está definida para a aula de índice $index. A inserção não será realizada.";
            $allValid = false; // Indica que houve um erro
        }
    }

    // Retorno de sucesso ou erro
    if ($allValid) {
        echo json_encode(['status' => 'success', 'message' => 'Dados enviados com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => implode("<br>", $messages)]);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro na conexão ou execução: ' . $e->getMessage()]);
} finally {
    $stmt = null; // Libera o statement
    $pdo = null;  // Fecha a conexão
}
?>
