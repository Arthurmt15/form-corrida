<?php require 'conexao.php';
$inscricoes = $pdo->query("SELECT * FROM inscricoes ORDER BY criado_em DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inscritos - Corrida</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>🏁 Inscritos (<?= count($inscricoes) ?>)</h3>
    <a href="index.php" class="btn btn-primary">Nova Inscrição</a>
  </div>
  <div class="card shadow">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead class="table-dark">
          <tr>
            <th>#</th><th>Nome</th><th>E-mail</th><th>Distância</th><th>Categoria</th><th>Camiseta</th><th>Data</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($inscricoes as $i): ?>
          <tr>
            <td><?= $i['id'] ?></td>
            <td><?= htmlspecialchars($i['nome']) ?></td>
            <td><?= htmlspecialchars($i['email']) ?></td>
            <td><span class="badge bg-info text-dark"><?= $i['distancia'] ?></span></td>
            <td><?= htmlspecialchars($i['categoria']) ?></td>
            <td><?= $i['tamanho_camiseta'] ?></td>
            <td><?= date('d/m/Y H:i', strtotime($i['criado_em'])) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php if (!count($inscricoes)): ?>
            <tr><td colspan="7" class="text-center py-4">Nenhuma inscrição ainda.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
