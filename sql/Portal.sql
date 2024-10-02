-- Tabela Instituicao (Necessária para referência em Curso)
CREATE TABLE Instituicao (
    ID_Instituicao INT PRIMARY KEY,
    Nome VARCHAR(50) NOT NULL
) DEFAULT CHARSET=utf8mb4;

-- Tabela Curso (criada antes da tabela Materias)
CREATE TABLE Curso (
    ID_Curso INT PRIMARY KEY,
    Nome VARCHAR(50) NOT NULL,
    Turno VARCHAR(50) NOT NULL,
    CargaHR INT NOT NULL, -- Mantenha o tipo de dado consistente
    Grade VARCHAR(50) NOT NULL,
    Coordenador VARCHAR(50) NOT NULL,
    ID_Instituicao INT,
    FOREIGN KEY (ID_Instituicao) REFERENCES Instituicao (ID_Instituicao)
) DEFAULT CHARSET=utf8mb4;

-- Tabela Professores (necessária para Materias)
CREATE TABLE Professores (
    Matricula INT PRIMARY KEY,
    RG VARCHAR(50),
    Nome VARCHAR(50) NOT NULL,
    DataN DATE NOT NULL,
    Telefone VARCHAR(50),
    Email VARCHAR(50) NOT NULL,
    Usuario VARCHAR(50) NOT NULL UNIQUE,
    Senha VARCHAR(50) NOT NULL,
    Endereco VARCHAR(50)
) DEFAULT CHARSET=utf8mb4;

-- Tabela Materias
CREATE TABLE Materias (
    ID_Materia INT PRIMARY KEY,
    Nome VARCHAR(50) NOT NULL,
    CargaHR INT NOT NULL, -- Consistência no tipo de dado
    Professor INT,
    ID_Curso INT,
    FOREIGN KEY (ID_Curso) REFERENCES Curso (ID_Curso),
    FOREIGN KEY (Professor) REFERENCES Professores (Matricula)
) DEFAULT CHARSET=utf8mb4;

-- Tabela Professores_Cursos (Associativa)
CREATE TABLE Professores_Cursos (
    ID_Professor INT,
    ID_Curso INT,
    PRIMARY KEY (ID_Professor, ID_Curso),
    FOREIGN KEY (ID_Professor) REFERENCES Professores (Matricula),
    FOREIGN KEY (ID_Curso) REFERENCES Curso (ID_Curso)
) DEFAULT CHARSET=utf8mb4;

-- Tabela Alunos (necessária para Atestado)
CREATE TABLE Alunos (
    MatriculaAL INT PRIMARY KEY,
    Nome VARCHAR(50) NOT NULL
) DEFAULT CHARSET=utf8mb4;

-- Tabela Atestado (referenciando Alunos e Professores)
CREATE TABLE Atestado (
    ID_Atestado INT PRIMARY KEY,
    ID_Professor INT NOT NULL,
    ID_Aluno INT NOT NULL,
    Descricao VARCHAR(50) NOT NULL,
    DataEmissao DATE NOT NULL,
    Docs VARCHAR(255) NOT NULL,
    UNIQUE (ID_Aluno, ID_Professor),
    FOREIGN KEY (ID_Aluno) REFERENCES Alunos (MatriculaAL),
    FOREIGN KEY (ID_Professor) REFERENCES Professores (Matricula)
) DEFAULT CHARSET=utf8mb4;

-- Tabela Aulas
CREATE TABLE Aulas (
    ID_Aula INT PRIMARY KEY AUTO_INCREMENT,
    MateriaID INT,
    Horario VARCHAR(50) NOT NULL,
    CursoID INT,
    FOREIGN KEY (MateriaID) REFERENCES Materias (ID_Materia),
    FOREIGN KEY (CursoID) REFERENCES Curso (ID_Curso)
) DEFAULT CHARSET=utf8mb4;

