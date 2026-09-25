<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('titulo', 'Corrida')</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
  <main class="page">
    <header class="hero">
      <div class="hero-icon">●</div>
      <div>
        <h1>@yield('h1')</h1>
        <p>@yield('subtitulo')</p>
      </div>
      <div class="runner">@yield('emoji', '🏃')</div>
    </header>
    @yield('conteudo')
  </main>
  @stack('scripts')
</body>
</html>
