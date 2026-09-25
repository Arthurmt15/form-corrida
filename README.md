# form-corrida

Formulário de inscrição para corrida. Duas versões:

- **Raiz (legado):** PHP puro + PDO + MySQL, sem framework.
- **`laravel/` (atual):** port Laravel 12 — models, FormRequest, API Resource, Blade, Pest/PHPUnit.

## Laravel (`laravel/`)

```bash
cd laravel
composer install
php artisan migrate   # sqlite zero-config (troque DB_* no .env p/ MySQL)
php artisan test      # 6 testes de feature
php artisan serve     # http://127.0.0.1:8000
```

Rotas: `/inscricoes/create` (form), `/inscricoes` (lista), `/consulta` (front da API), `/api/inscricoes` (JSON).

## Stack (legado)

- PHP 8 + PDO (prepared statements)
- MySQL (`corrida_db`, tabela `inscricoes`)
- Bootstrap 5 via CDN + `style.css` próprio
- JS puro (`viacep.js` consome ViaCEP)

## Estrutura (cada arquivo < 300 linhas)

| Arquivo | Contexto |
|---|---|
| `index.php` | Página principal; monta o form via `include` dos parciais |
| `form-*.php` | Seções 1–4 do formulário (identificação, contato, endereço, corrida) |
| `salvar.php` | Recebe o POST, valida e delega ao repositório |
| `listar.php` / `consulta.php` | Tabela server-side / front via `api.php` |
| `api.php` | GET JSON dos cadastrados (busca + paginação) |
| `config/database.php` | Conexão PDO via env; sem MySQL, `$db_ok=false` (modo visual) |
| `src/Seguranca.php` | Headers anti-ataque + sessão segura |
| `src/Csrf.php` | Token CSRF de uso único |
| `src/Validacao.php` | Sanitização + regras (coberta por `testes.php`) |
| `src/InscricaoRepository.php` | Todo o SQL (pessoas 1:N inscrições, transação) |
| `banco.sql` | Schema normalizado: `pessoas` + `inscricoes` (FK, UNIQUE, índices) |

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