-- Tabela AulasPerdidas
CREATE TABLE AulasPerdidas (
    ID_AulaPerdida INT PRIMARY KEY AUTO_INCREMENT,
    Data DATE NOT NULL,
    Observacao VARCHAR(255),
    AulaID INT,
    FOREIGN KEY (AulaID) REFERENCES Aulas (ID_Aula)
) DEFAULT CHARSET=utf8mb4;

-- Tabela Reposicoes
CREATE TABLE Reposicoes (
    ID_Reposicao INT PRIMARY KEY AUTO_INCREMENT,
    ID_AulaPerdida INT,
    Data DATE NOT NULL,
    Docs VARCHAR(255) NOT NULL,
    FOREIGN KEY (ID_AulaPerdida) REFERENCES AulasPerdidas (ID_AulaPerdida)
) DEFAULT CHARSET=utf8mb4;

-- Tabela Coordenadores
CREATE TABLE Coordenadores (
    ID_Coordenador INT PRIMARY KEY,
    Nome VARCHAR(50) NOT NULL,
    DataN DATE NOT NULL,
    EmailInstitucional VARCHAR(50) NOT NULL,
    Curso VARCHAR(50) NOT NULL,
    Email VARCHAR(50),
    Usuario VARCHAR(50) NOT NULL UNIQUE,
    Senha VARCHAR(50) NOT NULL,
    Telefone VARCHAR(50)
) DEFAULT CHARSET=utf8mb4;

