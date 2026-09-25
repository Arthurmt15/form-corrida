<?php
// Contexto: front do endpoint api.php — busca e lista cadastrados via fetch (JSON).
require 'seguranca.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consultar Inscritos - Corrida</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="page">
    <header class="hero">
      <div class="hero-icon">●</div>
      <div>
        <h1>Consultar Inscritos</h1>
        <p>Busca em tempo real via endpoint <b>api.php</b>.</p>
      </div>
      <div class="runner">🔍</div>
    </header>
    <div class="notice"><strong>ⓘ</strong>
      <form id="busca-form" class="search-row" style="flex:1">
        <input type="search" id="q" placeholder="Buscar nome, e-mail, cidade" autocomplete="off">
        <select id="limite" style="max-width:110px">
          <option value="10">10/pág</option>
          <option value="20" selected>20/pág</option>
          <option value="50">50/pág</option>
        </select>
        <button class="primary">Buscar</button>
        <a href="index.php" class="outline" style="display:inline-flex;align-items:center;padding:0 16px;text-decoration:none;font-weight:700;font-size:12px;background:#fff;color:#1559d8;border:1px solid #1559d8;border-radius:5px;height:38px">Nova Inscrição</a>
      </form>
    </div>
    <section class="card">
      <p id="status" style="font-size:13px;color:#7a8eaf">Digite para buscar…</p>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>ID</th><th>Nome</th><th>Documento</th><th>Contato</th><th>Cidade/UF</th><th>Distância</th><th>Status</th>
            </tr>
          </thead>
          <tbody id="resultados">
            <tr><td colspan="7" style="text-align:center">Nenhuma busca ainda.</td></tr>
          </tbody>
        </table>
      </div>
      <ul class="pagination" id="paginacao"></ul>
    </section>
  </main>
<script src="consulta.js"></script>
</body>
</html>
