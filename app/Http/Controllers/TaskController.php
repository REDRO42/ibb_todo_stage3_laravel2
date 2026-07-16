<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Yapılacak görevleri listeleyen metot.
     * Kullanıcının henüz tamamlamadığı ana görevleri bu sayfada gösteriyoruz.
     */
    public function yapilacaklar()
    {
        // 1. auth()->user(): Şu an sisteme giriş yapmış olan kullanıcıyı temsil eder.
        // 2. ->tasks(): Bu kullanıcının sahip olduğu tüm görevleri çeken veritabanı ilişkisidir.
        // 3. ->where('durum', 'Yapılacak'): Sadece durumu 'Yapılacak' olanları filtreler.
        // 4. ->get(): Filtrelenen verileri veritabanından bir koleksiyon (liste) olarak getirir.
        $tasks = auth()->user()->tasks()->where('durum', 'Yapılacak')->get();

        // resources/views/tasks/yapilacaklar.blade.php dosyasını ekrana basar.
        // İkinci parametredeki dizi ile view dosyasına $tasks (görev listesi) ve $count (görev sayısı) değişkenlerini yolluyoruz.
        return view('tasks.yapilacaklar', [
            'tasks' => $tasks,
            'count' => $tasks->count(),
        ]);
    }

    /**
     * Tamamlanmış görevleri listeleyen metot.
     * Kullanıcının bitirdiği görevleri arşiv niyetine gördüğü sayfa.
     */
    public function yapilmislar()
    {
        // Sadece giriş yapmış kullanıcıya ait, durumu 'Yapılmış' olan görevleri getirir.
        $tasks = auth()->user()->tasks()->where('durum', 'Yapılmış')->get();

        // Verileri tasks.yapilmislar view dosyasına aktarır.
        return view('tasks.yapilmislar', [
            'tasks' => $tasks,
            'count' => $tasks->count(),
        ]);
    }

    /**
     * Ertelenmiş görevleri listeleyen metot.
     * Bugün yapılmayacak, ileri bir tarihe atılmış görevler burada listelenir.
     */
    public function ertelenenler()
    {
        // Durumu 'Ertelenmiş' olan görevleri filtreler.
        $tasks = auth()->user()->tasks()->where('durum', 'Ertelenmiş')->get();

        // Verileri tasks.ertelenenler view dosyasına aktarır.
        return view('tasks.ertelenenler', [
            'tasks' => $tasks,
            'count' => $tasks->count(),
        ]);
    }

    /**
     * Taslak durumundaki görevleri listeleyen metot.
     * Üzerinde henüz kesin karar verilmemiş, not niteliğindeki görevlerdir.
     */
    public function taslaklar()
    {
        // Durumu 'Taslak' olan görevleri filtreler.
        $tasks = auth()->user()->tasks()->where('durum', 'Taslak')->get();

        // Verileri tasks.taslaklar view dosyasına aktarır.
        return view('tasks.taslaklar', [
            'tasks' => $tasks,
            'count' => $tasks->count(),
        ]);
    }

    /**
     * Yeni görev eklemek için form sayfasını gösteren metot.
     * Sadece HTML arayüzünü ekrana basar, işlem yapmaz.
     */
    public function create()
    {
        // resources/views/tasks/ekle.blade.php dosyasını gösterir.
        return view('tasks.ekle');
    }

    /**
     * Yeni görevi veritabanına kaydeden metot.
     * Ekleme formundan (create metodu) gelen veriler POST isteğiyle bu metoda düşer.
     * Request sınıfı, formdan gelen (input) tüm verilere ulaşmamızı sağlar.
     */
    public function store(Request $request)
    {
        // 1. Doğrulama (Validation): Formdan gelen verilerin kurallara uyup uymadığını kontrol ediyoruz.
        // Hatalıysa Laravel otomatik olarak formu geldiği yere geri gönderir ve hata mesajlarını gösterir.
        $request->validate([
            'baslik' => 'required|string|max:255', // Zorunlu, metin, maksimum 255 karakter
            'tarih'  => 'required|date',           // Zorunlu, geçerli bir tarih olmalı
            'durum'  => 'required|in:yapilacak,yapilmis,ertelenmis,taslak', // Zorunlu ve sadece bu 4 seçenekten biri olmalı
        ]);

        // 2. Formdan gelen durum anahtarını ('yapilacak'), veritabanına yazılacak gerçek metne ('Yapılacak') çeviriyoruz.
        // Task::$statusMap dizisi Task modeli içinde tanımladığımız bir eşleştirme tablosudur.
        $durumAdi = Task::$statusMap[$request->durum];

        // 3. Veritabanına kayıt işlemi:
        // auth()->user()->tasks()->create() kullanımı, görevi direkt giriş yapan kullanıcının ID'si ile ilişkilendirerek (user_id) kaydeder.
        auth()->user()->tasks()->create([
            'baslik'   => $request->baslik,     // Formdan gelen 'baslik' inputu
            'aciklama' => $request->aciklama,   // Formdan gelen 'aciklama' inputu (textarea)
            'tarih'    => $request->tarih,      // Formdan gelen 'tarih' inputu
            'durum'    => $durumAdi,            // Üst satırda çevirdiğimiz gerçek durum adı
        ]);

        // 4. Kullanıcıyı, yeni eklediği görevin durumuna göre ilgili listeye yönlendiriyoruz.
        // Örneğin durum 'yapilacak' ise 'tasks/yapilacaklar' adresine gidecek.
        $redirectMap = [
            'yapilacak'  => 'tasks/yapilacaklar',
            'yapilmis'   => 'tasks/yapilmislar',
            'ertelenmis' => 'tasks/ertelenenler',
            'taslak'     => 'tasks/taslaklar',
        ];

        // redirect() fonksiyonu kullanıcıyı belirtilen URL'ye yönlendirir.
        return redirect($redirectMap[$request->durum]);
    }

    /**
     * Var olan bir görevin durumunu güncelleyen metot (Örn: Yapılacaklardan -> Yapılmışlara taşıma).
     * URL: /tasks/{id}/status/{statusKey} şeklindedir ve parametreleri URL'den alır.
     *
     * @param int    $id        Güncellenecek görevin veritabanındaki ID numarası
     * @param string $statusKey Yeni durumun kısa anahtarı (örn: 'yapilmis')
     */
    public function updateStatus($id, $statusKey)
    {
        // 1. Güvenlik Kontrolü: URL'den gelen durum anahtarı bizim belirlediğimiz 4 seçenekten biri değilse,
        // işlemi reddedip kullanıcıyı ana sayfaya yönlendiriyoruz.
        if (!isset(Task::$statusMap[$statusKey])) {
            return redirect('/');
        }

        // 2. Güvenlik ve Veri Çekme: 
        // a) auth()->user()->tasks() diyerek KESİNLİKLE sadece bu kullanıcının görevleri içinde arama yapıyoruz. (Böylece başkasının görevi güncellenemez)
        // b) where('id', $id) ile sadece istediğimiz ID'yi arıyoruz.
        // c) firstOrFail() ile kaydı getiriyoruz. Eğer bu ID'de bir görev yoksa veya başkasına aitse Laravel otomatik olarak 404 (Bulunamadı) hatası fırlatır.
        $task = auth()->user()->tasks()->where('id', $id)->firstOrFail();

        // 3. Güncelleme: Bulduğumuz görevin durumunu (durum), statusMap'ten aldığımız yeni gerçek metin ile ('Yapılmış' vb.) güncelliyoruz.
        $task->update(['durum' => Task::$statusMap[$statusKey]]);

        // 4. Yönlendirme: Görevi nereye taşıdıysa, kullanıcıyı o sayfanın listesine geri yönlendiriyoruz.
        $redirectMap = [
            'yapilacak'  => 'tasks/yapilacaklar',
            'yapilmis'   => 'tasks/yapilmislar',
            'ertelenmis' => 'tasks/ertelenenler',
            'taslak'     => 'tasks/taslaklar',
        ];

        return redirect($redirectMap[$statusKey]);
    }
}
