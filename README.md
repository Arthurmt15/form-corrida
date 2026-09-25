# form-corrida

Sistema de inscrição para corrida: formulário em 4 seções, lista com busca/paginação e API JSON. Sem login — inscrição pública.

## Mapa do repositório

| Pasta | O quê | Status |
|---|---|---|
| `laravel/` | App principal (Laravel 12, PHP 8.2) | **Ativo — desenvolva aqui** |
| `legacy/` | Versão original em PHP puro (sem framework) | Referência — não evoluir |

## Domínio

- **Seções do formulário:** 1) Identificação (nome/razão, CPF/CNPJ, RG/IE, nascimento/fundação, gênero) · 2) Contato (2 e-mails, celular + WhatsApp, fixo) · 3) Endereço (CEP com autopreenchimento ViaCEP) · 4) Corrida (distância, camiseta, categoria, equipe, origem, status).
- **Regras de negócio:** CPF/CNPJ com dígitos verificadores; celular/fixo só números com DDD; uma pessoa pode se inscrever em várias distâncias, mas **nunca 2x na mesma** (`UNIQUE`); documento sempre mascarado na exibição (LGPD).

## Arquitetura Laravel (`laravel/`)

```text
routes/ ──▶ controllers ──▶ requests (validação) ──▶ models ──▶ banco
web.php:        InscricaoController    StoreInscricaoRequest     Pessoa 1:N Inscricao
  /inscricoes/*   Api/InscricaoController  Rules/CpfCnpj,Celular  InscricaoResource (JSON)
```

- **Fluxo de uma inscrição:** `POST /inscricoes` → `throttle` + CSRF → `StoreInscricaoRequest` valida → controller abre transação → `firstOrCreate` pessoa por CPF → cria inscrição → duplicata (`23000`) volta como erro amigável.
- **Views Blade** (`resources/views/inscricoes/`): `create` + 4 partials, `index` server-side, `consulta` (fetch na API). Layout moderno em `public/css/style.css`; `public/js/` tem máscaras, ViaCEP e consulta.
- **Banco:** migrations criam `pessoas` + `inscricoes` (FK com cascade, `UNIQUE`s, índices). Padrão **sqlite zero-config**; para MySQL, ajuste `DB_*` no `.env`.

## Como rodar

```bash
cd laravel
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate          # cria as tabelas (sqlite)
php artisan test             # 6 testes de feature (sqlite :memory:)
php artisan serve --port=8001
```

| URL | Tela |
|---|---|
| `/inscricoes/create` | Formulário |
| `/inscricoes?q=` | Lista com busca + paginação |
| `/consulta` | Front da API (tempo real) |
| `/api/inscricoes?q=&page=&per_page=` | JSON (doc mascarado, máx. 100/pág) |

**Legado** (só se precisar comparar): `cd legacy && php -S 127.0.0.1:8000` — exige MySQL com `legacy/banco.sql` importado e credenciais via env (`DB_*`, ver `legacy/.env.example`).

## Testes

- **Laravel** (`laravel/tests/Feature/InscricaoTest.php`): form abre · salva pessoa+inscrição · rejeita CPF inválido · rejeita letra no celular · bloqueia duplicata · API mascara documento.
- **Legado** (`legacy/testes.php`): 25 asserts sem framework (`php legacy/testes.php`).

## Segurança (resumo)

CSRF nativo (Blade) + token de uso único · `throttle` no POST (10/min) e na API (60/min) · honeypot anti-bot · SQL só via Eloquent/placeholders · XSS contido (`{{ }}`, `htmlspecialchars`, `esc()` no JS) · headers (CSP, anti-clickjacking) · erros genéricos ao usuário, detalhe só no log.

## Convenções

- Máx. **300 linhas por arquivo**; cada função tem comentário `// Contexto: …`.
- Todo push vai para a branch **`main`**.
- Commits em PT-BR: `feat:`, `fix:`, `style:`, `docs:`, `refactor:`, `chore:`, `test:`.

## Problemas comuns

- **Porta ocupada:** `:8000` é do legado, `:8001` do Laravel — troque com `--port=`.
- **`SQLSTATE` no legado:** MySQL parado ou `banco.sql` não importado.
- **Laravel do zero:** apague `laravel/database/database.sqlite` e rode `migrate` de novo.
