<?php
// Contexto: lista com busca + paginação + headers de segurança. LGPD: CPF mascarado.
require 'seguranca.php';
require 'conexao.php';
require 'validacao.php';

$busca = trim($_GET['q'] ?? '');
$pagina = max(1, (int) ($_GET['pagina'] ?? 1));
$por_pagina = 20;
$offset = ($pagina - 1) * $por_pagina;
$inscricoes = [];
$total = 0;

if ($pdo && $db_ok) {
  $where = '';
  $params = [];
  if ($busca !== '') {
    $where = 'WHERE nome LIKE :q OR email LIKE :q OR cidade LIKE :q';
    $params[':q'] = "%$busca%";
  }
  // COUNT filtrado pela busca (legível e separado da listagem).
  $stmt = $pdo->prepare("SELECT COUNT(*) AS t FROM inscricoes $where");
  $stmt->execute($params);
  $total = (int) $stmt->fetch()['t'];
  $stmt = $pdo->prepare(
    "SELECT id, nome, cpf_cnpj, email, celular, cidade, uf, distancia, status_cadastro, criado_em
     FROM inscricoes $where ORDER BY criado_em DESC LIMIT $por_pagina OFFSET $offset"
  );
  $stmt->execute($params);
  $inscricoes = $stmt->fetchAll();
}
$total_paginas = max(1, (int) ceil($total / $por_pagina));
$q_esc = htmlspecialchars($busca, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscritos - Corrida</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="page">
    <header class="hero">
      <div class="hero-icon">●</div>
      <div>
        <h1>🏁 Inscritos (<?= $total ?>)</h1>
        <p>Lista de inscrições com busca e paginação.</p>
      </div>
      <div class="runner">🏃</div>
    </header>
    <div class="notice"><strong>ⓘ</strong>
      <form method="GET" class="search-row" style="flex:1">
        <input type="search" name="q" placeholder="Buscar nome, e-mail, cidade" value="<?= $q_esc ?>">
        <button class="primary">Buscar</button>
        <a href="consulta.php" class="outline" style="display:inline-flex;align-items:center;padding:0 16px;text-decoration:none;font-weight:700;font-size:12px;background:#fff;color:#1559d8;border:1px solid #1559d8;border-radius:5px;height:38px">Via API</a>
        <a href="index.php" class="outline" style="display:inline-flex;align-items:center;padding:0 16px;text-decoration:none;font-weight:700;font-size:12px;background:#fff;color:#1559d8;border:1px solid #1559d8;border-radius:5px;height:38px">Nova Inscrição</a>
      </form>
    </div>
    <section class="card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>ID</th><th>Nome</th><th>CPF/CNPJ</th><th>Contato</th><th>Cidade/UF</th><th>Distância</th><th>Status</th><th>Criado em</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($inscricoes as $i): ?>
            <tr>
              <td><?= $i['id'] ?></td>
              <td><?= htmlspecialchars($i['nome']) ?></td>
              <td><?= mascarar_doc($i['cpf_cnpj']) ?></td>
              <td><?= htmlspecialchars($i['email']) ?><br><small><?= htmlspecialchars($i['celular']) ?></small></td>
              <td><?= htmlspecialchars($i['cidade']) ?>/<?= htmlspecialchars($i['uf']) ?></td>
              <td><span class="badge-distancia"><?= $i['distancia'] ?></span></td>
              <td><?= htmlspecialchars($i['status_cadastro']) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($i['criado_em'])) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (!count($inscricoes)): ?>
              <tr><td colspan="8" style="text-align:center">Nenhum cadastro encontrado.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <?php if ($total_paginas > 1): ?>
        <ul class="pagination">
          <?php for ($p = 1; $p <= $total_paginas; $p++): ?>
            <li class="<?= $p === $pagina ? 'active' : '' ?>">
              <a href="?q=<?= urlencode($busca) ?>&pagina=<?= $p ?>"><?= $p ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
