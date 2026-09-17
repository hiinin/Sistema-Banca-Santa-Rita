@extends('layouts.admin')

@section('title', 'Novo Conteúdo (Foto ou Vídeo)')

@section('content')
<div class="container-fluid p-0" style="max-width: 900px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Cadastrar Novo Conteúdo</h2>
            <p class="text-muted mb-0 small">Adicione fotos ou vídeos com categoria, prioridade e destaque para o site.</p>
        </div>
        <a href="{{ route('admin.contents.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <div class="card border-0 shadow-xs rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.contents.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <!-- Seleção de Tipo (Foto ou Vídeo) com Tabs/Botões de Rádio -->
                <div class="mb-4 p-3 bg-light rounded-3 border">
                    <label class="form-label fw-bold text-dark d-block mb-2">
                        Tipo de Conteúdo <span class="text-danger">*</span>
                    </label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="tipo" id="tipoFoto" value="foto" {{ old('tipo', 'foto') === 'foto' ? 'checked' : '' }} onchange="toggleMediaType()">
                        <label class="btn btn-outline-success py-2 fw-semibold d-flex align-items-center justify-content-center gap-2" for="tipoFoto">
                            <i class="bi bi-image fs-5"></i>
                            <span>Fotografia / Galeria</span>
                        </label>

                        <input type="radio" class="btn-check" name="tipo" id="tipoVideo" value="video" {{ old('tipo') === 'video' ? 'checked' : '' }} onchange="toggleMediaType()">
                        <label class="btn btn-outline-danger py-2 fw-semibold d-flex align-items-center justify-content-center gap-2" for="tipoVideo">
                            <i class="bi bi-play-circle fs-5"></i>
                            <span>Vídeo (YouTube / Vimeo / Arquivo)</span>
                        </label>
                    </div>
                    @error('tipo')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <!-- Título -->
                    <div class="col-12 col-md-8">
                        <label for="titulo" class="form-label fw-semibold text-dark">Título do Conteúdo <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('titulo') is-invalid @enderror" 
                               id="titulo" 
                               name="titulo" 
                               value="{{ old('titulo') }}" 
                               placeholder="Ex: Novos Gibis e Revistas em Destaque" 
                               required 
                               autofocus>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Categoria -->
                    <div class="col-12 col-md-4">
                        <label for="categoria_id" class="form-label fw-semibold text-dark">Categoria <span class="text-danger">*</span></label>
                        <select class="form-select @error('categoria_id') is-invalid @enderror" id="categoria_id" name="categoria_id" required>
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

                <!-- Slug Opcional -->
                <div class="mb-3">
                    <label for="slug" class="form-label fw-semibold text-dark">
                        Slug (URL Amigável) <span class="text-muted fw-normal small">(opcional, gerado automaticamente se vazio)</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted small">/conteudo/</span>
                        <input type="text" 
                               class="form-control @error('slug') is-invalid @enderror" 
                               id="slug" 
                               name="slug" 
                               value="{{ old('slug') }}" 
                               placeholder="ex: novos-gibis-e-revistas-destaque">
                    </div>
                    @error('slug')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Descrição -->
                <div class="mb-4">
                    <label for="descricao" class="form-label fw-semibold text-dark">Descrição / Informações do Conteúdo</label>
                    <textarea class="form-control @error('descricao') is-invalid @enderror" 
                              id="descricao" 
                              name="descricao" 
                              rows="4" 
                              placeholder="Descreva detalhes sobre a foto ou o vídeo que aparecerão na página de visualização...">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Seção Exclusiva para FOTO -->
                <div id="sectionPhoto" class="mb-4 p-3 rounded-3 border bg-light">
                    <h3 class="h6 fw-bold text-dark mb-2">
                        <i class="bi bi-camera me-1 text-success"></i> Upload da Fotografia
                    </h3>
                    <div class="row align-items-center g-3">
                        <div class="col-12 col-md-8">
                            <label for="imagem" class="form-label fw-semibold text-dark small">Selecionar Arquivo de Imagem <span class="text-danger">*</span></label>
                            <input type="file" 
                                   class="form-control @error('imagem') is-invalid @enderror" 
                                   id="imagem" 
                                   name="imagem" 
                                   accept="image/jpeg,image/png,image/webp,image/jpg" 
                                   onchange="previewImage(event)">
                            <div class="form-text small text-muted">Formatos permitidos: JPG, JPEG, PNG, WEBP. Tamanho máximo: 10MB.</div>
                            @error('imagem')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-4 text-center">
                            <div class="border rounded-2 p-1 bg-white mx-auto overflow-hidden d-flex align-items-center justify-content-center" style="width: 140px; height: 100px;">
                                <img id="imagePreview" src="{{ asset('images/placeholder-content.svg') }}" alt="Pré-visualização" class="w-100 h-100 object-fit-cover">
                            </div>
                            <span class="text-muted d-block mt-1" style="font-size: 0.72rem;">Pré-visualização</span>
                        </div>
                    </div>
                </div>

                <!-- Seção Exclusiva para VÍDEO -->
                <div id="sectionVideo" class="mb-4 p-3 rounded-3 border bg-light" style="display: none;">
                    <h3 class="h6 fw-bold text-dark mb-2">
                        <i class="bi bi-play-btn me-1 text-danger"></i> Configuração de Vídeo
                    </h3>
                    
                    <!-- Opção 1: URL YouTube / Vimeo -->
                    <div class="mb-3">
                        <label for="video_url" class="form-label fw-semibold text-dark small">
                            Opção 1: URL do Vídeo (YouTube ou Vimeo)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-danger">
                                <i class="bi bi-youtube"></i>
                            </span>
                            <input type="url" 
                                   class="form-control @error('video_url') is-invalid @enderror" 
                                   id="video_url" 
                                   name="video_url" 
                                   value="{{ old('video_url') }}" 
                                   placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                        <div class="form-text small text-muted">Cole o link completo do YouTube ou Vimeo. O player será configurado automaticamente.</div>
                        @error('video_url')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Opção 2: Upload de Vídeo -->
                    <div class="mb-3">
                        <label for="video_arquivo" class="form-label fw-semibold text-dark small">
                            Opção 2: Upload de Arquivo de Vídeo (MP4 / WEBM)
                        </label>
                        <input type="file" 
                               class="form-control @error('video_arquivo') is-invalid @enderror" 
                               id="video_arquivo" 
                               name="video_arquivo" 
                               accept="video/mp4,video/webm">
                        <div class="form-text small text-muted">Formatos: MP4 ou WEBM. Tamanho máximo: 50MB.</div>
                        @error('video_arquivo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Capa opcional para o vídeo -->
                    <div class="pt-2 border-top">
                        <label for="imagem_video" class="form-label fw-semibold text-dark small">
                            Imagem de Capa Personalizada (Opcional)
                        </label>
                        <input type="file" 
                               class="form-control" 
                               id="imagem_video" 
                               name="imagem" 
                               accept="image/jpeg,image/png,image/webp">
                        <div class="form-text small text-muted">Se não enviada, vídeos do YouTube utilizam a miniatura original automaticamente.</div>
                    </div>
                </div>

                <!-- Configurações de Publicação e Ordem -->
                <div class="row g-3 mb-4">
                    <!-- Ordem -->
                    <div class="col-6 col-md-3">
                        <label for="ordem" class="form-label fw-semibold text-dark">Ordem <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('ordem') is-invalid @enderror" 
                               id="ordem" 
                               name="ordem" 
                               value="{{ old('ordem', $nextOrder) }}" 
                               min="0" 
                               required>
                        <div class="form-text small text-muted">Prioridade no carrossel.</div>
                        @error('ordem')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Destaque -->
                    <div class="col-6 col-md-3">
                        <label for="destaque" class="form-label fw-semibold text-dark">Destaque <span class="text-danger">*</span></label>
                        <select class="form-select @error('destaque') is-invalid @enderror" id="destaque" name="destaque" required>
                            <option value="não" {{ old('destaque', 'não') === 'não' ? 'selected' : '' }}>Não (comum)</option>
                            <option value="sim" {{ old('destaque') === 'sim' ? 'selected' : '' }}>Sim (Hero / Carrossel Principal)</option>
                        </select>
                        @error('destaque')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-6 col-md-3">
                        <label for="status" class="form-label fw-semibold text-dark">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="publicado" {{ old('status', 'publicado') === 'publicado' ? 'selected' : '' }}>Publicado (visível)</option>
                            <option value="rascunho" {{ old('status') === 'rascunho' ? 'selected' : '' }}>Rascunho (oculto)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Data de Publicação -->
                    <div class="col-6 col-md-3">
                        <label for="data_publicacao" class="form-label fw-semibold text-dark">Data de Exibição</label>
                        <input type="date" 
                               class="form-control @error('data_publicacao') is-invalid @enderror" 
                               id="data_publicacao" 
                               name="data_publicacao" 
                               value="{{ old('data_publicacao', date('Y-m-d')) }}">
                        @error('data_publicacao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.contents.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-brand-accent px-4">
                        <i class="bi bi-check-lg me-1"></i> Publicar Conteúdo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleMediaType() {
        const isFoto = document.getElementById('tipoFoto').checked;
        const sectionPhoto = document.getElementById('sectionPhoto');
        const sectionVideo = document.getElementById('sectionVideo');

        if (isFoto) {
            sectionPhoto.style.display = 'block';
            sectionVideo.style.display = 'none';
        } else {
            sectionPhoto.style.display = 'none';
            sectionVideo.style.display = 'block';
        }
    }

    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Inicialização no carregamento
    document.addEventListener('DOMContentLoaded', toggleMediaType);
</script>
@endpush
