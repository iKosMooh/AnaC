<?php
// Configurações de conexão
$host = 'localhost';  // Endereço do servidor MySQL (no caso do phpMyAdmin, normalmente é 'localhost')
$dbname = 'pi';  // Nome do banco de dados
$username = 'root';  // Nome de usuário do MySQL
$password = '';  // Senha do MySQL

try {
    // Criando uma nova conexão PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Configurando o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //echo "Conexão bem-sucedida!";
    
} catch (PDOException $e) {
    // Em caso de erro, uma mensagem será exibida
    echo "Falha na conexão com o Banco de Dados: " . $e->getMessage();
}
?>
