CREATE TABLE Professores (
    Matricula INT PRIMARY KEY,
    RG VARCHAR(50),
    Nome VARCHAR(50) NOT NULL,
    DataN DATE NOT NULL,
    Telefone VARCHAR(50),
    Email VARCHAR(50) NOT NULL,
    Usuario VARCHAR(50) NOT NULL,
    Senha VARCHAR(50) NOT NULL,
    Endereco VARCHAR(50)
) DEFAULT CHARSET=utf8;

CREATE TABLE Alunos (
    MatriculaAL INT PRIMARY KEY,
    RG VARCHAR(50) NOT NULL,
    Nome VARCHAR(50) NOT NULL,
    DataN DATE NOT NULL,
    Telefone VARCHAR(50) NOT NULL,
    Email VARCHAR(50) NOT NULL,
    Usuario VARCHAR(50) NOT NULL,
    Senha VARCHAR(50) NOT NULL,
    Endereco VARCHAR(50) NOT NULL,
    Complemento VARCHAR(50),
    EmailInstitucional VARCHAR(50),
    Status VARCHAR(50) NOT NULL DEFAULT 'cadastrado',
    UNIQUE (RG, Usuario, EmailInstitucional)
) DEFAULT CHARSET=utf8;

CREATE TABLE Instituicao (
    ID_Instituicao INT PRIMARY KEY,
    Nome VARCHAR(50) NOT NULL,
    Endereco VARCHAR(50) NOT NULL,
    Complemento VARCHAR(50)
) DEFAULT CHARSET=utf8;

CREATE TABLE Curso (
    ID_Curso INT PRIMARY KEY,
    Nome VARCHAR(50) NOT NULL,
    Turno VARCHAR(50) NOT NULL,
    CargaHR VARCHAR(50) NOT NULL,
    Grade VARCHAR(50) NOT NULL,
    Coordenador VARCHAR(50) NOT NULL,
    ID_Instituicao INT,
    FOREIGN KEY (ID_Instituicao) REFERENCES Instituicao (ID_Instituicao)
) DEFAULT CHARSET=utf8;

CREATE TABLE Materias (
    ID_Materia INT PRIMARY KEY,
    Nome VARCHAR(50) NOT NULL,
    CargaHR VARCHAR(50) NOT NULL,
    Professor INT,
    ID_Curso INT,
    FOREIGN KEY (ID_Curso) REFERENCES Curso (ID_Curso)
) DEFAULT CHARSET=utf8;

CREATE TABLE CursoAtivo (
    ID_CursoA INT PRIMARY KEY,
    ID_Curso INT,
    Nome VARCHAR(50) NOT NULL,
    DataInicio DATE NOT NULL,
    DataFim DATE NOT NULL,
    Alunos INT NOT NULL,
    FOREIGN KEY (ID_Curso) REFERENCES Curso (ID_Curso)
) DEFAULT CHARSET=utf8;

CREATE TABLE PresencaAL (
    ID_CursoA INT,
    MatriculaAL INT,
    ID_Materia INT,
    Data DATE NOT NULL,
    Status VARCHAR(50) NOT NULL,
    FOREIGN KEY (ID_CursoA) REFERENCES CursoAtivo (ID_CursoA),
    FOREIGN KEY (MatriculaAL) REFERENCES Alunos (MatriculaAL),
    FOREIGN KEY (ID_Materia) REFERENCES Materias (ID_Materia)
) DEFAULT CHARSET=utf8;

CREATE TABLE Professores_Cursos (
    ID_Professor INT,
    ID_Curso INT,
    FOREIGN KEY (ID_Professor) REFERENCES Professores (Matricula),
    FOREIGN KEY (ID_Curso) REFERENCES Curso (ID_Curso)
) DEFAULT CHARSET=utf8;

CREATE TABLE Atestado (
    ID_Atestado INT PRIMARY KEY,
    ID_Aluno INT NOT NULL,
    ID_Professor INT NOT NULL,
    Descricao VARCHAR(50) NOT NULL,
    DataEmissao DATE NOT NULL,
    Docs VARCHAR(255) NOT NULL,
    UNIQUE (ID_Aluno, ID_Professor),
    FOREIGN KEY (ID_Aluno) REFERENCES Alunos (MatriculaAL),
    FOREIGN KEY (ID_Professor) REFERENCES Professores (Matricula)
) DEFAULT CHARSET=utf8;

CREATE TABLE Aulas (
    ID_Aula INT PRIMARY KEY AUTO_INCREMENT,
    MateriaID INT,
    Horario VARCHAR(50) NOT NULL,
    CursoID INT,
    FOREIGN KEY (MateriaID) REFERENCES Materias (ID_Materia),
    FOREIGN KEY (CursoID) REFERENCES Curso (ID_Curso)
) DEFAULT CHARSET=utf8mb4;

CREATE TABLE AulasPerdidas (
    ID_AulaPerdida INT PRIMARY KEY AUTO_INCREMENT,
    Data DATE NOT NULL,
    Observacao VARCHAR(255),
    AulaID INT,
    FOREIGN KEY (AulaID) REFERENCES Aulas (ID_Aula)
) DEFAULT CHARSET=utf8mb4;

CREATE TABLE Reposicoes (
    ID_Reposicao INT PRIMARY KEY AUTO_INCREMENT,
    ID_AulaPerdida INT,
    Data DATE NOT NULL,
    Docs VARCHAR(255) NOT NULL,
    FOREIGN KEY (ID_AulaPerdida) REFERENCES AulasPerdidas (ID_AulaPerdida)
) DEFAULT CHARSET=utf8mb4;

CREATE TABLE Coordenadores (
    ID_Coordenador INT PRIMARY KEY,
    Nome VARCHAR(50) NOT NULL,
    DataN DATE NOT NULL,
    EmailInstitucional VARCHAR(50) NOT NULL,
    Curso VARCHAR(50) NOT NULL,
    Email VARCHAR(50),
    Usuario VARCHAR(50) NOT NULL,
    Senha VARCHAR(50) NOT NULL,
    Telefone VARCHAR(50),
    UNIQUE (Usuario)
) DEFAULT CHARSET=utf8mb4;
