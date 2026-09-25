<section class="card">
  <h2><span class="green">3</span> Endereço Completo</h2>
  <div class="grid address-top">
    <label>CEP *
      <input type="text" name="cep" id="cep" value="{{ old('cep') }}" required maxlength="9" placeholder="00000-000" inputmode="numeric" pattern="[0-9\-]+" title="Somente números">
    </label>
    <label>Logradouro *
      <input type="text" name="logradouro" id="logradouro" value="{{ old('logradouro') }}" required maxlength="150" placeholder="Rua, Avenida, Praça, etc.">
    </label>
  </div>
  <div class="grid three">
    <label>Número *
      <input type="text" name="numero" value="{{ old('numero') }}" required maxlength="10" placeholder="Número">
    </label>
    <label>Complemento
      <input type="text" name="complemento" value="{{ old('complemento') }}" maxlength="100" placeholder="Apto, Bloco, Sala, etc.">
    </label>
    <label>Bairro *
      <input type="text" name="bairro" id="bairro" value="{{ old('bairro') }}" required maxlength="100" placeholder="Bairro">
    </label>
  </div>
  <div class="grid address-bottom">
    <label>Cidade *
      <input type="text" name="cidade" id="cidade" value="{{ old('cidade') }}" required maxlength="100" placeholder="Cidade">
    </label>
    <label>Estado (UF) *
      <input type="text" name="uf" id="uf" value="{{ old('uf') }}" required maxlength="2" placeholder="SP">
    </label>
  </div>
</section>
