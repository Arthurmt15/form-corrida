<?php
// Contexto: token CSRF por sessão. Protege contra envio forjado de outro site.
// Uso: csrf_input() no form; csrf_validar($_POST['csrf_token'] ?? '') no salvar.php.

function csrf_token(): string {
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

function csrf_input(): string {
  $t = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
  return "<input type=\"hidden\" name=\"csrf_token\" value=\"$t\">";
}

function csrf_validar(?string $recebido): bool {
  $esperado = $_SESSION['csrf_token'] ?? '';
  if (!$esperado || !$recebido) return false;
  return hash_equals($esperado, $recebido);
}
