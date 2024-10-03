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

CREATE TABLE Reposicao 
( 
    ID_AulaPerdida INT NOT NULL,  
    Data DATE NOT NULL,  
    Docs VARCHAR(255) NOT NULL,  
    ID_Reposicao INT PRIMARY KEY AUTO_INCREMENT
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
