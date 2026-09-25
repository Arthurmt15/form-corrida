<?php
// Contexto: recebe POST, aplica segurança + validação e delega ao InscricaoRepository.
require 'src/Seguranca.php';
require 'src/Csrf.php';
require 'config/database.php';
require 'src/Validacao.php';
require 'src/InscricaoRepository.php';

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

// 5. Validação central (regras em src/Validacao.php, cobertas por testes.php).
$erros = validar_inscricao($dados);
if ($erros) falhar($erros[0]);
if (!$db_ok || !$pdo) falhar('Serviço temporariamente indisponível. Tente novamente em instantes.');

// 6. Separa pessoa x inscrição; repositório grava em transação.
$pessoa = array_intersect_key($dados, array_flip(['nome','nome_social','tipo_pessoa','cpf_cnpj','rg_ie','data_nascimento','genero','email','email2','celular','tem_whatsapp','telefone_fixo','cep','logradouro','numero','complemento','bairro','cidade','uf']));
$insc = [
  'distancia' => $dados['distancia'],
  'tamanho_camiseta' => $dados['tamanho_camiseta'],
  'categoria' => $dados['categoria'],
  'equipe' => $dados['equipe'],
  'origem' => $dados['origem'],
  'status_cadastro' => $dados['status_cadastro'],
  'aceite_regulamento' => $dados['aceite'],
];

try {
  $id = InscricaoRepository::salvar($pdo, $pessoa, $insc);
  unset($_SESSION['csrf_token']); // token de uso único
  header("Location: index.php?sucesso=1&id=$id");
} catch (RuntimeException $e) {
  if ($e->getMessage() === 'DUPLICADO') falhar('Este CPF/CNPJ já está inscrito nesta distância.');
  falhar('Erro ao salvar. Tente novamente.');
} catch (PDOException $e) {
  error_log('Erro salvar inscricao: ' . $e->getMessage()); // log interno, sem vazar detalhe
  falhar('Erro ao salvar. Tente novamente.');
}
