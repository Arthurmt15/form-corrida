<?php
// Contexto: roteador do php -S — devolve 404 p/ arquivos sensíveis (schema, env, código-fonte).
// Uso: php -S 127.0.0.1:8000 -t legacy legacy/router.php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

$bloqueado = preg_match('#(^|/)\.#', $uri) // dotfiles (.env, .git)
  || preg_match('#\.(sql|env|md|lock|xml|yml|yaml|dist)$#i', $uri) // schema, configs, docs
  || preg_match('#^/(config|src)(/|$)#', $uri) // código-fonte PHP interno
  || basename($uri) === 'router.php';

if ($bloqueado) {
  http_response_code(404);
  exit('Not Found');
}

return false; // serve o arquivo normalmente
