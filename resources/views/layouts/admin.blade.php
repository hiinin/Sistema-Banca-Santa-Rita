<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Painel') | Banca Santa Rita</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS / Assets via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-light">
    <div class="d-flex" id="wrapper">
        <!-- Sidebar Desktop -->
        <aside class="admin-sidebar d-none d-lg-flex flex-column flex-shrink-0 p-3 shadow-sm">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-25 px-2">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center gap-2">
                    <img src="{{ asset('images/logo-banca-santa-rita.svg') }}" alt="Banca Santa Rita" style="height: 38px; filter: brightness(0) invert(1);">
                </a>
            </div>

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i>
                        <span>Categorias</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.contents.index') }}" class="nav-link {{ request()->routeIs('admin.contents.*') ? 'active' : '' }}">
                        <i class="bi bi-collection-play"></i>
                        <span>Fotos & Vídeos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="bi bi-grid-3x3-gap"></i>
                        <span>Itens em Exposição</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.configurations.index') }}" class="nav-link {{ request()->routeIs('admin.configurations.*') ? 'active' : '' }}">
                        <i class="bi bi-sliders"></i>
                        <span>Configurações</span>
                    </a>
                </li>
            </ul>

            <hr class="border-secondary border-opacity-25 my-3">

            <div class="px-2 mb-2">
                <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span>Ver Site Público</span>
                </a>
            </div>

            <!-- Usuário logado e logout -->
            <div class="dropdown pt-2 border-top border-secondary border-opacity-25 px-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; font-weight: 700;">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="text-truncate">
                            <div class="small fw-semibold text-white text-truncate">{{ Auth::user()->name ?? 'Admin' }}</div>
                            <div class="text-muted text-truncate" style="font-size: 0.72rem;">{{ Auth::user()->email ?? '' }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link text-white-50 p-1 hover-white" title="Sair do painel">
                            <i class="bi bi-box-arrow-right fs-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Offcanvas Mobile Sidebar -->
        <div class="offcanvas offcanvas-start admin-sidebar text-white p-3" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
            <div class="offcanvas-header pb-3 border-bottom border-secondary border-opacity-25 px-2">
                <h5 class="offcanvas-title fw-bold" id="mobileSidebarLabel">Banca Santa Rita</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
            </div>
            <div class="offcanvas-body p-0 pt-3 d-flex flex-column justify-content-between">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="bi bi-tags"></i>
                            <span>Categorias</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.contents.index') }}" class="nav-link {{ request()->routeIs('admin.contents.*') ? 'active' : '' }}">
                            <i class="bi bi-collection-play"></i>
                            <span>Fotos & Vídeos</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="bi bi-grid-3x3-gap"></i>
                            <span>Itens em Exposição</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.configurations.index') }}" class="nav-link {{ request()->routeIs('admin.configurations.*') ? 'active' : '' }}">
                            <i class="bi bi-sliders"></i>
                            <span>Configurações</span>
                        </a>
                    </li>
                </ul>

                <div>
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-light w-100 mb-3 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-up-right"></i>
                        <span>Ver Site Público</span>
                    </a>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100 btn-sm d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sair do Painel</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <div class="admin-content d-flex flex-column flex-grow-1">
            <!-- Topbar -->
            <header class="bg-white border-bottom border-light-subtle px-3 px-lg-4 py-3 d-flex justify-content-between align-items-center sticky-top shadow-xs">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary d-lg-none p-1 px-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar" aria-label="Abrir Menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <h1 class="h5 mb-0 fw-bold text-dark font-heading">@yield('title', 'Painel')</h1>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill d-none d-sm-inline-flex align-items-center gap-1">
                        <span class="spinner-grow spinner-grow-sm text-success me-1" style="width: 8px; height: 8px;" role="status"></span>
                        Painel Online
                    </span>
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-secondary d-none d-md-inline-flex align-items-center gap-1">
                        <i class="bi bi-globe"></i>
                        <span>Ver Site</span>
                    </a>
                </div>
            </header>

            <!-- Mensagens Flash -->
            <div class="px-3 px-lg-4 pt-3">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-check-circle-fill fs-5 me-2 flex-shrink-0"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5 me-2 flex-shrink-0"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-info-circle-fill fs-5 me-2 flex-shrink-0"></i>
                        <div>{{ session('info') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif
            </div>

            <!-- Conteúdo da Página -->
            <main class="px-3 px-lg-4 py-3 flex-grow-1">
                @yield('content')
            </main>

            <!-- Footer do Painel -->
            <footer class="bg-white border-top py-3 px-4 text-center text-muted small mt-auto">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                    <div>
                        &copy; {{ date('Y') }} <strong>Banca Santa Rita</strong> • Expositor Digital & Sistema de Mídia
                    </div>
                    <div class="text-muted" style="font-size: 0.8rem;">
                        Laravel 11 • Bootstrap 5 • PHP 8.4
                    </div>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
