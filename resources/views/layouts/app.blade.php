<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Banca Santa Rita | Expositor Digital & Mídias')</title>
    <meta name="description" content="@yield('meta_description', 'Conheça o expositor digital da Banca Santa Rita. Informações diárias, revistas, jornais, gibis, livros e novidades.')">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Open Graph SEO -->
    <meta property="og:title" content="@yield('title', 'Banca Santa Rita | Expositor Digital & Mídias')">
    <meta property="og:description" content="@yield('meta_description', 'Conheça o expositor digital da Banca Santa Rita.')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo-banca-santa-rita.svg'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS e JS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="no-overflow-x d-flex flex-column min-vh-100">
    <!-- Atalho de acessibilidade -->
    <a href="#mainContent" class="skip-to-content">Pular para o conteúdo principal</a>

    @php
        $siteConfig = \App\Models\Configuration::current();
    @endphp

    <!-- Topbar Institucional -->
    <div class="bg-dark text-white py-1 px-3 d-none d-md-block" style="background-color: var(--brand-dark) !important; font-size: 0.8rem;">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                @if($siteConfig->endereco)
                    <span><i class="bi bi-geo-alt text-success me-1"></i> {{ $siteConfig->endereco }}</span>
                @endif
                @if($siteConfig->horario)
                    <span class="d-none d-lg-inline"><i class="bi bi-clock text-success me-1"></i> {{ $siteConfig->horario }}</span>
                @endif
            </div>
            <div class="d-flex align-items-center gap-3">
                @if($siteConfig->instagram)
                    <a href="https://instagram.com/{{ $siteConfig->instagram }}" target="_blank" class="text-white-50 hover-white text-decoration-none" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                @endif
                @if($siteConfig->facebook)
                    <a href="https://facebook.com/{{ $siteConfig->facebook }}" target="_blank" class="text-white-50 hover-white text-decoration-none" title="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                @endif
                <a href="{{ route('admin.login') }}" class="text-white-50 hover-white text-decoration-none d-flex align-items-center gap-1" title="Área do Administrador">
                    <i class="bi bi-lock-fill text-success" style="font-size: 0.75rem;"></i>
                    <span>Admin</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Header Principal da Banca -->
    <header class="site-header">
        <nav class="navbar navbar-expand-lg py-2 py-lg-3">
            <div class="container">
                <!-- Logo da Banca -->
                <a class="navbar-brand py-0 me-4" href="{{ route('home') }}">
                    <img src="{{ $siteConfig->logo_url }}" alt="{{ $siteConfig->nome_banca }}" style="max-height: 48px;" class="d-inline-block align-text-top">
                </a>

                <!-- Botão Menu Mobile -->
                <button class="navbar-toggler border-0 p-2 text-dark shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="Abrir Menu">
                    <i class="bi bi-list fs-2" style="color: var(--brand-dark);"></i>
                </button>

                <!-- Menu Desktop -->
                <div class="collapse navbar-collapse d-none d-lg-flex justify-content-between">
                    <ul class="navbar-nav mb-2 mb-lg-0 gap-1 fw-semibold">
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('home') ? 'active text-success' : 'text-dark' }}" href="{{ route('home') }}">
                                Início
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('site.about') ? 'active text-success' : 'text-dark' }}" href="{{ route('site.about') }}">
                                Sobre Nós
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('site.contents.*') ? 'active text-success' : 'text-dark' }}" href="{{ route('site.contents.index') }}">
                                Fotos & Vídeos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('site.products.*') ? 'active text-success' : 'text-dark' }}" href="{{ route('site.products.index') }}">
                                Itens em Exposição
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 {{ request()->routeIs('site.contact') ? 'active text-success' : 'text-dark' }}" href="{{ route('site.contact') }}">
                                Contato
                            </a>
                        </li>
                    </ul>

                    <!-- Botão WhatsApp Direto -->
                    <div class="d-flex align-items-center">
                        <a href="{{ $siteConfig->getWhatsappUrl() }}" target="_blank" class="btn btn-brand-accent btn-sm shadow-sm py-2 px-3">
                            <i class="bi bi-whatsapp fs-6"></i>
                            <span>Falar no WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Offcanvas Menu Mobile -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
        <div class="offcanvas-header border-bottom py-3">
            <h5 class="offcanvas-title fw-bold" id="mobileMenuLabel" style="color: var(--brand-dark);">
                Menu da Banca
            </h5>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between p-4">
            <ul class="navbar-nav gap-2 fw-semibold fs-5">
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('home') ? 'text-success' : 'text-dark' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door me-2 text-success"></i> Início
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('site.about') ? 'text-success' : 'text-dark' }}" href="{{ route('site.about') }}">
                        <i class="bi bi-info-circle me-2 text-success"></i> Sobre Nós
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('site.contents.*') ? 'text-success' : 'text-dark' }}" href="{{ route('site.contents.index') }}">
                        <i class="bi bi-camera-video me-2 text-success"></i> Fotos & Vídeos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('site.products.*') ? 'text-success' : 'text-dark' }}" href="{{ route('site.products.index') }}">
                        <i class="bi bi-grid-3x3-gap me-2 text-success"></i> Itens em Exposição
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('site.contact') ? 'text-success' : 'text-dark' }}" href="{{ route('site.contact') }}">
                        <i class="bi bi-envelope me-2 text-success"></i> Contato
                    </a>
                </li>
            </ul>

            <div class="pt-4 border-top">
                <a href="{{ $siteConfig->getWhatsappUrl() }}" target="_blank" class="btn btn-brand-accent w-100 py-3 mb-3 shadow-sm">
                    <i class="bi bi-whatsapp fs-5 me-1"></i> Falar no WhatsApp
                </a>
                <div class="text-center">
                    <a href="{{ route('admin.login') }}" class="small text-muted text-decoration-none">
                        <i class="bi bi-lock me-1"></i> Acesso Administrativo
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <main id="mainContent" class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Botão Flutuante do WhatsApp -->
    <a href="{{ $siteConfig->getWhatsappUrl() }}" 
       target="_blank" 
       class="position-fixed bottom-0 end-0 m-3 m-md-4 rounded-circle bg-success text-white shadow-lg d-flex align-items-center justify-content-center text-decoration-none z-3" 
       style="width: 58px; height: 58px; background-color: #25D366 !important; transition: transform 0.25s ease;"
       title="Falar com a Banca Santa Rita pelo WhatsApp"
       onmouseover="this.style.transform='scale(1.1)'"
       onmouseout="this.style.transform='scale(1)'">
        <i class="bi bi-whatsapp fs-2"></i>
    </a>

    <!-- Footer Institucional -->
    <footer class="site-footer mt-auto">
        <div class="container">
            <div class="row g-4 mb-5">
                <!-- Coluna 1: Sobre a Banca -->
                <div class="col-12 col-lg-4">
                    <div class="mb-3">
                        <img src="{{ $siteConfig->logo_url }}" alt="{{ $siteConfig->nome_banca }}" style="max-height: 44px; filter: brightness(0) invert(1);">
                    </div>
                    <p class="small text-white-50 mb-3" style="line-height: 1.6;">
                        {{ $siteConfig->descricao ?: 'Seu ponto de encontro com a leitura, informação diária, quadrinhos, revistas e conveniência.' }}
                    </p>
                    <div class="d-flex gap-2">
                        @if($siteConfig->instagram)
                            <a href="https://instagram.com/{{ $siteConfig->instagram }}" target="_blank" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                        @endif
                        @if($siteConfig->facebook)
                            <a href="https://facebook.com/{{ $siteConfig->facebook }}" target="_blank" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                        @endif
                        @if($siteConfig->whatsapp)
                            <a href="{{ $siteConfig->getWhatsappUrl() }}" target="_blank" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Coluna 2: Navegação Rápida -->
                <div class="col-6 col-md-3 col-lg-2">
                    <h4 class="h6 fw-bold text-white mb-3 font-heading">Navegação</h4>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><a href="{{ route('home') }}">Início</a></li>
                        <li><a href="{{ route('site.about') }}">Sobre Nós</a></li>
                        <li><a href="{{ route('site.contents.index') }}">Fotos & Vídeos</a></li>
                        <li><a href="{{ route('site.products.index') }}">Itens em Exposição</a></li>
                        <li><a href="{{ route('site.contact') }}">Contato & Localização</a></li>
                    </ul>
                </div>

                <!-- Coluna 3: Categorias em Destaque -->
                <div class="col-6 col-md-3 col-lg-2">
                    <h4 class="h6 fw-bold text-white mb-3 font-heading">Expositor</h4>
                    @php
                        $footerCategories = \App\Models\Category::active()->ordered()->take(5)->get();
                    @endphp
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        @foreach($footerCategories as $fCat)
                            <li>
                                <a href="{{ route('site.products.index', ['categoria_id' => $fCat->id]) }}">
                                    {{ $fCat->nome }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Coluna 4: Horário & Atendimento -->
                <div class="col-12 col-md-6 col-lg-4">
                    <h4 class="h6 fw-bold text-white mb-3 font-heading">Atendimento & Localização</h4>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-3 text-white-50">
                        @if($siteConfig->endereco)
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-geo-alt text-success mt-1"></i>
                                <span>{{ $siteConfig->endereco }}</span>
                            </li>
                        @endif
                        @if($siteConfig->horario)
                            <li class="d-flex align-items-start gap-2">
                                <i class="bi bi-clock text-success mt-1"></i>
                                <span>{{ $siteConfig->horario }}</span>
                            </li>
                        @endif
                        @if($siteConfig->telefone)
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-telephone text-success"></i>
                                <span>{{ $siteConfig->telefone }}</span>
                            </li>
                        @endif
                        @if($siteConfig->email)
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-envelope text-success"></i>
                                <span>{{ $siteConfig->email }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Direitos e Créditos -->
            <div class="pt-4 border-top border-secondary border-opacity-25 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small text-white-50">
                <div>
                    &copy; {{ date('Y') }} <strong>{{ $siteConfig->nome_banca }}</strong> • Todos os direitos reservados.
                </div>
                <div class="text-white-50">
                    Catálogo Informativo & Expositor Digital
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal Global de Lightbox para Fotos -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-labelledby="lightboxModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <div class="modal-header border-0 pb-0 justify-content-end">
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center p-2">
                    <img id="lightboxImage" src="" alt="" class="img-fluid rounded-3 shadow-lg" style="max-height: 80vh; object-fit: contain;">
                    <div class="bg-dark text-white p-3 rounded-bottom-3 mt-2 text-start">
                        <h3 class="h6 fw-bold text-white mb-1" id="lightboxTitle"></h3>
                        <p class="small text-white-50 mb-0" id="lightboxDesc"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Global de Player de Vídeo -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark text-white border-0 shadow-lg">
                <div class="modal-header border-0 pb-2">
                    <h3 class="modal-title h6 fw-bold text-white" id="videoModalTitle">Vídeo</h3>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Fechar" onclick="stopVideoPlayback()"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="ratio ratio-16x9" id="videoContainer">
                        <!-- Iframe ou vídeo dinâmico inserido via JavaScript -->
                    </div>
                </div>
                <div class="modal-footer border-0 py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" onclick="stopVideoPlayback()">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Lightbox para fotos
        function openLightbox(imageUrl, title, description) {
            document.getElementById('lightboxImage').src = imageUrl;
            document.getElementById('lightboxTitle').textContent = title || '';
            document.getElementById('lightboxDesc').textContent = description || '';
            const modal = new bootstrap.Modal(document.getElementById('lightboxModal'));
            modal.show();
        }

        // Modal de Vídeo com parada automática ao fechar
        function openVideoModal(videoEmbedUrl, videoFileUrl, title) {
            document.getElementById('videoModalTitle').textContent = title || 'Reprodução de Vídeo';
            const container = document.getElementById('videoContainer');
            
            if (videoEmbedUrl) {
                container.innerHTML = `<iframe src="${videoEmbedUrl}" title="${title}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
            } else if (videoFileUrl) {
                container.innerHTML = `<video controls autoplay class="w-100 h-100"><source src="${videoFileUrl}" type="video/mp4">Seu navegador não suporta a tag de vídeo.</video>`;
            }

            const modal = new bootstrap.Modal(document.getElementById('videoModal'));
            modal.show();
        }

        function stopVideoPlayback() {
            const container = document.getElementById('videoContainer');
            if (container) {
                container.innerHTML = ''; // Esvazia o container para cessar áudio/execução imediatamente
            }
        }

        // Para reprodução se o modal for fechado via tecla ESC ou clique fora
        document.addEventListener('DOMContentLoaded', function() {
            const videoModalEl = document.getElementById('videoModal');
            if (videoModalEl) {
                videoModalEl.addEventListener('hidden.bs.modal', stopVideoPlayback);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
