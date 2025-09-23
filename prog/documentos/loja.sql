-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 23/09/2025 às 22:53
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `loja`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `administradores`
--

INSERT INTO `administradores` (`id`, `usuario`, `senha`) VALUES
(2, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `email`, `senha`, `telefone`, `endereco`) VALUES
(1, 'Cliente Teste', 'cliente', 'cliente123', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `cliente_nome` varchar(255) NOT NULL,
  `cliente_email` varchar(255) NOT NULL,
  `cliente_telefone` varchar(20) DEFAULT NULL,
  `cliente_endereco` text NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `cliente_nome`, `cliente_email`, `cliente_telefone`, `cliente_endereco`, `valor_total`, `data_pedido`) VALUES
(1, 'gustavo henrique ', 'gh58577@gmail.com', NULL, 'travessa oito, santarem', 39.90, '2025-09-09 23:37:36'),
(2, 'asdadsda', 'asdadsdaa@gmail.com', NULL, 'dadasdada, adasdad', 39.90, '2025-09-10 00:35:07'),
(3, 'fvdfdfdfdgfd', 'vbfddfbdbfdbf@gmail.com', NULL, 'tv oito, sasasaadasa', 169.80, '2025-09-15 02:27:46'),
(4, 'gustavo ', 'adsdafa@gmail.com', NULL, 'adsdaadsad, adsadsda', 209.70, '2025-09-17 00:12:03'),
(5, 'Gustavo henriquee', 'gikberto.fernandes@gmail.com', NULL, 'travessa oito, santarem', 39.90, '2025-09-22 22:09:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_itens`
--

CREATE TABLE `pedido_itens` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `cor` varchar(50) NOT NULL,
  `tamanho` varchar(10) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedido_itens`
--

INSERT INTO `pedido_itens` (`id`, `pedido_id`, `produto_id`, `cor`, `tamanho`, `quantidade`, `preco_unitario`) VALUES
(1, 1, 1, 'Branco', 'G', 1, 39.90),
(2, 2, 1, 'Branco', 'G', 1, 39.90),
(3, 3, 1, 'Branco', 'M', 1, 39.90),
(4, 3, 2, 'Azul', '38', 1, 129.90),
(5, 4, 1, 'Branco', 'G', 2, 39.90),
(6, 4, 2, 'Azul', '38', 1, 129.90),
(7, 5, 1, 'Branco', 'G', 1, 39.90);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `imagem_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `categoria`, `imagem_url`) VALUES
(1, 'Camiseta Básica Branca', 'Uma camiseta de algodão, perfeita para o dia a dia.', 39.90, 'Camisetas', '68d1c5992f3265.18552161-04305990010_1.jpg.webp'),
(2, 'Calça Jeans Skinny', 'Calça jeans azul, corte skinny, ideal para um visual moderno.', 129.90, 'Calças', '68d1c57bc61783.12083744-calca_jeans_feminina_skinny_com_recorte_bolso_109_1_a0a2630e1ee2956a43f8be7e8b5052bb_20250805135636.jpg.webp'),
(3, 'Blusa de Frio', 'Blusa de lã macia e confortável, para os dias mais frios.', 90.00, 'Blusas', '68d1c50e1006a7.77806802-482926-4.jpg.webp'),
(4, 'Vestido Estampado', 'Vestido curto e floral, perfeito para o verão.', 159.90, 'Vestidos', '68d1c60a5b0724.60079096-vestido_de_festa_floral_branco_off_white_ref_2854_5848_1_55957d8d308a442884d2e5ad1e605517_20250731160616.jpg.webp'),
(7, 'camisa nike', 'nike', 99.00, 'Camisetas', '68d1c4e19fe947.06803067-93148231A5.jpg.avif'),
(8, 'calca nike', 'nike', 100.00, 'Calças', '68d1c5ce1e1de1.14809243-277305-1.jpg.webp');

-- --------------------------------------------------------

--
-- Estrutura para tabela `variacoes`
--

CREATE TABLE `variacoes` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `cor` varchar(50) NOT NULL,
  `tamanho` varchar(10) NOT NULL,
  `estoque` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `variacoes`
--

INSERT INTO `variacoes` (`id`, `produto_id`, `cor`, `tamanho`, `estoque`) VALUES
(1, 1, 'Branco', 'P', 20),
(2, 1, 'Branco', 'M', 25),
(3, 1, 'Branco', 'G', 15),
(4, 2, 'Azul', '38', 10),
(5, 2, 'Azul', '40', 12),
(6, 3, 'Cinza', 'M', 30),
(7, 4, 'Floral', 'P', 5),
(8, 4, 'Floral', 'M', 8),
(11, 3, 'Vermelho', 'G', 10);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `variacoes`
--
ALTER TABLE `variacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `variacoes`
--
ALTER TABLE `variacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD CONSTRAINT `pedido_itens_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedido_itens_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Restrições para tabelas `variacoes`
--
ALTER TABLE `variacoes`
  ADD CONSTRAINT `variacoes_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
