-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/10/2024 às 02:58
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
-- Banco de dados: `sistema_clientes`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `arquivo_pdf` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `email`, `arquivo_pdf`, `created_at`) VALUES
(1, 'ikosmooh1', 'caiogamerof@gmail.com', '66ff35a774691.Caio Souza.pdf', '2024-10-04 00:24:07'),
(2, 'Cliente 1', 'cliente1@example.com', 'cliente1.pdf', '2024-10-04 00:57:07'),
(3, 'Cliente 2', 'cliente2@example.com', 'cliente2.pdf', '2024-10-04 00:57:07'),
(4, 'Cliente 3', 'cliente3@example.com', 'cliente3.pdf', '2024-10-04 00:57:07'),
(5, 'Cliente 4', 'cliente4@example.com', 'cliente4.pdf', '2024-10-04 00:57:07'),
(6, 'Cliente 5', 'cliente5@example.com', 'cliente5.pdf', '2024-10-04 00:57:07');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel_acesso` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `nivel_acesso`) VALUES
(1, 'ikosmooh', 'iKosMooh@123.c', '$2y$10$L27yOz3l7ASONPTcGZPklOuJ.PnbW3hLHbcqzEgxNB8HUU/OKeu5q', 'ADMIN'),
(2, 'Administrador', 'admin@example.com', 'senha_admin', 'admin'),
(3, 'Usuário 1', 'usuario1@example.com', 'senha_usuario1', 'usuario'),
(4, 'Usuário 2', 'usuario2@example.com', 'senha_usuario2', 'usuario'),
(5, 'Usuário 3', 'usuario3@example.com', 'senha_usuario3', 'usuario'),
(6, 'Usuário 4', 'usuario4@example.com', 'senha_usuario4', 'usuario');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
