<?php
// Contexto: sanitização + validação central. Testada por testes.php. Usada por salvar.php.
// Regras: XSS (escape na saída), e-mail, datas, listas fechadas, tamanhos máximos.

// Contexto: remove tags/trim e corta no tamanho máximo (anti-overflow e XSS armazenado).
function limpar(string $v, int $max = 150): string {
  $v = trim(strip_tags($v));
  return mb_substr($v, 0, $max);
}

// Contexto: e-mail válido no formato padrão (usado no principal e no secundário).
function email_ok(string $v): bool {
  return (bool) filter_var($v, FILTER_VALIDATE_EMAIL);
}

// Contexto: data real no formato AAAA-MM-DD e nunca futura (nascimento/fundação).
function data_ok(string $v): bool {
  $d = DateTime::createFromFormat('Y-m-d', $v);
  return $d && $d->format('Y-m-d') === $v && $v <= date('Y-m-d');
}

// Contexto: confere os 2 dígitos verificadores do CPF; sequências repetidas (111...) são falsas.
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

// Contexto: confere os 2 dígitos verificadores do CNPJ (pesos 5-2 e 6-2).
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

// Contexto: aceita CPF (11) ou CNPJ (14) com dígitos válidos; qualquer letra reprova.
function cpf_cnpj_ok(string $v): bool {
  if (preg_match('/[a-zA-Z]/', $v)) return false; // letras nunca são válidas aqui
  $n = preg_replace('/\D/', '', $v);
  if (strlen($n) === 11) return cpf_digitos_ok($n);
  if (strlen($n) === 14) return cnpj_digitos_ok($n);
  return false;
}

// Contexto: fixo tem 10 dígitos e celular 11 (ambos com DDD); letras reprovam.
function celular_ok(string $v): bool {
  if ($v === '' || preg_match('/[a-zA-Z]/', $v)) return false;
  $n = preg_replace('/\D/', '', $v);
  return in_array(strlen($n), [10, 11], true); // fixo=10, celular=11 (com DDD)
}

// Contexto: exibe só início/fim do documento (LGPD) — ex: 529.***.***-25.
function mascarar_doc(string $v): string {
  $n = preg_replace('/\D/', '', $v);
  if (strlen($n) === 11) return substr($n, 0, 3) . '.***.***-' . substr($n, -2);
  if (strlen($n) === 14) return substr($n, 0, 2) . '.***.***/****-' . substr($n, -2);
  return '***';
}

// Contexto: CEP no formato 00000-000 (só números); letras reprovam.
function cep_ok(string $v): bool {
  if (preg_match('/[a-zA-Z]/', $v)) return false; // CEP só aceita números
  return (bool) preg_match('/^\d{5}-?\d{3}$/', trim($v));
}

// Contexto: UF precisa ser uma das 27 siglas oficiais (case-insensitive).
function uf_ok(string $v): bool {
  $ufs = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];
  return in_array(strtoupper(trim($v)), $ufs, true);
}

// Contexto: valor precisa pertencer à lista fechada (distância, status etc.).
function enum_ok(string $v, array $lista): bool {
  return in_array($v, $lista, true);
}

// Contexto: valida o pacote completo da inscrição; retorna erros (vazio = válido). Usada no salvar.php.
function validar_inscricao(array $d): array {
  $erros = [];
  foreach (['nome','cpf_cnpj','data_nascimento','email','celular','cep','logradouro','numero','bairro','cidade','uf','distancia','tamanho_camiseta','categoria'] as $c) {
    if (empty($d[$c])) $erros[] = "Campo obrigatório faltando: $c";
  }
  if (!empty($d['email']) && !email_ok($d['email'])) $erros[] = 'E-mail principal inválido.';
  if (!empty($d['email2']) && !email_ok($d['email2'])) $erros[] = 'E-mail secundário inválido.';
  if (!empty($d['cpf_cnpj']) && !cpf_cnpj_ok($d['cpf_cnpj'])) $erros[] = 'CPF/CNPJ inválido (dígitos verificadores).';
  if (!empty($d['celular']) && !celular_ok($d['celular'])) $erros[] = 'Celular inválido (somente números, com DDD).';
  if (!empty($d['telefone_fixo']) && !celular_ok($d['telefone_fixo'])) $erros[] = 'Telefone fixo inválido (somente números).';
  if (!empty($d['cep']) && !cep_ok($d['cep'])) $erros[] = 'CEP inválido.';
  if (!empty($d['uf']) && !uf_ok($d['uf'])) $erros[] = 'UF inválida.';
  if (!empty($d['data_nascimento']) && !data_ok($d['data_nascimento'])) $erros[] = 'Data de nascimento/fundação inválida.';
  if (!empty($d['distancia']) && !enum_ok($d['distancia'], ['5km','10km','21km','42km'])) $erros[] = 'Distância inválida.';
  if (!empty($d['status_cadastro']) && !enum_ok($d['status_cadastro'], ['Ativo','Inativo','Bloqueado'])) $erros[] = 'Status inválido.';
  if (empty($d['aceite'])) $erros[] = 'É preciso aceitar o regulamento.';
  return $erros;
}
