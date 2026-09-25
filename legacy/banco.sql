-- Contexto: schema normalizado — pessoas (quem/onde) 1:N inscricoes (corrida).
-- Uma pessoa pode ter várias inscrições (ex: 5km e 10km), sem duplicar dados pessoais.
CREATE DATABASE IF NOT EXISTS corrida_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE corrida_db;

CREATE TABLE IF NOT EXISTS pessoas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  nome_social VARCHAR(150),
  tipo_pessoa ENUM('FISICA','JURIDICA') NOT NULL DEFAULT 'FISICA',
  cpf_cnpj VARCHAR(18) NOT NULL,
  rg_ie VARCHAR(20),
  data_nascimento DATE NOT NULL,
  genero VARCHAR(20),
  email VARCHAR(150) NOT NULL,
  email2 VARCHAR(150),
  celular VARCHAR(20) NOT NULL,
  tem_whatsapp TINYINT(1) NOT NULL DEFAULT 1,
  telefone_fixo VARCHAR(20),
  cep VARCHAR(9) NOT NULL,
  logradouro VARCHAR(150) NOT NULL,
  numero VARCHAR(10) NOT NULL,
  complemento VARCHAR(100),
  bairro VARCHAR(100) NOT NULL,
  cidade VARCHAR(100) NOT NULL,
  uf CHAR(2) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_pessoa_doc (cpf_cnpj),
  UNIQUE KEY uq_pessoa_email (email),
  KEY idx_pessoa_cidade (cidade)
);

CREATE TABLE IF NOT EXISTS inscricoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pessoa_id INT NOT NULL,
  distancia ENUM('5km','10km','21km','42km') NOT NULL,
  tamanho_camiseta ENUM('PP','P','M','G','GG') NOT NULL,
  categoria VARCHAR(50) NOT NULL,
  equipe VARCHAR(100),
  origem VARCHAR(50) NOT NULL DEFAULT 'Site',
  status_cadastro ENUM('Ativo','Inativo','Bloqueado') NOT NULL DEFAULT 'Ativo',
  aceite_regulamento TINYINT(1) NOT NULL DEFAULT 0,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_insc_pessoa FOREIGN KEY (pessoa_id) REFERENCES pessoas (id) ON DELETE CASCADE,
  UNIQUE KEY uq_pessoa_distancia (pessoa_id, distancia),
  KEY idx_insc_status (status_cadastro),
  KEY idx_insc_distancia (distancia)
);
