@extends('layouts.admin')

@section('title', 'Editar Conteúdo: ' . $content->titulo)

@section('content')
<div class="container-fluid p-0" style="max-width: 900px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Editar Conteúdo</h2>
            <p class="text-muted mb-0 small">Atualize fotos, vídeos ou informações de exibição do conteúdo #{{ $content->id }}.</p>
        </div>
        <a href="{{ route('admin.contents.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <div class="card border-0 shadow-xs rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.contents.update', $content->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <!-- Seleção de Tipo -->
                <div class="mb-4 p-3 bg-light rounded-3 border">
                    <label class="form-label fw-bold text-dark d-block mb-2">
                        Tipo de Conteúdo <span class="text-danger">*</span>
                    </label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="tipo" id="tipoFoto" value="foto" {{ old('tipo', $content->tipo) === 'foto' ? 'checked' : '' }} onchange="toggleMediaType()">
                        <label class="btn btn-outline-success py-2 fw-semibold d-flex align-items-center justify-content-center gap-2" for="tipoFoto">
                            <i class="bi bi-image fs-5"></i>
                            <span>Fotografia / Galeria</span>
                        </label>

                        <input type="radio" class="btn-check" name="tipo" id="tipoVideo" value="video" {{ old('tipo', $content->tipo) === 'video' ? 'checked' : '' }} onchange="toggleMediaType()">
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
                               value="{{ old('titulo', $content->titulo) }}" 
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
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('categoria_id', $content->categoria_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Slug -->
                <div class="mb-3">
                    <label for="slug" class="form-label fw-semibold text-dark">Slug (URL Amigável)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted small">/conteudo/</span>
                        <input type="text" 
                               class="form-control @error('slug') is-invalid @enderror" 
                               id="slug" 
                               name="slug" 
                               value="{{ old('slug', $content->slug) }}">
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
                              rows="4">{{ old('descricao', $content->descricao) }}</textarea>
                    @error('descricao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Seção de FOTO -->
                <div id="sectionPhoto" class="mb-4 p-3 rounded-3 border bg-light">
                    <h3 class="h6 fw-bold text-dark mb-2">
                        <i class="bi bi-camera me-1 text-success"></i> Imagem do Conteúdo
                    </h3>
                    <div class="row align-items-center g-3">
                        <div class="col-12 col-md-4 text-center">
                            <div class="border rounded-2 p-1 bg-white mx-auto overflow-hidden d-flex align-items-center justify-content-center" style="width: 160px; height: 110px;">
                                <img id="imagePreview" src="{{ $content->image_url }}" alt="{{ $content->titulo }}" class="w-100 h-100 object-fit-cover">
                            </div>
                            <span class="text-muted d-block mt-1" style="font-size: 0.72rem;">Imagem Atual / Pré-visualização</span>
                        </div>
                        <div class="col-12 col-md-8">
                            <label for="imagem" class="form-label fw-semibold text-dark small">Substituir Imagem (Opcional)</label>
                            <input type="file" 
                                   class="form-control @error('imagem') is-invalid @enderror" 
                                   id="imagem" 
                                   name="imagem" 
                                   accept="image/jpeg,image/png,image/webp,image/jpg" 
                                   onchange="previewImage(event)">
                            <div class="form-text small text-muted">Deixe em branco para manter a imagem atual. O envio de uma nova imagem substituirá a anterior.</div>
                            @error('imagem')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Seção de VÍDEO -->
                <div id="sectionVideo" class="mb-4 p-3 rounded-3 border bg-light" style="display: none;">
                    <h3 class="h6 fw-bold text-dark mb-2">
                        <i class="bi bi-play-btn me-1 text-danger"></i> Configuração de Vídeo
                    </h3>

                    @if($content->isVideo() && $content->video_embed_url)
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Player Atual:</label>
                            <div class="ratio ratio-16x9 rounded-2 overflow-hidden border bg-dark" style="max-height: 220px;">
                                <iframe src="{{ $content->video_embed_url }}" title="{{ $content->titulo }}" allowfullscreen></iframe>
                            </div>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="video_url" class="form-label fw-semibold text-dark small">URL do Vídeo (YouTube ou Vimeo)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-danger">
                                <i class="bi bi-youtube"></i>
                            </span>
                            <input type="url" 
                                   class="form-control @error('video_url') is-invalid @enderror" 
                                   id="video_url" 
                                   name="video_url" 
                                   value="{{ old('video_url', $content->video_url) }}" 
                                   placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                        @error('video_url')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="video_arquivo" class="form-label fw-semibold text-dark small">Substituir Arquivo de Vídeo (Opcional)</label>
                        <input type="file" 
                               class="form-control @error('video_arquivo') is-invalid @enderror" 
                               id="video_arquivo" 
                               name="video_arquivo" 
                               accept="video/mp4,video/webm">
                        <div class="form-text small text-muted">Formatos: MP4 ou WEBM. Deixe em branco se não desejar alterar o arquivo.</div>
                        @error('video_arquivo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Ordem, Destaque e Status -->
                <div class="row g-3 mb-4">
                    <!-- Ordem -->
                    <div class="col-6 col-md-3">
                        <label for="ordem" class="form-label fw-semibold text-dark">Ordem <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('ordem') is-invalid @enderror" 
                               id="ordem" 
                               name="ordem" 
                               value="{{ old('ordem', $content->ordem) }}" 
                               min="0" 
                               required>
                        @error('ordem')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Destaque -->
                    <div class="col-6 col-md-3">
                        <label for="destaque" class="form-label fw-semibold text-dark">Destaque <span class="text-danger">*</span></label>
                        <select class="form-select @error('destaque') is-invalid @enderror" id="destaque" name="destaque" required>
                            <option value="não" {{ old('destaque', $content->destaque) === 'não' ? 'selected' : '' }}>Não</option>
                            <option value="sim" {{ old('destaque', $content->destaque) === 'sim' ? 'selected' : '' }}>Sim (Hero / Carrossel)</option>
                        </select>
                        @error('destaque')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-6 col-md-3">
                        <label for="status" class="form-label fw-semibold text-dark">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="publicado" {{ old('status', $content->status) === 'publicado' ? 'selected' : '' }}>Publicado (visível)</option>
                            <option value="rascunho" {{ old('status', $content->status) === 'rascunho' ? 'selected' : '' }}>Rascunho (oculto)</option>
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
                               value="{{ old('data_publicacao', $content->data_publicacao ? $content->data_publicacao->format('Y-m-d') : '') }}">
                        @error('data_publicacao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <span class="text-muted small">
                        Cadastrado em: {{ $content->created_at->format('d/m/Y H:i') }}
                    </span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.contents.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-brand-accent px-4">
                            <i class="bi bi-check-lg me-1"></i> Atualizar Conteúdo
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

    document.addEventListener('DOMContentLoaded', toggleMediaType);
</script>
@endpush
