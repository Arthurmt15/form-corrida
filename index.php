<?php require 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inscrição - Corrida</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      <div class="card shadow">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0">🏃 Formulário de Inscrição - Corrida</h4>
        </div>
        <div class="card-body">

          <?php if (isset($_GET['sucesso'])): ?>
            <div class="alert alert-success">Inscrição realizada com sucesso!</div>
          <?php endif; ?>
          <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
          <?php endif; ?>

          <form action="salvar.php" method="POST" class="row g-3 needs-validation" novalidate>

            <div class="col-md-6">
              <label class="form-label">Nome completo *</label>
              <input type="text" name="nome" class="form-control" required maxlength="100">
              <div class="invalid-feedback">Informe seu nome.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">E-mail *</label>
              <input type="email" name="email" class="form-control" required maxlength="150">
              <div class="invalid-feedback">Informe um e-mail válido.</div>
            </div>

            <div class="col-md-4">
              <label class="form-label">CPF *</label>
              <input type="text" name="cpf" class="form-control" required placeholder="000.000.000-00" maxlength="14">
            </div>

            <div class="col-md-4">
              <label class="form-label">Data de Nascimento *</label>
              <input type="date" name="data_nascimento" class="form-control" required>
            </div>

            <div class="col-md-4">
              <label class="form-label">Telefone</label>
              <input type="text" name="telefone" class="form-control" placeholder="(00) 00000-0000" maxlength="20">
            </div>

            <div class="col-md-4">
              <label class="form-label">Sexo *</label>
              <select name="sexo" class="form-select" required>
                <option value="">Selecione...</option>
                <option>Masculino</option>
                <option>Feminino</option>
                <option>Outro</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">Distância *</label>
              <select name="distancia" class="form-select" required>
                <option value="">Selecione...</option>
                <option value="5km">5 km</option>
                <option value="10km">10 km</option>
                <option value="21km">Meia Maratona (21 km)</option>
                <option value="42km">Maratona (42 km)</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">Camiseta *</label>
              <select name="tamanho_camiseta" class="form-select" required>
                <option value="">Selecione...</option>
                <option>PP</option>
                <option>P</option>
                <option>M</option>
                <option>G</option>
                <option>GG</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Categoria *</label>
              <select name="categoria" class="form-select" required>
                <option value="">Selecione...</option>
                <option value="Geral">Geral</option>
                <option value="18-29">18 a 29 anos</option>
                <option value="30-39">30 a 39 anos</option>
                <option value="40-49">40 a 49 anos</option>
                <option value="50+">50 anos ou mais</option>
                <option value="PCD">PCD</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Equipe (opcional)</label>
              <input type="text" name="equipe" class="form-control" maxlength="100">
            </div>

            <div class="col-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="aceite_regulamento" value="1" id="aceite" required>
                <label class="form-check-label" for="aceite">Li e aceito o regulamento da prova *</label>
              </div>
            </div>

            <div class="col-12 d-grid d-md-flex gap-2 justify-content-md-end">
              <button type="reset" class="btn btn-secondary">Limpar</button>
              <button type="submit" class="btn btn-primary">Confirmar Inscrição</button>
              <a href="listar.php" class="btn btn-outline-success">Ver Inscritos</a>
            </div>

          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Validação Bootstrap
(() => {
  const forms = document.querySelectorAll('.needs-validation');
  forms.forEach(form => {
    form.addEventListener('submit', e => {
      if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
      form.classList.add('was-validated');
    });
  });
})();
</script>
</body>
</html>
