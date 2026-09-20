@extends('layouts.app')

@section('title', ($config->nome_banca ?: 'Banca Santa Rita') . ' | Expositor Digital & Mídias')
@section('meta_description', $config->descricao ?: 'Conheça o expositor digital e catálogo informativo da Banca Santa Rita. Fotos, vídeos, quadrinhos, revistas, jornais e novidades diárias.')

@section('content')
<!-- 1. Hero Section Modernizado -->
<section class="hero-section hero-gradient-mesh py-5 position-relative text-white overflow-hidden">
    <div class="container py-lg-4 position-relative z-1">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-12 col-lg-7">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" style="background-color: rgba(27, 199, 54, 0.15); border: 1px solid rgba(27, 199, 54, 0.35);">
                    <span class="status-indicator-dot"></span>
                    <span class="small fw-semibold text-white">Aberto Hoje • Expositor Digital & Reservas</span>
                </div>
                <h1 class="display-4 fw-extrabold text-white mb-3" style="letter-spacing: -0.5px;">
                    {{ $config->nome_banca ?: 'Banca Santa Rita' }}
                </h1>
                <p class="lead text-white-50 mb-4" style="max-width: 580px; line-height: 1.6;">
                    {{ $config->descricao ?: 'Tradição, cultura, revistas, jornais, colecionáveis e os principais lançamentos editoriais bem no coração do bairro. Conheça nossos itens em exposição!' }}
                </p>

                <!-- Barra de Busca Rápida Integrada no Hero -->
                <form action="{{ route('site.products.index') }}" method="GET" class="hero-search-box d-flex align-items-center mb-3" style="max-width: 540px;">
                    <i class="bi bi-search text-white-50 ms-2 me-2"></i>
                    <input type="text" name="search" class="form-control text-white" placeholder="Buscar jornal, gibi, mangá, revista, autor..." aria-label="Buscar itens no expositor da banca">
                    <button type="submit" class="btn btn-brand-accent rounded-pill px-3 py-2 btn-sm fw-semibold">
                        <span>Buscar</span>
                    </button>
                </form>

                <!-- Tags de Navegação Rápida -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                    <span class="small text-white-50">Populares:</span>
                    <a href="{{ route('site.products.index') }}?search=jornal" class="hero-category-tag">🗞️ Jornais</a>
                    <a href="{{ route('site.products.index') }}?search=manga" class="hero-category-tag">🦸 Mangás & HQs</a>
                    <a href="{{ route('site.products.index') }}?search=revista" class="hero-category-tag">📰 Revistas</a>
                    <a href="{{ route('site.products.index') }}?search=livro" class="hero-category-tag">📚 Livros</a>
                </div>

                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ route('site.products.index') }}" class="btn btn-brand-accent px-4 py-3 shadow">
                        <i class="bi bi-grid-3x3-gap-fill fs-5"></i>
                        <span>Ver Itens em Exposição</span>
                    </a>
                    <a href="{{ route('site.contents.index') }}" class="btn btn-outline-light px-4 py-3">
                        <i class="bi bi-camera-video fs-5"></i>
                        <span>Fotos & Vídeos</span>
                    </a>
                </div>

                <!-- Barra de Estatísticas da Banca -->
                <div class="row pt-4 border-top border-light border-opacity-10 g-3" style="max-width: 540px;">
                    <div class="col-4">
                        <div class="h4 fw-bold text-white mb-0" style="color: var(--brand-accent) !important;">+500</div>
                        <div class="small text-white-50" style="font-size: 0.78rem;">Títulos no Acervo</div>
                    </div>
                    <div class="col-4">
                        <div class="h4 fw-bold text-white mb-0" style="color: var(--brand-accent) !important;">Diário</div>
                        <div class="small text-white-50" style="font-size: 0.78rem;">Lançamentos Frescos</div>
                    </div>
                    <div class="col-4">
                        <div class="h4 fw-bold text-white mb-0" style="color: var(--brand-accent) !important;">WhatsApp</div>
                        <div class="small text-white-50" style="font-size: 0.78rem;">Reservas Imediatas</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                @if($heroContent)
                    <div class="card bg-white text-dark rounded-4 shadow-lg border-0 overflow-hidden">
                        <div class="position-relative">
                            <img src="{{ $heroContent->image_url }}" alt="{{ $heroContent->titulo }}" class="w-100" style="height: 240px; object-fit: cover;">
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge badge-featured">Destaque</span>
                            </div>
                            @if($heroContent->is_video)
                                <button type="button" 
                                        class="position-absolute top-50 start-50 translate-middle btn btn-light rounded-circle shadow-lg d-flex align-items-center justify-content-center" 
                                        style="width: 54px; height: 54px;" 
                                        onclick="openVideoModal('{{ $heroContent->video_embed_url }}', '{{ $heroContent->video_file_url }}', '{{ addslashes($heroContent->titulo) }}')"
                                        aria-label="Assistir vídeo">
                                    <i class="bi bi-play-fill text-success fs-3 ms-1"></i>
                                </button>
                            @else
                                <button type="button" 
                                        class="position-absolute top-50 start-50 translate-middle btn btn-light rounded-circle shadow-lg d-flex align-items-center justify-content-center" 
                                        style="width: 54px; height: 54px;" 
                                        onclick="openLightbox('{{ $heroContent->image_url }}', '{{ addslashes($heroContent->titulo) }}', '{{ addslashes($heroContent->descricao) }}')"
                                        aria-label="Ver foto em alta resolução">
                                    <i class="bi bi-zoom-in text-success fs-4"></i>
                                </button>
                            @endif
                        </div>
                        <div class="p-3 p-md-4">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge-category">{{ $heroContent->category->nome }}</span>
                                <span class="small text-muted"><i class="bi bi-calendar3 me-1"></i> {{ $heroContent->formatted_date }}</span>
                            </div>
                            <h2 class="h5 fw-bold mb-2">
                                <a href="{{ route('site.contents.show', $heroContent->slug) }}" class="text-decoration-none text-dark hover-green">
                                    {{ $heroContent->titulo }}
                                </a>
                            </h2>
                            <p class="small text-muted mb-0 text-truncate-2">
                                {{ Str::limit($heroContent->descricao, 100) }}
                            </p>
                        </div>
                    </div>
                @else
                    <div class="card bg-white bg-opacity-10 border-light border-opacity-25 rounded-4 p-4 text-center">
                        <i class="bi bi-newspaper display-3 text-success mb-3"></i>
                        <h2 class="h5 fw-bold text-white mb-2">Bem-vindo à Banca Santa Rita</h2>
                        <p class="small text-white-50 mb-0">Confira abaixo nossos destaques diários, fotos, vídeos e itens do catálogo.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- 2. Carrossel de Destaques (Swiper.js) -->
