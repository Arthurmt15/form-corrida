<?php
// Contexto: sanitização + validação central. Testada por testes.php. Usada por salvar.php.
// Regras: XSS (escape na saída), e-mail, datas, listas fechadas, tamanhos máximos.

function limpar(string $v, int $max = 150): string {
  $v = trim(strip_tags($v));
  return mb_substr($v, 0, $max);
}

function email_ok(string $v): bool {
  return (bool) filter_var($v, FILTER_VALIDATE_EMAIL);
}

function data_ok(string $v): bool {
  $d = DateTime::createFromFormat('Y-m-d', $v);
  return $d && $d->format('Y-m-d') === $v && $v <= date('Y-m-d');
}

function cpf_digitos_ok(string $n): bool {
  if (strlen($n) !== 11 || preg_match('/^(\d)\1{10}$/', $n)) return false;
  for ($t = 9; $t < 11; $t++) {
    $s = 0;
    for ($i = 0; $i < $t; $i++) $s += (int) $n[$i] * (($t + 1) - $i);
    $d = ((10 * $s) % 11) % 10;
    if ((int) $n[$t] !== $d) return false;
  }
  return true;
}

function cnpj_digitos_ok(string $n): bool {
  if (strlen($n) !== 14 || preg_match('/^(\d)\1{13}$/', $n)) return false;
  $p1 = [5,4,3,2,9,8,7,6,5,4,3,2];
  $p2 = [6,5,4,3,2,9,8,7,6,5,4,3,2];
  foreach ([$p1, $p2] as $k => $pesos) {
    $s = 0;
    for ($i = 0; $i < 12 + $k; $i++) $s += (int) $n[$i] * $pesos[$i];
    $d = $s % 11 < 2 ? 0 : 11 - ($s % 11);
    if ((int) $n[12 + $k] !== $d) return false;
  }
  return true;
}

function cpf_cnpj_ok(string $v): bool {
  $n = preg_replace('/\D/', '', $v);
  if (strlen($n) === 11) return cpf_digitos_ok($n);
  if (strlen($n) === 14) return cnpj_digitos_ok($n);
  return false;
}

// LGPD: mascara documento na exibição (mostra só início/fim).
function mascarar_doc(string $v): string {
  $n = preg_replace('/\D/', '', $v);
  if (strlen($n) === 11) return substr($n, 0, 3) . '.***.***-' . substr($n, -2);
  if (strlen($n) === 14) return substr($n, 0, 2) . '.***.***/****-' . substr($n, -2);
  return '***';
}

function cep_ok(string $v): bool {
  return (bool) preg_match('/^\d{5}-?\d{3}$/', trim($v));
}

function uf_ok(string $v): bool {
  $ufs = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];
  return in_array(strtoupper(trim($v)), $ufs, true);
}

function enum_ok(string $v, array $lista): bool {
  return in_array($v, $lista, true);
}

// Valida o pacote da inscrição; retorna lista de erros (vazia = válido).
function validar_inscricao(array $d): array {
  $erros = [];
  foreach (['nome','cpf_cnpj','data_nascimento','email','celular','cep','logradouro','numero','bairro','cidade','uf','distancia','tamanho_camiseta','categoria'] as $c) {
    if (empty($d[$c])) $erros[] = "Campo obrigatório faltando: $c";
  }
  if (!empty($d['email']) && !email_ok($d['email'])) $erros[] = 'E-mail principal inválido.';
  if (!empty($d['email2']) && !email_ok($d['email2'])) $erros[] = 'E-mail secundário inválido.';
  if (!empty($d['cpf_cnpj']) && !cpf_cnpj_ok($d['cpf_cnpj'])) $erros[] = 'CPF/CNPJ inválido (dígitos verificadores).';
  if (!empty($d['cep']) && !cep_ok($d['cep'])) $erros[] = 'CEP inválido.';
  if (!empty($d['uf']) && !uf_ok($d['uf'])) $erros[] = 'UF inválida.';
  if (!empty($d['data_nascimento']) && !data_ok($d['data_nascimento'])) $erros[] = 'Data de nascimento/fundação inválida.';
  if (!empty($d['distancia']) && !enum_ok($d['distancia'], ['5km','10km','21km','42km'])) $erros[] = 'Distância inválida.';
  if (!empty($d['status_cadastro']) && !enum_ok($d['status_cadastro'], ['Ativo','Inativo','Bloqueado'])) $erros[] = 'Status inválido.';
  if (empty($d['aceite'])) $erros[] = 'É preciso aceitar o regulamento.';
  return $erros;
}
