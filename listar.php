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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inscritos - Corrida</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>
<body class="bg-white">
<div class="container py-5">
  <div class="card-tema card shadow p-3 mb-3">
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
      <h3 class="mb-0 titulo-faixa">🏁 Inscritos (<?= $total ?>)</h3>
      <form method="GET" class="d-flex gap-2">
        <input type="search" name="q" class="form-control" placeholder="Buscar nome, e-mail, cidade" value="<?= $q_esc ?>">
        <button class="btn btn-azul">Buscar</button>
        <a href="index.php" class="btn btn-amarelo">Nova Inscrição</a>
      </form>
    </div>
  </div>
  <div class="card shadow card-tema">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead class="thead-tema">
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
            <td><span class="badge badge-distancia"><?= $i['distancia'] ?></span></td>
            <td><?= htmlspecialchars($i['status_cadastro']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($i['criado_em'])) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php if (!count($inscricoes)): ?>
            <tr><td colspan="8" class="text-center py-4">Nenhum cadastro encontrado.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php if ($total_paginas > 1): ?>
  <nav class="mt-3">
    <ul class="pagination">
      <?php for ($p = 1; $p <= $total_paginas; $p++): ?>
        <li class="page-item <?= $p === $pagina ? 'active' : '' ?>">
          <a class="page-link" href="?q=<?= urlencode($busca) ?>&pagina=<?= $p ?>"><?= $p ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
  <?php endif; ?>
</div>
</body>
</html>
