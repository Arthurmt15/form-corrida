@extends('layouts.app')

@section('titulo', 'Inscritos - Corrida')
@section('h1', '🏁 Inscritos ('.$inscricoes->total().')')
@section('subtitulo', 'Lista de inscrições com busca e paginação.')

@section('conteudo')
  <div class="notice"><strong>ⓘ</strong>
    <form method="GET" action="{{ route('inscricoes.index') }}" class="search-row" style="flex:1">
      <input type="search" name="q" placeholder="Buscar nome, e-mail, cidade" value="{{ $q }}">
      <button class="primary">Buscar</button>
      <a href="{{ route('consulta') }}" class="outline" style="display:inline-flex;align-items:center;padding:0 16px;text-decoration:none;font-weight:700;font-size:12px;background:#fff;color:#1559d8;border:1px solid #1559d8;border-radius:5px;height:38px">Via API</a>
      <a href="{{ route('inscricoes.create') }}" class="outline" style="display:inline-flex;align-items:center;padding:0 16px;text-decoration:none;font-weight:700;font-size:12px;background:#fff;color:#1559d8;border:1px solid #1559d8;border-radius:5px;height:38px">Nova Inscrição</a>
    </form>
  </div>
  <section class="card">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th><th>Nome</th><th>CPF/CNPJ</th><th>Contato</th><th>Cidade/UF</th><th>Distância</th><th>Status</th><th>Criado em</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($inscricoes as $i)
            <tr>
              <td>{{ $i->id }}</td>
              <td>{{ $i->pessoa->nome }}</td>
              <td>{{ $i->documentoMascarado() }}</td>
              <td>{{ $i->pessoa->email }}<br><small>{{ $i->pessoa->celular }}</small></td>
              <td>{{ $i->pessoa->cidade }}/{{ $i->pessoa->uf }}</td>
              <td><span class="badge-distancia">{{ $i->distancia }}</span></td>
              <td>{{ $i->status_cadastro }}</td>
              <td>{{ $i->created_at->format('d/m/Y H:i') }}</td>
            </tr>
          @empty
            <tr><td colspan="8" style="text-align:center">Nenhum cadastro encontrado.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div style="margin:16px 18px 20px">{{ $inscricoes->links() }}</div>
  </section>
@endsection
