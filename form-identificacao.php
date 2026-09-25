<?php // Parcial: seção 1 - Identificação (quem é). Incluído via index.php. ?>
<h5 class="titulo-faixa mt-2">1. Dados de Identificação</h5>
<div class="col-md-6">
  <label class="form-label">Nome completo / Razão Social *</label>
  <input type="text" name="nome" class="form-control" required maxlength="150">
</div>
<div class="col-md-6">
  <label class="form-label">Nome social / Fantasia</label>
  <input type="text" name="nome_social" class="form-control" maxlength="150">
</div>
<div class="col-md-4">
  <label class="form-label">Tipo de pessoa *</label>
  <select name="tipo_pessoa" id="tipo_pessoa" class="form-select" required>
    <option value="FISICA">Física (CPF)</option>
    <option value="JURIDICA">Jurídica (CNPJ)</option>
  </select>
</div>
<div class="col-md-4">
  <label class="form-label">CPF / CNPJ *</label>
  <input type="text" name="cpf_cnpj" class="form-control" required maxlength="18" placeholder="CPF ou CNPJ">
</div>
<div class="col-md-4">
  <label class="form-label">RG / Inscrição Estadual</label>
  <input type="text" name="rg_ie" class="form-control" maxlength="20">
</div>
<div class="col-md-6">
  <label class="form-label">Nascimento / Fundação *</label>
  <input type="date" name="data_nascimento" class="form-control" required>
</div>
<div class="col-md-6">
  <label class="form-label">Gênero</label>
  <select name="genero" class="form-select">
    <option value="">Prefiro não informar</option>
    <option>Masculino</option>
    <option>Feminino</option>
    <option>Outro</option>
  </select>
</div>
