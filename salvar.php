<?php
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.php');
  exit;
}

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$cpf = trim($_POST['cpf'] ?? '');
$data_nascimento = $_POST['data_nascimento'] ?? '';
$sexo = $_POST['sexo'] ?? '';
$categoria = trim($_POST['categoria'] ?? '');
$distancia = $_POST['distancia'] ?? '';
$tamanho_camiseta = $_POST['tamanho_camiseta'] ?? '';
$telefone = trim($_POST['telefone'] ?? '');
$equipe = trim($_POST['equipe'] ?? '');
$aceite = isset($_POST['aceite_regulamento']) ? 1 : 0;

// Validação básica
if (!$nome || !$email || !$cpf || !$data_nascimento || !$sexo || !$categoria || !$distancia || !$tamanho_camiseta || !$aceite) {
  header('Location: index.php?erro=' . urlencode('Preencha todos os campos obrigatórios.'));
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header('Location: index.php?erro=' . urlencode('E-mail inválido.'));
  exit;
}

try {
  $sql = "INSERT INTO inscricoes (nome, email, cpf, data_nascimento, sexo, categoria, distancia, tamanho_camiseta, telefone, equipe, aceite_regulamento)
          VALUES (:nome, :email, :cpf, :data_nascimento, :sexo, :categoria, :distancia, :tamanho_camiseta, :telefone, :equipe, :aceite)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':nome' => $nome,
    ':email' => $email,
    ':cpf' => $cpf,
    ':data_nascimento' => $data_nascimento,
    ':sexo' => $sexo,
    ':categoria' => $categoria,
    ':distancia' => $distancia,
    ':tamanho_camiseta' => $tamanho_camiseta,
    ':telefone' => $telefone ?: null,
    ':equipe' => $equipe ?: null,
    ':aceite' => $aceite,
  ]);
  header('Location: index.php?sucesso=1');
} catch (PDOException $e) {
  header('Location: index.php?erro=' . urlencode('Erro ao salvar: ' . $e->getMessage()));
}
