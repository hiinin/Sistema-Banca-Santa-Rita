@extends('layouts.admin')

@section('title', 'Gerenciamento de Conteúdos (Fotos e Vídeos)')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Fotos & Vídeos</h2>
            <p class="text-muted mb-0 small">Cadastre e gerencie fotos da banca, novidades, vídeos e destaques do site público.</p>
        </div>
        <a href="{{ route('admin.contents.create') }}" class="btn btn-brand-accent shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Novo Conteúdo
        </a>
    </div>

    <!-- Barra de Filtros e Busca -->
    <div class="card border-0 shadow-xs rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.contents.index') }}" class="row g-2 align-items-center">
                <!-- Busca Textual -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Buscar por título..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Categoria -->
                <div class="col-6 col-md-3 col-lg-2">
                    <select name="categoria_id" class="form-select form-select-sm">
                        <option value="">Todas as Categorias</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo (Foto / Vídeo) -->
                <div class="col-6 col-md-2 col-lg-2">
                    <select name="tipo" class="form-select form-select-sm">
                        <option value="">Todos os Tipos</option>
                        <option value="foto" {{ request('tipo') === 'foto' ? 'selected' : '' }}>Fotos</option>
                        <option value="video" {{ request('tipo') === 'video' ? 'selected' : '' }}>Vídeos</option>
                    </select>
                </div>

                <!-- Status -->
                <div class="col-6 col-md-2 col-lg-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Todos os Status</option>
                        <option value="publicado" {{ request('status') === 'publicado' ? 'selected' : '' }}>Publicados</option>
                        <option value="rascunho" {{ request('status') === 'rascunho' ? 'selected' : '' }}>Rascunhos</option>
                    </select>
                </div>

                <!-- Destaque -->
                <div class="col-6 col-md-2 col-lg-1">
                    <select name="destaque" class="form-select form-select-sm">
                        <option value="">Destaque</option>
                        <option value="sim" {{ request('destaque') === 'sim' ? 'selected' : '' }}>Sim</option>
                        <option value="não" {{ request('destaque') === 'não' ? 'selected' : '' }}>Não</option>
                    </select>
                </div>

                <!-- Botões -->
                <div class="col-12 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-brand-institutional btn-sm flex-grow-1">
                        Filtrar
                    </button>
                    @if(request()->anyFilled(['search', 'categoria_id', 'tipo', 'status', 'destaque']))
                        <a href="{{ route('admin.contents.index') }}" class="btn btn-outline-secondary btn-sm" title="Limpar Filtros">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Conteúdos -->
    <div class="card border-0 shadow-xs rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 ps-lg-4" style="width: 80px;">Mídia</th>
                        <th>Título</th>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Destaque</th>
                        <th>Ordem</th>
                        <th class="text-end pe-3 pe-lg-4" style="width: 140px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contents as $content)
                        <tr>
                            <td class="ps-3 ps-lg-4">
                                <div class="rounded-2 overflow-hidden bg-light position-relative" style="width: 58px; height: 42px;">
                                    <img src="{{ $content->image_url }}" alt="{{ $content->titulo }}" class="w-100 h-100 object-fit-cover">
                                    @if($content->isVideo())
                                        <span class="position-absolute bottom-0 end-0 bg-danger text-white rounded-start px-1" style="font-size: 0.65rem;">
                                            <i class="bi bi-play-fill"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark text-truncate" style="max-width: 280px;" title="{{ $content->titulo }}">
                                    {{ $content->titulo }}
                                </div>
                                <code class="small text-muted">{{ $content->slug }}</code>
                            </td>
                            <td>
                                <span class="badge-category">{{ $content->category->nome ?? 'Geral' }}</span>
                            </td>
                            <td>
                                @if($content->isPhoto())
                                    <span class="badge bg-info-subtle text-info"><i class="bi bi-image me-1"></i> Foto</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger"><i class="bi bi-play-circle me-1"></i> Vídeo</span>
                                @endif
                            </td>
                            <td>
                                @if($content->isPublished())
                                    <span class="badge bg-success-subtle text-success">Publicado</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">Rascunho</span>
                                @endif
                            </td>
                            <td>
                                @if($content->isFeatured())
                                    <span class="badge bg-warning text-dark fw-bold">
                                        <i class="bi bi-star-fill me-1"></i> Sim
                                    </span>
                                @else
                                    <span class="text-muted small">Não</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 fw-bold">
                                    #{{ $content->ordem }}
                                </span>
                            </td>
                            <td class="text-end pe-3 pe-lg-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.contents.edit', $content->id) }}" 
                                       class="btn btn-sm btn-outline-secondary p-1 px-2" 
                                       title="Editar Conteúdo">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger p-1 px-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteContentModal{{ $content->id }}"
                                            title="Excluir Conteúdo">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>

                                <!-- Modal de Exclusão -->
                                <div class="modal fade" id="deleteContentModal{{ $content->id }}" tabindex="-1" aria-labelledby="deleteContentLabel{{ $content->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-start">
                                            <div class="modal-header">
                                                <h5 class="modal-title h6 fw-bold" id="deleteContentLabel{{ $content->id }}">
                                                    Confirmar Exclusão
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="mb-1">Deseja realmente remover o conteúdo <strong>{{ $content->titulo }}</strong>?</p>
                                                <p class="text-danger small mb-0">Esta ação excluirá o conteúdo e os arquivos associados do servidor.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('admin.contents.destroy', $content->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Confirmar Exclusão</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-collection-play fs-1 d-block mb-2 text-secondary"></i>
                                Nenhum conteúdo encontrado para os filtros selecionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contents->hasPages())
            <div class="card-footer bg-white py-3 border-top">
                <div class="d-flex justify-content-center">
                    {{ $contents->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
