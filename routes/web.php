<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Laravel UI tarafından oluşturulan kimlik doğrulama rotaları (login, register, logout vb.)
Auth::routes();

// Herkesin görebileceği genel ana sayfa
Route::get('/', function () {
    return view('welcome');
});

// Giriş yapmış kullanıcılara özel rotalar
Route::middleware('auth')->group(function () {

    // Kullanıcının görev panosu
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Bu rotalar görevleri farklı durumlara göre ayrı sayfalarda göstermeyi sağlar.
    Route::get('/tasks/yapilacaklar', [TaskController::class, 'yapilacaklar'])->name('tasks.yapilacaklar');
    Route::get('/tasks/yapilmislar', [TaskController::class, 'yapilmislar'])->name('tasks.yapilmislar');
    Route::get('/tasks/ertelenenler', [TaskController::class, 'ertelenenler'])->name('tasks.ertelenenler');
    Route::get('/tasks/taslaklar', [TaskController::class, 'taslaklar'])->name('tasks.taslaklar');

    // Yeni görev oluşturmak için form sayfasını açan rota ile form verisini kaydeden rota.
    Route::get('/tasks/ekle', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // Bir görevin durumunu değiştirmek için kullanılan rota.
    Route::get('/tasks/{id}/status/{statusKey}', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
});
