@extends('layouts.app')

@section('title', 'Sobre Nós | ' . ($config->nome_banca ?: 'Banca Santa Rita'))
@section('meta_description', 'Conheça a história da Banca Santa Rita, seu ponto de encontro com a informação, revistas, quadrinhos, livros e conveniência.')

@section('content')
<!-- Header da Página -->
<div class="py-5 text-white" style="background: linear-gradient(135deg, var(--brand-dark) 0%, #1e3322 100%);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Início</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Sobre Nós</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold mb-2">Sobre a {{ $config->nome_banca ?: 'Banca Santa Rita' }}</h1>
        <p class="lead text-white-50 mb-0">Tradição, convivência e amor pela leitura no coração do nosso bairro.</p>
    </div>
</div>

<!-- Conteúdo Institucional -->
<div class="py-5">
    <div class="container">
        <div class="row align-items-center g-5 mb-5">
            <div class="col-12 col-lg-6">
                <span class="text-uppercase small fw-bold" style="color: var(--brand-accent);">Nossa História</span>
                <h2 class="h2 fw-bold mb-4">Mais do que uma banca, um ponto de encontro cultural</h2>
                <div class="text-muted" style="line-height: 1.8;">
                    @if($config->sobre)
                        {!! nl2br(e($config->sobre)) !!}
                    @else
                        <p>
                            Fundada com a missão de levar informação fresca, cultura e entretenimento para a comunidade local, a <strong>{{ $config->nome_banca ?: 'Banca Santa Rita' }}</strong> consolidou-se como uma referência em atendimento acolhedor e variedade editorial.
                        </p>
                        <p>
                            Aqui você encontra os principais jornais diários do país, revistas de atualidades, lançamentos em mangás, gibis clássicos, livros dos mais diversos gêneros e colecionáveis para entusiastas de todas as idades.
                        </p>
                        <p>
                            Nosso expositor digital foi criado especialmente para aproximar ainda mais nossos clientes e leitores do nosso acervo, permitindo que você acompanhe o que chega às prateleiras sem sair de casa!
                        </p>
                    @endif
                </div>

                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="{{ route('site.products.index') }}" class="btn btn-brand-institutional">
                        <i class="bi bi-grid-3x3-gap me-1"></i> Explorar Catálogo
                    </a>
                    <a href="{{ $config->getWhatsappUrl() }}" target="_blank" class="btn btn-brand-accent">
                        <i class="bi bi-whatsapp me-1"></i> Falar Conosco
                    </a>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="position-relative">
                    <div class="rounded-4 overflow-hidden shadow-lg border">
                        <img src="{{ $config->logo_url }}" alt="{{ $config->nome_banca }}" class="w-100 p-5 bg-light" style="max-height: 380px; object-fit: contain;">
                    </div>
                    <div class="position-absolute bottom-0 end-0 bg-success text-white p-3 rounded-3 m-3 shadow d-none d-sm-flex align-items-center gap-2" style="background-color: var(--brand-accent) !important;">
                        <i class="bi bi-heart-fill fs-4"></i>
                        <div>
                            <div class="fw-bold">Atendimento Amigo</div>
                            <div class="small">Sempre perto de você</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pilares e Valores -->
        <div class="row g-4 py-4 border-top border-bottom my-5">
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center p-3 shadow-sm" style="background-color: var(--brand-accent-light); color: var(--brand-institutional); min-width: 54px; height: 54px;">
                        <i class="bi bi-newspaper fs-4"></i>
                    </div>
                    <div>
                        <h3 class="h6 fw-bold mb-1">Informação Atualizada</h3>
                        <p class="small text-muted mb-0">Recebemos jornais e periódicos diariamente nas primeiras horas da manhã.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center p-3 shadow-sm" style="background-color: var(--brand-accent-light); color: var(--brand-institutional); min-width: 54px; height: 54px;">
                        <i class="bi bi-collection fs-4"></i>
                    </div>
                    <div>
                        <h3 class="h6 fw-bold mb-1">Acervo e Coleções</h3>
                        <p class="small text-muted mb-0">Quadrinhos, figurinhas, álbuns e livros selecionados para colecionadores e leitores.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center p-3 shadow-sm" style="background-color: var(--brand-accent-light); color: var(--brand-institutional); min-width: 54px; height: 54px;">
                        <i class="bi bi-whatsapp fs-4"></i>
                    </div>
                    <div>
                        <h3 class="h6 fw-bold mb-1">Contato Ágil</h3>
                        <p class="small text-muted mb-0">Consulte a chegada de edições diretamente via WhatsApp com nosso atendente.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fotos do Espaço da Banca -->
        @if($fotosBanca->isNotEmpty())
            <div class="mt-5">
                <div class="text-center max-w-700 mx-auto mb-4">
                    <span class="text-uppercase small fw-bold" style="color: var(--brand-accent);">Nosso Cantinho</span>
                    <h2 class="h3 fw-bold">Imagens do Espaço e do Cotidiano</h2>
                    <p class="text-muted small">Clique em qualquer foto para ampliar e visualizar os detalhes.</p>
                </div>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
                    @foreach($fotosBanca as $foto)
                        <div class="col">
                            <div class="card card-brand cursor-pointer" onclick="openLightbox('{{ $foto->image_url }}', '{{ addslashes($foto->titulo) }}', '{{ addslashes($foto->descricao) }}')">
                                <div class="card-img-wrapper">
                                    <img src="{{ $foto->image_url }}" alt="{{ $foto->titulo }}" loading="lazy">
                                    <div class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-75">
                                        <i class="bi bi-zoom-in"></i>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <h3 class="h6 fw-bold mb-1 text-truncate">{{ $foto->titulo }}</h3>
                                    <small class="text-muted">{{ $foto->category->nome ?? 'Espaço' }}</small>
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
