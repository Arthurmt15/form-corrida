<?php // Parcial: seção 2 - Contato (como falar). Incluído via index.php. ?>
<h5 class="titulo-faixa mt-3">2. Informações de Contato</h5>
<div class="col-md-6">
  <label class="form-label">E-mail principal *</label>
  <input type="email" name="email" class="form-control" required maxlength="150">
</div>
<div class="col-md-6">
  <label class="form-label">E-mail secundário</label>
  <input type="email" name="email2" class="form-control" maxlength="150">
</div>
<div class="col-md-5">
  <label class="form-label">Celular (DDD) *</label>
  <input type="text" name="celular" class="form-control" required maxlength="20" placeholder="(00) 00000-0000">
</div>
<div class="col-md-3 d-flex align-items-end">
  <div class="form-check">
    <input class="form-check-input" type="checkbox" name="tem_whatsapp" value="1" id="zap" checked>
    <label class="form-check-label" for="zap">Tem WhatsApp</label>
  </div>
</div>
<div class="col-md-4">
  <label class="form-label">Telefone fixo / comercial</label>
  <input type="text" name="telefone_fixo" class="form-control" maxlength="20">
</div>
