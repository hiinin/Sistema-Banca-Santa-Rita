@extends('layouts.app')

@section('title', 'Fotos & Vídeos | ' . ($config->nome_banca ?: 'Banca Santa Rita'))
@section('meta_description', 'Acompanhe a galeria de fotos e vídeos da Banca Santa Rita. Lançamentos, novidades diárias, ambiente e destaques em primeira mão.')

@section('content')
<!-- Header da Página -->
<div class="py-5 text-white" style="background: linear-gradient(135deg, var(--brand-dark) 0%, #1e3322 100%);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Início</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Fotos & Vídeos</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold mb-2">Mídias & Publicações</h1>
        <p class="lead text-white-50 mb-0">Galeria visual com fotos de novidades, bastidores e vídeos dos nossos lançamentos.</p>
    </div>
</div>

<!-- Filtros e Listagem -->
<div class="py-5 bg-light">
    <div class="container">
        <!-- Barra de Filtros -->
        <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4">
            <form action="{{ route('site.contents.index') }}" method="GET" class="row g-3 align-items-center">
                <!-- Filtro por Tipo -->
                <div class="col-12 col-md-3">
                    <label for="tipoSelect" class="form-label small fw-semibold text-muted mb-1">Tipo de Mídia</label>
                    <select name="tipo" id="tipoSelect" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos os Tipos (Fotos & Vídeos)</option>
                        <option value="foto" {{ request('tipo') === 'foto' ? 'selected' : '' }}>Apenas Fotos</option>
                        <option value="video" {{ request('tipo') === 'video' ? 'selected' : '' }}>Apenas Vídeos</option>
                    </select>
                </div>

                <!-- Filtro por Categoria -->
                <div class="col-12 col-md-4">
                    <label for="categoriaSelect" class="form-label small fw-semibold text-muted mb-1">Categoria</label>
                    <select name="categoria_id" id="categoriaSelect" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas as Categorias</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (string)request('categoria_id') === (string)$category->id ? 'selected' : '' }}>
                                {{ $category->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Busca por Palavra-chave -->
                <div class="col-12 col-md-5">
                    <label for="searchInput" class="form-label small fw-semibold text-muted mb-1">Pesquisar</label>
                    <div class="input-group">
                        <input type="text" name="search" id="searchInput" class="form-control" placeholder="Título ou descrição..." value="{{ request('search') }}">
                        <button class="btn btn-brand-institutional" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(request()->hasAny(['tipo', 'categoria_id', 'search']))
                            <a href="{{ route('site.contents.index') }}" class="btn btn-outline-secondary" title="Limpar Filtros">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Grade de Conteúdos -->
        @if($contents->isEmpty())
            <div class="text-center py-5 bg-white rounded-4 shadow-sm my-4">
                <i class="bi bi-camera-video text-muted display-3 mb-3"></i>
                <h2 class="h5 fw-bold text-dark">Nenhum conteúdo encontrado</h2>
                <p class="text-muted small mb-3">Não encontramos fotos ou vídeos correspondentes aos filtros aplicados.</p>
                <a href="{{ route('site.contents.index') }}" class="btn btn-sm btn-outline-brand">
                    <i class="bi bi-arrow-clockwise me-1"></i> Ver Todas as Mídias
                </a>
            </div>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 mb-5">
                @foreach($contents as $content)
                    <div class="col">
                        <div class="card card-brand">
                            <div class="card-img-wrapper">
                                <img src="{{ $content->image_url }}" alt="{{ $content->titulo }}" loading="lazy">
                                <span class="position-absolute top-0 start-0 m-2 badge {{ $content->is_video ? 'bg-danger' : 'bg-primary' }} text-white">
                                    <i class="bi {{ $content->is_video ? 'bi-play-btn-fill' : 'bi-camera-fill' }} me-1"></i>
                                    {{ $content->tipo_label }}
                                </span>
                                @if($content->is_featured)
                                    <span class="position-absolute top-0 end-0 m-2 badge badge-featured">
                                        Destaque
                                    </span>
                                @endif

                                @if($content->is_video)
                                    <button type="button" 
                                            class="position-absolute top-50 start-50 translate-middle btn btn-danger rounded-circle shadow-lg d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 50px;"
                                            onclick="openVideoModal('{{ $content->video_embed_url }}', '{{ $content->video_file_url }}', '{{ addslashes($content->titulo) }}')"
                                            aria-label="Assistir ao vídeo">
                                        <i class="bi bi-play-fill text-white fs-3 ms-1"></i>
                                    </button>
                                @else
                                    <button type="button" 
                                            class="position-absolute top-50 start-50 translate-middle btn btn-light rounded-circle shadow-lg d-flex align-items-center justify-content-center"
                                            style="width: 46px; height: 46px;"
                                            onclick="openLightbox('{{ $content->image_url }}', '{{ addslashes($content->titulo) }}', '{{ addslashes($content->descricao) }}')"
                                            aria-label="Ampliar foto">
                                        <i class="bi bi-zoom-in text-dark fs-5"></i>
                                    </button>
                                @endif
                            </div>

                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge-category">{{ $content->category->nome }}</span>
                                    <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> {{ $content->formatted_date }}</small>
                                </div>
                                <h2 class="h6 fw-bold mb-2">
                                    <a href="{{ route('site.contents.show', $content->slug) }}" class="text-decoration-none text-dark">
                                        {{ $content->titulo }}
                                    </a>
                                </h2>
                                <p class="small text-muted mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $content->descricao }}
                                </p>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('site.contents.show', $content->slug) }}" class="btn btn-sm btn-outline-brand flex-grow-1">
                                        <span>Ver Detalhes</span>
                                        <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                    @if($content->is_video)
                                        <button type="button" 
                                                class="btn btn-sm btn-danger px-3"
                                                onclick="openVideoModal('{{ $content->video_embed_url }}', '{{ $content->video_file_url }}', '{{ addslashes($content->titulo) }}')"
                                                title="Assistir vídeo">
                                            <i class="bi bi-play-fill fs-6"></i>
                                        </button>
                                    @else
                                        <button type="button" 
                                                class="btn btn-sm btn-light border px-3"
                                                onclick="openLightbox('{{ $content->image_url }}', '{{ addslashes($content->titulo) }}', '{{ addslashes($content->descricao) }}')"
                                                title="Ampliar foto">
                                            <i class="bi bi-arrows-fullscreen fs-6"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginação -->
            <div class="d-flex justify-content-center">
                {{ $contents->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
