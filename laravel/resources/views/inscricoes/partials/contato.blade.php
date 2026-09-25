<section class="card">
  <h2><span>2</span> Informações de Contato</h2>
  <div class="grid two">
    <label>E-mail principal *
      <input type="email" name="email" value="{{ old('email') }}" required maxlength="150" placeholder="seu@email.com">
    </label>
    <label>E-mail secundário
      <input type="email" name="email2" value="{{ old('email2') }}" maxlength="150" placeholder="outro@email.com">
    </label>
  </div>
  <div class="grid contact-row">
    <label>Celular (DDD) *
      <input type="tel" name="celular" value="{{ old('celular') }}" required maxlength="20" placeholder="(00) 00000-0000" inputmode="numeric" pattern="[0-9()\- ]+" title="Somente números">
    </label>
    <label class="check-label"><input type="checkbox" name="tem_whatsapp" value="1" @checked(old('tem_whatsapp', true))> Tem WhatsApp</label>
    <label>Telefone fixo / comercial
      <input type="tel" name="telefone_fixo" value="{{ old('telefone_fixo') }}" maxlength="20" placeholder="(00) 0000-0000" inputmode="numeric" pattern="[0-9()\- ]+" title="Somente números">
    </label>
  </div>
</section>
