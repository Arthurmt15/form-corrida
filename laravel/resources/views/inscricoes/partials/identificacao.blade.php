<section class="card">
  <h2><span>1</span> Dados de Identificação</h2>
  <div class="grid two">
    <label>Nome completo / Razão Social *
      <input type="text" name="nome" value="{{ old('nome') }}" required maxlength="150" placeholder="Digite seu nome completo ou razão social">
    </label>
    <label>Nome social / Fantasia
      <input type="text" name="nome_social" value="{{ old('nome_social') }}" maxlength="150" placeholder="Digite o nome social / fantasia">
    </label>
  </div>
  <div class="grid three">
    <label>Tipo de pessoa *
      <select name="tipo_pessoa" required>
        <option value="FISICA" @selected(old('tipo_pessoa') !== 'JURIDICA')>Física (CPF)</option>
        <option value="JURIDICA" @selected(old('tipo_pessoa') === 'JURIDICA')>Jurídica (CNPJ)</option>
      </select>
    </label>
    <label>CPF / CNPJ *
      <input type="text" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}" required maxlength="18" placeholder="000.000.000-00" inputmode="numeric" pattern="[0-9.\-/ ]+" title="Somente números">
    </label>
    <label>RG / Inscrição Estadual
      <input type="text" name="rg_ie" value="{{ old('rg_ie') }}" maxlength="20" placeholder="Digite o RG ou inscrição estadual">
    </label>
  </div>
  <div class="grid two">
    <label>Nascimento / Fundação *
      <input type="date" name="data_nascimento" value="{{ old('data_nascimento') }}" required>
    </label>
    <label>Gênero
      <select name="genero">
        <option value="">Prefiro não informar</option>
        @foreach (['Masculino', 'Feminino', 'Outro'] as $g)
          <option @selected(old('genero') === $g)>{{ $g }}</option>
        @endforeach
      </select>
    </label>
  </div>
</section>
