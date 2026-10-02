-- ============================================================
--  Banco de dados: niltonbakargi
--
--  ANTES de importar:
--  1. Crie o banco via cPanel > MySQL Databases
--  2. Selecione o banco criado no phpMyAdmin (menu lateral)
--  3. Clique em Importar e selecione este arquivo
-- ============================================================

-- 1. TABELA DE ADMIN (login)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin` (
  `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario`  VARCHAR(60)  NOT NULL UNIQUE,
  `senha`    VARCHAR(255) NOT NULL COMMENT 'bcrypt hash',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuário padrão: admin / senha: nilton2025
-- (troque a senha no primeiro acesso)
INSERT INTO `admin` (`usuario`, `senha`) VALUES
('admin', '$2y$12$wQz3K5X1mN8vL0pT7uRe4OqF6dYjCbHsIgWnMoUkEaVxPtZlDrBci');


-- 2. TABELA DE TRABALHOS
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `trabalhos` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `titulo`      VARCHAR(150)    NOT NULL,
  `categoria`   ENUM('florestal','geo') NOT NULL,
  `descricao`   TEXT            NOT NULL,
  `data_obra`   DATE            NULL     COMMENT 'data em que o serviço foi realizado',
  `criado_em`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ativo`       TINYINT(1)      NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_categoria` (`categoria`),
  KEY `idx_ativo`     (`ativo`),
  KEY `idx_criado_em` (`criado_em`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- 3. TABELA DE FOTOS (múltiplas por trabalho)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `trabalho_fotos` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `trabalho_id` INT UNSIGNED NOT NULL,
  `arquivo`     VARCHAR(255) NOT NULL COMMENT 'caminho relativo em /uploads/',
  `ordem`       TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'foto 0 = capa',
  PRIMARY KEY (`id`),
  KEY `idx_trabalho` (`trabalho_id`),
  CONSTRAINT `fk_foto_trabalho`
    FOREIGN KEY (`trabalho_id`)
    REFERENCES `trabalhos` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- 4. TABELA DE AVALIACOES DE CLIENTES (uso futuro)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `avaliacoes` (
  `id`           INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `trabalho_id`  INT UNSIGNED,
  `nome_cliente` VARCHAR(150)     NOT NULL,
  `texto`        TEXT,
  `nota`         TINYINT UNSIGNED NOT NULL COMMENT '1 a 5 estrelas',
  `aprovado`     TINYINT(1)       NOT NULL DEFAULT 0 COMMENT 'moderacao pelo admin antes de exibir',
  `criado_em`    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_aprovado` (`aprovado`),
  CONSTRAINT `fk_avaliacao_trabalho`
    FOREIGN KEY (`trabalho_id`)
    REFERENCES `trabalhos` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
