// Contexto: consome api.php via fetch e renderiza tabela + paginação (com escape anti-XSS).
// Contexto: atalho para buscar elemento por id.
const $ = (id) => document.getElementById(id);
let paginaAtual = 1;

// Contexto: escapa HTML da API antes de injetar na tabela (anti-XSS).
function esc(v) {
  return String(v ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
}

// Contexto: busca página na api.php e redesenha tabela, status e paginação (com debounce no input).
async function buscar(pagina = 1) {
  paginaAtual = pagina;
  const q = $('q').value.trim();
  const limite = $('limite').value;
  $('status').textContent = 'Buscando…';
  try {
    const r = await fetch(`api.php?q=${encodeURIComponent(q)}&pagina=${pagina}&limite=${limite}`);
    const j = await r.json();
    if (!r.ok) throw new Error(j.erro || 'Falha na busca.');
    $('status').textContent = `${j.total} resultado(s) — página ${j.pagina}/${j.total_paginas}`;
    $('resultados').innerHTML = j.dados.length ? j.dados.map((i) => `
      <tr>
        <td>${i.id}</td>
        <td>${esc(i.nome)}</td>
        <td>${esc(i.documento)}</td>
        <td>${esc(i.email)}<br><small>${esc(i.celular)}</small></td>
        <td>${esc(i.cidade)}/${esc(i.uf)}</td>
        <td><span class="badge-distancia">${esc(i.distancia)}</span></td>
        <td>${esc(i.status)}</td>
      </tr>`).join('')
      : '<tr><td colspan="7" style="text-align:center">Nenhum cadastro encontrado.</td></tr>';
    $('paginacao').innerHTML = j.total_paginas > 1
      ? Array.from({ length: j.total_paginas }, (_, k) => `
        <li class="${k + 1 === j.pagina ? 'active' : ''}">
          <a href="#" data-pag="${k + 1}">${k + 1}</a>
        </li>`).join('')
      : '';
  } catch (e) {
    $('status').textContent = e.message;
    $('resultados').innerHTML = '<tr><td colspan="7" style="text-align:center">Erro na consulta.</td></tr>';
  }
}

$('busca-form').addEventListener('submit', (e) => { e.preventDefault(); buscar(1); });
$('paginacao').addEventListener('click', (e) => {
  const a = e.target.closest('a[data-pag]');
  if (a) { e.preventDefault(); buscar(Number(a.dataset.pag)); }
});
let timer;
$('q').addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(() => buscar(1), 400); });
buscar(1);
