@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid p-0">
    <!-- Boas-vindas e Ações Rápidas -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Visão Geral da Banca</h2>
            <p class="text-muted mb-0 small">Monitore os conteúdos de mídia e itens em exposição no site público da {{ $bancaConfig->nome_banca }}.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.contents.create') }}" class="btn btn-brand-accent btn-sm shadow-sm">
                <i class="bi bi-plus-lg"></i> Novo Conteúdo
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-brand-institutional btn-sm shadow-sm">
                <i class="bi bi-bag-plus"></i> Novo Item
            </a>
        </div>
    </div>

    <!-- Cards de Métricas -->
    <div class="row g-3 mb-4">
        <!-- Total de Conteúdos -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-xs h-100 rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Total de Conteúdos</span>
                        <div class="rounded-2 p-2 bg-success bg-opacity-10 text-success">
                            <i class="bi bi-collection fs-5"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-dark mb-1">{{ $metrics['totalConteudos'] }}</h3>
                    <div class="d-flex align-items-center gap-1 text-muted small">
                        <i class="bi bi-check2-circle text-success"></i>
                        <span>Fotos e vídeos cadastrados</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteúdos Publicados -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-xs h-100 rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Publicados no Site</span>
                        <div class="rounded-2 p-2 bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-eye fs-5"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-primary mb-1">{{ $metrics['conteudosPublicados'] }}</h3>
                    <div class="d-flex align-items-center gap-1 text-muted small">
                        <span class="badge bg-success-subtle text-success">Ativos</span>
                        <span>Visíveis ao público</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteúdos em Rascunho -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-xs h-100 rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Em Rascunho</span>
                        <div class="rounded-2 p-2 bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-pencil-square fs-5"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-dark mb-1">{{ $metrics['conteudosRascunho'] }}</h3>
                    <div class="d-flex align-items-center gap-1 text-muted small">
                        <i class="bi bi-shield-lock"></i>
                        <span>Ocultos do site</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total de Fotos -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-xs h-100 rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Total de Fotos</span>
                        <div class="rounded-2 p-2 bg-info bg-opacity-10 text-info">
                            <i class="bi bi-camera fs-5"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-dark mb-1">{{ $metrics['totalFotos'] }}</h3>
                    <div class="d-flex align-items-center gap-1 text-muted small">
                        <i class="bi bi-images text-info"></i>
                        <span>Galeria e destaques</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total de Vídeos -->
        <div class="col-6 col-lg-4">
            <div class="card border-0 shadow-xs h-100 rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Total de Vídeos</span>
                        <div class="rounded-2 p-2 bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-play-btn fs-5"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-dark mb-1">{{ $metrics['totalVideos'] }}</h3>
                    <div class="d-flex align-items-center gap-1 text-muted small">
                        <i class="bi bi-youtube text-danger"></i>
                        <span>YouTube / Vimeo / Uploads</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Itens em Exposição -->
        <div class="col-6 col-lg-4">
            <div class="card border-0 shadow-xs h-100 rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Itens em Exposição</span>
                        <div class="rounded-2 p-2 bg-success bg-opacity-10 text-success">
                            <i class="bi bi-newspaper fs-5"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-dark mb-1">{{ $metrics['totalItens'] }}</h3>
                    <div class="d-flex align-items-center gap-1 text-muted small">
                        <i class="bi bi-bookmark-star text-success"></i>
                        <span>Jornais, Revistas e Livros</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total de Categorias -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-xs h-100 rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-semibold">Total de Categorias</span>
                        <div class="rounded-2 p-2 bg-secondary bg-opacity-10 text-secondary">
                            <i class="bi bi-folder2-open fs-5"></i>
                        </div>
                    </div>
                    <h3 class="h2 fw-bold text-dark mb-1">{{ $metrics['totalCategorias'] }}</h3>
                    <div class="d-flex align-items-center gap-1 text-muted small">
                        <i class="bi bi-tags"></i>
                        <span>Segmentação de conteúdos</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Tabela de Conteúdos Recentes -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-xs rounded-3">
                <div class="card-header bg-white py-3 px-3 px-lg-4 d-flex justify-content-between align-items-center border-bottom">
                    <h3 class="h6 mb-0 fw-bold text-dark">
                        <i class="bi bi-clock-history me-1 text-success"></i> Conteúdos Cadastrados Recentemente
                    </h3>
                    <a href="{{ route('admin.contents.index') }}" class="small text-decoration-none fw-semibold text-success">
                        Ver todos <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 ps-lg-4" style="width: 70px;">Mídia</th>
                                <th>Título</th>
                                <th>Categoria</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th class="text-end pe-3 pe-lg-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosConteudos as $conteudo)
                                <tr>
                                    <td class="ps-3 ps-lg-4">
                                        <div class="rounded-2 overflow-hidden bg-light position-relative" style="width: 50px; height: 38px;">
                                            <img src="{{ $conteudo->image_url }}" alt="{{ $conteudo->titulo }}" class="w-100 h-100 object-fit-cover">
                                            @if($conteudo->isVideo())
                                                <span class="position-absolute bottom-0 end-0 bg-danger text-white rounded-start px-1" style="font-size: 0.6rem;">
                                                    <i class="bi bi-play-fill"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark text-truncate" style="max-width: 260px;" title="{{ $conteudo->titulo }}">
                                            {{ $conteudo->titulo }}
                                        </div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">
                                            Ordem: #{{ $conteudo->ordem }}
                                            @if($conteudo->isFeatured())
                                                • <span class="text-warning fw-semibold"><i class="bi bi-star-fill"></i> Destaque</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-category">{{ $conteudo->category->nome ?? 'Geral' }}</span>
                                    </td>
                                    <td>
                                        @if($conteudo->isPhoto())
                                            <span class="badge bg-info-subtle text-info"><i class="bi bi-image me-1"></i> Foto</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger"><i class="bi bi-play-circle me-1"></i> Vídeo</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($conteudo->isPublished())
                                            <span class="badge bg-success-subtle text-success">Publicado</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Rascunho</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3 pe-lg-4">
                                        <a href="{{ route('admin.contents.edit', $conteudo->id) }}" class="btn btn-sm btn-outline-secondary p-1 px-2" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                        Nenhum conteúdo cadastrado até o momento.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Coluna Lateral: Itens Recentes & Informações da Banca -->
        <div class="col-12 col-xl-4">
            <!-- Itens Recentes em Exposição -->
            <div class="card border-0 shadow-xs rounded-3 mb-4">
                <div class="card-header bg-white py-3 px-3 d-flex justify-content-between align-items-center border-bottom">
                    <h3 class="h6 mb-0 fw-bold text-dark">
                        <i class="bi bi-grid me-1 text-success"></i> Itens no Expositor
                    </h3>
                    <a href="{{ route('admin.products.index') }}" class="small text-decoration-none fw-semibold text-success">
                        Ver todos
                    </a>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex flex-column gap-3">
                        @forelse($ultimosItens as $item)
                            <div class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-light">
                                <div class="d-flex align-items-center gap-2 overflow-hidden">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->nome }}" class="rounded-2 object-fit-cover flex-shrink-0" style="width: 44px; height: 44px;">
                                    <div class="text-truncate">
                                        <div class="fw-semibold text-dark text-truncate small">{{ $item->nome }}</div>
                                        <div class="text-muted" style="font-size: 0.72rem;">{{ $item->category->nome ?? 'Item' }}</div>
                                    </div>
                                </div>
                                <div class="text-end ps-2 flex-shrink-0">
                                    @if($item->preco)
                                        <div class="fw-bold text-success small">{{ $item->formatted_price }}</div>
                                    @else
                                        <div class="text-muted small">Sob consulta</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small text-center mb-0">Nenhum item em exposição cadastrado.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Dados Rápidos da Banca -->
            <div class="card border-0 shadow-xs rounded-3">
                <div class="card-header bg-white py-3 px-3 border-bottom">
                    <h3 class="h6 mb-0 fw-bold text-dark">
                        <i class="bi bi-shop me-1 text-success"></i> {{ $bancaConfig->nome_banca }}
                    </h3>
                </div>
                <div class="card-body p-3">
                    <ul class="list-unstyled small mb-3">
                        <li class="mb-2 d-flex align-items-start gap-2">
                            <i class="bi bi-geo-alt text-success mt-1"></i>
                            <span>{{ $bancaConfig->endereco ?? 'Endereço não configurado' }}</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-whatsapp text-success"></i>
                            <span>{{ $bancaConfig->whatsapp ?? 'Não informado' }}</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-clock text-success"></i>
                            <span class="text-truncate">{{ $bancaConfig->horario ?? 'Horário flexível' }}</span>
                        </li>
                    </ul>
                    <a href="{{ route('admin.configurations.index') }}" class="btn btn-outline-brand btn-sm w-100">
                        <i class="bi bi-gear"></i> Editar Informações da Banca
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
