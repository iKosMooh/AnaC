<?php
    $host = "localhost";
    $dbname = "sistema_clientes";
    $username = "root";
    $password = "";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }catch (PDOException $e){
        die("Erro na conexão: " . $e->getMessage());
    }