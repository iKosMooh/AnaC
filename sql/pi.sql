-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13/11/2024 às 00:46
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "-03:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pi`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `aula`
--

CREATE TABLE `aula` (
  `ID_Aula` int(11) NOT NULL,
  `ID_Materia` int(11) NOT NULL,
  `Horario_Inicio` time NOT NULL,
  `ID_Curso` int(11) NOT NULL,
  `Horario_Termino` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `aula_nao_ministrada`
--

CREATE TABLE `aula_nao_ministrada` (
  `ID_Aula_Nao_Ministrada` int(11) NOT NULL,
  `Date_Time` date NOT NULL,
  `Observacao` varchar(255) DEFAULT NULL,
  `ID_Aula` int(11) NOT NULL,
  `ID_Professor` int(11) DEFAULT NULL,
  `ID_Materia` int(11) DEFAULT NULL,
  `Justificado` varchar(50) NOT NULL DEFAULT 'Não Justificado',
  `docs` varchar(255) NOT NULL,
  `Aula_Reposta` varchar(30) NOT NULL DEFAULT 'Não'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `coordenadores`
--

CREATE TABLE `coordenadores` (
  `ID_Coordenador` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `DataN` date NOT NULL,
  `EmailInstitucional` varchar(100) NOT NULL,
  `Curso` varchar(100) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Usuario` varchar(50) NOT NULL,
  `Senha` varchar(50) NOT NULL,
  `Telefone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `coordenadores`
--

INSERT INTO `coordenadores` (`ID_Coordenador`, `Nome`, `DataN`, `EmailInstitucional`, `Curso`, `Email`, `Usuario`, `Senha`, `Telefone`) VALUES
(1, 'Ana Paula', '1975-04-10', 'ana.paula@instituicao.edu', 'Engenharia', 'ana.pessoal@gmail.com', 'anapaula', 'senha123', '11999999999'),
(2, 'Carlos Silva', '1980-08-15', 'carlos.silva@instituicao.edu', 'Administração', 'carlos.silva@gmail.com', 'carlossilva', 'senha123', '11988888888'),
(3, 'Juliana Costa', '1978-12-20', 'juliana.costa@instituicao.edu', 'Direito', 'juliana.costa@gmail.com', 'julianacosta', 'senha123', '11977777777'),
(4, 'Roberto Nunes', '1970-05-25', 'roberto.nunes@instituicao.edu', 'Medicina', 'roberto.nunes@gmail.com', 'robertonunes', 'senha123', '11966666666'),
(5, 'Maria Oliveira', '1985-07-30', 'maria.oliveira@instituicao.edu', 'Psicologia', 'maria.oliveira@gmail.com', 'mariaoliveira', 'senha123', '11955555555');

-- --------------------------------------------------------

--
-- Estrutura para tabela `curso`
--

CREATE TABLE `curso` (
  `ID_Curso` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Turno` varchar(20) NOT NULL,
  `CargaHR` int(11) NOT NULL,
  `Grade` varchar(100) NOT NULL,
  `ID_Coordenador` int(11) NOT NULL,
  `ID_Instituicao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `curso`
--

INSERT INTO `curso` (`ID_Curso`, `Nome`, `Turno`, `CargaHR`, `Grade`, `ID_Coordenador`, `ID_Instituicao`) VALUES
(1, 'Desenvolvimento de Software Multiplataforma', 'Noturno', 2800, 'Grade 1', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursoativo`
--

CREATE TABLE `cursoativo` (
  `ID_CursoA` int(11) NOT NULL,
  `ID_Curso` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `DataInicio` date NOT NULL,
  `DataFim` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cursoativo`
--

INSERT INTO `cursoativo` (`ID_CursoA`, `ID_Curso`, `Nome`, `DataInicio`, `DataFim`) VALUES
(1, 1, 'DSM 1º Semestre 2024', '2024-02-01', '2026-12-11');

-- --------------------------------------------------------

--
-- Estrutura para tabela `instituicao`
--

CREATE TABLE `instituicao` (
  `ID_Instituicao` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Endereco` varchar(255) NOT NULL,
  `Complemento` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `instituicao`
--

INSERT INTO `instituicao` (`ID_Instituicao`, `Nome`, `Endereco`, `Complemento`) VALUES
(1, 'Fatec Itapira - \"Dr. Ogari de Castro Pacheco\"', 'R. Tereza Lera Paoletti, 590 - Bela Vista, Itapira - SP, 13974-080', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `materias`
--

CREATE TABLE `materias` (
  `ID_Materia` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `CargaHR` int(11) NOT NULL,
  `ID_Curso` int(11) NOT NULL,
  `ID_Professor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `professores`
--

CREATE TABLE `professores` (
  `ID_Professor` int(11) NOT NULL,
  `RG` varchar(20) DEFAULT NULL,
  `Nome` varchar(100) NOT NULL,
  `DataN` date NOT NULL,
  `Telefone` varchar(15) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `Senha` varchar(50) NOT NULL,
  `Endereco` varchar(255) NOT NULL,
  `Complemento` varchar(50) DEFAULT NULL,
  `EmailInstitucional` varchar(100) NOT NULL,
  `Curso` varchar(100) NOT NULL,
  `Turno` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professores`
--

INSERT INTO `professores` (`ID_Professor`, `RG`, `Nome`, `DataN`, `Telefone`, `Email`, `Usuario`, `Senha`, `Endereco`, `Complemento`, `EmailInstitucional`, `Curso`, `Turno`) VALUES
(1, '123456789', 'José Almeida', '1985-01-10', '11912345678', 'jose.almeida@gmail.com', 'josealmeida', 'senha123', 'Rua 1', 'Apt 101', 'jose.almeida@instituicao.edu', 'Matemática', 'Manhã'),
(2, '987654321', 'Maria Silva', '1990-02-20', '11987654321', 'maria.silva@gmail.com', 'mariasilva', 'senha123', 'Rua 2', 'Apt 202', 'maria.silva@instituicao.edu', 'História', 'Tarde'),
(3, '123123123', 'Paulo Santos', '1982-03-15', '11912312345', 'paulo.santos@gmail.com', 'paulosantos', 'senha123', 'Rua 3', 'Apt 303', 'paulo.santos@instituicao.edu', 'Física', 'Noite'),
(4, '321321321', 'Carla Souza', '1987-04-25', '11932132143', 'carla.souza@gmail.com', 'carlasouza', 'senha123', 'Rua 4', 'Apt 404', 'carla.souza@instituicao.edu', 'Química', 'Manhã'),
(5, '456456456', 'Ricardo Ferreira', '1988-05-30', '11945645678', 'ricardo.ferreira@gmail.com', 'ricardoferreira', 'senha123', 'Rua 5', 'Apt 505', 'ricardo.ferreira@instituicao.edu', 'Geografia', 'Tarde');

-- --------------------------------------------------------

--
-- Estrutura para tabela `professores_cursos`
--

CREATE TABLE `professores_cursos` (
  `ID_Professor` int(11) NOT NULL,
  `ID_Curso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professores_cursos`
--

INSERT INTO `professores_cursos` (`ID_Professor`, `ID_Curso`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `reposicao`
--

CREATE TABLE `reposicao` (
  `ID_Reposicao` int(11) NOT NULL,
  `ID_Aula_Nao_Ministrada` int(11) NOT NULL,
  `DataReposicao` date DEFAULT NULL,
  `docs_plano_aula` varchar(255) DEFAULT NULL,
  `Status_Pedido` varchar(20) NOT NULL,
  `Resposta_Coordenador` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `reposicao`
--

INSERT INTO `reposicao` (`ID_Reposicao`, `ID_Aula_Nao_Ministrada`, `DataReposicao`, `docs_plano_aula`, `Status_Pedido`, `Resposta_Coordenador`) VALUES
(2, 18, '2005-01-01', NULL, 'Pendente', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `aula`
--
ALTER TABLE `aula`
  ADD PRIMARY KEY (`ID_Aula`),
  ADD KEY `ID_Materia` (`ID_Materia`),
  ADD KEY `ID_Curso` (`ID_Curso`);

--
-- Índices de tabela `aula_nao_ministrada`
--
ALTER TABLE `aula_nao_ministrada`
  ADD PRIMARY KEY (`ID_Aula_Nao_Ministrada`),
  ADD KEY `ID_Aula` (`ID_Aula`),
  ADD KEY `fk_professor` (`ID_Professor`),
  ADD KEY `fk_materias` (`ID_Materia`);

--
-- Índices de tabela `coordenadores`
--
ALTER TABLE `coordenadores`
  ADD PRIMARY KEY (`ID_Coordenador`),
  ADD UNIQUE KEY `Usuario` (`Usuario`);

--
-- Índices de tabela `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`ID_Curso`),
  ADD KEY `ID_Coordenador` (`ID_Coordenador`),
  ADD KEY `ID_Instituicao` (`ID_Instituicao`);

--
-- Índices de tabela `cursoativo`
--
ALTER TABLE `cursoativo`
  ADD PRIMARY KEY (`ID_CursoA`),
  ADD KEY `ID_Curso` (`ID_Curso`);

--
-- Índices de tabela `instituicao`
--
ALTER TABLE `instituicao`
  ADD PRIMARY KEY (`ID_Instituicao`);

--
-- Índices de tabela `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`ID_Materia`),
  ADD KEY `ID_Curso` (`ID_Curso`),
  ADD KEY `ID_Professor` (`ID_Professor`);

--
-- Índices de tabela `professores`
--
ALTER TABLE `professores`
  ADD PRIMARY KEY (`ID_Professor`),
  ADD UNIQUE KEY `Usuario` (`Usuario`),
  ADD UNIQUE KEY `EmailInstitucional` (`EmailInstitucional`),
  ADD UNIQUE KEY `RG` (`RG`);

--
-- Índices de tabela `professores_cursos`
--
ALTER TABLE `professores_cursos`
  ADD KEY `ID_Professor` (`ID_Professor`),
  ADD KEY `ID_Curso` (`ID_Curso`);

--
-- Índices de tabela `reposicao`
--
ALTER TABLE `reposicao`
  ADD PRIMARY KEY (`ID_Reposicao`),
  ADD KEY `ID_Aula_Nao_Ministrada` (`ID_Aula_Nao_Ministrada`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aula`
--
ALTER TABLE `aula`
  MODIFY `ID_Aula` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `aula_nao_ministrada`
--
ALTER TABLE `aula_nao_ministrada`
  MODIFY `ID_Aula_Nao_Ministrada` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `coordenadores`
--
ALTER TABLE `coordenadores`
  MODIFY `ID_Coordenador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `curso`
--
ALTER TABLE `curso`
  MODIFY `ID_Curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `cursoativo`
--
ALTER TABLE `cursoativo`
  MODIFY `ID_CursoA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `instituicao`
--
ALTER TABLE `instituicao`
  MODIFY `ID_Instituicao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `materias`
--
ALTER TABLE `materias`
  MODIFY `ID_Materia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `professores`
--
ALTER TABLE `professores`
  MODIFY `ID_Professor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `reposicao`
--
ALTER TABLE `reposicao`
  MODIFY `ID_Reposicao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `aula`
--
ALTER TABLE `aula`
  ADD CONSTRAINT `aula_ibfk_1` FOREIGN KEY (`ID_Materia`) REFERENCES `materias` (`ID_Materia`) ON DELETE CASCADE,
  ADD CONSTRAINT `aula_ibfk_2` FOREIGN KEY (`ID_Curso`) REFERENCES `curso` (`ID_Curso`) ON DELETE CASCADE;

--
-- Restrições para tabelas `aula_nao_ministrada`
--
ALTER TABLE `aula_nao_ministrada`
  ADD CONSTRAINT `aula_nao_ministrada_ibfk_1` FOREIGN KEY (`ID_Aula`) REFERENCES `aula` (`ID_Aula`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_materias` FOREIGN KEY (`ID_Materia`) REFERENCES `materias` (`ID_Materia`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_professor` FOREIGN KEY (`ID_Professor`) REFERENCES `professores` (`ID_Professor`) ON DELETE CASCADE;

--
-- Restrições para tabelas `curso`
--
ALTER TABLE `curso`
  ADD CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`ID_Coordenador`) REFERENCES `coordenadores` (`ID_Coordenador`) ON DELETE CASCADE,
  ADD CONSTRAINT `curso_ibfk_2` FOREIGN KEY (`ID_Instituicao`) REFERENCES `instituicao` (`ID_Instituicao`) ON DELETE CASCADE;

--
-- Restrições para tabelas `cursoativo`
--
ALTER TABLE `cursoativo`
  ADD CONSTRAINT `cursoativo_ibfk_1` FOREIGN KEY (`ID_Curso`) REFERENCES `curso` (`ID_Curso`) ON DELETE CASCADE;

--
-- Restrições para tabelas `materias`
--
ALTER TABLE `materias`
  ADD CONSTRAINT `materias_ibfk_1` FOREIGN KEY (`ID_Curso`) REFERENCES `curso` (`ID_Curso`) ON DELETE CASCADE,
  ADD CONSTRAINT `materias_ibfk_2` FOREIGN KEY (`ID_Professor`) REFERENCES `professores` (`ID_Professor`) ON DELETE CASCADE;

--
-- Restrições para tabelas `professores_cursos`
--
ALTER TABLE `professores_cursos`
  ADD CONSTRAINT `professores_cursos_ibfk_1` FOREIGN KEY (`ID_Professor`) REFERENCES `professores` (`ID_Professor`) ON DELETE CASCADE,
  ADD CONSTRAINT `professores_cursos_ibfk_2` FOREIGN KEY (`ID_Curso`) REFERENCES `curso` (`ID_Curso`) ON DELETE CASCADE;

--
-- Restrições para tabelas `reposicao`
--
ALTER TABLE `reposicao`
  ADD CONSTRAINT `reposicao_ibfk_1` FOREIGN KEY (`ID_Aula_Nao_Ministrada`) REFERENCES `aula_nao_ministrada` (`ID_Aula_Nao_Ministrada`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
