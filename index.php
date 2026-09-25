<?php
// Contexto: página principal — shell moderno (hero/cards) + 4 parciais do formulário.
require 'seguranca.php';
require 'csrf.php';
require 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscrição - Corrida</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="page">
    <header class="hero">
      <div class="hero-icon">●</div>
      <div>
        <h1>Inscrição - Corrida</h1>
        <p>Preencha os dados abaixo para realizar sua inscrição na corrida.</p>
      </div>
      <div class="runner">🏃</div>
    </header>

    <?php if (isset($_GET['sucesso'])): ?>
      <div class="notice"><strong>ⓘ</strong><span>Cadastro realizado! ID: <?= htmlspecialchars($_GET['id'] ?? '') ?></span></div>
    <?php endif; ?>
    <?php if (isset($_GET['erro'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
    <?php endif; ?>

    <form action="salvar.php" method="POST" class="needs-validation" novalidate>
      <?= csrf_input() ?>
      <!-- Honeypot anti-bot: humanos não preenchem (campo oculto). -->
      <input type="text" name="site_url" value="" style="display:none" tabindex="-1" autocomplete="off">
      <?php include 'form-identificacao.php'; ?>
      <?php include 'form-contato.php'; ?>
      <?php include 'form-endereco.php'; ?>
      <?php include 'form-corrida.php'; ?>
    </form>
  </main>
<script src="viacep.js"></script>
<script src="mascaras.js"></script>
<script>
// Validação simples: marca form como validado; required nativo bloqueia envio.
(() => {
  document.querySelectorAll('.needs-validation').forEach(f => {
    f.addEventListener('submit', e => {
      if (!f.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
      f.classList.add('was-validated');
    });
  });
})();
</script>
</body>
</html>
