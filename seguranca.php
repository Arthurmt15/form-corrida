<?php
// Contexto: headers anti-ataque + sessão com cookies seguros. Incluir no topo das páginas.
// Protege contra: clickjacking, sniffing, XSS básico e roubo de sessão.

// Anti-clickjacking: impede a página dentro de iframe.
header('X-Frame-Options: DENY');
// Bloqueia MIME-sniffing.
header('X-Content-Type-Options: nosniff');
// XSS auditor + política de referrer mínima.
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: no-referrer');
// CSP enxuta: só self + Bootstrap CDN + ViaCEP.
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net; connect-src 'self' https://viacep.com.br; img-src 'self' data:; object-src 'none'; frame-ancestors 'none'");

if (session_status() === PHP_SESSION_NONE) {
  // Cookie de sessão: HttpOnly + SameSite=Lax (+ Secure quando HTTPS).
  $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
  session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax',
  ]);
  session_start();
}
