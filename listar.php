<?php
// Contexto: lista resumida de inscritos (id, nome, doc, contato, cidade/UF, distância, status).
require 'conexao.php';
// Sem banco: exibe aviso em vez de quebrar; com banco: busca resumo.
$inscricoes = ($pdo && $db_ok)
  ? $pdo->query("SELECT id, nome, cpf_cnpj, email, celular, cidade, uf, distancia, status_cadastro, criado_em FROM inscricoes ORDER BY criado_em DESC")->fetchAll()
  : [];
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
    <div class="d-flex justify-content-between align-items-center">
      <h3 class="mb-0 titulo-faixa">🏁 Inscritos (<?= count($inscricoes) ?>)</h3>
      <a href="index.php" class="btn btn-amarelo">Nova Inscrição</a>
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
            <td><?= htmlspecialchars($i['cpf_cnpj']) ?></td>
            <td><?= htmlspecialchars($i['email']) ?><br><small><?= htmlspecialchars($i['celular']) ?></small></td>
            <td><?= htmlspecialchars($i['cidade']) ?>/<?= htmlspecialchars($i['uf']) ?></td>
            <td><span class="badge badge-distancia"><?= $i['distancia'] ?></span></td>
            <td><?= htmlspecialchars($i['status_cadastro']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($i['criado_em'])) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php if (!count($inscricoes)): ?>
            <tr><td colspan="8" class="text-center py-4">Nenhum cadastro ainda.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
