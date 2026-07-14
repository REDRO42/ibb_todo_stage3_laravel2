@extends('layouts.app')

@section('content')
<!-- Sayfa Üst Bilgi -->
<div class="text-muted small mb-3">
    <i class="bi bi-list-task"></i> SAYFA 3: YAPILACAKLAR LİSTESİ (DURUM: YAPILACAK)
</div>

<!-- Başlık -->
<h4 class="mb-3"><i class="bi bi-list-task"></i> Yapılacak Görevler ({{ $count }})</h4>

<!-- Tablo -->
<div class="welcome-card p-0">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Başlık</th>
                <th>Tarih</th>
                <th>Durum</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
            <tr>
                <td>{{ $task->id }}</td>
                <td>
                    <strong>{{ $task->baslik }}</strong>
                    @if ($task->aciklama)
                        <div class="small text-muted mt-1">{!! nl2br(e($task->aciklama)) !!}</div>
                    @endif
                </td>
                <td>{{ $task->tarih }}</td>
                <td><span class="badge-yapilacak">Yapılacak</span></td>
                <td>
                    <a href="{{ route('tasks.updateStatus', [$task->id, 'yapilmis']) }}" class="btn-action btn-action-yapilmis"><i class="bi bi-check-lg"></i> Yapılmış</a>
                    <a href="{{ route('tasks.updateStatus', [$task->id, 'ertelenmis']) }}" class="btn-action btn-action-ertelenmis"><i class="bi bi-skip-forward-fill"></i> Ertelenmiş</a>
                    <a href="{{ route('tasks.updateStatus', [$task->id, 'taslak']) }}" class="btn-action btn-action-taslak"><i class="bi bi-file-earmark"></i> Taslak</a>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Yapılacak görev bulunmuyor.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Durum Etiketi -->
<div class="text-end mt-3">
    <span class="page-status-badge page-status-badge-yapilacak">Durum: Yapılacak</span>
</div>
@endsection
