-- ==========================================================
-- SCRIPT DE INICIALIZAÇÃO COMPLETO - BANCA SANTA RITA (SUPABASE)
-- Execute este script no SQL Editor do Supabase (supabase.com)
-- ==========================================================

CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- 1. Tabela de Usuários
CREATE TABLE IF NOT EXISTS users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 2. Tokens de Redefinição de Senha
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

-- 3. Tabela de Sessões (Essencial para login no Vercel Serverless)
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE CASCADE,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS sessions_user_id_index ON sessions (user_id);
CREATE INDEX IF NOT EXISTS sessions_last_activity_index ON sessions (last_activity);

-- 4. Tabela de Cache
CREATE TABLE IF NOT EXISTS cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration BIGINT NOT NULL
);
CREATE INDEX IF NOT EXISTS cache_expiration_index ON cache (expiration);

CREATE TABLE IF NOT EXISTS cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration BIGINT NOT NULL
);

-- 5. Tabela de Categorias
CREATE TABLE IF NOT EXISTS categories (
    id BIGSERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    descricao TEXT NULL,
    status VARCHAR(50) DEFAULT 'ativo' NOT NULL,
    ordem INTEGER DEFAULT 0 NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
CREATE INDEX IF NOT EXISTS categories_status_index ON categories (status);
CREATE INDEX IF NOT EXISTS categories_ordem_index ON categories (ordem);

-- 6. Tabela de Conteúdos (Mídias, Fotos e Vídeos)
CREATE TABLE IF NOT EXISTS contents (
    id BIGSERIAL PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    descricao TEXT NULL,
    tipo VARCHAR(50) DEFAULT 'foto' NOT NULL,
    categoria_id BIGINT NOT NULL REFERENCES categories(id) ON DELETE CASCADE,
    imagem VARCHAR(255) NULL,
    video_url VARCHAR(255) NULL,
    video_arquivo VARCHAR(255) NULL,
    ordem INTEGER DEFAULT 0 NOT NULL,
    status VARCHAR(50) DEFAULT 'publicado' NOT NULL,
    destaque VARCHAR(10) DEFAULT 'não' NOT NULL,
    data_publicacao TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
CREATE INDEX IF NOT EXISTS contents_tipo_index ON contents (tipo);
CREATE INDEX IF NOT EXISTS contents_status_index ON contents (status);
CREATE INDEX IF NOT EXISTS contents_destaque_index ON contents (destaque);
CREATE INDEX IF NOT EXISTS contents_data_publicacao_index ON contents (data_publicacao);

-- 7. Tabela de Itens em Exposição (Catálogo)
CREATE TABLE IF NOT EXISTS products (
    id BIGSERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    descricao TEXT NULL,
    imagem VARCHAR(255) NULL,
    preco NUMERIC(10, 2) NULL,
    categoria_id BIGINT NULL REFERENCES categories(id) ON DELETE SET NULL,
    destaque VARCHAR(10) DEFAULT 'não' NOT NULL,
    status VARCHAR(50) DEFAULT 'publicado' NOT NULL,
    ordem INTEGER DEFAULT 0 NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
CREATE INDEX IF NOT EXISTS products_destaque_index ON products (destaque);
CREATE INDEX IF NOT EXISTS products_status_index ON products (status);
CREATE INDEX IF NOT EXISTS products_ordem_index ON products (ordem);

-- 8. Tabela de Configurações da Banca
CREATE TABLE IF NOT EXISTS configurations (
    id BIGSERIAL PRIMARY KEY,
    nome_banca VARCHAR(255) DEFAULT 'Banca Santa Rita' NOT NULL,
    logo VARCHAR(255) NULL,
    favicon VARCHAR(255) NULL,
    descricao TEXT NULL,
    endereco VARCHAR(255) NULL,
    telefone VARCHAR(255) NULL,
    whatsapp VARCHAR(255) NULL,
    email VARCHAR(255) NULL,
    instagram VARCHAR(255) NULL,
    facebook VARCHAR(255) NULL,
    horario VARCHAR(255) NULL,
    latitude VARCHAR(255) NULL,
    longitude VARCHAR(255) NULL,
    texto_sobre TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- 9. Tabela de Controle de Migrations do Laravel
CREATE TABLE IF NOT EXISTS migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

-- ==========================================================
-- CARGA DE DADOS INICIAIS (SEEDS)
-- ==========================================================

-- Usuário Administrador (admin@bancasantarita.com.br / admin123)
INSERT INTO users (id, name, email, password, email_verified_at, created_at, updated_at)
VALUES (
    1,
    'Administrador da Banca',
    'admin@bancasantarita.com.br',
    crypt('admin123', gen_salt('bf', 12)),
    NOW(),
    NOW(),
    NOW()
) ON CONFLICT (email) DO NOTHING;

-- Configuração da Banca Santa Rita (Maringá - PR)
INSERT INTO configurations (
    id, nome_banca, descricao, endereco, telefone, whatsapp, email, 
    instagram, facebook, horario, latitude, longitude, texto_sobre, created_at, updated_at
) VALUES (
    1,
    'Banca Santa Rita',
    'Seu ponto de encontro com a informação, cultura, revistas, jornais e novidades na Praça do Peladão em Maringá.',
    'Praça 7 de Setembro (Praça do Peladão), s/n - Ao lado do Hospital Bom Samaritano, Zona 05, Maringá - PR',
    '(44) 9842-4758',
    '4498424758',
    'contato@bancasantarita.com.br',
    'bancasantarita',
    'bancasantaritaoficial',
    'Segunda a Sexta: das 08:00 às 18:00 | Sábados: das 09:00 às 17:00',
    '-23.422934',
    '-51.952967',
    'Fundada com o compromisso de manter viva a tradição da leitura e da boa conversa, a **Banca Santa Rita** é seu ponto de referência na Praça do Peladão (Praça 7 de Setembro), ao lado do Hospital Bom Samaritano, em Maringá - PR. Oferecemos um acervo cuidadosamente selecionado com as principais publicações nacionais e internacionais, jornais matinais, as revistas mais conceituadas de atualidades e design, gibis clássicos e novidades em mangás, além de colecionáveis e lançamentos literários.',
    NOW(),
    NOW()
) ON CONFLICT (id) DO UPDATE SET
    endereco = EXCLUDED.endereco,
    telefone = EXCLUDED.telefone,
    whatsapp = EXCLUDED.whatsapp,
    horario = EXCLUDED.horario,
    latitude = EXCLUDED.latitude,
    longitude = EXCLUDED.longitude;

-- Categorias Iniciais
INSERT INTO categories (id, nome, slug, descricao, status, ordem, created_at, updated_at) VALUES
(1, 'Destaques & Novidades', 'destaques-novidades', 'Os principais lançamentos da semana e itens em evidência na Banca Santa Rita.', 'ativo', 1, NOW(), NOW()),
(2, 'Jornais do Dia', 'jornais-do-dia', 'Principais periódicos nacionais e regionais atualizados diariamente.', 'ativo', 2, NOW(), NOW()),
(3, 'Revistas & Periódicos', 'revistas-periodicos', 'Revistas sobre atualidades, ciência, negócios, moda, saúde e decoração.', 'ativo', 3, NOW(), NOW()),
(4, 'Quadrinhos & Mangás', 'quadrinhos-mangas', 'Gibis clássicos, graphic novels, mangás japoneses e edições especiais.', 'ativo', 4, NOW(), NOW()),
(5, 'Livros & Best-Sellers', 'livros-best-sellers', 'Literatura variada, desenvolvimento pessoal, romances e não-ficção.', 'ativo', 5, NOW(), NOW()),
(6, 'Colecionáveis & Álbuns', 'colecionaveis-albuns', 'Álbuns de figurinhas, miniaturas em escala, cards colecionáveis e passatempos.', 'ativo', 6, NOW(), NOW()),
(7, 'Papelaria & Conveniência', 'papelaria-conveniencia', 'Itens práticos escolares, canetas, cadernos e utilidades rápidas para o dia a dia.', 'ativo', 7, NOW(), NOW())
ON CONFLICT (slug) DO NOTHING;

-- Conteúdos (Mídias em Destaque)
INSERT INTO contents (titulo, slug, descricao, tipo, categoria_id, imagem, ordem, status, destaque, data_publicacao, created_at, updated_at) VALUES
('Nova Fachada e Espaço Renovado da Banca Santa Rita', 'nova-fachada-espaco-renovado-banca-santa-rita', 'Conheça o novo layout da Banca Santa Rita na Praça do Peladão, pensado para oferecer mais conforto e acessibilidade.', 'foto', 1, 'images/demo-banca-fachada.svg', 1, 'publicado', 'sim', NOW(), NOW(), NOW()),
('Jornais do Dia Logo Cedo na Banca', 'jornais-do-dia-logo-cedo-na-banca', 'Recebemos as principais manchetes do Brasil e do mundo todas as manhãs. Passe na banca a partir das 08h.', 'foto', 2, 'images/demo-jornais-manhas.svg', 2, 'publicado', 'sim', NOW(), NOW(), NOW()),
('Exposição de Revistas e Publicações Especiais', 'exposicao-revistas-publicacoes-especiais', 'Títulos de tecnologia, negócios, gastronomia, arquitetura e bem-estar prontos para leitura na Banca Santa Rita.', 'foto', 3, 'images/demo-revistas-semanais.svg', 3, 'publicado', 'não', NOW(), NOW(), NOW()),
('Espaço Geek: Universo Marvel, DC e Mangás', 'espaco-geek-universo-marvel-dc-mangas', 'Seção especial dedicada aos quadrinhos de heróis, edições definitivas e os mangás mais procurados do momento.', 'foto', 4, 'images/demo-gibis-mangas.svg', 4, 'publicado', 'sim', NOW(), NOW(), NOW())
ON CONFLICT (slug) DO NOTHING;

-- Itens em Exposição (Produtos)
INSERT INTO products (nome, slug, descricao, imagem, preco, categoria_id, destaque, status, ordem, created_at, updated_at) VALUES
('Jornal Folha de S.Paulo - Edição Completa', 'jornal-folha-de-s-paulo', 'Edição impressa matinal com cadernos de Cotidiano, Opinião, Ilustrada, Mercado e Esporte.', 'images/demo-jornais-manhas.svg', 9.00, 2, 'sim', 'publicado', 1, NOW(), NOW()),
('Revista Superinteressante - Edição do Mês', 'revista-superinteressante', 'Dossiês profundos sobre ciência, história, comportamento, futuro e mistérios do universo.', 'images/demo-revistas-semanais.svg', 24.90, 3, 'sim', 'publicado', 2, NOW(), NOW()),
('Revista Piauí - Edição Mensal', 'revista-piaui', 'Jornalismo narrativo aprofundado, reportagens investigativas, ensaios e humor refinado.', 'images/demo-revistas-semanais.svg', 32.00, 3, 'não', 'publicado', 3, NOW(), NOW()),
('Graphic Novel Clássicos dos Quadrinhos', 'graphic-novel-classicos-quadrinhos', 'Edição de colecionador em capa dura com ilustrações exclusivas e acabamento premium.', 'images/demo-gibis-mangas.svg', 69.90, 4, 'sim', 'publicado', 4, NOW(), NOW()),
('Mangá Shonen Jump - Volume 01 Especial', 'manga-shonen-jump-volume-01', 'O começo de uma das maiores sagas dos quadrinhos japoneses em papel especial.', 'images/demo-gibis-mangas.svg', 34.90, 4, 'sim', 'publicado', 5, NOW(), NOW()),
('Livro: Hábitos Atômicos (James Clear)', 'livro-habitos-atomicos', 'Método comprovado para desenvolver bons hábitos e se livrar dos maus todos os dias.', 'images/demo-banca-fachada.svg', 59.90, 5, 'sim', 'publicado', 6, NOW(), NOW()),
('Álbum de Figurinhas Oficial com 5 Envelopes', 'album-figurinhas-oficial-com-5-envelopes', 'Kit inicial para começar sua coleção de figurinhas com álbum ilustrado e figurinhas metalizadas.', 'images/demo-banca-fachada.svg', 18.00, 6, 'sim', 'publicado', 7, NOW(), NOW())
ON CONFLICT (slug) DO NOTHING;

-- Registro das Migrações para o Laravel
INSERT INTO migrations (migration, batch) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2026_09_17_185833_create_categories_table', 1),
('2026_09_17_185834_create_contents_table', 1),
('2026_09_17_185835_create_products_table', 1),
('2026_09_17_185836_create_configurations_table', 1),
('2026_09_21_120000_update_banca_santa_rita_contact_and_location', 1)
ON CONFLICT DO NOTHING;
