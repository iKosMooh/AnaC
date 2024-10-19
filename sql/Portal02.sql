-- Create Instituicao Table
CREATE TABLE Instituicao (
    ID_Instituicao INT PRIMARY KEY AUTO_INCREMENT,  
    Nome VARCHAR(100) NOT NULL UNIQUE,  
    Endereco VARCHAR(255) NOT NULL,  
    Complemento VARCHAR(50)  
);

-- Create Coordenadores Table
CREATE TABLE Coordenadores (
    ID_Coordenador INT PRIMARY KEY AUTO_INCREMENT,  
    Nome VARCHAR(100) NOT NULL,  
    DataN DATE NOT NULL,  
    EmailInstitucional VARCHAR(100) NOT NULL,  
    Curso VARCHAR(100) NOT NULL,  
    Email VARCHAR(100),  
    Usuario VARCHAR(50) NOT NULL UNIQUE,  
    Senha VARCHAR(50) NOT NULL,  
    Telefone VARCHAR(15)  
); 

-- Create Curso Table
CREATE TABLE Curso (
    ID_Curso INT PRIMARY KEY AUTO_INCREMENT,  
    Nome VARCHAR(100) NOT NULL,  
    Turno VARCHAR(20) NOT NULL,  
    CargaHR INT NOT NULL,  
    Grade VARCHAR(100) NOT NULL,  
    ID_Coordenador INT NOT NULL,  
    ID_Instituicao INT NOT NULL,  
    FOREIGN KEY (ID_Coordenador) REFERENCES Coordenadores(ID_Coordenador),
    FOREIGN KEY (ID_Instituicao) REFERENCES Instituicao(ID_Instituicao)
); 

-- Create Professores Table
CREATE TABLE Professores (
    ID_Professor INT PRIMARY KEY AUTO_INCREMENT,  
    RG VARCHAR(20),  
    Nome VARCHAR(100) NOT NULL,  
    DataN DATE NOT NULL,  
    Telefone VARCHAR(15),  
    Email VARCHAR(100) NOT NULL,  
    Usuario VARCHAR(50) NOT NULL UNIQUE,  
    Senha VARCHAR(50) NOT NULL,  
    Endereco VARCHAR(255) NOT NULL,  
    Complemento VARCHAR(50),  
    EmailInstitucional VARCHAR(100),  
    Curso VARCHAR(100) NOT NULL,  
    Turno VARCHAR(20) NOT NULL,  
    UNIQUE (RG, Usuario, EmailInstitucional)
); 

-- Create Materias Table
CREATE TABLE Materias (
    ID_Materia INT PRIMARY KEY AUTO_INCREMENT,  
    Nome_Materia VARCHAR(100) NOT NULL,  
    CargaHR INT NOT NULL,  
    ID_Curso INT NOT NULL,  
    ID_Professor INT NOT NULL,  
    FOREIGN KEY (ID_Curso) REFERENCES Curso(ID_Curso),
    FOREIGN KEY (ID_Professor) REFERENCES Professores(ID_Professor)
); 

-- Create CursoAtivo Table
CREATE TABLE CursoAtivo (
    ID_CursoA INT PRIMARY KEY AUTO_INCREMENT,  
    ID_Curso INT NOT NULL,  
    Nome VARCHAR(100) NOT NULL,  
    DataInicio DATE NOT NULL,  
    DataFim DATE NOT NULL,  
    FOREIGN KEY (ID_Curso) REFERENCES Curso(ID_Curso)
); 

-- Create Aula Table
CREATE TABLE Aula (
    ID_Aula INT PRIMARY KEY AUTO_INCREMENT,  
    ID_Materia INT NOT NULL,  
    Horario_Inicio TIME NOT NULL,  
    ID_Curso INT NOT NULL,  
    Horario_Termino TIME NOT NULL,  
    FOREIGN KEY (ID_Materia) REFERENCES Materias(ID_Materia),
    FOREIGN KEY (ID_Curso) REFERENCES Curso(ID_Curso)
); 

-- Create Reposicao Table
CREATE TABLE Reposicao (
    ID_Reposicao INT PRIMARY KEY AUTO_INCREMENT,  
    ID_Aula_Nao_Ministrada INT NOT NULL,  
    DataReposicao DATE,  
    Status VARCHAR(20) NOT NULL,  
    Status_Pedido VARCHAR(20) NOT NULL  
); 

-- Create Aula_Nao_Ministrada Table
CREATE TABLE Aula_Nao_Ministrada (
    ID_Aula INT PRIMARY KEY,  
    Data_Time DATE NOT NULL,  
    observacao VARCHAR(255),  
    ID_Aula_Nao_Ministrada INT NOT NULL,  
    FOREIGN KEY (ID_Aula) REFERENCES Aula(ID_Aula),
    FOREIGN KEY (ID_Aula_Nao_Ministrada) REFERENCES Reposicao(ID_Reposicao)
);

