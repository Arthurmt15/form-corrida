<?php
// Contexto: recebe POST do index.php, valida (obrigatórios + e-mails + aceite) e insere via PDO preparado.
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.php');
  exit;
}

function post($k, $default = '') {
  return trim($_POST[$k] ?? $default);
}

$dados = [
  'nome' => post('nome'),
  'nome_social' => post('nome_social') ?: null,
  'tipo_pessoa' => post('tipo_pessoa', 'FISICA'),
  'cpf_cnpj' => post('cpf_cnpj'),
  'rg_ie' => post('rg_ie') ?: null,
  'data_nascimento' => post('data_nascimento'),
  'genero' => post('genero') ?: null,
  'email' => post('email'),
  'email2' => post('email2') ?: null,
  'celular' => post('celular'),
  'tem_whatsapp' => isset($_POST['tem_whatsapp']) ? 1 : 0,
  'telefone_fixo' => post('telefone_fixo') ?: null,
  'cep' => post('cep'),
  'logradouro' => post('logradouro'),
  'numero' => post('numero'),
  'complemento' => post('complemento') ?: null,
  'bairro' => post('bairro'),
  'cidade' => post('cidade'),
  'uf' => strtoupper(post('uf')),
  'distancia' => post('distancia'),
  'tamanho_camiseta' => post('tamanho_camiseta'),
  'categoria' => post('categoria'),
  'equipe' => post('equipe') ?: null,
  'origem' => post('origem', 'Site'),
  'status_cadastro' => post('status_cadastro', 'Ativo'),
  'aceite' => isset($_POST['aceite_regulamento']) ? 1 : 0,
];

$obrigatorios = ['nome','cpf_cnpj','data_nascimento','email','celular','cep','logradouro','numero','bairro','cidade','uf','distancia','tamanho_camiseta','categoria'];
foreach ($obrigatorios as $c) {
  if (empty($dados[$c])) {
    header('Location: index.php?erro=' . urlencode("Campo obrigatório faltando: $c"));
    exit;
  }
}

if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
  header('Location: index.php?erro=' . urlencode('E-mail principal inválido.'));
  exit;
}
if ($dados['email2'] && !filter_var($dados['email2'], FILTER_VALIDATE_EMAIL)) {
  header('Location: index.php?erro=' . urlencode('E-mail secundário inválido.'));
  exit;
}
if (!$dados['aceite']) {
  header('Location: index.php?erro=' . urlencode('É preciso aceitar o regulamento.'));
  exit;
}

try {
  $cols = ['nome','nome_social','tipo_pessoa','cpf_cnpj','rg_ie','data_nascimento','genero','email','email2','celular','tem_whatsapp','telefone_fixo','cep','logradouro','numero','complemento','bairro','cidade','uf','distancia','tamanho_camiseta','categoria','equipe','origem','status_cadastro','aceite_regulamento'];
  $sql = 'INSERT INTO inscricoes (' . implode(',', $cols) . ') VALUES (:' . implode(',:', $cols) . ')';
  $params = [];
  foreach ($cols as $c) {
    $params[":$c"] = ($c === 'aceite_regulamento') ? $dados['aceite'] : $dados[$c];
  }
  $pdo->prepare($sql)->execute($params);
  $id = $pdo->lastInsertId();
  header("Location: index.php?sucesso=1&id=$id");
} catch (PDOException $e) {
  header('Location: index.php?erro=' . urlencode('Erro ao salvar: ' . $e->getMessage()));
}
