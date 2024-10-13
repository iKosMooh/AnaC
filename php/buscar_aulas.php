<?php
// Incluindo o arquivo de conexão
require '../PHP/connect.php';

try {
    // Verificar se o ID do professor foi passado
    if (isset($_GET['id'])) {
        $idProfessor = $_GET['id'];

        // Preparar a consulta SQL com junção
        $sql = "SELECT A.ID_Aula, A.Data_Time, M.Nome AS Nome_Materia, Au.Horario_Inicio, Au.Horario_Termino, C.Nome AS Nome_Curso
FROM Aula_Nao_Ministrada A
JOIN Materias M ON A.ID_Materia = M.ID_Materia
JOIN CursoAtivo C ON M.ID_Curso = C.ID_Curso
JOIN Aula Au ON A.ID_Aula = Au.ID_Aula
WHERE A.ID_Professor = :id_professor

                ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_professor', $idProfessor, PDO::PARAM_INT);

        // Executar a consulta
        $stmt->execute();

        // Buscar resultados
        $aulas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retornar os resultados em formato JSON
        echo json_encode($aulas);
    }
} catch (PDOException $e) {
    // Em caso de erro
    echo json_encode(['error' => $e->getMessage()]);
}
