@extends('layouts.admin')

@section('title', 'Nova Categoria')

@section('content')
<div class="container-fluid p-0" style="max-width: 800px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Cadastrar Nova Categoria</h2>
            <p class="text-muted mb-0 small">Crie uma nova seção para agrupar fotos, vídeos ou itens expostos.</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <div class="card border-0 shadow-xs rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.categories.store') }}" method="POST" novalidate>
                @csrf

                <!-- Nome da Categoria -->
                <div class="mb-3">
                    <label for="nome" class="form-label fw-semibold text-dark">Nome da Categoria <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('nome') is-invalid @enderror" 
                           id="nome" 
                           name="nome" 
                           value="{{ old('nome') }}" 
                           placeholder="Ex: Revistas & Periódicos" 
                           required 
                           autofocus>
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slug Opcional / Auto-gerado -->
                <div class="mb-3">
                    <label for="slug" class="form-label fw-semibold text-dark">
                        Slug (URL Amigável) <span class="text-muted fw-normal small">(opcional, gerado automaticamente se vazio)</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted small">/categoria/</span>
                        <input type="text" 
                               class="form-control @error('slug') is-invalid @enderror" 
                               id="slug" 
                               name="slug" 
                               value="{{ old('slug') }}" 
                               placeholder="ex: revistas-periodicos">
                    </div>
                    @error('slug')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Descrição -->
                <div class="mb-3">
                    <label for="descricao" class="form-label fw-semibold text-dark">Descrição Curta</label>
                    <textarea class="form-control @error('descricao') is-invalid @enderror" 
                              id="descricao" 
                              name="descricao" 
                              rows="3" 
                              placeholder="Breve resumo sobre o tipo de conteúdo desta categoria...">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <!-- Ordem de Exibição -->
                    <div class="col-12 col-md-6">
                        <label for="ordem" class="form-label fw-semibold text-dark">Ordem de Exibição <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('ordem') is-invalid @enderror" 
                               id="ordem" 
                               name="ordem" 
                               value="{{ old('ordem', $nextOrder) }}" 
                               min="0" 
                               required>
                        <div class="form-text small text-muted">Quanto menor o número, maior a prioridade de exibição.</div>
                        @error('ordem')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-12 col-md-6">
                        <label for="status" class="form-label fw-semibold text-dark">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="ativo" {{ old('status', 'ativo') === 'ativo' ? 'selected' : '' }}>Ativo (visível no site)</option>
                            <option value="inativo" {{ old('status') === 'inativo' ? 'selected' : '' }}>Inativo (oculto)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-brand-accent px-4">
                        <i class="bi bi-check-lg me-1"></i> Salvar Categoria
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
