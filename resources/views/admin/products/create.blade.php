@extends('layouts.admin')

@section('title', 'Novo Item em Exposição')

@section('content')
<div class="container-fluid p-0" style="max-width: 850px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Cadastrar Item no Expositor</h2>
            <p class="text-muted mb-0 small">Cadastre jornais, revistas, quadrinhos, livros ou colecionáveis para exibição no catálogo público.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <div class="card border-0 shadow-xs rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="row g-3 mb-3">
                    <!-- Nome do Item -->
                    <div class="col-12 col-md-8">
                        <label for="nome" class="form-label fw-semibold text-dark">Nome do Item <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nome') is-invalid @enderror" 
                               id="nome" 
                               name="nome" 
                               value="{{ old('nome') }}" 
                               placeholder="Ex: Revista Veja - Edição Especial" 
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
                                <option value="{{ $category->id }}" {{ old('categoria_id') == $category->id ? 'selected' : '' }}>
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
                                   value="{{ old('preco') }}" 
                                   placeholder="0,00">
                        </div>
                        <div class="form-text small text-muted">Este valor é puramente informativo para consulta dos visitantes no site.</div>
                        @error('preco')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Slug Opcional -->
                    <div class="col-12 col-md-6">
                        <label for="slug" class="form-label fw-semibold text-dark">
                            Slug (URL Amigável) <span class="text-muted fw-normal small">(opcional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted small">/item/</span>
                            <input type="text" 
                                   class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" 
                                   name="slug" 
                                   value="{{ old('slug') }}" 
                                   placeholder="ex: revista-veja-edicao-especial">
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
                              rows="3" 
                              placeholder="Resumo do item, destaques da edição, autor ou detalhes para o leitor...">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Upload de Imagem com Preview -->
                <div class="mb-4 p-3 rounded-3 border bg-light">
                    <h3 class="h6 fw-bold text-dark mb-2">
                        <i class="bi bi-image me-1 text-success"></i> Imagem do Item
                    </h3>
                    <div class="row align-items-center g-3">
                        <div class="col-12 col-md-8">
                            <label for="imagem" class="form-label fw-semibold text-dark small">Foto da Capa / Produto</label>
                            <input type="file" 
                                   class="form-control @error('imagem') is-invalid @enderror" 
                                   id="imagem" 
                                   name="imagem" 
                                   accept="image/jpeg,image/png,image/webp,image/jpg" 
                                   onchange="previewItemImage(event)">
                            <div class="form-text small text-muted">Formatos: JPG, JPEG, PNG, WEBP. Tamanho máx: 10MB.</div>
                            @error('imagem')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-4 text-center">
                            <div class="border rounded-2 p-1 bg-white mx-auto overflow-hidden d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                <img id="itemImagePreview" src="{{ asset('images/placeholder-item.svg') }}" alt="Pré-visualização" class="w-100 h-100 object-fit-cover">
                            </div>
                            <span class="text-muted d-block mt-1" style="font-size: 0.72rem;">Pré-visualização</span>
                        </div>
                    </div>
                </div>

                <!-- Configurações de Ordem, Destaque e Status -->
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <label for="ordem" class="form-label fw-semibold text-dark">Ordem <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('ordem') is-invalid @enderror" 
                               id="ordem" 
                               name="ordem" 
                               value="{{ old('ordem', $nextOrder) }}" 
                               min="0" 
                               required>
                        <div class="form-text small text-muted">Prioridade no catálogo da banca.</div>
                        @error('ordem')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-6 col-md-4">
                        <label for="destaque" class="form-label fw-semibold text-dark">Destaque <span class="text-danger">*</span></label>
                        <select class="form-select @error('destaque') is-invalid @enderror" id="destaque" name="destaque" required>
                            <option value="não" {{ old('destaque', 'não') === 'não' ? 'selected' : '' }}>Não</option>
                            <option value="sim" {{ old('destaque') === 'sim' ? 'selected' : '' }}>Sim (em evidência)</option>
                        </select>
                        @error('destaque')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-6 col-md-4">
                        <label for="status" class="form-label fw-semibold text-dark">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="publicado" {{ old('status', 'publicado') === 'publicado' ? 'selected' : '' }}>Publicado (visível no catálogo)</option>
                            <option value="rascunho" {{ old('status') === 'rascunho' ? 'selected' : '' }}>Rascunho (oculto)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-brand-accent px-4">
                        <i class="bi bi-check-lg me-1"></i> Publicar Item no Catálogo
                    </button>
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
