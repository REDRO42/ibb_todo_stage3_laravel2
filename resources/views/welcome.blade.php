@extends('layouts.app')

@section('content')
<div class="container text-center py-5 mt-5">
    <h1 class="display-4 fw-bold text-primary mb-4">TodoApp'e Hoş Geldiniz!</h1>
    <p class="lead text-muted mb-5">
        Görevlerinizi düzenlemenin, ertelemenin ve kolayca takip etmenin en modern yolu.
        Hemen kayıt olun ve kendi yapılacaklar listenizi oluşturun!
    </p>
    <div>
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg rounded-pill px-5 py-3 me-3 shadow-sm">
            <i class="bi bi-person-plus me-2"></i> Ücretsiz Kayıt Ol
        </a>
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg rounded-pill px-5 py-3 shadow-sm">
            <i class="bi bi-box-arrow-in-right me-2"></i> Giriş Yap
        </a>
    </div>
</div>
@endsection
