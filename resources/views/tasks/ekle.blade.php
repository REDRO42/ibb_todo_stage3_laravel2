@extends('layouts.app')

@section('content')
<!-- Sayfa Üst Bilgi -->
<div class="text-muted small mb-3">
    <i class="bi bi-plus-circle"></i> SAYFA 2: YENİ GÖREV EKLE
</div>

<!-- Başlık -->
<h4 class="mb-3"><i class="bi bi-plus-circle"></i> Yeni Görev Ekle</h4>

<!-- Hata mesajı -->
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Ekleme Formu -->
<div class="welcome-card">
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf

        <!-- Başlık -->
        <div class="mb-3">
            <label for="baslik" class="form-label fw-bold">
                <i class="bi bi-pencil"></i> Görev Başlığı
            </label>
            <input type="text"
                   class="form-control"
                   id="baslik"
                   name="baslik"
                   placeholder="Görev başlığını yazın..."
                   value="{{ old('baslik') }}"
                   required>
        </div>

        <!-- Açıklama -->
        <div class="mb-3">
            <label for="aciklama" class="form-label fw-bold">
                <i class="bi bi-card-text"></i> Açıklama <span class="text-muted fw-normal">(İsteğe bağlı)</span>
            </label>
            <textarea class="form-control"
                      id="aciklama"
                      name="aciklama"
                      rows="3"
                      placeholder="Görevle ilgili detayları buraya yazabilirsiniz...">{{ old('aciklama') }}</textarea>
        </div>

        <!-- Tarih -->
        <div class="mb-3">
            <label for="tarih" class="form-label fw-bold">
                <i class="bi bi-calendar-event"></i> Tarih
            </label>
            <input type="date"
                   class="form-control"
                   id="tarih"
                   name="tarih"
                   value="{{ old('tarih', date('Y-m-d')) }}"
                   required>
        </div>

        <!-- Durum -->
        <div class="mb-4">
            <label for="durum" class="form-label fw-bold">
                <i class="bi bi-flag"></i> Durum
            </label>
            <select class="form-select" id="durum" name="durum">
                <option value="yapilacak" {{ old('durum') == 'yapilacak' ? 'selected' : '' }}>Yapılacak</option>
                <option value="yapilmis" {{ old('durum') == 'yapilmis' ? 'selected' : '' }}>Yapılmış</option>
                <option value="ertelenmis" {{ old('durum') == 'ertelenmis' ? 'selected' : '' }}>Ertelenmiş</option>
                <option value="taslak" {{ old('durum') == 'taslak' ? 'selected' : '' }}>Taslak</option>
            </select>
        </div>

        <!-- Butonlar -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Görevi Ekle
            </button>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> İptal
            </a>
        </div>
    </form>
</div>
@endsection
