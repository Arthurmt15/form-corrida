<?php // Parcial: seção 4 - Corrida + controle interno (origem/status). ?>
<section class="card">
  <h2><span>4</span> Dados da Corrida</h2>
  <div class="grid three">
    <label>Distância *
      <select name="distancia" required>
        <option value="">Selecione...</option>
        <option value="5km">5 km</option>
        <option value="10km">10 km</option>
        <option value="21km">Meia Maratona (21 km)</option>
        <option value="42km">Maratona (42 km)</option>
      </select>
    </label>
    <label>Camiseta *
      <select name="tamanho_camiseta" required>
        <option>PP</option><option>P</option>
        <option selected>M</option><option>G</option><option>GG</option>
      </select>
    </label>
    <label>Categoria *
      <select name="categoria" required>
        <option value="Geral">Geral</option>
        <option value="18-29">18 a 29 anos</option>
        <option value="30-39">30 a 39 anos</option>
        <option value="40-49">40 a 49 anos</option>
        <option value="50+">50 anos ou mais</option>
        <option value="PCD">PCD</option>
      </select>
    </label>
  </div>
  <div class="grid two">
    <label>Equipe (opcional)
      <input type="text" name="equipe" maxlength="100" placeholder="Nome da equipe">
    </label>
    <label>Origem do cadastro *
      <select name="origem" required>
        <option value="Site">Site</option>
        <option value="Loja física">Loja física</option>
        <option value="Indicação">Indicação</option>
        <option value="Outro">Outro</option>
      </select>
    </label>
  </div>
  <div class="grid status">
    <label>Status *
      <select name="status_cadastro" required>
        <option value="Ativo" selected>Ativo</option>
        <option value="Inativo">Inativo</option>
        <option value="Bloqueado">Bloqueado</option>
      </select>
    </label>
  </div>
  <label class="accept"><input type="checkbox" name="aceite_regulamento" value="1" id="aceite" required> Li e aceito o regulamento da prova *</label>

  <div class="actions">
    <button type="reset" class="secondary">↻ &nbsp; Limpar</button>
    <button type="submit" class="primary">✓ &nbsp; Confirmar Inscrição</button>
    <a href="listar.php" class="outline">◉ &nbsp; Ver Inscritos</a>
  </div>
</section>
