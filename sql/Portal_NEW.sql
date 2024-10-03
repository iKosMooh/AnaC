-- Criar a tabela Coordenadores antes, pois ela é referenciada em Curso
CREATE TABLE Coordenadores 
( 
    ID_Coordenador INT PRIMARY KEY,  
    Nome VARCHAR(100) NOT NULL,  
    DataN DATE NOT NULL,  
    EmailInstitucional VARCHAR(100) NOT NULL,  
    Curso VARCHAR(100) NOT NULL,  
    Email VARCHAR(100),  
    Usuario VARCHAR(50) NOT NULL,  
    Senha VARCHAR(100) NOT NULL,  
    Telefone VARCHAR(15),  
    UNIQUE (Usuario)
); 

CREATE TABLE Professores 
( 
    MatriculaPR INT PRIMARY KEY,  
    RG VARCHAR(20),  
    Nome VARCHAR(100) NOT NULL,  
    DataN DATE NOT NULL,  
    Telefone VARCHAR(15),  
    Email VARCHAR(100) NOT NULL,  
    Usuario VARCHAR(50) NOT NULL,  
    Senha VARCHAR(100) NOT NULL,  
    Endereco VARCHAR(255) NOT NULL,  
    Complemento VARCHAR(255),  
    EmailInstitucional VARCHAR(100),  
    Curso VARCHAR(100) NOT NULL,  
    Turno VARCHAR(20) NOT NULL,  
    UNIQUE (RG, Usuario, EmailInstitucional)
); 

CREATE TABLE Curso 
( 
    ID_Curso INT PRIMARY KEY,  
    Nome VARCHAR(100) NOT NULL,  
    Turno VARCHAR(20) NOT NULL,  
    CargaHR INT NOT NULL,  
    Grade VARCHAR(255) NOT NULL,  
    Coordenador INT NOT NULL,  
    FOREIGN KEY (Coordenador) REFERENCES Coordenadores (ID_Coordenador) ON DELETE CASCADE
); 

CREATE TABLE Instituicao 
( 
    ID_Instituicao INT PRIMARY KEY,  
    Nome VARCHAR(100) NOT NULL,  
    Endereco VARCHAR(255) NOT NULL,  
    Complemento VARCHAR(255)
); 

CREATE TABLE Materias 
( 
    ID_Materia INT PRIMARY KEY,  
    Nome VARCHAR(100) NOT NULL,  
    CargaHR INT NOT NULL,  
    Professor INT,  
    ID_Curso INT,
    FOREIGN KEY (ID_Curso) REFERENCES Curso (ID_Curso) ON DELETE CASCADE,
    FOREIGN KEY (Professor) REFERENCES Professores (MatriculaPR) ON DELETE SET NULL
); 

CREATE TABLE CursoAtivo 
( 
    ID_CursoA INT PRIMARY KEY,  
    ID_Curso INT,  
    Nome VARCHAR(100) NOT NULL,  
    DataInicio DATE NOT NULL,  
    DataFim DATE NOT NULL,  
    FOREIGN KEY (ID_Curso) REFERENCES Curso (ID_Curso) ON DELETE CASCADE
); 

CREATE TABLE Presenca 
( 
    ID_CursoA INT,  
    ID_Materia INT,  
    Data DATE NOT NULL,  
    Status VARCHAR(20) NOT NULL,  
    MatriculaPR INT NOT NULL,  
    FOREIGN KEY (ID_CursoA) REFERENCES CursoAtivo (ID_CursoA) ON DELETE CASCADE,
    FOREIGN KEY (ID_Materia) REFERENCES Materias (ID_Materia) ON DELETE CASCADE,
    FOREIGN KEY (MatriculaPR) REFERENCES Professores (MatriculaPR) ON DELETE CASCADE
); 

CREATE TABLE Atestado 
( 
    ID_Atestado INT PRIMARY KEY,  
    MatriculaPR INT NOT NULL,  
    Descricao VARCHAR(255) NOT NULL,  
    DataEmissao DATE NOT NULL,  
    Status VARCHAR(20) NOT NULL DEFAULT 'pendente',  
    FOREIGN KEY (MatriculaPR) REFERENCES Professores (MatriculaPR) ON DELETE CASCADE
); 

CREATE TABLE Aula 
( 
    ID_Aula INT PRIMARY KEY,  
    ID_Materia INT,  
    Horario VARCHAR(50) NOT NULL,  
    ID_Curso INT,
    FOREIGN KEY (ID_Materia) REFERENCES Materias (ID_Materia) ON DELETE CASCADE,
    FOREIGN KEY (ID_Curso) REFERENCES Curso (ID_Curso) ON DELETE CASCADE
); 

CREATE TABLE AulaPerdida 
( 
    ID_Aula INT PRIMARY KEY,  
    Data DATE NOT NULL,  
    Observacao VARCHAR(255),  
    FOREIGN KEY (ID_Aula) REFERENCES Aula (ID_Aula) ON DELETE CASCADE
); 