-- Create Presenca Table
CREATE TABLE Presenca (
    ID_Materia INT NOT NULL,  
    Data DATE NOT NULL,  
    Status VARCHAR(20) NOT NULL,  
    ID_Professor INT NOT NULL,  
    ID_CursoA INT NOT NULL,  
    FOREIGN KEY (ID_Materia) REFERENCES Materias(ID_Materia),
    FOREIGN KEY (ID_Professor) REFERENCES Professores(ID_Professor),
    FOREIGN KEY (ID_CursoA) REFERENCES CursoAtivo(ID_CursoA)
); 

-- Create Professores_Cursos Table
CREATE TABLE Professores_Cursos (
    ID_Professor INT,  
    ID_Curso INT NOT NULL,  
    FOREIGN KEY (ID_Professor) REFERENCES Professores(ID_Professor),
    FOREIGN KEY (ID_Curso) REFERENCES Curso(ID_Curso)
); 

-- Create Atestado Table
CREATE TABLE Atestado (
    ID_Atestado INT PRIMARY KEY AUTO_INCREMENT,  
    ID_Professor INT NOT NULL,  
    Descricao VARCHAR(255) NOT NULL,  
    DataEmissao DATE NOT NULL,  
    FOREIGN KEY (ID_Professor) REFERENCES Professores(ID_Professor)
);


-- Inserir Dados
-- Inserir Instituições
INSERT INTO Instituicao (Nome, Endereco, Complemento) 
VALUES 
('Instituto de Ciências Exatas', 'Rua A, 100', 'Prédio B'),
('Faculdade de Humanidades', 'Rua B, 200', 'Prédio C'),
('Universidade de Medicina', 'Rua C, 300', 'Prédio D'),
('Centro de Psicologia', 'Rua D, 400', 'Prédio E'),
('Escola de Administração', 'Rua E, 500', 'Prédio F');

-- Inserir Coordenadores
INSERT INTO Coordenadores (Nome, DataN, EmailInstitucional, Curso, Email, Usuario, Senha, Telefone) 
VALUES 
('Ana Paula', '1975-04-10', 'ana.paula@instituicao.edu', 'Engenharia', 'ana.pessoal@gmail.com', 'anapaula', 'senha123', '11999999999'),
('Carlos Silva', '1980-08-15', 'carlos.silva@instituicao.edu', 'Administração', 'carlos.silva@gmail.com', 'carlossilva', 'senha123', '11988888888'),
('Juliana Costa', '1978-12-20', 'juliana.costa@instituicao.edu', 'Direito', 'juliana.costa@gmail.com', 'julianacosta', 'senha123', '11977777777'),
('Roberto Nunes', '1970-05-25', 'roberto.nunes@instituicao.edu', 'Medicina', 'roberto.nunes@gmail.com', 'robertonunes', 'senha123', '11966666666'),
('Maria Oliveira', '1985-07-30', 'maria.oliveira@instituicao.edu', 'Psicologia', 'maria.oliveira@gmail.com', 'mariaoliveira', 'senha123', '11955555555');

-- Inserir Cursos
INSERT INTO Curso (Nome, Turno, CargaHR, Grade, ID_Coordenador, ID_Instituicao) 
VALUES 
('Matemática', 'Manhã', 1600, 'Grade 1', 1, 1),
('História', 'Tarde', 1400, 'Grade 2', 2, 2),
('Física', 'Noite', 1500, 'Grade 3', 3, 3),
('Química', 'Manhã', 1600, 'Grade 4', 4, 4),
('Geografia', 'Tarde', 1400, 'Grade 5', 5, 5);

-- Inserir Professores
INSERT INTO Professores (RG, Nome, DataN, Telefone, Email, Usuario, Senha, Endereco, Complemento, EmailInstitucional, Curso, Turno) 
VALUES 
('123456789', 'José Almeida', '1985-01-10', '11912345678', 'jose.almeida@gmail.com', 'josealmeida', 'senha123', 'Rua 1', 'Apt 101', 'jose.almeida@instituicao.edu', 'Matemática', 'Manhã'),
('987654321', 'Maria Silva', '1990-02-20', '11987654321', 'maria.silva@gmail.com', 'mariasilva', 'senha123', 'Rua 2', 'Apt 202', 'maria.silva@instituicao.edu', 'História', 'Tarde'),
('123123123', 'Paulo Santos', '1982-03-15', '11912312345', 'paulo.santos@gmail.com', 'paulosantos', 'senha123', 'Rua 3', 'Apt 303', 'paulo.santos@instituicao.edu', 'Física', 'Noite'),
('321321321', 'Carla Souza', '1987-04-25', '11932132143', 'carla.souza@gmail.com', 'carlasouza', 'senha123', 'Rua 4', 'Apt 404', 'carla.souza@instituicao.edu', 'Química', 'Manhã'),
('456456456', 'Lucas Pereira', '1992-05-30', '11945645678', 'lucas.pereira@gmail.com', 'lucaspereira', 'senha123', 'Rua 5', 'Apt 505', 'lucas.pereira@instituicao.edu', 'Geografia', 'Tarde');

-- Inserir Matérias
INSERT INTO Materias (Nome_Materia, CargaHR, ID_Curso, ID_Professor) 
VALUES 
('Cálculo I', 80, 1, 1),
('História Antiga', 60, 2, 2),
('Mecânica Clássica', 90, 3, 3),
('Química Orgânica', 80, 4, 4),
('Geografia Física', 70, 5, 5);
