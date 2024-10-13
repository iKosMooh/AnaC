<?php
// Incluindo o arquivo de conexão
require 'connect.php';

try {
    if (isset($_GET['id_aula'])) {
        $idAula = $_GET['id_aula'];

        // Preparar a consulta SQL para buscar horários da aula
        $sql = "SELECT Horario_Inicio, Horario_Termino FROM Aula WHERE ID_Aula = :id_aula";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_aula', $idAula, PDO::PARAM_INT);

        // Executar a consulta
        $stmt->execute();

        // Buscar resultados
        $horarios = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retornar os horários em formato JSON
        echo json_encode($horarios);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
