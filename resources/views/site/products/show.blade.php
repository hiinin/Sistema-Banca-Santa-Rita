@extends('layouts.app')

@section('title', $item->nome . ' | ' . ($config->nome_banca ?: 'Banca Santa Rita'))
@section('meta_description', Str::limit(strip_tags($item->descricao), 150))
@section('og_image', $item->image_url)

@section('schema_json')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $item->nome,
    'image' => $item->image_url,
    'description' => Str::limit(strip_tags((string) $item->descricao), 200),
    'sku' => 'ITEM-' . $item->id,
    'category' => $item->category?->nome,
    'offers' => [
        '@type' => 'Offer',
        'priceCurrency' => 'BRL',
        'price' => (float) ($item->preco ?? 0),
        'availability' => 'https://schema.org/InStock',
        'seller' => [
            '@type' => 'Newsstand',
            'name' => $config->nome_banca,
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Início',
            'item' => route('home'),
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Itens em Exposição',
            'item' => route('site.products.index'),
        ],
        [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $item->nome,
            'item' => route('site.products.show', $item->slug),
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
<!-- Breadcrumb -->
<div class="py-4 bg-light border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Início</a></li>
                <li class="breadcrumb-item"><a href="{{ route('site.products.index') }}" class="text-decoration-none text-muted">Itens em Exposição</a></li>
                <li class="breadcrumb-item"><a href="{{ route('site.products.index', ['categoria_id' => $item->categoria_id]) }}" class="text-decoration-none text-muted">{{ $item->category->nome }}</a></li>
                <li class="breadcrumb-item active text-truncate" style="max-width: 250px;" aria-current="page">{{ $item->nome }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="row g-5 align-items-start mb-5">
            <!-- Imagem do Item -->
            <div class="col-12 col-lg-5">
                <div class="card border rounded-4 overflow-hidden shadow-sm position-relative cursor-pointer" onclick="openLightbox('{{ $item->image_url }}', '{{ addslashes($item->nome) }}', '{{ addslashes($item->descricao) }}')">
                    <img src="{{ $item->image_url }}" alt="{{ $item->nome }}" class="w-100" style="max-height: 480px; object-fit: contain; background-color: #fbfcfb;">
                    <div class="position-absolute bottom-0 end-0 m-3 btn btn-light btn-sm shadow d-flex align-items-center gap-1">
                        <i class="bi bi-zoom-in"></i>
                        <span>Ampliar</span>
                    </div>
                </div>
            </div>

            <!-- Informações do Item -->
            <div class="col-12 col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge-category">{{ $item->category->nome }}</span>
                    <span class="badge bg-success bg-opacity-10 text-success fw-semibold border border-success border-opacity-25 px-2 py-1">
                        <i class="bi bi-shop me-1"></i> No Expositor
                    </span>
                </div>

                <h1 class="h2 fw-bold text-dark mb-3">
                    {{ $item->nome }}
                </h1>

                <!-- Preço Informativo -->
                <div class="p-3 rounded-3 mb-4 bg-light border">
                    <div class="small text-muted mb-1">Preço de capa / sugerido:</div>
                    <div class="display-6 fw-bold" style="color: var(--brand-institutional);">
                        {{ $item->formatted_price }}
                    </div>
                    <div class="small text-muted mt-1">
                        * Valor informativo de capa ou referência do editor.
                    </div>
                </div>

                <!-- Botão de Consulta via WhatsApp -->
                <div class="mb-4">
                    <a href="{{ $whatsappUrl }}" 
                       target="_blank" 
                       class="btn btn-brand-accent btn-lg w-100 py-3 shadow-sm d-flex align-items-center justify-content-center gap-2 fs-5">
                        <i class="bi bi-whatsapp fs-4"></i>
                        <span>Consultar Disponibilidade no WhatsApp</span>
                    </a>
                </div>

                <!-- Caixa Informativa: Como Funciona o Expositor -->
                <div class="p-3 rounded-3 mb-4" style="background-color: var(--brand-accent-light); border: 1px solid rgba(27, 199, 54, 0.2);">
                    <div class="d-flex gap-2">
                        <i class="bi bi-info-circle-fill text-success fs-5 flex-shrink-0 mt-1"></i>
                        <div class="small text-dark">
                            <strong>Como funciona o Expositor Digital:</strong><br>
                            Este catálogo online é uma vitrine dos itens que compõem o acervo da Banca Santa Rita. <strong>Não realizamos cobrança ou venda direta pelo site.</strong>
                            Para confirmar a disponibilidade imediata ou reservar a sua edição, clique no botão do WhatsApp acima para falar diretamente com nosso atendente.
                        </div>
                    </div>
                </div>

                <!-- Descrição do Item -->
                <div class="mb-4">
                    <h2 class="h5 fw-bold text-dark font-heading mb-3">Detalhes do Item</h2>
                    <div class="text-secondary" style="line-height: 1.8;">
                        {!! nl2br(e($item->descricao)) !!}
                    </div>
                </div>

                <!-- Ações Complementares -->
                <div class="d-flex flex-wrap gap-2 pt-3 border-top">
                    <a href="{{ route('site.products.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Voltar ao Catálogo
                    </a>
                    <a href="{{ route('site.contact') }}" class="btn btn-outline-brand btn-sm">
                        <i class="bi bi-geo-alt me-1"></i> Onde nos Encontrar
                    </a>
                </div>
            </div>
        </div>

        <!-- Itens Relacionados -->
        @if($relacionados->isNotEmpty())
            <div class="pt-5 border-top">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="text-uppercase small fw-bold" style="color: var(--brand-institutional);">Mais Opções</span>
                        <h2 class="h4 fw-bold mb-0">Outros Itens em {{ $item->category->nome }}</h2>
                    </div>
                    <a href="{{ route('site.products.index', ['categoria_id' => $item->categoria_id]) }}" class="btn btn-outline-secondary btn-sm">
                        Ver Categoria
                    </a>
                </div>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                    @foreach($relacionados as $rel)
                        <div class="col">
                            <div class="card card-brand">
                                <div class="card-img-wrapper aspect-product">
                                    <img src="{{ $rel->image_url }}" alt="{{ $rel->nome }}" loading="lazy">
                                </div>
                                <div class="card-body">
                                    <h3 class="h6 fw-bold mb-1 text-truncate">
                                        <a href="{{ route('site.products.show', $rel->slug) }}" class="text-decoration-none text-dark">
                                            {{ $rel->nome }}
                                        </a>
                                    </h3>
                                    <span class="fw-bold small mb-2 d-block" style="color: var(--brand-institutional);">
                                        {{ $rel->formatted_price }}
                                    </span>
                                    <a href="{{ route('site.products.show', $rel->slug) }}" class="btn btn-sm btn-outline-brand w-100">
                                        Ver Item
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
