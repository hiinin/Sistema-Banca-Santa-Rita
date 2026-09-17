@extends('layouts.admin')

@section('title', 'Configurações da Banca')

@section('content')
<div class="container-fluid p-0" style="max-width: 950px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Configurações da Banca</h2>
            <p class="text-muted mb-0 small">Edite os dados institucionais, contatos, WhatsApp, horário de atendimento e textos da Banca Santa Rita.</p>
        </div>
        <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-brand btn-sm">
            <i class="bi bi-box-arrow-up-right me-1"></i> Visualizar no Site
        </a>
    </div>

    <div class="card border-0 shadow-xs rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.configurations.update') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <!-- 1. Identidade e Marca -->
                <div class="mb-4">
                    <h3 class="h6 fw-bold text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-shop text-success"></i> 1. Identidade & Apresentação
                    </h3>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="nome_banca" class="form-label fw-semibold text-dark">Nome da Banca <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nome_banca') is-invalid @enderror" 
                                   id="nome_banca" 
                                   name="nome_banca" 
                                   value="{{ old('nome_banca', $config->nome_banca) }}" 
                                   required>
                            @error('nome_banca')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="descricao" class="form-label fw-semibold text-dark">Slogan / Descrição Rápida</label>
                            <input type="text" 
                                   class="form-control @error('descricao') is-invalid @enderror" 
                                   id="descricao" 
                                   name="descricao" 
                                   value="{{ old('descricao', $config->descricao) }}" 
                                   placeholder="Ex: Seu ponto de encontro com a cultura e a informação">
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Logo e Favicon -->
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-light rounded-2 border">
                                <label class="form-label fw-semibold text-dark small d-block">Logotipo da Banca</label>
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <div class="p-2 bg-white rounded border d-flex align-items-center justify-content-center" style="height: 50px; min-width: 120px;">
                                        <img id="logoPreview" src="{{ $config->logo_url }}" alt="Logo Atual" style="max-height: 38px; max-width: 110px;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="file" 
                                               class="form-control form-control-sm @error('logo') is-invalid @enderror" 
                                               id="logo" 
                                               name="logo" 
                                               accept="image/png,image/jpeg,image/webp,image/svg+xml" 
                                               onchange="previewLogo(event)">
                                    </div>
                                </div>
                                <span class="text-muted small" style="font-size: 0.72rem;">Formatos: PNG, JPG, WEBP ou SVG.</span>
                                @error('logo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-light rounded-2 border">
                                <label class="form-label fw-semibold text-dark small d-block">Ícone do Navegador (Favicon)</label>
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <div class="p-2 bg-white rounded border d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <img id="faviconPreview" src="{{ $config->favicon_url }}" alt="Favicon Atual" style="width: 24px; height: 24px;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="file" 
                                               class="form-control form-control-sm @error('favicon') is-invalid @enderror" 
                                               id="favicon" 
                                               name="favicon" 
                                               accept=".ico,image/png,image/svg+xml" 
                                               onchange="previewFavicon(event)">
                                    </div>
                                </div>
                                <span class="text-muted small" style="font-size: 0.72rem;">Formatos: ICO, PNG ou SVG.</span>
                                @error('favicon')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Contatos & WhatsApp -->
                <div class="mb-4">
                    <h3 class="h6 fw-bold text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-chat-dots text-success"></i> 2. Contatos & Atendimento
                    </h3>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="whatsapp" class="form-label fw-semibold text-dark">
                                WhatsApp <span class="text-success small fw-normal">(canal direto de contato do site)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-success"><i class="bi bi-whatsapp"></i></span>
                                <input type="text" 
                                       class="form-control @error('whatsapp') is-invalid @enderror" 
                                       id="whatsapp" 
                                       name="whatsapp" 
                                       value="{{ old('whatsapp', $config->whatsapp) }}" 
                                       placeholder="15998765432">
                            </div>
                            <div class="form-text small text-muted">
                                Link gerado para o visitante: 
                                <a href="{{ $config->getWhatsappUrl() }}" target="_blank" class="text-success text-decoration-none">
                                    {{ $config->getWhatsappUrl() }}
                                </a>
                            </div>
                            @error('whatsapp')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="telefone" class="form-label fw-semibold text-dark">Telefone Fixo / Comercial</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-telephone"></i></span>
                                <input type="text" 
                                       class="form-control @error('telefone') is-invalid @enderror" 
                                       id="telefone" 
                                       name="telefone" 
                                       value="{{ old('telefone', $config->telefone) }}" 
                                       placeholder="(15) 3232-1000">
                            </div>
                            @error('telefone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label fw-semibold text-dark">E-mail de Contato</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $config->email) }}" 
                                       placeholder="contato@bancasantarita.com.br">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="endereco" class="form-label fw-semibold text-dark">Endereço Completo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" 
                                       class="form-control @error('endereco') is-invalid @enderror" 
                                       id="endereco" 
                                       name="endereco" 
                                       value="{{ old('endereco', $config->endereco) }}" 
                                       placeholder="Praça Coronel Fernando Prestes, s/n - Centro">
                            </div>
                            @error('endereco')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 3. Redes Sociais -->
                <div class="mb-4">
                    <h3 class="h6 fw-bold text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-share text-success"></i> 3. Redes Sociais
                    </h3>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="instagram" class="form-label fw-semibold text-dark">Instagram (usuário sem @)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-danger"><i class="bi bi-instagram"></i></span>
                                <span class="input-group-text bg-light text-muted small">instagram.com/</span>
                                <input type="text" 
                                       class="form-control @error('instagram') is-invalid @enderror" 
                                       id="instagram" 
                                       name="instagram" 
                                       value="{{ old('instagram', $config->instagram) }}" 
                                       placeholder="bancasantarita">
                            </div>
                            @error('instagram')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="facebook" class="form-label fw-semibold text-dark">Facebook</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-primary"><i class="bi bi-facebook"></i></span>
                                <span class="input-group-text bg-light text-muted small">facebook.com/</span>
                                <input type="text" 
                                       class="form-control @error('facebook') is-invalid @enderror" 
                                       id="facebook" 
                                       name="facebook" 
                                       value="{{ old('facebook', $config->facebook) }}" 
                                       placeholder="bancasantaritaoficial">
                            </div>
                            @error('facebook')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 4. Horários & Mapa -->
                <div class="mb-4">
                    <h3 class="h6 fw-bold text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-clock text-success"></i> 4. Horário de Funcionamento & Localização
                    </h3>

                    <div class="mb-3">
                        <label for="horario" class="form-label fw-semibold text-dark">Horário de Funcionamento Exibido no Site</label>
                        <input type="text" 
                               class="form-control @error('horario') is-invalid @enderror" 
                               id="horario" 
                               name="horario" 
                               value="{{ old('horario', $config->horario) }}" 
                               placeholder="Segunda a Sábado: 06h às 20h | Domingos e Feriados: 06h às 14h">
                        @error('horario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="latitude" class="form-label fw-semibold text-dark">Latitude (Google Maps)</label>
                            <input type="text" 
                                   class="form-control @error('latitude') is-invalid @enderror" 
                                   id="latitude" 
                                   name="latitude" 
                                   value="{{ old('latitude', $config->latitude) }}" 
                                   placeholder="-23.5015">
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="longitude" class="form-label fw-semibold text-dark">Longitude (Google Maps)</label>
                            <input type="text" 
                                   class="form-control @error('longitude') is-invalid @enderror" 
                                   id="longitude" 
                                   name="longitude" 
                                   value="{{ old('longitude', $config->longitude) }}" 
                                   placeholder="-47.4581">
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 5. Texto Sobre Nós -->
                <div class="mb-4">
                    <h3 class="h6 fw-bold text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-journal-text text-success"></i> 5. Texto Institucional (Página Sobre Nós)
                    </h3>

                    <div class="mb-3">
                        <label for="texto_sobre" class="form-label fw-semibold text-dark">História e Tradição da Banca</label>
                        <textarea class="form-control @error('texto_sobre') is-invalid @enderror" 
                                  id="texto_sobre" 
                                  name="texto_sobre" 
                                  rows="6" 
                                  placeholder="Escreva a história da banca, anos de fundação e proposta de valor...">{{ old('texto_sobre', $config->texto_sobre) }}</textarea>
                        @error('texto_sobre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <button type="submit" class="btn btn-brand-accent px-4 py-2">
                        <i class="bi bi-check-lg me-1"></i> Salvar Configurações da Banca
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewLogo(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewFavicon(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('faviconPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
