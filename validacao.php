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

function cpf_cnpj_ok(string $v): bool {
  $n = preg_replace('/\D/', '', $v);
  return in_array(strlen($n), [11, 14], true); // dígitos: CPF=11, CNPJ=14
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
  if (!empty($d['cpf_cnpj']) && !cpf_cnpj_ok($d['cpf_cnpj'])) $erros[] = 'CPF/CNPJ inválido (11 ou 14 dígitos).';
  if (!empty($d['cep']) && !cep_ok($d['cep'])) $erros[] = 'CEP inválido.';
  if (!empty($d['uf']) && !uf_ok($d['uf'])) $erros[] = 'UF inválida.';
  if (!empty($d['data_nascimento']) && !data_ok($d['data_nascimento'])) $erros[] = 'Data de nascimento/fundação inválida.';
  if (!empty($d['distancia']) && !enum_ok($d['distancia'], ['5km','10km','21km','42km'])) $erros[] = 'Distância inválida.';
  if (!empty($d['status_cadastro']) && !enum_ok($d['status_cadastro'], ['Ativo','Inativo','Bloqueado'])) $erros[] = 'Status inválido.';
  if (empty($d['aceite'])) $erros[] = 'É preciso aceitar o regulamento.';
  return $erros;
}