/* INSERTS PARA TESTES GENÉRICOS/POPULAÇÃO DE TABELAS

-- Populando a tabela Instituicao
INSERT INTO Instituicao (ID_Instituicao, Nome) VALUES
(1, 'Instituto Tecnológico A'),
(2, 'Universidade B'),
(3, 'Faculdade C'),
(4, 'Centro Universitário D'),
(5, 'Escola Técnica E');

-- Populando a tabela Curso
INSERT INTO Curso (ID_Curso, Nome, Turno, CargaHR, Grade, Coordenador, ID_Instituicao) VALUES
(1, 'Engenharia de Software', 'Matutino', 4000, 'GradeA', 'Coordenador1', 1),
(2, 'Análise de Sistemas', 'Vespertino', 3600, 'GradeB', 'Coordenador2', 2),
(3, 'Redes de Computadores', 'Noturno', 3000, 'GradeC', 'Coordenador3', 3),
(4, 'Ciência de Dados', 'Matutino', 4200, 'GradeD', 'Coordenador4', 4),
(5, 'Sistemas de Informação', 'Noturno', 3800, 'GradeE', 'Coordenador5', 5);

-- Populando a tabela Professores
INSERT INTO Professores (Matricula, RG, Nome, DataN, Telefone, Email, Usuario, Senha, Endereco) VALUES
(1001, '123456789', 'Prof. João', '1975-06-15', '11987654321', 'joao@institutoa.com', 'joao123', 'senhaJoao', 'Rua A, 123'),
(1002, '987654321', 'Prof. Maria', '1980-09-22', '11987654322', 'maria@universidadeb.com', 'maria123', 'senhaMaria', 'Rua B, 456'),
(1003, '123123123', 'Prof. Carlos', '1982-12-01', '11987654323', 'carlos@faculdadec.com', 'carlos123', 'senhaCarlos', 'Rua C, 789'),
(1004, '321321321', 'Prof. Ana', '1978-03-10', '11987654324', 'ana@centrouniversitariod.com', 'ana123', 'senhaAna', 'Rua D, 101'),
(1005, '456456456', 'Prof. Pedro', '1990-07-05', '11987654325', 'pedro@escolatecnicae.com', 'pedro123', 'senhaPedro', 'Rua E, 202');

-- Populando a tabela Materias
INSERT INTO Materias (ID_Materia, Nome, CargaHR, Professor, ID_Curso) VALUES
(1, 'Programação I', 60, 1001, 1),
(2, 'Banco de Dados', 80, 1002, 2),
(3, 'Redes de Computadores', 70, 1003, 3),
(4, 'Inteligência Artificial', 100, 1004, 4),
(5, 'Segurança da Informação', 90, 1005, 5);

-- Populando a tabela Professores_Cursos
INSERT INTO Professores_Cursos (ID_Professor, ID_Curso) VALUES
(1001, 1),
(1002, 2),
(1003, 3),
(1004, 4),
(1005, 5);

-- Populando a tabela Alunos
INSERT INTO Alunos (MatriculaAL, Nome) VALUES
(2001, 'Aluno A'),
(2002, 'Aluno B'),
(2003, 'Aluno C'),
(2004, 'Aluno D'),
(2005, 'Aluno E');

-- Populando a tabela Atestado
INSERT INTO Atestado (ID_Atestado, ID_Professor, ID_Aluno, Descricao, DataEmissao, Docs) VALUES
(1, 1001, 2001, 'Atestado médico de 2 dias', '2024-01-01', 'atestado_01.pdf'),
(2, 1002, 2002, 'Atestado médico de 3 dias', '2024-02-01', 'atestado_02.pdf'),
(3, 1003, 2003, 'Atestado médico de 1 dia', '2024-03-01', 'atestado_03.pdf'),
(4, 1004, 2004, 'Atestado médico de 4 dias', '2024-04-01', 'atestado_04.pdf'),
(5, 1005, 2005, 'Atestado médico de 2 dias', '2024-05-01', 'atestado_05.pdf');

-- Populando a tabela Aulas
INSERT INTO Aulas (MateriaID, Horario, CursoID) VALUES
(1, '08:00 - 10:00', 1),
(2, '10:00 - 12:00', 2),
(3, '14:00 - 16:00', 3),
(4, '16:00 - 18:00', 4),
(5, '18:00 - 20:00', 5);

-- Populando a tabela AulasPerdidas
INSERT INTO AulasPerdidas (Data, Observacao, AulaID) VALUES
('2024-09-01', 'Aula cancelada por falta de energia', 1),
('2024-09-02', 'Aula cancelada por manifestação', 2),
('2024-09-03', 'Aula cancelada por chuvas intensas', 3),
('2024-09-04', 'Aula cancelada por problemas técnicos', 4),
('2024-09-05', 'Aula cancelada por greve', 5);

-- Populando a tabela Reposicoes
INSERT INTO Reposicoes (ID_AulaPerdida, Data, Docs) VALUES
(1, '2024-09-10', 'reposicao_01.pdf'),
(2, '2024-09-11', 'reposicao_02.pdf'),
(3, '2024-09-12', 'reposicao_03.pdf'),
(4, '2024-09-13', 'reposicao_04.pdf'),
(5, '2024-09-14', 'reposicao_05.pdf');

-- Populando a tabela Coordenadores
INSERT INTO Coordenadores (ID_Coordenador, Nome, DataN, EmailInstitucional, Curso, Email, Usuario, Senha, Telefone) VALUES
(3001, 'Coord. José', '1970-01-01', 'jose@institutoa.com', 'Engenharia de Software', 'jose@gmail.com', 'jose123', 'senhaJose', '11987654326'),
(3002, 'Coord. Carla', '1972-02-02', 'carla@universidadeb.com', 'Análise de Sistemas', 'carla@gmail.com', 'carla123', 'senhaCarla', '11987654327'),
(3003, 'Coord. Fernanda', '1974-03-03', 'fernanda@faculdadec.com', 'Redes de Computadores', 'fernanda@gmail.com', 'fernanda123', 'senhaFernanda', '11987654328'),
(3004, 'Coord. Lucas', '1976-04-04', 'lucas@centrouniversitariod.com', 'Ciência de Dados', 'lucas@gmail.com', 'lucas123', 'senhaLucas', '11987654329'),
(3005, 'Coord. Paula', '1978-05-05', 'paula@escolatecnicae.com', 'Sistemas de Informação', 'paula@gmail.com', 'paula123', 'senhaPaula', '11987654330');


*/