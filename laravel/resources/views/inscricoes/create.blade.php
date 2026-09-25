@extends('layouts.app')

@section('titulo', 'Inscrição - Corrida')
@section('h1', 'Inscrição - Corrida')
@section('subtitulo', 'Preencha os dados abaixo para realizar sua inscrição na corrida.')

@section('conteudo')
  @if (session('sucesso_id'))
    <div class="notice"><strong>ⓘ</strong><span>Cadastro realizado! ID: {{ session('sucesso_id') }}</span></div>
  @endif
  @if (session('sucesso'))
    <div class="notice"><strong>ⓘ</strong><span>Cadastro recebido!</span></div>
  @endif
  @if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <form action="{{ route('inscricoes.store') }}" method="POST" novalidate>
    @csrf
    <input type="text" name="site_url" value="" style="display:none" tabindex="-1" autocomplete="off">
    @include('inscricoes.partials.identificacao')
    @include('inscricoes.partials.contato')
    @include('inscricoes.partials.endereco')
    @include('inscricoes.partials.corrida')
  </form>
@endsection

@push('scripts')
  <script src="{{ asset('js/viacep.js') }}"></script>
  <script src="{{ asset('js/mascaras.js') }}"></script>
@endpush
