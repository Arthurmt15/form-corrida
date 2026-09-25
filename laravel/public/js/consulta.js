// Consome /api/inscricoes (paginador do Laravel) e renderiza tabela + paginação (com escape anti-XSS).
const $ = (id) => document.getElementById(id);

function esc(v) {
  return String(v ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
}

async function buscar(pagina = 1) {
  const q = $('q').value.trim();
  const perPage = $('limite').value;
  $('status').textContent = 'Buscando…';
  try {
    const r = await fetch(`/api/inscricoes?q=${encodeURIComponent(q)}&page=${pagina}&per_page=${perPage}`);
    const j = await r.json();
    if (!r.ok) throw new Error(j.message || 'Falha na busca.');
    $('status').textContent = `${j.meta.total} resultado(s) — página ${j.meta.current_page}/${j.meta.last_page}`;
    $('resultados').innerHTML = j.data.length ? j.data.map((i) => `
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
    $('paginacao').innerHTML = j.meta.last_page > 1
      ? Array.from({ length: j.meta.last_page }, (_, k) => `
        <li class="${k + 1 === j.meta.current_page ? 'active' : ''}">
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
