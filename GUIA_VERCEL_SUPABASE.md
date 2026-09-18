# Guia Completo de Hospedagem: Vercel + Supabase (PostgreSQL)
## Sistema & Expositor Digital — Banca Santa Rita

Este guia explica passo a passo como colocar no ar tanto o **Site Público da Banca** quanto o **Painel Administrativo (`/admin`)** e a **API REST** utilizando a **Vercel** para a aplicação web (PHP/Serverless) e o **Supabase** para o banco de dados (PostgreSQL gerenciado).

---

## 🏗️ Arquitetura da Solução

```text
┌─────────────────────────────────────────────────────────────┐
│                       VERCEL (Edge & Serverless)            │
│  - Site Público: https://seu-dominio.vercel.app            │
│  - Painel Admin: https://seu-dominio.vercel.app/admin       │
│  - API REST:     https://seu-dominio.vercel.app/api         │
│  - Runtime:      vercel-php (Serverless Function)           │
│  - Assets:       Vite + Bootstrap + Swiper na CDN Global    │
└──────────────────────────────┬──────────────────────────────┘
                               │ Conexão SSL / PgBouncer
                               ▼
┌─────────────────────────────────────────────────────────────┐
│                    SUPABASE (PostgreSQL)                    │
│  - Usuários (Admin) e Sessões seguras em banco              │
│  - Categorias, Fotos, Vídeos, Itens em Exposição            │
│  - Configurações da Banca Santa Rita                        │
└─────────────────────────────────────────────────────────────┘
```

---

## Passo 1: Criar o Banco de Dados no Supabase

