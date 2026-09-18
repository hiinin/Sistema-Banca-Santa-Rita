# Banca Santa Rita — Expositor Digital & Sistema de Mídia

Sistema web completo para a **Banca Santa Rita**, composto por **Painel Administrativo**, **Site Público (Expositor Digital)** e **API REST**.

O projeto funciona como uma vitrine e catálogo informativo digital para os clientes e frequentadores da banca acompanharem novidades diárias, fotos de lançamentos, vídeos, horários, localização e itens em exposição (revistas, jornais, mangás, gibis, livros e colecionáveis) com canal direto de atendimento e consulta via WhatsApp.

> **Aviso de Escopo:** Este projeto **NÃO** é um e-commerce ou sistema de vendas online. Não há carrinho, checkout, gateway de pagamento, estoque ou controle financeiro. Os preços informados no catálogo são preços de capa ou sugeridos, servindo como referência informativa para consulta presencial ou via WhatsApp.

---

## 🎨 Identidade Visual & Design System

O projeto adota uma paleta exclusiva e harmoniosa desenhada para refletir tradição, dinamismo e cultura:

* **Verde Destaque / Accent:** `#1BC736` (Badges, botões de ação e estados ativos)
* **Verde Institucional:** `#35613B` (Identidade institucional, títulos de seção e botões secundários)
* **Verde Noturno / Dark:** `#263328` (Topbar, rodapé, sidebar e textos principais)
* **Superfícies & Bordas:** Tons suaves `#f6f8f6` e `#e3e8e4` para máxima legibilidade
* **Tipografia:** Google Fonts (*Plus Jakarta Sans* para títulos e *Inter* para corpo de texto)

---

## 🚀 Funcionalidades Principais

### 1. Painel Administrativo (`/admin`)
* **Autenticação Segura:** Proteção contra força bruta com rate limiting (5 tentativas/minuto), middleware `auth` e logout seguro.
* **Dashboard em Tempo Real:** 7 métricas principais (Total de fotos, vídeos, destaques, itens em exposição, categorias ativas e status do expositor) e tabelas com os cadastros mais recentes.
* **Gerenciamento de Categorias (`/admin/categorias`):**
  * Criação com geração automática de slug em tempo real via JavaScript.
  * Proteção de integridade referencial: bloqueio de exclusão de categorias com vínculos.
* **Gerenciamento de Conteúdos (`/admin/conteudos`):**
  * Cadastro de Fotos e Vídeos com toggle dinâmico de campos.
  * Suporte a upload de fotos (JPG, PNG, WEBP até 5MB) e vídeos (MP4, WEBM até 50MB).
  * Suporte a URLs de incorporação do YouTube e Vimeo com conversão automática para formato embed.
  * Limpeza automática de arquivos substituídos ou deletados no storage.
* **Gerenciamento de Itens em Exposição (`/admin/produtos`):**
  * Cadastro de títulos, categorias, descrições, imagens e preços sugeridos/de capa.
  * Sanitizador automático de valores em moeda Real (BRL).
* **Configurações da Banca (`/admin/configuracoes`):**
  * Atualização de nome, slogan, texto "Sobre a Banca", endereço, horários de atendimento, telefone, e-mail e redes sociais.
  * Upload de logotipo e favicon personalizados.
  * Integração com coordenadas geográficas do Google Maps.
  * Gerador de links dinâmicos para WhatsApp.

### 2. Site Público & Expositor Digital
* **Página Inicial (`/`):**
  * Hero banner dinâmico com destaque editorial e badges da banca.
  * Carrossel de destaques alimentado por **Swiper.js** (responsivo: 4 desktop, 3 laptop, 2 tablet, 1 mobile).
  * Vitrine dos itens em exposição com preço sugerido e botão direto para consulta no WhatsApp.
  * Galeria de fotos recentes com abertura em Lightbox modal de alta resolução.
  * Galeria de vídeos recentes com player modal responsivo e cessação imediata de áudio ao fechar.
* **Sobre a Banca (`/sobre`):**
  * História, pilares de atendimento da banca, fotos do espaço e chamada para visita presencial.
* **Mídias & Publicações (`/conteudos`):**
  * Filtros interativos por tipo de mídia (Fotos/Vídeos), categoria e busca por palavras-chave.
  * Paginação estilizada Bootstrap 5.
* **Detalhe de Conteúdo (`/conteudo/{slug}`):**
  * Player de vídeo dedicado 16:9 ou foto em alta definição com zoom.
  * Conteúdos relacionados da mesma categoria e botão de compartilhamento no WhatsApp.
* **Catálogo de Itens em Exposição (`/produtos`):**
  * Filtro por categoria e busca por nome.
  * Paginação de itens e cartões elegantes com micro-animações.
* **Detalhe do Item (`/produto/{slug}`):**
  * Foto detalhada, preço informativo de capa, explicação sobre o funcionamento do catálogo e botão com mensagem personalizada para atendimento via WhatsApp.
* **Contato & Localização (`/contato`):**
  * Cartões de atendimento com WhatsApp, telefone, e-mail e horários.
  * Mapa do Google Maps incorporado de forma responsiva.
* **SEO e Acessibilidade:**
  * Metatags completas, OpenGraph dinâmico para redes sociais e link canônico em todas as páginas.
  * `robots.txt` e `sitemap.xml` dinâmico gerado em tempo real com todas as URLs públicas.
  * Atalho de acessibilidade (*skip-to-content*), estados de foco visíveis (`:focus-visible`) e conformidade WCAG AA.

