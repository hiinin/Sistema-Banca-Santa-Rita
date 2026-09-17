@extends('layouts.auth')

@section('title', 'Acesso ao Painel')

@section('content')
<div class="auth-header">
    <a href="{{ url('/') }}" title="Ir para o site público">
        <img src="{{ asset('images/logo-banca-santa-rita.svg') }}" alt="Banca Santa Rita" class="img-fluid mb-2" style="max-height: 55px;">
    </a>
    <h1 class="h5 fw-bold text-dark mb-1">Painel Administrativo</h1>
    <p class="text-muted small mb-0">Gerenciamento do Expositor Digital e Mídias</p>
</div>

<div class="auth-body">
    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            <div>{{ session('info') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-3" role="alert">
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.store') }}" novalidate>
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold text-dark small">E-mail de Acesso</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                       placeholder="seu.email@exemplo.com"
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       autocomplete="username">
            </div>
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label for="password" class="form-label fw-semibold text-dark small mb-0">Senha</label>
            </div>
            <div class="input-group mt-1">
                <span class="input-group-text bg-light text-muted border-end-0">
                    <i class="bi bi-lock"></i>
                </span>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" 
                       placeholder="Digite sua senha"
                       required 
                       autocomplete="current-password">
            </div>
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
            <label class="form-check-label small text-muted user-select-none" for="remember">
                Lembrar deste dispositivo
            </label>
        </div>

        <button type="submit" class="btn btn-brand-accent w-100 py-2 shadow-sm">
            <i class="bi bi-box-arrow-in-right me-1"></i> Entrar no Painel
        </button>
    </form>

    <div class="text-center mt-4 pt-3 border-top">
        <a href="{{ url('/') }}" class="text-decoration-none small text-muted hover-accent">
            <i class="bi bi-arrow-left me-1"></i> Voltar ao site público
        </a>
    </div>
</div>
@endsection
