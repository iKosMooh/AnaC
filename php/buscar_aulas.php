<?php
// Incluindo o arquivo de conexão
require '../PHP/connect.php';

try {
    // Verificar se o ID do professor foi passado e se o método é GET
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        $idProfessor = $_GET['id'];

        // Preparar a consulta SQL com junção
        $sql = "SELECT A.ID_Aula, A.Data_Time, M.Nome AS Nome_Materia, Au.Horario_Inicio, Au.Horario_Termino, C.Nome AS Nome_Curso
                FROM Aula_Nao_Ministrada A
                JOIN Materias M ON A.ID_Materia = M.ID_Materia
                JOIN CursoAtivo C ON M.ID_Curso = C.ID_Curso
                JOIN Aula Au ON A.ID_Aula = Au.ID_Aula
                WHERE A.ID_Professor = :id_professor";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_professor', $idProfessor, PDO::PARAM_INT);

        // Executar a consulta
        $stmt->execute();

        // Buscar resultados
        $aulas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retornar os resultados em formato JSON
        echo json_encode($aulas);
    }

    // Verificar se o método é POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_professor'])) {
        $id_professor = $_POST['id_professor'];
        $sql = "SELECT 
                Aula.ID_Aula, 
                Aula.Horario_Inicio, 
                Aula.Horario_Termino,
                Aula.ID_Materia, 
                Materias.Nome AS Nome_Materia, 
                CursoAtivo.nome AS Nome_Curso 
                FROM Aula
                INNER JOIN Materias ON Aula.ID_Materia = Materias.ID_Materia
                INNER JOIN CursoAtivo ON Materias.ID_Curso = CursoAtivo.ID_Curso
                INNER JOIN Professores_Cursos ON CursoAtivo.ID_Curso = Professores_Cursos.ID_Curso
                WHERE Professores_Cursos.ID_Professor = :id_professor";
    
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_professor', $id_professor, PDO::PARAM_INT);
        $stmt->execute();
        
        // Buscar resultados e armazenar em $aulas
        $aulas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Retornar os resultados em formato JSON
        echo json_encode($aulas);
    }
    
} catch (PDOException $e) {
    // Em caso de erro
    echo json_encode(['error' => $e->getMessage()]);
}
?>
