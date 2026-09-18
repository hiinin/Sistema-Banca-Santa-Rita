@extends('layouts.app')

@section('title', $content->titulo . ' | ' . ($config->nome_banca ?: 'Banca Santa Rita'))
@section('meta_description', Str::limit(strip_tags($content->descricao), 150))
@section('og_image', $content->image_url)

@section('content')
<!-- Header & Breadcrumb -->
<div class="py-4 bg-light border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Início</a></li>
                <li class="breadcrumb-item"><a href="{{ route('site.contents.index') }}" class="text-decoration-none text-muted">Fotos & Vídeos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('site.contents.index', ['categoria_id' => $content->categoria_id]) }}" class="text-decoration-none text-muted">{{ $content->category->nome }}</a></li>
                <li class="breadcrumb-item active text-truncate" style="max-width: 250px;" aria-current="page">{{ $content->titulo }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Coluna Principal do Conteúdo -->
            <div class="col-12 col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge {{ $content->is_video ? 'bg-danger' : 'bg-primary' }} text-white px-3 py-2">
                        <i class="bi {{ $content->is_video ? 'bi-play-btn-fill' : 'bi-camera-fill' }} me-1"></i>
                        {{ $content->tipo_label }}
                    </span>
                    <span class="badge-category">{{ $content->category->nome }}</span>
                    @if($content->is_featured)
                        <span class="badge badge-featured">Destaque</span>
                    @endif
                    <span class="small text-muted ms-auto">
                        <i class="bi bi-calendar3 me-1"></i> {{ $content->formatted_date }}
                    </span>
                </div>

                <h1 class="h2 fw-bold mb-4" style="color: var(--brand-dark);">
                    {{ $content->titulo }}
                </h1>

                <!-- Mídia Principal (Vídeo ou Foto) -->
                <div class="mb-4 rounded-4 overflow-hidden shadow-sm border bg-dark">
                    @if($content->is_video)
                        <div class="ratio ratio-16x9">
                            @if($content->video_embed_url)
                                <iframe src="{{ $content->video_embed_url }}" title="{{ $content->titulo }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            @elseif($content->video_file_url)
                                <video controls class="w-100 h-100" poster="{{ $content->image_url }}">
                                    <source src="{{ $content->video_file_url }}" type="video/mp4">
                                    Seu navegador não suporta a tag de vídeo.
                                </video>
                            @else
                                <div class="d-flex align-items-center justify-content-center text-white">
                                    <p class="mb-0">Vídeo indisponível</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="position-relative cursor-pointer" onclick="openLightbox('{{ $content->image_url }}', '{{ addslashes($content->titulo) }}', '{{ addslashes($content->descricao) }}')">
                            <img src="{{ $content->image_url }}" alt="{{ $content->titulo }}" class="w-100" style="max-height: 520px; object-fit: contain; background-color: #0f1611;">
                            <div class="position-absolute bottom-0 end-0 m-3 btn btn-light btn-sm shadow d-flex align-items-center gap-1">
                                <i class="bi bi-zoom-in"></i>
                                <span>Ampliar</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Descrição Textual do Conteúdo -->
                <div class="bg-white p-4 rounded-4 border shadow-sm mb-4">
                    <h2 class="h5 fw-bold mb-3 font-heading" style="color: var(--brand-dark);">Descrição</h2>
                    <div class="text-secondary lead fs-6" style="line-height: 1.8;">
                        {!! nl2br(e($content->descricao)) !!}
                    </div>
                </div>

                <!-- Compartilhamento & Ações -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 bg-light rounded-3 border">
                    <span class="small fw-semibold text-muted">Gostou deste conteúdo? Compartilhe:</span>
                    <div class="d-flex gap-2">
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($content->titulo . ' - Confira na Banca Santa Rita: ' . url()->current()) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-success d-flex align-items-center gap-1"
                           style="background-color: #25D366; border-color: #25D366;">
                            <i class="bi bi-whatsapp"></i>
                            <span>WhatsApp</span>
                        </a>
                        <a href="{{ route('site.contents.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Voltar à Galeria
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar / Conteúdos Relacionados -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background-color: var(--brand-institutional-light);">
                    <h3 class="h6 fw-bold mb-3 text-dark">
                        <i class="bi bi-info-circle me-1 text-success"></i> Visite Nossa Banca
                    </h3>
                    <p class="small text-muted mb-3">
                        Venha conferir nossas novidades pessoalmente ou consulte a disponibilidade de edições pelo nosso WhatsApp.
                    </p>
                    <a href="{{ $config->getWhatsappUrl("Olá! Vi o conteúdo '{$content->titulo}' no site da Banca Santa Rita e gostaria de mais informações.") }}" 
                       target="_blank" 
                       class="btn btn-brand-accent btn-sm w-100">
                        <i class="bi bi-whatsapp me-1"></i> Falar com Atendente
                    </a>
                </div>

                @if($relacionados->isNotEmpty())
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h3 class="h6 fw-bold mb-3 text-dark font-heading">
                            Mais em {{ $content->category->nome }}
                        </h3>
                        <div class="d-flex flex-column gap-3">
                            @foreach($relacionados as $rel)
                                <a href="{{ route('site.contents.show', $rel->slug) }}" class="text-decoration-none group">
                                    <div class="d-flex gap-3 align-items-center">
                                        <div class="rounded-3 overflow-hidden position-relative flex-shrink-0" style="width: 72px; height: 56px;">
                                            <img src="{{ $rel->image_url }}" alt="{{ $rel->titulo }}" class="w-100 h-100" style="object-fit: cover;">
                                            @if($rel->is_video)
                                                <div class="position-absolute top-50 start-50 translate-middle badge bg-danger p-1 rounded-circle">
                                                    <i class="bi bi-play-fill" style="font-size: 0.6rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="overflow-hidden">
                                            <h4 class="small fw-semibold text-dark mb-1 text-truncate">{{ $rel->titulo }}</h4>
                                            <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-calendar3 me-1"></i> {{ $rel->formatted_date }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
