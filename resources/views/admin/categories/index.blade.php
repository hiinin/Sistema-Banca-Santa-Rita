@extends('layouts.admin')

@section('title', 'Gerenciamento de Categorias')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Categorias</h2>
            <p class="text-muted mb-0 small">Organize as seções temáticas de fotos, vídeos e itens em exposição da banca.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-brand-accent shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Nova Categoria
        </a>
    </div>

    <!-- Barra de Pesquisa e Filtros -->
    <div class="card border-0 shadow-xs rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Buscar por nome ou descrição..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-brand-institutional">
                        Filtrar
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Categorias -->
    <div class="card border-0 shadow-xs rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 ps-lg-4" style="width: 80px;">Ordem</th>
                        <th>Nome / Slug</th>
                        <th>Descrição</th>
                        <th>Vínculos</th>
                        <th>Status</th>
                        <th class="text-end pe-3 pe-lg-4" style="width: 140px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="ps-3 ps-lg-4">
                                <span class="badge bg-light text-dark border px-2 py-1 fw-bold">
                                    #{{ $category->ordem }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $category->nome }}</div>
                                <code class="small text-muted">{{ $category->slug }}</code>
                            </td>
                            <td>
                                <div class="text-muted small text-truncate" style="max-width: 320px;" title="{{ $category->descricao }}">
                                    {{ $category->descricao ?: 'Sem descrição informada.' }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge bg-info-subtle text-info" title="Fotos e Vídeos">
                                        <i class="bi bi-collection-play me-1"></i> {{ $category->contents_count }} mídias
                                    </span>
                                    <span class="badge bg-success-subtle text-success" title="Itens em Exposição">
                                        <i class="bi bi-grid-3x3-gap me-1"></i> {{ $category->products_count }} itens
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if($category->status === 'ativo')
                                    <span class="badge bg-success-subtle text-success">Ativo</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Inativo</span>
                                @endif
                            </td>
                            <td class="text-end pe-3 pe-lg-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                       class="btn btn-sm btn-outline-secondary p-1 px-2" 
                                       title="Editar Categoria">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger p-1 px-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $category->id }}"
                                            title="Excluir Categoria">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>

                                <!-- Modal de Confirmação de Exclusão -->
                                <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $category->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-start">
                                            <div class="modal-header">
                                                <h5 class="modal-title h6 fw-bold" id="deleteModalLabel{{ $category->id }}">
                                                    Confirmar Exclusão
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="mb-1">Deseja realmente remover a categoria <strong>{{ $category->nome }}</strong>?</p>
                                                @if($category->contents_count > 0 || $category->products_count > 0)
                                                    <div class="alert alert-warning small mt-2 mb-0">
                                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                                        Esta categoria possui <strong>{{ $category->contents_count }}</strong> conteúdo(s) e <strong>{{ $category->products_count }}</strong> item(ns) associados. O sistema não permitirá a exclusão até que os vínculos sejam reatribuídos.
                                                    </div>
                                                @else
                                                    <p class="text-muted small mb-0">Esta ação não poderá ser desfeita.</p>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline">
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
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-tags fs-1 d-block mb-2 text-secondary"></i>
                                Nenhuma categoria encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="card-footer bg-white py-3 border-top">
                <div class="d-flex justify-content-center">
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
