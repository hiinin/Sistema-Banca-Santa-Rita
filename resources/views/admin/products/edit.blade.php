@extends('layouts.admin')

@section('title', 'Editar Item: ' . $product->nome)

@section('content')
<div class="container-fluid p-0" style="max-width: 850px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Editar Item do Expositor</h2>
            <p class="text-muted mb-0 small">Atualize as informações, preço de capa ou foto do item #{{ $product->id }}.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <div class="card border-0 shadow-xs rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <!-- Nome do Item -->
                    <div class="col-12 col-md-8">
                        <label for="nome" class="form-label fw-semibold text-dark">Nome do Item <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nome') is-invalid @enderror" 
                               id="nome" 
                               name="nome" 
                               value="{{ old('nome', $product->nome) }}" 
                               required 
                               autofocus>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Categoria -->
                    <div class="col-12 col-md-4">
                        <label for="categoria_id" class="form-label fw-semibold text-dark">Categoria</label>
                        <select class="form-select @error('categoria_id') is-invalid @enderror" id="categoria_id" name="categoria_id">
                            <option value="">Selecione uma categoria...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('categoria_id', $product->categoria_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <!-- Preço Informativo -->
                    <div class="col-12 col-md-6">
                        <label for="preco" class="form-label fw-semibold text-dark">
                            Preço de Capa / Sugerido <span class="text-muted fw-normal small">(opcional, apenas informativo)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted">R$</span>
                            <input type="text" 
                                   class="form-control @error('preco') is-invalid @enderror" 
                                   id="preco" 
                                   name="preco" 
                                   value="{{ old('preco', $product->preco ? number_format($product->preco, 2, ',', '.') : '') }}" 
                                   placeholder="0,00">
                        </div>
                        @error('preco')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div class="col-12 col-md-6">
                        <label for="slug" class="form-label fw-semibold text-dark">Slug (URL Amigável)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted small">/item/</span>
                            <input type="text" 
                                   class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" 
                                   name="slug" 
                                   value="{{ old('slug', $product->slug) }}">
                        </div>
                        @error('slug')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Descrição -->
                <div class="mb-4">
                    <label for="descricao" class="form-label fw-semibold text-dark">Descrição / Detalhes da Edição</label>
                    <textarea class="form-control @error('descricao') is-invalid @enderror" 
                              id="descricao" 
                              name="descricao" 
                              rows="3">{{ old('descricao', $product->descricao) }}</textarea>
                    @error('descricao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Upload de Imagem com Imagem Atual e Preview -->
                <div class="mb-4 p-3 rounded-3 border bg-light">
                    <h3 class="h6 fw-bold text-dark mb-2">
                        <i class="bi bi-image me-1 text-success"></i> Imagem do Item
                    </h3>
                    <div class="row align-items-center g-3">
                        <div class="col-12 col-md-4 text-center">
                            <div class="border rounded-2 p-1 bg-white mx-auto overflow-hidden d-flex align-items-center justify-content-center" style="width: 110px; height: 110px;">
                                <img id="itemImagePreview" src="{{ $product->image_url }}" alt="{{ $product->nome }}" class="w-100 h-100 object-fit-cover">
                            </div>
                            <span class="text-muted d-block mt-1" style="font-size: 0.72rem;">Imagem Atual / Nova</span>
                        </div>
                        <div class="col-12 col-md-8">
                            <label for="imagem" class="form-label fw-semibold text-dark small">Substituir Imagem (Opcional)</label>
                            <input type="file" 
                                   class="form-control @error('imagem') is-invalid @enderror" 
                                   id="imagem" 
                                   name="imagem" 
                                   accept="image/jpeg,image/png,image/webp,image/jpg" 
                                   onchange="previewItemImage(event)">
                            <div class="form-text small text-muted">Deixe em branco para manter a imagem atual. O envio de nova foto substituirá a anterior.</div>
                            @error('imagem')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Ordem, Destaque e Status -->
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <label for="ordem" class="form-label fw-semibold text-dark">Ordem <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('ordem') is-invalid @enderror" 
                               id="ordem" 
                               name="ordem" 
                               value="{{ old('ordem', $product->ordem) }}" 
                               min="0" 
                               required>
                        @error('ordem')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-6 col-md-4">
                        <label for="destaque" class="form-label fw-semibold text-dark">Destaque <span class="text-danger">*</span></label>
                        <select class="form-select @error('destaque') is-invalid @enderror" id="destaque" name="destaque" required>
                            <option value="não" {{ old('destaque', $product->destaque) === 'não' ? 'selected' : '' }}>Não</option>
                            <option value="sim" {{ old('destaque', $product->destaque) === 'sim' ? 'selected' : '' }}>Sim (em evidência)</option>
                        </select>
                        @error('destaque')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-6 col-md-4">
                        <label for="status" class="form-label fw-semibold text-dark">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="publicado" {{ old('status', $product->status) === 'publicado' ? 'selected' : '' }}>Publicado (visível)</option>
                            <option value="rascunho" {{ old('status', $product->status) === 'rascunho' ? 'selected' : '' }}>Rascunho (oculto)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <span class="text-muted small">
                        Cadastrado em: {{ $product->created_at->format('d/m/Y H:i') }}
                    </span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-brand-accent px-4">
                            <i class="bi bi-check-lg me-1"></i> Atualizar Item
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewItemImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('itemImagePreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
