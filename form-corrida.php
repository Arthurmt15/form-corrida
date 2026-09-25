<?php // Parcial: seção 4 - Corrida + controle interno (origem/status). ?>
<h5 class="titulo-faixa mt-3">4. Dados da Corrida</h5>
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
    <option>PP</option><option>P</option>
    <option selected>M</option><option>G</option><option>GG</option>
  </select>
</div>
<div class="col-md-4">
  <label class="form-label">Categoria *</label>
  <select name="categoria" class="form-select" required>
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
<div class="col-md-6">
  <label class="form-label">Origem do cadastro *</label>
  <select name="origem" class="form-select" required>
    <option value="Site">Site</option>
    <option value="Loja física">Loja física</option>
    <option value="Indicação">Indicação</option>
    <option value="Outro">Outro</option>
  </select>
</div>
<div class="col-md-6">
  <label class="form-label">Status *</label>
  <select name="status_cadastro" class="form-select" required>
    <option value="Ativo" selected>Ativo</option>
    <option value="Inativo">Inativo</option>
    <option value="Bloqueado">Bloqueado</option>
  </select>
</div>
<div class="col-12">
  <div class="form-check">
    <input class="form-check-input" type="checkbox" name="aceite_regulamento" value="1" id="aceite" required>
    <label class="form-check-label" for="aceite">Li e aceito o regulamento da prova *</label>
  </div>
</div>
