// Contexto: máscaras automáticas (CPF/CNPJ, celular, CEP, UF). Sem dependências.
function soDigitos(v, max) { return v.replace(/\D/g, '').slice(0, max); }
function maskDoc(v) {
  const n = soDigitos(v, 14);
  if (n.length <= 11) {
    return n.replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d{1,2})$/, '$1-$2');
  }
  return n.replace(/(\d{2})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1/$2').replace(/(\d{4})(\d{1,2})$/, '$1-$2');
}
document.addEventListener('input', (e) => {
  const el = e.target;
  if (el.name === 'cpf_cnpj') el.value = maskDoc(el.value);
  if (el.name === 'celular' || el.name === 'telefone_fixo') {
    const n = soDigitos(el.value, 11);
    el.value = n.length > 10
      ? n.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3')
      : n.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
  }
  if (el.name === 'cep') el.value = soDigitos(el.value, 8).replace(/(\d{5})(\d{0,3})/, '$1-$2').replace(/-$/, '');
  if (el.name === 'uf') el.value = el.value.replace(/[^a-zA-Z]/g, '').slice(0, 2).toUpperCase();
});
