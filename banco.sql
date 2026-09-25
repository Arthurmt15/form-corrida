CREATE DATABASE IF NOT EXISTS corrida_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE corrida_db;

CREATE TABLE IF NOT EXISTS inscricoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  cpf VARCHAR(14) NOT NULL,
  data_nascimento DATE NOT NULL,
  sexo ENUM('Masculino','Feminino','Outro') NOT NULL,
  categoria VARCHAR(50) NOT NULL,
  distancia ENUM('5km','10km','21km','42km') NOT NULL,
  tamanho_camiseta ENUM('PP','P','M','G','GG') NOT NULL,
  telefone VARCHAR(20),
  equipe VARCHAR(100),
  aceite_regulamento TINYINT(1) NOT NULL DEFAULT 0,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
