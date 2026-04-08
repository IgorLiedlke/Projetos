-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/04/2026 às 21:28
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
-- Banco de dados: `destino_centro_turismo`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `pacotes`
--

CREATE TABLE `pacotes` (
  `id_pacote` int(11) NOT NULL,
  `destino` varchar(100) NOT NULL,
  `descricao_logistica` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `vagas_disponiveis` int(11) NOT NULL,
  `categoria` enum('Congresso','Missão Tech','Incentivo','Reunião') DEFAULT 'Reunião'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pacotes`
--

INSERT INTO `pacotes` (`id_pacote`, `destino`, `descricao_logistica`, `preco`, `data_inicio`, `data_fim`, `vagas_disponiveis`, `categoria`) VALUES
(1, 'São Paulo - Centro Financeiro', 'Hospedagem na região da Av. Paulista. Business center 24h, café da manhã executivo e traslado para o aeroporto de Congonhas incluso.', 1850.00, '2026-05-10', '2026-05-13', 15, 'Reunião'),
(2, 'Florianópolis - Polo Tecnológico', 'Ideal para missões de inovação. Inclui hospedagem próxima ao Sapiens Parque e transporte privativo para visitas técnicas.', 2200.00, '2026-06-15', '2026-06-18', 10, 'Missão Tech'),
(3, 'Rio de Janeiro - Convenção Offshore', 'Focado no setor de energia. Hotel 5 estrelas em frente ao local do evento com salas de apoio exclusivas para reuniões de networking.', 3100.00, '2026-07-20', '2026-07-24', 20, 'Congresso'),
(4, 'Bento Gonçalves - Viagem de Incentivo', 'Pacote de premiação de alto padrão em vinícolas premium. Experiência gastronômica e logística de luxo para diretores e parceiros.', 4500.00, '2026-08-05', '2026-08-09', 8, 'Incentivo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_pacote` int(11) DEFAULT NULL,
  `data_solicitacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_reserva` enum('Pendente','Confirmada','Cancelada') DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome_completo` varchar(150) NOT NULL,
  `email_corporativo` varchar(100) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `preferencias_executivas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `pacotes`
--
ALTER TABLE `pacotes`
  ADD PRIMARY KEY (`id_pacote`);

--
-- Índices de tabela `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_pacote` (`id_pacote`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email_corporativo` (`email_corporativo`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `pacotes`
--
ALTER TABLE `pacotes`
  MODIFY `id_pacote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_pacote`) REFERENCES `pacotes` (`id_pacote`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
