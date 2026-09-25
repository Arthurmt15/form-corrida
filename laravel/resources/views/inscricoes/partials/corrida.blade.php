<section class="card">
  <h2><span>4</span> Dados da Corrida</h2>
  <div class="grid three">
    <label>Distância *
      <select name="distancia" required>
        <option value="">Selecione...</option>
        @foreach (['5km' => '5 km', '10km' => '10 km', '21km' => 'Meia Maratona (21 km)', '42km' => 'Maratona (42 km)'] as $v => $t)
          <option value="{{ $v }}" @selected(old('distancia') === $v)>{{ $t }}</option>
        @endforeach
      </select>
    </label>
    <label>Camiseta *
      <select name="tamanho_camiseta" required>
        @foreach (['PP', 'P', 'M', 'G', 'GG'] as $t)
          <option @selected(old('tamanho_camiseta', 'M') === $t)>{{ $t }}</option>
        @endforeach
      </select>
    </label>
    <label>Categoria *
      <select name="categoria" required>
        @foreach (['Geral', '18-29', '30-39', '40-49', '50+', 'PCD'] as $c)
          <option value="{{ $c }}" @selected(old('categoria') === $c)>{{ $c }}</option>
        @endforeach
      </select>
    </label>
  </div>
  <div class="grid two">
    <label>Equipe (opcional)
      <input type="text" name="equipe" value="{{ old('equipe') }}" maxlength="100" placeholder="Nome da equipe">
    </label>
    <label>Origem do cadastro *
      <select name="origem" required>
        @foreach (['Site', 'Loja física', 'Indicação', 'Outro'] as $o)
          <option @selected(old('origem') === $o)>{{ $o }}</option>
        @endforeach
      </select>
    </label>
  </div>
  <div class="grid status">
    <label>Status *
      <select name="status_cadastro" required>
        @foreach (['Ativo', 'Inativo', 'Bloqueado'] as $s)
          <option @selected(old('status_cadastro', 'Ativo') === $s)>{{ $s }}</option>
        @endforeach
      </select>
    </label>
  </div>
  <label class="accept"><input type="checkbox" name="aceite_regulamento" value="1" required> Li e aceito o regulamento da prova *</label>

  <div class="actions">
    <button type="reset" class="secondary">↻ &nbsp; Limpar</button>
    <button type="submit" class="primary">✓ &nbsp; Confirmar Inscrição</button>
    <a href="{{ route('inscricoes.index') }}" class="outline">◉ &nbsp; Ver Inscritos</a>
  </div>
</section>
