@extends('layouts.app')

@section('title', 'Contato & Localização | ' . ($config->nome_banca ?: 'Banca Santa Rita'))
@section('meta_description', 'Entre em contato com a Banca Santa Rita. Horários de funcionamento, telefone, WhatsApp, redes sociais e endereço completo.')

@section('content')
<!-- Header da Página -->
<div class="py-5 text-white" style="background: linear-gradient(135deg, var(--brand-dark) 0%, #1e3322 100%);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Início</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Contato & Localização</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold mb-2">Contato & Localização</h1>
        <p class="lead text-white-50 mb-0">Estamos prontos para atender você, tirar dúvidas e receber sua visita na banca.</p>
    </div>
</div>

<div class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Coluna de Informações de Contato -->
            <div class="col-12 col-lg-5">
                <span class="text-uppercase small fw-bold" style="color: var(--brand-accent);">Fale Conosco</span>
                <h2 class="h3 fw-bold mb-4" style="color: var(--brand-dark);">Canais de Atendimento</h2>

                <div class="d-flex flex-column gap-3 mb-4">
                    <!-- WhatsApp -->
                    <div class="card border-0 shadow-sm rounded-4 p-3" style="background-color: var(--brand-accent-light); border-left: 5px solid var(--brand-accent) !important;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background-color: #25D366 !important;">
                                <i class="bi bi-whatsapp fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="small fw-semibold text-muted d-block">WhatsApp da Banca</span>
                                <strong class="fs-6 text-dark">{{ $config->whatsapp ?: '(11) 98765-4321' }}</strong>
                            </div>
                            <a href="{{ $config->getWhatsappUrl('Olá! Vim através do site e gostaria de tirar uma dúvida com a Banca Santa Rita.') }}" target="_blank" class="btn btn-sm btn-brand-accent">
                                Mensagem
                            </a>
                        </div>
                    </div>

                    <!-- Telefone -->
                    @if($config->telefone)
                        <div class="card border-0 shadow-sm rounded-4 p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background-color: var(--brand-institutional-light); color: var(--brand-institutional);">
                                    <i class="bi bi-telephone fs-4"></i>
                                </div>
                                <div>
                                    <span class="small fw-semibold text-muted d-block">Telefone</span>
                                    <strong class="fs-6 text-dark">{{ $config->telefone }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Endereço -->
                    <div class="card border-0 shadow-sm rounded-4 p-3">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 48px; height: 48px; background-color: var(--brand-institutional-light); color: var(--brand-institutional);">
                                <i class="bi bi-geo-alt fs-4"></i>
                            </div>
                            <div>
                                <span class="small fw-semibold text-muted d-block">Endereço da Banca</span>
                                <strong class="fs-6 text-dark">{{ $config->endereco ?: 'Praça Central, s/n - Centro' }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Horário -->
                    <div class="card border-0 shadow-sm rounded-4 p-3">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 48px; height: 48px; background-color: var(--brand-institutional-light); color: var(--brand-institutional);">
                                <i class="bi bi-clock fs-4"></i>
                            </div>
                            <div>
                                <span class="small fw-semibold text-muted d-block">Horário de Funcionamento</span>
                                <strong class="fs-6 text-dark">{{ $config->horario ?: 'Segunda a Sábado: 06h às 20h | Domingos: 06h às 14h' }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- E-mail -->
                    @if($config->email)
                        <div class="card border-0 shadow-sm rounded-4 p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background-color: var(--brand-institutional-light); color: var(--brand-institutional);">
                                    <i class="bi bi-envelope fs-4"></i>
                                </div>
                                <div>
                                    <span class="small fw-semibold text-muted d-block">E-mail</span>
                                    <strong class="fs-6 text-dark">{{ $config->email }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Redes Sociais -->
                @if($config->instagram || $config->facebook)
                    <div class="p-3 bg-light rounded-4 border">
                        <span class="small fw-bold text-muted d-block mb-2">Acompanhe nas Redes Sociais</span>
                        <div class="d-flex gap-2">
                            @if($config->instagram)
                                <a href="https://instagram.com/{{ $config->instagram }}" target="_blank" class="btn btn-outline-dark btn-sm d-inline-flex align-items-center gap-2">
                                    <i class="bi bi-instagram text-danger"></i>
                                    <span>&#64;{{ $config->instagram }}</span>
                                </a>
                            @endif
                            @if($config->facebook)
                                <a href="https://facebook.com/{{ $config->facebook }}" target="_blank" class="btn btn-outline-dark btn-sm d-inline-flex align-items-center gap-2">
                                    <i class="bi bi-facebook text-primary"></i>
                                    <span>Facebook</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Coluna de Localização no Mapa -->
            <div class="col-12 col-lg-7">
                <span class="text-uppercase small fw-bold" style="color: var(--brand-institutional);">Como Chegar</span>
                <h2 class="h3 fw-bold mb-4" style="color: var(--brand-dark);">Localização da Banca no Mapa</h2>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                    @php
                        $lat = $config->latitude ?: '-23.550520';
                        $lng = $config->longitude ?: '-46.633308';
                    @endphp
                    <div class="ratio ratio-16x9" style="min-height: 380px;">
                        <iframe 
                            src="https://maps.google.com/maps?q={{ $lat }},{{ $lng }}&hl=pt-BR&z=16&output=embed" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Mapa de localização da {{ $config->nome_banca ?: 'Banca Santa Rita' }}">
                        </iframe>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $lat }},{{ $lng }}" 
                       target="_blank" 
                       class="btn btn-brand-institutional">
                        <i class="bi bi-map me-1"></i> Abrir no Google Maps
                    </a>
                    <a href="{{ $config->getWhatsappUrl('Olá! Gostaria de saber como chegar à Banca Santa Rita.') }}" 
                       target="_blank" 
                       class="btn btn-brand-accent">
                        <i class="bi bi-whatsapp me-1"></i> Pedir Ponto de Referência
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
