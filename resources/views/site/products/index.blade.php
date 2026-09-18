@extends('layouts.app')

@section('title', 'Itens em Exposição | ' . ($config->nome_banca ?: 'Banca Santa Rita'))
@section('meta_description', 'Consulte o catálogo de itens em exposição na Banca Santa Rita. Revistas, jornais, gibis, livros e colecionáveis disponíveis.')

@section('content')
<!-- Header da Página -->
<div class="py-5 text-white" style="background: linear-gradient(135deg, var(--brand-dark) 0%, #1e3322 100%);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Início</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Itens em Exposição</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold mb-2">Catálogo & Expositor de Itens</h1>
        <p class="lead text-white-50 mb-0">Revistas, periódicos, coleções, livros e quadrinhos em destaque no nosso acervo.</p>
    </div>
</div>

<!-- Aviso de Catálogo Informativo -->
<div class="py-3 bg-white border-bottom">
    <div class="container">
        <div class="d-flex align-items-center gap-3 p-2 px-3 rounded-3" style="background-color: var(--brand-accent-light); border: 1px solid rgba(27, 199, 54, 0.25);">
            <i class="bi bi-info-circle-fill text-success fs-5"></i>
            <span class="small text-dark">
                <strong>Expositor Digital Informativo:</strong> Os valores exibidos são preços de capa ou sugeridos. Para consultar disponibilidade em tempo real ou reservar um exemplar, fale com nosso atendente via WhatsApp.
            </span>
        </div>
    </div>
</div>

<!-- Listagem e Filtros -->
<div class="py-5 bg-light">
    <div class="container">
        <!-- Barra de Filtros -->
        <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4">
            <form action="{{ route('site.products.index') }}" method="GET" class="row g-3 align-items-center">
                <!-- Filtro por Categoria -->
                <div class="col-12 col-md-5">
                    <label for="catSelect" class="form-label small fw-semibold text-muted mb-1">Filtrar por Categoria</label>
                    <select name="categoria_id" id="catSelect" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas as Categorias</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (string)request('categoria_id') === (string)$category->id ? 'selected' : '' }}>
                                {{ $category->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Busca por Nome do Item -->
                <div class="col-12 col-md-7">
                    <label for="searchProd" class="form-label small fw-semibold text-muted mb-1">Pesquisar Item</label>
                    <div class="input-group">
                        <input type="text" name="search" id="searchProd" class="form-control" placeholder="Título da revista, gibi, livro..." value="{{ request('search') }}">
                        <button class="btn btn-brand-institutional" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(request()->hasAny(['categoria_id', 'search']))
                            <a href="{{ route('site.products.index') }}" class="btn btn-outline-secondary" title="Limpar Filtros">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Grade de Produtos / Itens -->
        @if($itens->isEmpty())
            <div class="text-center py-5 bg-white rounded-4 shadow-sm my-4">
                <i class="bi bi-journal-x text-muted display-3 mb-3"></i>
                <h2 class="h5 fw-bold text-dark">Nenhum item encontrado</h2>
                <p class="text-muted small mb-3">Não encontramos publicações ou itens correspondentes à sua busca.</p>
                <a href="{{ route('site.products.index') }}" class="btn btn-sm btn-outline-brand">
                    <i class="bi bi-arrow-clockwise me-1"></i> Ver Catálogo Completo
                </a>
            </div>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
                @foreach($itens as $item)
                    <div class="col">
                        <div class="card card-brand">
                            <div class="card-img-wrapper aspect-product">
                                <img src="{{ $item->image_url }}" alt="{{ $item->nome }}" loading="lazy">
                                <span class="position-absolute top-0 start-0 m-2 badge bg-dark bg-opacity-75 text-white">
                                    {{ $item->category->nome }}
                                </span>
                            </div>
                            <div class="card-body">
                                <h2 class="h6 fw-bold mb-1" style="min-height: 2.6rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <a href="{{ route('site.products.show', $item->slug) }}" class="text-decoration-none text-dark">
                                        {{ $item->nome }}
                                    </a>
                                </h2>
                                <div class="mb-3">
                                    <span class="small text-muted d-block" style="font-size: 0.75rem;">Preço de capa / sugerido:</span>
                                    <span class="fs-5 fw-bold" style="color: var(--brand-institutional);">
                                        {{ $item->formatted_price }}
                                    </span>
                                </div>
                                <div class="mt-auto d-grid gap-2">
                                    <a href="{{ route('site.products.show', $item->slug) }}" class="btn btn-sm btn-outline-brand">
                                        <i class="bi bi-info-circle me-1"></i> Ver Detalhes
                                    </a>
                                    <a href="{{ $config->getWhatsappUrl("Olá! Vi o item '{$item->nome}' no catálogo do site da Banca Santa Rita e gostaria de consultar se está disponível.") }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-brand-accent">
                                        <i class="bi bi-whatsapp me-1"></i> Consultar WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginação -->
            <div class="d-flex justify-content-center">
                {{ $itens->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
