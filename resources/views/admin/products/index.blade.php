@extends('layouts.admin')

@section('title', 'Itens em Exposição (Catálogo da Banca)')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Itens em Exposição</h2>
            <p class="text-muted mb-0 small">Cadastre os jornais, revistas, quadrinhos, livros e itens expostos na Banca Santa Rita.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-brand-accent shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Novo Item
        </a>
    </div>

    <!-- Barra de Filtros e Busca -->
    <div class="card border-0 shadow-xs rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 align-items-center">
                <!-- Busca Textual -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Buscar por nome do item..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Categoria -->
                <div class="col-6 col-md-3 col-lg-3">
                    <select name="categoria_id" class="form-select form-select-sm">
                        <option value="">Todas as Categorias</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nome }}
                            </option>
                        @endforeach
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
                <div class="col-6 col-md-2 col-lg-2">
                    <select name="destaque" class="form-select form-select-sm">
                        <option value="">Destaque</option>
                        <option value="sim" {{ request('destaque') === 'sim' ? 'selected' : '' }}>Sim</option>
                        <option value="não" {{ request('destaque') === 'não' ? 'selected' : '' }}>Não</option>
                    </select>
                </div>

                <!-- Botões -->
                <div class="col-6 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-brand-institutional btn-sm flex-grow-1">
                        Filtrar
                    </button>
                    @if(request()->anyFilled(['search', 'categoria_id', 'status', 'destaque']))
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm" title="Limpar Filtros">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Itens -->
    <div class="card border-0 shadow-xs rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 ps-lg-4" style="width: 70px;">Imagem</th>
                        <th>Nome do Item</th>
                        <th>Categoria</th>
                        <th>Preço Informativo</th>
                        <th>Status</th>
                        <th>Destaque</th>
                        <th>Ordem</th>
                        <th class="text-end pe-3 pe-lg-4" style="width: 140px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="ps-3 ps-lg-4">
                                <div class="rounded-2 overflow-hidden bg-light border" style="width: 48px; height: 48px;">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->nome }}" class="w-100 h-100 object-fit-cover">
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark text-truncate" style="max-width: 280px;" title="{{ $product->nome }}">
                                    {{ $product->nome }}
                                </div>
                                <code class="small text-muted">{{ $product->slug }}</code>
                            </td>
                            <td>
                                <span class="badge-category">{{ $product->category->nome ?? 'Sem categoria' }}</span>
                            </td>
                            <td>
                                @if($product->preco)
                                    <span class="fw-bold text-success">{{ $product->formatted_price }}</span>
                                    <span class="d-block text-muted" style="font-size: 0.7rem;">preço sugerido</span>
                                @else
                                    <span class="text-muted small">Sob consulta</span>
                                @endif
                            </td>
                            <td>
                                @if($product->isPublished())
                                    <span class="badge bg-success-subtle text-success">Publicado</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">Rascunho</span>
                                @endif
                            </td>
                            <td>
                                @if($product->isFeatured())
                                    <span class="badge bg-warning text-dark fw-bold">
                                        <i class="bi bi-star-fill me-1"></i> Sim
                                    </span>
                                @else
                                    <span class="text-muted small">Não</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 fw-bold">
                                    #{{ $product->ordem }}
                                </span>
                            </td>
                            <td class="text-end pe-3 pe-lg-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                       class="btn btn-sm btn-outline-secondary p-1 px-2" 
                                       title="Editar Item">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger p-1 px-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteProductModal{{ $product->id }}"
                                            title="Excluir Item">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>

                                <!-- Modal de Exclusão -->
                                <div class="modal fade" id="deleteProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="deleteProductLabel{{ $product->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-start">
                                            <div class="modal-header">
                                                <h5 class="modal-title h6 fw-bold" id="deleteProductLabel{{ $product->id }}">
                                                    Confirmar Exclusão
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="mb-1">Deseja realmente remover o item <strong>{{ $product->nome }}</strong> do expositor?</p>
                                                <p class="text-danger small mb-0">Esta ação não poderá ser desfeita.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
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
                                <i class="bi bi-grid-3x3-gap fs-1 d-block mb-2 text-secondary"></i>
                                Nenhum item em exposição encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="card-footer bg-white py-3 border-top">
                <div class="d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