### 3. API REST Pública (`/api/*`)
Exposição estrita apenas de conteúdos e itens com `status = publicado`:
* `GET /api/banca` — Dados institucionais e configurações da banca.
* `GET /api/categorias` — Lista de categorias ativas.
* `GET /api/conteudos` — Lista paginada de mídias publicadas (filtros por `tipo`, `categoria_id` e `search`).
* `GET /api/conteudos/destaques` — Conteúdos destacados.
* `GET /api/conteudos/fotos` — Apenas fotos publicadas.
* `GET /api/conteudos/videos` — Apenas vídeos publicados.
* `GET /api/conteudos/{slug}` — Detalhe da mídia com itens relacionados.
* `GET /api/produtos` — Catálogo de itens em exposição (filtros por `categoria_id` e `search`).
* `GET /api/produtos/destaques` — Itens em destaque no expositor.
* `GET /api/produtos/{slug}` — Detalhe do item com link formatado para WhatsApp.

---

## 💻 Requisitos do Ambiente

* **PHP:** 8.4 ou superior (com extensões `pdo_sqlite` ou `pdo_mysql`, `mbstring`, `fileinfo`, `curl`).
* **Composer:** 2.x
* **Node.js:** 18.x ou superior & **NPM:** 9.x ou superior
* **Banco de Dados:** SQLite (padrão local) ou MySQL / MariaDB

---

## ⚙️ Instalação Passo a Passo

### 1. Clonar o Repositório
```bash
git clone https://github.com/hiinin/Sistema-Banca-Santa-Rita.git
cd Sistema-Banca-Santa-Rita
```

### 2. Instalar Dependências do PHP
```bash
composer install
```

### 3. Configurar o Ambiente
Copie o arquivo de exemplo e gere a chave de criptografia do Laravel:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Executar Migrações e Seeders
O banco de dados SQLite já é configurado por padrão. Execute as migrações com dados demonstrativos reais (administrador padrão, categorias, fotos, vídeos, produtos e configurações completas):
```bash
php artisan migrate --seed
```

### 5. Criar Link Simbólico de Armazenamento
Para que os uploads de mídias sejam servidos publicamente:
```bash
php artisan storage:link
```

### 6. Instalar Dependências Frontend & Compilar Assets
```bash
npm install
npm run build
```

*(Em ambiente de desenvolvimento, você pode utilizar `npm run dev` para hot-reload)*

### 7. Iniciar o Servidor Local
```bash
php artisan serve
```

Acesse a aplicação no seu navegador:
* **Site Público:** [http://127.0.0.1:8000](http://127.0.0.1:8000)
* **Painel Administrativo:** [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)
* **Sitemap XML:** [http://127.0.0.1:8000/sitemap.xml](http://127.0.0.1:8000/sitemap.xml)

---

## 🔑 Credenciais do Administrador

Ao executar `php artisan db:seed`, o usuário administrador é gerado com as seguintes credenciais padrão:

* **URL de Acesso:** `/admin/login`
* **E-mail:** `admin@bancasantarita.com.br`
* **Senha:** `admin123`

---

## 🧪 Testes Automatizados

O sistema conta com suíte de testes com cobertura completa de regras de negócio, autorização, CRUDs, endpoints da API e renderização das páginas públicas:

```bash
# Executar todos os testes da aplicação
php vendor/bin/phpunit

# Executar testes específicos por área
php vendor/bin/phpunit tests/Feature/PublicSitePagesTest.php
php vendor/bin/phpunit tests/Feature/ApiEndpointsTest.php
php vendor/bin/phpunit tests/Feature/AdminContentTest.php
php vendor/bin/phpunit tests/Feature/AdminProductTest.php
php vendor/bin/phpunit tests/Feature/AdminCategoryTest.php
php vendor/bin/phpunit tests/Feature/AdminAuthTest.php
php vendor/bin/phpunit tests/Feature/AdminConfigurationTest.php
```

**Resultado dos Testes:**
* **66 Testes**
* **447 Asserções**
* **100% de Aprovação (0 erros, 0 falhas)**

---

## 📦 Estrutura de Diretórios Principal

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/           # Autenticação, Dashboard, Categorias, Conteúdos, Produtos, Configurações
│   │   ├── Api/             # Controllers REST API (Configuration, Category, Content, Product)
│   │   └── Site/            # Home, About, ContentPublic, ProductPublic, Contact, Sitemap
│   ├── Requests/Admin/      # FormRequests de validação com sanitização e autorização
│   └── Resources/           # API Resources com formatação dos dados JSON
├── Models/                  # Category, Content, Product, Configuration, User (com scopes e accessors)
└── Services/                # MediaUploadService (upload, validação e deleção segura de mídia)
resources/
├── css/
│   └── app.css              # Design system com tokens da marca, Swiper, WCAG e micro-animações
├── js/
│   └── app.js               # Bootstrap 5 e Swiper.js bundle
└── views/
    ├── admin/               # Telas do painel administrativo
    ├── layouts/             # Layouts mestres (app.blade.php, admin.blade.php, auth.blade.php)
    └── site/                # Páginas públicas (home, about, contents, products, contact, sitemap)
```

---

## 📄 Licença
Este software foi desenvolvido como solução sob medida para a Banca Santa Rita sob a licença [MIT](LICENSE).
