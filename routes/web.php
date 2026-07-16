<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Auth::routes()
 * Laravel UI paketinin sihirli komutudur. Arka planda login, register, password reset, 
 * logout gibi tüm temel kullanıcı işlemleri için gerekli rotaları (GET ve POST) otomatik olarak tanımlar.
 */
Auth::routes();

/**
 * Route::get('/', function() {})
 * Saf PHP'deki index.php mantığıdır. Kullanıcı tarayıcıda site adresine (http://site.com/) girdiğinde bu blok çalışır.
 * Burada bir Controller çağırmak yerine "Closure" (anonim fonksiyon) kullanarak 
 * direkt olarak resources/views/welcome.blade.php dosyasını ekrana (view) basıyoruz.
 * Bu sayfayı (Hoş Geldiniz) herkes görebilir.
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * Route::middleware('auth')->group(...)
 * Güvenlik Duvarı: Bu grubun içine yazdığımız TÜM rotalara erişebilmek için 
 * kullanıcının sisteme giriş (login) yapmış olması KESİNLİKLE zorunludur.
 * Misafir (giriş yapmamış) bir kullanıcı bu linklerden birine tıklarsa veya adres çubuğuna yazarsa,
 * Laravel onu otomatik olarak '/login' sayfasına yönlendirir. (Eski Core PHP'deki session kontrolü gibi).
 */
Route::middleware('auth')->group(function () {

    /**
     * Dashboard / Ana Pano (Kullanıcı giriş yaptıktan sonra)
     * ->name('home'): Bu rotaya 'home' ismini veriyoruz.
     * Artık projede "href='/home'" yazmak yerine "href='{{ route('home') }}'" yazabiliyoruz.
     * Böylece ileride /home adresini /pano yapsak bile, HTML içindeki linkleri tek tek düzeltmemize gerek kalmaz.
     */
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    /**
     * GET Rotaları (Veri Okuma)
     * Tarayıcı üzerinden veya linke tıklayarak ulaşılan adresler.
     * Sol taraf: URL adresi ('/tasks/yapilacaklar')
     * Sağ taraf: [HangiController::class, 'hangiMetotÇalışacak']
     */
    Route::get('/tasks/yapilacaklar', [TaskController::class, 'yapilacaklar'])->name('tasks.yapilacaklar');
    Route::get('/tasks/yapilmislar', [TaskController::class, 'yapilmislar'])->name('tasks.yapilmislar');
    Route::get('/tasks/ertelenenler', [TaskController::class, 'ertelenenler'])->name('tasks.ertelenenler');
    Route::get('/tasks/taslaklar', [TaskController::class, 'taslaklar'])->name('tasks.taslaklar');

    // Görev Ekleme Formunu Ekrana Basan Rota (GET)
    Route::get('/tasks/ekle', [TaskController::class, 'create'])->name('tasks.create');
    
    // Görev Ekleme Formu Gönderildiğinde Veriyi Karşılayan Rota (POST)
    // Güvenlik için form işlemlerinde (veritabanına yazma) her zaman POST kullanılır.
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    /**
     * Dinamik Rotalar (Değişken Parametre Alan Rotalar)
     * Süslü parantez içindeki {id} ve {statusKey} kısımları değişkendir.
     * Örneğin: /tasks/5/status/yapilmis linkine tıklandığında, 
     * TaskController içindeki updateStatus($id, $statusKey) metoduna parametre olarak sırasıyla 5 ve "yapilmis" değerleri gönderilir.
     */
    Route::get('/tasks/{id}/status/{statusKey}', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
});
