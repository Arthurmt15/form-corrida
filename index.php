<?php
// Contexto: página principal — monta o formulário por includes (4 parciais) + validações Bootstrap.
require 'seguranca.php';
require 'csrf.php';
require 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inscrição - Corrida</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>
<body class="bg-white">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card shadow card-tema">
        <div class="card-header card-header-tema">
          <h4 class="mb-0 titulo-faixa">🏃 Inscrição - Corrida</h4>
        </div>
        <div class="card-body">
          <?php if (isset($_GET['sucesso'])): ?>
            <div class="alert alert-success">Cadastro realizado! ID: <?= htmlspecialchars($_GET['id'] ?? '') ?></div>
          <?php endif; ?>
          <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
          <?php endif; ?>
          <?php if (empty($db_ok)): ?>
            <div class="alert alert-warning">Sem conexão MySQL — formulário em modo visual. Inicie o MySQL e importe o banco.sql para salvar.</div>
          <?php endif; ?>
          <form action="salvar.php" method="POST" class="row g-3 needs-validation" novalidate>
            <?= csrf_input() ?>
            <!-- Honeypot anti-bot: humanos não preenchem (campo oculto). -->
            <input type="text" name="site_url" value="" style="display:none" tabindex="-1" autocomplete="off">
            <?php include 'form-identificacao.php'; ?>
            <?php include 'form-contato.php'; ?>
            <?php include 'form-endereco.php'; ?>
            <?php include 'form-corrida.php'; ?>
            <div class="col-12 d-grid d-md-flex gap-2 justify-content-md-end mt-4">
              <button type="reset" class="btn btn-preto">Limpar</button>
              <button type="submit" class="btn btn-azul">Confirmar Inscrição</button>
              <a href="listar.php" class="btn btn-amarelo">Ver Inscritos</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="viacep.js"></script>
<script src="mascaras.js"></script>
<script>
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
