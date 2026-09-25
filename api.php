<?php
// Contexto: API GET de cadastrados em JSON. Uso: api.php?q=Ana&pagina=1&limite=20.
// LGPD: documento sai mascarado; e-mail secundário e IDs internos não são expostos.
require 'src/Seguranca.php';
require 'config/database.php';
require 'src/Validacao.php';
require 'src/InscricaoRepository.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  http_response_code(405);
  echo json_encode(['erro' => 'Use GET.']);
  exit;
}

if (!$pdo || empty($db_ok)) {
  http_response_code(503);
  echo json_encode(['erro' => 'Serviço temporariamente indisponível.']);
  exit;
}

$busca = trim($_GET['q'] ?? '');
$pagina = max(1, (int) ($_GET['pagina'] ?? 1));
$limite = min(100, max(1, (int) ($_GET['limite'] ?? 20))); // teto anti-abuso
$offset = ($pagina - 1) * $limite;

$total = InscricaoRepository::contar($pdo, $busca);

$dados = [];
foreach (InscricaoRepository::buscar($pdo, $busca, $limite, $offset) as $i) {
  $dados[] = [
    'id' => (int) $i['id'],
    'nome' => $i['nome'],
    'nome_social' => $i['nome_social'],
    'tipo_pessoa' => $i['tipo_pessoa'],
    'documento' => mascarar_doc($i['cpf_cnpj']), // mascarado (LGPD)
    'email' => $i['email'],
    'celular' => $i['celular'],
    'whatsapp' => (bool) $i['tem_whatsapp'],
    'cidade' => $i['cidade'],
    'uf' => $i['uf'],
    'distancia' => $i['distancia'],
    'categoria' => $i['categoria'],
    'equipe' => $i['equipe'],
    'origem' => $i['origem'],
    'status' => $i['status_cadastro'],
    'criado_em' => $i['criado_em'],
  ];
}

echo json_encode([
  'total' => $total,
  'pagina' => $pagina,
  'limite' => $limite,
  'total_paginas' => max(1, (int) ceil($total / $limite)),
  'dados' => $dados,
], JSON_UNESCAPED_UNICODE);