@if($destaques->isNotEmpty())
<section class="py-5 bg-light border-bottom">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
            <div>
                <span class="text-uppercase small fw-bold" style="color: var(--brand-accent);">Mídias em Foco</span>
                <h2 class="h3 fw-bold mb-0">Destaques da Banca</h2>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center swiper-prev-destaques" style="width: 36px; height: 36px;" aria-label="Anterior">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center swiper-next-destaques" style="width: 36px; height: 36px;" aria-label="Próximo">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        <div class="swiper" id="destaquesSwiper">
            <div class="swiper-wrapper py-2">
                @foreach($destaques as $destaque)
                    <div class="swiper-slide">
                        <div class="card card-brand">
                            <div class="card-img-wrapper">
                                <img src="{{ $destaque->image_url }}" alt="{{ $destaque->titulo }}" loading="lazy">
                                <span class="position-absolute top-0 start-0 m-2 badge {{ $destaque->is_video ? 'bg-danger' : 'bg-primary' }} text-white">
                                    <i class="bi {{ $destaque->is_video ? 'bi-play-btn-fill' : 'bi-camera-fill' }} me-1"></i>
                                    {{ $destaque->tipo_label }}
                                </span>
                                @if($destaque->is_video)
                                    <button type="button" 
                                            class="position-absolute top-50 start-50 translate-middle btn btn-light rounded-circle shadow d-flex align-items-center justify-content-center"
                                            style="width: 48px; height: 48px;"
                                            onclick="openVideoModal('{{ $destaque->video_embed_url }}', '{{ $destaque->video_file_url }}', '{{ addslashes($destaque->titulo) }}')"
                                            aria-label="Assistir vídeo">
                                        <i class="bi bi-play-fill text-success fs-4 ms-1"></i>
                                    </button>
                                @else
                                    <button type="button" 
                                            class="position-absolute top-50 start-50 translate-middle btn btn-light rounded-circle shadow d-flex align-items-center justify-content-center"
                                            style="width: 44px; height: 44px;"
                                            onclick="openLightbox('{{ $destaque->image_url }}', '{{ addslashes($destaque->titulo) }}', '{{ addslashes($destaque->descricao) }}')"
                                            aria-label="Ampliar foto">
                                        <i class="bi bi-arrows-fullscreen text-dark fs-6"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge-category">{{ $destaque->category->nome }}</span>
                                    <small class="text-muted">{{ $destaque->formatted_date }}</small>
                                </div>
                                <h3 class="h6 fw-bold mb-2">
                                    <a href="{{ route('site.contents.show', $destaque->slug) }}" class="text-decoration-none text-dark">
                                        {{ $destaque->titulo }}
                                    </a>
                                </h3>
                                <p class="small text-muted mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $destaque->descricao }}
                                </p>
                                <a href="{{ route('site.contents.show', $destaque->slug) }}" class="btn btn-sm btn-outline-brand w-100">
                                    <span>Ver Conteúdo</span>
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination position-relative mt-4"></div>
        </div>
    </div>
