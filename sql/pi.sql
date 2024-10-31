-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 31/10/2024 às 01:12
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


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

--
-- Despejando dados para a tabela `aula`
--

INSERT INTO `aula` (`ID_Aula`, `ID_Materia`, `Horario_Inicio`, `ID_Curso`, `Horario_Termino`) VALUES
(1, 1, '08:00:00', 1, '09:00:00'),
(2, 2, '10:00:00', 2, '11:00:00'),
(3, 3, '14:00:00', 3, '15:00:00'),
(4, 4, '16:00:00', 4, '17:00:00'),
(5, 5, '18:00:00', 5, '19:00:00');

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

--
-- Despejando dados para a tabela `aula_nao_ministrada`
--

INSERT INTO `aula_nao_ministrada` (`ID_Aula_Nao_Ministrada`, `Date_Time`, `Observacao`, `ID_Aula`, `ID_Professor`, `ID_Materia`, `Justificado`, `docs`, `Aula_Reposta`) VALUES
(1, '2024-02-15', 'Aula cancelada', 1, 1, 1, 'Justificado', '', 'Não'),
(2, '2024-02-20', 'Professor ausente', 2, 2, NULL, 'Não Justificado', '', 'Não'),
(3, '2024-02-25', 'Problemas técnicos', 3, NULL, NULL, 'Não Justificado', '', 'Não'),
(4, '2024-03-01', 'Feriado', 4, NULL, NULL, 'Não Justificado', '', 'Não'),
(5, '2024-03-05', '1231231', 5, 3, 3, 'Justificado', '', 'Não'),
(12, '0001-01-01', '123', 1, 1, 1, 'Justificado', '', 'Não'),
(13, '0001-01-01', '123', 2, 2, 2, 'Não Justificado', '', 'Não'),
(14, '0002-02-21', '4324', 1, 1, 1, 'Justificado', '', 'Não'),
(15, '0032-02-12', '213', 1, 1, 1, 'Não Justificado', '', 'Não');

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
(1, 'Matemática', 'Manhã', 1600, 'Grade 1', 1, 1),
(2, 'História', 'Tarde', 1400, 'Grade 2', 2, 2),
(3, 'Física', 'Noite', 1500, 'Grade 3', 3, 3),
(4, 'Química', 'Manhã', 1600, 'Grade 4', 4, 4),
(5, 'Geografia', 'Tarde', 1400, 'Grade 5', 5, 5);

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
(1, 1, 'Matemática', '2024-01-01', '2024-12-31'),
(2, 2, 'História', '2024-01-01', '2024-12-31'),
(3, 3, 'Física', '2024-01-01', '2024-12-31'),
(4, 4, 'Química', '2024-01-01', '2024-12-31'),
(5, 5, 'Geografia', '2024-01-01', '2024-12-31');

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
(1, 'Instituto de Ciências Exatas', 'Rua A, 100', 'Prédio B'),
(2, 'Faculdade de Humanidades', 'Rua B, 200', 'Prédio C'),
(3, 'Universidade de Medicina', 'Rua C, 300', 'Prédio D'),
(4, 'Centro de Psicologia', 'Rua D, 400', 'Prédio E'),
(5, 'Escola de Administração', 'Rua E, 500', 'Prédio F');

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

--
-- Despejando dados para a tabela `materias`
--

INSERT INTO `materias` (`ID_Materia`, `Nome`, `CargaHR`, `ID_Curso`, `ID_Professor`) VALUES
(1, 'Álgebra', 80, 1, 1),
(2, 'História Antiga', 60, 2, 2),
(3, 'Cinemática', 90, 3, 3),
(4, 'Química Orgânica', 70, 4, 4),
(5, 'Geopolítica', 80, 5, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `presenca`
--

CREATE TABLE `presenca` (
  `ID_Materia` int(11) NOT NULL,
  `Data` date NOT NULL,
  `Status` varchar(20) NOT NULL,
  `ID_Professor` int(11) NOT NULL,
  `ID_CursoA` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `presenca`
--

INSERT INTO `presenca` (`ID_Materia`, `Data`, `Status`, `ID_Professor`, `ID_CursoA`) VALUES
(1, '2024-01-10', 'Presente', 1, 1),
(2, '2024-01-15', 'Ausente', 2, 2),
(3, '2024-01-20', 'Presente', 3, 3),
(4, '2024-01-25', 'Presente', 4, 4),
(5, '2024-01-30', 'Ausente', 5, 5);

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
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `reposicao`
--

CREATE TABLE `reposicao` (
  `ID_Reposicao` int(11) NOT NULL,
  `ID_Aula_Nao_Ministrada` int(11) NOT NULL,
  `DataReposicao` date DEFAULT NULL,
  `Mensagem` varchar(255) NOT NULL,
  `docs_plano_aula` varchar(255) DEFAULT NULL,
  `Status_Pedido` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `reposicao`
--

INSERT INTO `reposicao` (`ID_Reposicao`, `ID_Aula_Nao_Ministrada`, `DataReposicao`, `Mensagem`, `docs_plano_aula`, `Status_Pedido`) VALUES
(1, 1, '2024-02-20', '', '', 'Rejeitado'),
(2, 2, '2024-02-25', '', '', 'Aguardando'),
(3, 3, '2024-03-01', '', '', 'Aprovada'),
(4, 4, '2024-03-05', '', '', 'Aguardando'),
(5, 5, '2024-03-10', '1', 'Aula 06 - Comando SELECT - Parte 2.pdf', 'Pendente'),
(6, 1, '0024-03-01', 'teste', '', ''),
(7, 1, '0001-01-01', 'teste', '', 'Pendente');

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
-- Índices de tabela `presenca`
--
ALTER TABLE `presenca`
  ADD KEY `ID_Materia` (`ID_Materia`),
  ADD KEY `ID_Professor` (`ID_Professor`),
  ADD KEY `ID_CursoA` (`ID_CursoA`);

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
  MODIFY `ID_Aula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `aula_nao_ministrada`
--
ALTER TABLE `aula_nao_ministrada`
  MODIFY `ID_Aula_Nao_Ministrada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `coordenadores`
--
ALTER TABLE `coordenadores`
  MODIFY `ID_Coordenador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `curso`
--
ALTER TABLE `curso`
  MODIFY `ID_Curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `cursoativo`
--
ALTER TABLE `cursoativo`
  MODIFY `ID_CursoA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `instituicao`
--
ALTER TABLE `instituicao`
  MODIFY `ID_Instituicao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `materias`
--
ALTER TABLE `materias`
  MODIFY `ID_Materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `professores`
--
ALTER TABLE `professores`
  MODIFY `ID_Professor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `reposicao`
--
ALTER TABLE `reposicao`
  MODIFY `ID_Reposicao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
-- Restrições para tabelas `presenca`
--
ALTER TABLE `presenca`
  ADD CONSTRAINT `presenca_ibfk_1` FOREIGN KEY (`ID_Materia`) REFERENCES `materias` (`ID_Materia`) ON DELETE CASCADE,
  ADD CONSTRAINT `presenca_ibfk_2` FOREIGN KEY (`ID_Professor`) REFERENCES `professores` (`ID_Professor`) ON DELETE CASCADE,
  ADD CONSTRAINT `presenca_ibfk_3` FOREIGN KEY (`ID_CursoA`) REFERENCES `cursoativo` (`ID_CursoA`) ON DELETE CASCADE;

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