1. Acesse [supabase.com](https://supabase.com) e crie uma conta ou faça login.
2. No painel inicial, clique em **"New Project"**.
3. Escolha a sua organização e preencha:
   * **Name:** `banca-santa-rita`
   * **Database Password:** Escolha uma senha forte (anote-a!).
   * **Region:** Escolha a mais próxima do seu público (ex: `São Paulo (sa-east-1)`).
4. Aguarde cerca de 1 a 2 minutos até o Supabase provisionar o banco de dados.
5. Quando o projeto estiver pronto:
   * No menu lateral esquerdo, clique no ícone de engrenagem **Project Settings** (ou acesse a aba **Connect** no topo).
   * Vá em **Database** -> seção **Connection parameters** (ou **Connection Pooling**).
   * Selecione o modo **Session** (Porta `5432`) ou **Transaction** (Porta `6543`).
   * Anote os seguintes dados:
     * **Host:** `aws-0-sa-east-1.pooler.supabase.com` (ou similar)
     * **Port:** `6543` (ou `5432`)
     * **Database:** `postgres`
     * **User:** `postgres.[SEU_PROJECT_REF]`
     * **Password:** [A senha que você cadastrou no passo 3]

---

## Passo 2: Executar as Migrações e Seeds no Supabase

Como a Vercel é um ambiente serverless web sem terminal SSH interativo, a forma recomendada e mais rápida de criar a estrutura inicial de tabelas e carregar o administrador padrão no Supabase é executar o comando a partir do seu terminal local.

No seu terminal no computador, configure temporariamente o `.env` com as credenciais do Supabase:

```ini
DB_CONNECTION=pgsql
DB_HOST=aws-0-sa-east-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.seu_project_ref
DB_PASSWORD=sua_senha_do_supabase
DB_SSLMODE=require
```

Em seguida, execute no terminal:

```bash
php artisan migrate:fresh --seed --force
```

Esse comando criará automaticamente no Supabase:
- Tabela de **usuários** e o administrador padrão (`admin@bancasantarita.com.br` / `admin123`)
- Tabela de **sessões** (para manter o login no painel na Vercel)
- Tabelas de **categorias**, **conteúdos** (fotos/vídeos), **produtos** (itens em exposição) e **configurações da banca** pré-populadas.

---

## Passo 3: Conectar o Repositório na Vercel

O projeto já inclui os arquivos prontos de configuração da Vercel:
* `vercel.json` (rotas serverless, cache de templates em `/tmp` e runtime PHP)
* `api/index.php` (ponto de entrada da aplicação)
* `.vercelignore` (ignora arquivos desnecessários no deploy)

Para conectar:
1. Acesse [vercel.com](https://vercel.com) e faça login.
2. Clique em **"Add New..."** -> **"Project"**.
3. Localize e importe o repositório GitHub:
   `hiinin/Sistema-Banca-Santa-Rita`
4. Na tela de configuração do projeto:
   * **Project Name:** `sistema-banca-santa-rita` (ou o nome que desejar)
   * **Framework Preset:** Deixe selecionado **Other** (o arquivo `vercel.json` configurará o runtime automaticamente).
   * **Root Directory:** `./`

---

## Passo 4: Adicionar as Variáveis de Ambiente na Vercel

Antes de clicar em Deploy, expanda a seção **"Environment Variables"** na Vercel e cadastre as seguintes chaves:

| Nome da Variável | Valor Recomendado | Descrição |
| :--- | :--- | :--- |
| `APP_NAME` | `"Banca Santa Rita"` | Nome da aplicação |
| `APP_ENV` | `production` | Ambiente de produção |
| `APP_DEBUG` | `false` | Desabilita exibição de erros sensíveis |
| `APP_KEY` | `base64:...` *(copie do seu .env local)* | Chave de criptografia do Laravel |
| `APP_URL` | `https://seu-projeto.vercel.app` | URL gerada pela Vercel |
| `DB_CONNECTION` | `pgsql` | Driver PostgreSQL |
| `DB_HOST` | `aws-0-sa-east-1.pooler.supabase.com` | Host obtido no Supabase |
| `DB_PORT` | `6543` | Porta de pooling do Supabase |
| `DB_DATABASE` | `postgres` | Nome do banco do Supabase |
| `DB_USERNAME` | `postgres.seu-project-ref` | Usuário do Supabase |
| `DB_PASSWORD` | `sua-senha-supabase` | Senha cadastrada no Supabase |
| `DB_SSLMODE` | `require` | Exigido para conexão com Supabase |
| `SESSION_DRIVER` | `database` | Mantém sessões persistidas no banco |
| `CACHE_STORE` | `database` | Cache armazenado no banco |
| `LOG_CHANNEL` | `stderr` | Exibe logs direto no dashboard da Vercel |

---

## Passo 5: Realizar o Deploy

1. Clique no botão **"Deploy"**.
2. A Vercel iniciará o processo:
   - Instalação dos pacotes Node (`npm install`)
   - Compilação dos assets com Vite (`npm run build`)
   - Instalação das dependências do Composer via runtime PHP Serverless
   - Disponibilização na CDN global com HTTPS automático.
3. Ao finalizar, clique em **"Continue to Dashboard"** ou acesse a URL fornecida pela Vercel!

---

## 🔐 Acessando o Sistema em Produção

* **Site Público da Banca:**
  `https://seu-projeto.vercel.app`
* **Painel Administrativo:**
  `https://seu-projeto.vercel.app/admin`
  - **Login:** `admin@bancasantarita.com.br`
  - **Senha:** `admin123`
* **API REST:**
  `https://seu-projeto.vercel.app/api/banca`
* **Sitemap XML:**
  `https://seu-projeto.vercel.app/sitemap.xml`

---

## 💡 Observações Importantes sobre Arquivos e Uploads na Vercel

* **Assets estáticos e fotos demonstrativas:** Todos os arquivos de `public/images/` e os bundles de CSS/JS compilados em `public/build/` são servidos de forma permanente e ultra-rápida pela CDN global da Vercel.
* **Upload de novas mídias via painel:** No ambiente serverless da Vercel, o disco local é efêmero (read-only exceto `/tmp`). Para salvar novas fotos enviadas pelo painel no futuro de forma definitiva, você pode utilizar o **Supabase Storage** (que já vem integrado no Supabase com compatibilidade S3) ou um bucket S3/Cloudinary configurando as credenciais no `.env`.