CREATE TABLE Reposicao 
( 
    ID_AulaPerdida INT NOT NULL,  
    Data DATE NOT NULL,  
    Docs VARCHAR(255) NOT NULL,  
    ID_Reposicao INT PRIMARY KEY AUTO_INCREMENT,
    FOREIGN KEY (ID_AulaPerdida) REFERENCES AulaPerdida (ID_Aula) ON DELETE CASCADE
); 

/* Dados Genéricos */
-- Inserir Coordenadores
INSERT INTO Coordenadores (ID_Coordenador, Nome, DataN, EmailInstitucional, Curso, Email, Usuario, Senha, Telefone) 
VALUES 
(1, 'Ana Paula', '1975-04-10', 'ana.paula@instituicao.edu', 'Engenharia', 'ana.pessoal@gmail.com', 'anapaula', 'senha123', '11999999999'),
(2, 'Carlos Silva', '1980-08-15', 'carlos.silva@instituicao.edu', 'Administração', 'carlos.silva@gmail.com', 'carlossilva', 'senha123', '11988888888'),
(3, 'Juliana Costa', '1978-12-20', 'juliana.costa@instituicao.edu', 'Direito', 'juliana.costa@gmail.com', 'julianacosta', 'senha123', '11977777777'),
(4, 'Roberto Nunes', '1970-05-25', 'roberto.nunes@instituicao.edu', 'Medicina', 'roberto.nunes@gmail.com', 'robertonunes', 'senha123', '11966666666'),
(5, 'Maria Oliveira', '1985-07-30', 'maria.oliveira@instituicao.edu', 'Psicologia', 'maria.oliveira@gmail.com', 'mariaoliveira', 'senha123', '11955555555');

-- Inserir Professores
INSERT INTO Professores (MatriculaPR, RG, Nome, DataN, Telefone, Email, Usuario, Senha, Endereco, Complemento, EmailInstitucional, Curso, Turno) 
VALUES 
(1, '123456789', 'José Almeida', '1985-01-10', '11912345678', 'jose.almeida@gmail.com', 'josealmeida', 'senha123', 'Rua 1', 'Apt 101', 'jose.almeida@instituicao.edu', 'Matemática', 'Manhã'),
(2, '987654321', 'Maria Silva', '1990-02-20', '11987654321', 'maria.silva@gmail.com', 'mariasilva', 'senha123', 'Rua 2', 'Apt 202', 'maria.silva@instituicao.edu', 'História', 'Tarde'),
(3, '123123123', 'Paulo Santos', '1982-03-15', '11912312345', 'paulo.santos@gmail.com', 'paulosantos', 'senha123', 'Rua 3', 'Apt 303', 'paulo.santos@instituicao.edu', 'Física', 'Noite'),
(4, '321321321', 'Carla Souza', '1987-04-25', '11932132143', 'carla.souza@gmail.com', 'carlasouza', 'senha123', 'Rua 4', 'Apt 404', 'carla.souza@instituicao.edu', 'Química', 'Manhã'),
(5, '456456456', 'Lucas Pereira', '1992-05-30', '11945645678', 'lucas.pereira@gmail.com', 'lucaspereira', 'senha123', 'Rua 5', 'Apt 505', 'lucas.pereira@instituicao.edu', 'Geografia', 'Tarde');

-- Inserir Cursos
INSERT INTO Curso (ID_Curso, Nome, Turno, CargaHR, Grade, Coordenador) 
VALUES 
(1, 'Matemática', 'Manhã', 1600, 'Grade 1', 1),
(2, 'História', 'Tarde', 1400, 'Grade 2', 2),
(3, 'Física', 'Noite', 1500, 'Grade 3', 3),
(4, 'Química', 'Manhã', 1600, 'Grade 4', 4),
(5, 'Geografia', 'Tarde', 1400, 'Grade 5', 5);

-- Inserir Instituições
INSERT INTO Instituicao (ID_Instituicao, Nome, Endereco, Complemento) 
VALUES 
(1, 'Instituto de Ciências Exatas', 'Rua A, 100', 'Prédio B'),
(2, 'Faculdade de Humanidades', 'Rua B, 200', 'Prédio C'),
(3, 'Universidade de Medicina', 'Rua C, 300', 'Prédio D'),
(4, 'Centro de Psicologia', 'Rua D, 400', 'Prédio E'),
(5, 'Escola de Administração', 'Rua E, 500', 'Prédio F');

-- Inserir Matérias
INSERT INTO Materias (ID_Materia, Nome, CargaHR, Professor, ID_Curso) 
VALUES 
(1, 'Cálculo I', 80, 1, 1),
(2, 'História Antiga', 60, 2, 2),
(3, 'Mecânica Clássica', 90, 3, 3),
(4, 'Química Orgânica', 80, 4, 4),
(5, 'Geografia Física', 70, 5, 5);
