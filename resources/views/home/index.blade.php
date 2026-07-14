@extends('layouts.app')

@section('content')
<!-- Sayfa Üst Bilgi -->
<div class="text-muted small mb-3">
    <i class="bi bi-house-door"></i> SAYFA 1: ANA SAYFA / GİRİŞ
</div>

<!-- Hoş Geldiniz Kartı -->
<div class="welcome-card text-center mb-4">
    <p class="text-muted mb-4">Bootstrap 5 ile tasarlanmış MVC yapısında arayüz</p>

    <h3 class="mb-3">👋 Hoş Geldiniz!</h3>
    <p class="text-muted mb-4">Yapılacak işlerinizi yönetmek için üst menüyü kullanın.</p>

    <!-- Durum Barları -->
    <div class="d-flex flex-wrap justify-content-center gap-2">
        <a href="{{ route('tasks.yapilacaklar') }}" class="text-decoration-none">
            <div class="status-bar status-bar-yapilacak">
                <span><i class="bi bi-exclamation-diamond-fill"></i> Yapılacak: {{ $counts['Yapılacak'] ?? 0 }}</span>
            </div>
        </a>
        <a href="{{ route('tasks.yapilmislar') }}" class="text-decoration-none">
            <div class="status-bar status-bar-yapilmis">
                <span><i class="bi bi-check-circle-fill"></i> Yapılmış: {{ $counts['Yapılmış'] ?? 0 }}</span>
            </div>
        </a>
        <a href="{{ route('tasks.ertelenenler') }}" class="text-decoration-none">
            <div class="status-bar status-bar-ertelenmis">
                <span><i class="bi bi-skip-forward-fill"></i> Ertelenmiş: {{ $counts['Ertelenmiş'] ?? 0 }}</span>
            </div>
        </a>
        <a href="{{ route('tasks.taslaklar') }}" class="text-decoration-none">
            <div class="status-bar status-bar-taslak">
                <span><i class="bi bi-file-earmark-fill"></i> Taslak: {{ $counts['Taslak'] ?? 0 }}</span>
            </div>
        </a>
    </div>
</div>
@endsection
