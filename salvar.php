<?php
// Contexto: recebe POST, aplica segurança (CSRF/honeypot/rate-limit) + validacao.php e insere via PDO.
require 'seguranca.php';
require 'csrf.php';
require 'conexao.php';
require 'validacao.php';

function falhar(string $msg): void {
  header('Location: index.php?erro=' . urlencode($msg));
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.php');
  exit;
}

// 1. Honeypot: se preenchido, é bot — finge sucesso sem salvar.
if (!empty($_POST['site_url'])) {
  header('Location: index.php?sucesso=1');
  exit;
}

// 2. CSRF: bloqueia envio forjado de outro site.
if (!csrf_validar($_POST['csrf_token'] ?? null)) {
  falhar('Sessão expirada. Recarregue o formulário.');
}

// 3. Rate-limit simples: 1 envio a cada 5s por sessão.
$agora = time();
if (!empty($_SESSION['ultimo_envio']) && ($agora - $_SESSION['ultimo_envio']) < 5) {
  falhar('Aguarde alguns segundos antes de reenviar.');
}
$_SESSION['ultimo_envio'] = $agora;

// 4. Sanitiza entradas (limites de tamanho = anti-overflow).
$dados = [
  'nome' => limpar($_POST['nome'] ?? ''),
  'nome_social' => limpar($_POST['nome_social'] ?? '') ?: null,
  'tipo_pessoa' => enum_ok($_POST['tipo_pessoa'] ?? '', ['FISICA','JURIDICA']) ? $_POST['tipo_pessoa'] : 'FISICA',
  'cpf_cnpj' => limpar($_POST['cpf_cnpj'] ?? '', 18),
  'rg_ie' => limpar($_POST['rg_ie'] ?? '', 20) ?: null,
  'data_nascimento' => limpar($_POST['data_nascimento'] ?? '', 10),
  'genero' => limpar($_POST['genero'] ?? '', 20) ?: null,
  'email' => limpar($_POST['email'] ?? ''),
  'email2' => limpar($_POST['email2'] ?? '') ?: null,
  'celular' => limpar($_POST['celular'] ?? '', 20),
  'tem_whatsapp' => isset($_POST['tem_whatsapp']) ? 1 : 0,
  'telefone_fixo' => limpar($_POST['telefone_fixo'] ?? '', 20) ?: null,
  'cep' => limpar($_POST['cep'] ?? '', 9),
  'logradouro' => limpar($_POST['logradouro'] ?? ''),
  'numero' => limpar($_POST['numero'] ?? '', 10),
  'complemento' => limpar($_POST['complemento'] ?? '', 100) ?: null,
  'bairro' => limpar($_POST['bairro'] ?? '', 100),
  'cidade' => limpar($_POST['cidade'] ?? '', 100),
  'uf' => strtoupper(limpar($_POST['uf'] ?? '', 2)),
  'distancia' => limpar($_POST['distancia'] ?? '', 5),
  'tamanho_camiseta' => limpar($_POST['tamanho_camiseta'] ?? '', 3),
  'categoria' => limpar($_POST['categoria'] ?? '', 50),
  'equipe' => limpar($_POST['equipe'] ?? '', 100) ?: null,
  'origem' => limpar($_POST['origem'] ?? '', 50) ?: 'Site',
  'status_cadastro' => limpar($_POST['status_cadastro'] ?? '', 10) ?: 'Ativo',
  'aceite' => isset($_POST['aceite_regulamento']) ? 1 : 0,
];

// 5. Validação central (regras em validacao.php, cobertas por testes.php).
$erros = validar_inscricao($dados);
if ($erros) falhar($erros[0]);
if (!$db_ok || !$pdo) falhar('Serviço temporariamente indisponível. Tente novamente em instantes.');

try {
  // 6. SQL com placeholders = anti SQL injection (nunca concatena valor).
  $cols = ['nome','nome_social','tipo_pessoa','cpf_cnpj','rg_ie','data_nascimento','genero','email','email2','celular','tem_whatsapp','telefone_fixo','cep','logradouro','numero','complemento','bairro','cidade','uf','distancia','tamanho_camiseta','categoria','equipe','origem','status_cadastro','aceite_regulamento'];
  $sql = 'INSERT INTO inscricoes (' . implode(',', $cols) . ') VALUES (:' . implode(',:', $cols) . ')';
  $params = [];
  foreach ($cols as $c) {
    $params[":$c"] = ($c === 'aceite_regulamento') ? $dados['aceite'] : $dados[$c];
  }
  $pdo->prepare($sql)->execute($params);
  unset($_SESSION['csrf_token']); // token de uso único
  header('Location: index.php?sucesso=1&id=' . $pdo->lastInsertId());
} catch (PDOException $e) {
  error_log('Erro salvar inscricao: ' . $e->getMessage()); // log interno, sem vazar detalhe
  falhar('Erro ao salvar. Tente novamente.');
}
