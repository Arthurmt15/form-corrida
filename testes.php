<?php
// Contexto: testes sem framework. Roda via CLI (php testes.php) ou navegador (testes.php).
// Cobre validacao.php: e-mail, CPF/CNPJ, CEP, UF, data, enums e pacote completo.
require __DIR__ . '/validacao.php';

$pass = 0; $fail = 0;
function check(string $nome, bool $cond): void {
  global $pass, $fail;
  if ($cond) { $pass++; echo "PASS: $nome\n"; }
  else { $fail++; echo "FAIL: $nome\n"; }
}

check('email válido', email_ok('a@b.com'));
check('email inválido', !email_ok('a@b'));
check('cpf 11 dígitos', cpf_cnpj_ok('123.456.789-09'));
check('cnpj 14 dígitos', cpf_cnpj_ok('12.345.678/0001-90'));
check('cpf curto rejeitado', !cpf_cnpj_ok('123'));
check('cep com hífen', cep_ok('01310-100'));
check('cep 8 dígitos', cep_ok('01310100'));
check('cep inválido', !cep_ok('123'));
check('uf válida', uf_ok('sp'));
check('uf inválida', !uf_ok('XX'));
check('data válida', data_ok('2000-01-01'));
check('data futura rejeitada', !data_ok('2999-01-01'));
check('distância válida', enum_ok('10km', ['5km','10km','21km','42km']));
check('distância inválida', !enum_ok('99km', ['5km','10km','21km','42km']));
check('limpar remove tags', limpar('<b>oi</b>') === 'oi');

// Pacote completo válido não gera erros.
$ok = [
  'nome' => 'Ana', 'cpf_cnpj' => '12345678909', 'data_nascimento' => '2000-01-01',
  'email' => 'a@b.com', 'celular' => '11999999999', 'cep' => '01310-100',
  'logradouro' => 'Rua X', 'numero' => '10', 'bairro' => 'B', 'cidade' => 'SP',
  'uf' => 'SP', 'distancia' => '5km', 'tamanho_camiseta' => 'M',
  'categoria' => 'Geral', 'status_cadastro' => 'Ativo', 'aceite' => 1,
];
check('pacote válido sem erros', validar_inscricao($ok) === []);
check('pacote vazio gera erros', count(validar_inscricao([])) > 0);

echo "\nResultado: $pass passed, $fail failed\n";
exit($fail > 0 ? 1 : 0);
