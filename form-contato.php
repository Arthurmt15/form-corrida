<?php // Parcial: seção 2 - Contato (como falar). Incluído via index.php. ?>
<section class="card">
  <h2><span>2</span> Informações de Contato</h2>
  <div class="grid two">
    <label>E-mail principal *
      <input type="email" name="email" required maxlength="150" placeholder="seu@email.com">
    </label>
    <label>E-mail secundário
      <input type="email" name="email2" maxlength="150" placeholder="outro@email.com">
    </label>
  </div>
  <div class="grid contact-row">
    <label>Celular (DDD) *
      <input type="tel" name="celular" required maxlength="20" placeholder="(00) 00000-0000">
    </label>
    <label class="check-label"><input type="checkbox" name="tem_whatsapp" value="1" id="zap" checked> Tem WhatsApp</label>
    <label>Telefone fixo / comercial
      <input type="tel" name="telefone_fixo" maxlength="20" placeholder="(00) 0000-0000">
    </label>
  </div>
</section>
