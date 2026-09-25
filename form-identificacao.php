<?php // Parcial: seção 1 - Identificação (quem é). Incluído via index.php. ?>
<section class="card">
  <h2><span>1</span> Dados de Identificação</h2>
  <div class="grid two">
    <label>Nome completo / Razão Social *
      <input type="text" name="nome" required maxlength="150" placeholder="Digite seu nome completo ou razão social">
    </label>
    <label>Nome social / Fantasia
      <input type="text" name="nome_social" maxlength="150" placeholder="Digite o nome social / fantasia">
    </label>
  </div>
  <div class="grid three">
    <label>Tipo de pessoa *
      <select name="tipo_pessoa" id="tipo_pessoa" required>
        <option value="FISICA">Física (CPF)</option>
        <option value="JURIDICA">Jurídica (CNPJ)</option>
      </select>
    </label>
    <label>CPF / CNPJ *
      <input type="text" name="cpf_cnpj" required maxlength="18" placeholder="000.000.000-00">
    </label>
    <label>RG / Inscrição Estadual
      <input type="text" name="rg_ie" maxlength="20" placeholder="Digite o RG ou inscrição estadual">
    </label>
  </div>
  <div class="grid two">
    <label>Nascimento / Fundação *
      <input type="date" name="data_nascimento" required>
    </label>
    <label>Gênero
      <select name="genero">
        <option value="">Prefiro não informar</option>
        <option>Masculino</option>
        <option>Feminino</option>
        <option>Outro</option>
      </select>
    </label>
  </div>
</section>
