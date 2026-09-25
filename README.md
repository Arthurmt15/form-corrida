# form-corrida

Formulário de inscrição para corrida em PHP + MySQL + Bootstrap 5.

## Stack

- PHP 8 + PDO (prepared statements)
- MySQL (`corrida_db`, tabela `inscricoes`)
- Bootstrap 5 via CDN + `style.css` próprio
- JS puro (`viacep.js` consome ViaCEP)

## Estrutura (cada arquivo < 300 linhas)

| Arquivo | Contexto |
|---|---|
| `index.php` | Página principal; monta o form via `include` dos parciais |
| `form-identificacao.php` | Seção 1 — quem é (nome/razão, CPF/CNPJ, RG/IE, nascimento/fundação, gênero) |
| `form-contato.php` | Seção 2 — como falar (e-mails, celular + WhatsApp, fixo) |
| `form-endereco.php` | Seção 3 — onde está (CEP autopreenche via `viacep.js`) |
| `form-corrida.php` | Seção 4 — corrida + controle interno (distância, camiseta, categoria, origem, status) |
| `salvar.php` | Recebe o POST, valida e insere com PDO |
| `listar.php` | Tabela resumida dos inscritos |
| `conexao.php` | Conexão PDO; se MySQL cair, `$db_ok=false` e o form renderiza em modo visual |
| `banco.sql` | Schema + `CREATE TABLE inscricoes` |
| `style.css` | Tema: branco predominante, azul secundário, detalhes preto/amarelo |

## Como rodar

1. Inicie o MySQL e importe o schema:
   ```sql
   SOURCE banco.sql;
   ```
2. Ajuste credenciais em `conexao.php` (`host`, `user`, `pass`).
3. Sirva o projeto:
   ```bash
   php -S 127.0.0.1:8000
   ```
4. Acesse:
   - `http://127.0.0.1:8000/index.php` — formulário
   - `http://127.0.0.1:8000/listar.php` — inscritos

## Convenções

- Máx. 300 linhas por arquivo — quebre em parciais em vez de crescer um arquivo.
- Comentário de contexto no topo de cada arquivo (1–2 linhas).
- Todo push vai para a branch `main`.
- Validação dupla: HTML/Bootstrap no front + checagem em `salvar.php`.
