@extends('layouts.app')

@section('title', 'Página Não Encontrada | Banca Santa Rita')
@section('meta_description', 'A página que você está procurando não foi encontrada na Banca Santa Rita.')

@section('content')
<div class="container py-5 my-5 text-center">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="p-4 p-md-5 rounded-4 shadow-sm bg-white border">
                <span class="badge badge-category mb-3 px-3 py-2 fs-6">Erro 404</span>
                <h1 class="h2 fw-bold text-dark mb-3">Página Não Encontrada</h1>
                <p class="text-muted mb-4">
                    O conteúdo ou exemplar que você estava procurando pode ter sido movido, renomeado ou não está mais disponível no catálogo.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('home') }}" class="btn btn-brand-institutional">
                        <i class="bi bi-house-door me-1"></i> Ir para o Início
                    </a>
                    <a href="{{ route('site.products.index') }}" class="btn btn-brand-accent">
                        <i class="bi bi-grid-3x3-gap me-1"></i> Ver Catálogo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
