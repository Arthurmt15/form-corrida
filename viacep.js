document.getElementById('cep')?.addEventListener('blur', async (e) => {
  const cep = e.target.value.replace(/\D/g, '');
  if (cep.length !== 8) return;
  try {
    const r = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
    const d = await r.json();
    if (d.erro) return;
    document.getElementById('logradouro').value = d.logradouro || '';
    document.getElementById('bairro').value = d.bairro || '';
    document.getElementById('cidade').value = d.localidade || '';
    document.getElementById('uf').value = d.uf || '';
  } catch (_) { /* ignora */ }
});
