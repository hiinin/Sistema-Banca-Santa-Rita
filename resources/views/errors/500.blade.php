@extends('layouts.app')

@section('title', 'Instabilidade Temporária | Banca Santa Rita')
@section('meta_description', 'Pedimos desculpas pelo inconveniente temporário.')

@section('content')
<div class="container py-5 my-5 text-center">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="p-4 p-md-5 rounded-4 shadow-sm bg-white border">
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 mb-3 px-3 py-2 fs-6">Aviso do Sistema</span>
                <h1 class="h2 fw-bold text-dark mb-3">Instabilidade Momentânea</h1>
                <p class="text-muted mb-4">
                    Nossa equipe já foi notificada. Por favor, tente recarregar a página em alguns instantes ou entre em contato diretamente pelo WhatsApp.
                </p>
                @php
                    $siteConfig = \App\Models\Configuration::current();
                @endphp
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('home') }}" class="btn btn-brand-institutional">
                        <i class="bi bi-arrow-clockwise me-1"></i> Voltar ao Início
                    </a>
                    <a href="{{ $siteConfig->getWhatsappUrl('Olá, notei uma instabilidade no site e gostaria de atendimento.') }}" target="_blank" class="btn btn-brand-accent">
                        <i class="bi bi-whatsapp me-1"></i> Falar no WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