</section>
@endif

<!-- 3. Seção Itens em Exposição (Catálogo Informativo) -->
<section class="py-5">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
            <div>
                <span class="text-uppercase small fw-bold" style="color: var(--brand-institutional);">Catálogo Aberto</span>
                <h2 class="h3 fw-bold mb-0">Itens em Exposição na Banca</h2>
            </div>
            <a href="{{ route('site.products.index') }}" class="btn btn-outline-brand btn-sm">
                <span>Ver Catálogo Completo</span>
                <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        @if($itens->isEmpty())
            <div class="text-center py-5 bg-light rounded-4">
                <i class="bi bi-journal-album text-muted display-4"></i>
                <p class="text-muted mt-3 mb-0">Nenhum item em exposição no momento. Volte em breve!</p>
            </div>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
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
                                <h3 class="h6 fw-bold mb-1" style="min-height: 2.6rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <a href="{{ route('site.products.show', $item->slug) }}" class="text-decoration-none text-dark">
                                        {{ $item->nome }}
                                    </a>
                                </h3>
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
        @endif
    </div>
</section>

<!-- 4. Seção Fotos Recentes -->
@if($fotos->isNotEmpty())
<section class="py-5 bg-light border-top">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
            <div>
                <span class="text-uppercase small fw-bold text-muted">Galeria de Imagens</span>
                <h2 class="h3 fw-bold mb-0">Fotos Recentes da Banca</h2>
            </div>
            <a href="{{ route('site.contents.index', ['tipo' => 'foto']) }}" class="btn btn-sm btn-outline-secondary">
                <span>Ver Todas as Fotos</span>
                <i class="bi bi-images ms-1"></i>
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            @foreach($fotos as $foto)
                <div class="col">
                    <div class="card card-brand">
                        <div class="card-img-wrapper cursor-pointer" onclick="openLightbox('{{ $foto->image_url }}', '{{ addslashes($foto->titulo) }}', '{{ addslashes($foto->descricao) }}')">
                            <img src="{{ $foto->image_url }}" alt="{{ $foto->titulo }}" loading="lazy">
                            <div class="position-absolute inset-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 hover-opacity-100 transition-all">
                                <i class="bi bi-arrows-fullscreen text-white fs-3"></i>
                            </div>
                            <span class="position-absolute top-0 start-0 m-2 badge bg-dark bg-opacity-75 text-white">
                                {{ $foto->category->nome }}
                            </span>
                        </div>
                        <div class="card-body">
                            <h3 class="h6 fw-bold mb-1">
                                <a href="{{ route('site.contents.show', $foto->slug) }}" class="text-decoration-none text-dark">
                                    {{ $foto->titulo }}
                                </a>
                            </h3>
                            <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> {{ $foto->formatted_date }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- 5. Seção Vídeos Recentes -->
@if($videos->isNotEmpty())
<section class="py-5 border-top">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
            <div>
                <span class="text-uppercase small fw-bold text-danger">Mídia em Vídeo</span>
                <h2 class="h3 fw-bold mb-0">Vídeos da Banca</h2>
            </div>
            <a href="{{ route('site.contents.index', ['tipo' => 'video']) }}" class="btn btn-sm btn-outline-secondary">
                <span>Ver Todos os Vídeos</span>
                <i class="bi bi-play-circle ms-1"></i>
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            @foreach($videos as $video)
                <div class="col">
                    <div class="card card-brand">
                        <div class="card-img-wrapper cursor-pointer" onclick="openVideoModal('{{ $video->video_embed_url }}', '{{ $video->video_file_url }}', '{{ addslashes($video->titulo) }}')">
                            <img src="{{ $video->image_url }}" alt="{{ $video->titulo }}" loading="lazy">
                            <div class="position-absolute top-50 start-50 translate-middle btn btn-danger rounded-circle shadow d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="bi bi-play-fill text-white fs-3 ms-1"></i>
                            </div>
                            <span class="position-absolute top-0 start-0 m-2 badge bg-danger text-white">
                                Vídeo
                            </span>
                        </div>
                        <div class="card-body">
                            <h3 class="h6 fw-bold mb-1" style="min-height: 2.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <a href="{{ route('site.contents.show', $video->slug) }}" class="text-decoration-none text-dark">
                                    {{ $video->titulo }}
                                </a>
                            </h3>
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <span class="badge-category">{{ $video->category->nome }}</span>
                                <small class="text-muted">{{ $video->formatted_date }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- 6. Banner Institucional & Contato Rápido -->
<section class="py-5 text-white" style="background-color: var(--brand-institutional);">
    <div class="container text-center py-3">
        <h2 class="display-6 fw-bold mb-3">Passe na Banca Santa Rita!</h2>
        <p class="lead text-white-50 mx-auto mb-4" style="max-width: 650px;">
            Venha bater um papo, conferir os jornais do dia, novidades em quadrinhos e revistas de variedades. Estamos sempre prontos para atender você!
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ $config->getWhatsappUrl() }}" target="_blank" class="btn btn-brand-accent px-4 py-3 shadow">
                <i class="bi bi-whatsapp fs-5"></i>
                <span>Falar Diretamente pelo WhatsApp</span>
            </a>
            <a href="{{ route('site.contact') }}" class="btn btn-outline-light px-4 py-3">
                <i class="bi bi-geo-alt fs-5"></i>
                <span>Ver Endereço e Horários</span>
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swiper !== 'undefined' && document.getElementById('destaquesSwiper')) {
            new Swiper('#destaquesSwiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: false,
                autoplay: {
                    delay: 4500,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-next-destaques',
                    prevEl: '.swiper-prev-destaques',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 24,
                    },
                    1200: {
                        slidesPerView: 4,
                        spaceBetween: 24,
                    }
                }
            });
        }
    });
</script>
@endpush
