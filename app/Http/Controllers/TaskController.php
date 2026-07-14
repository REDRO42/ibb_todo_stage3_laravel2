<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Yapılacak görevleri listeleyen metot.
     * Bu metot veritabanından sadece "Yapılacak" durumundaki görevleri çeker ve ilgili sayfaya gönderir.
     */
    public function yapilacaklar()
    {
        // Sadece giriş yapmış kullanıcıya ait yapılacak görevleri getirir.
        $tasks = auth()->user()->tasks()->where('durum', 'Yapılacak')->get();

        // Görüntü katmanına görev listesi ve toplam sayı iletilir.
        return view('tasks.yapilacaklar', [
            'tasks' => $tasks,
            'count' => $tasks->count(),
        ]);
    }

    /**
     * Tamamlanmış görevleri listeleyen metot.
     * Bu metot, veritabanında "Yapılmış" olarak işaretlenmiş görevleri bulur ve ayrı bir sayfada gösterir.
     */
    public function yapilmislar()
    {
        // Sadece giriş yapmış kullanıcıya ait tamamlanmış görevleri getirir.
        $tasks = auth()->user()->tasks()->where('durum', 'Yapılmış')->get();

        // Görevlerin listesi ve adet bilgisi view'e gönderilir.
        return view('tasks.yapilmislar', [
            'tasks' => $tasks,
            'count' => $tasks->count(),
        ]);
    }

    /**
     * Ertelenmiş görevleri listeleyen metot.
     * Bu metot, daha sonra yapılması planlanan ama şu an beklemede olan görevleri filtreler.
     */
    public function ertelenenler()
    {
        // Sadece giriş yapmış kullanıcıya ait ertelenmiş görevleri çekip listeler.
        $tasks = auth()->user()->tasks()->where('durum', 'Ertelenmiş')->get();

        // Görünüme görev listesi ve toplam adet bilgisi gönderilir.
        return view('tasks.ertelenenler', [
            'tasks' => $tasks,
            'count' => $tasks->count(),
        ]);
    }

    /**
     * Taslak durumundaki görevleri listeleyen metot.
     * Henüz tamamlanmamış ya da kayda değer görülmeyen görevlerin bekleme listesi burada görüntülenir.
     */
    public function taslaklar()
    {
        // Sadece giriş yapmış kullanıcıya ait taslak görevleri getirir.
        $tasks = auth()->user()->tasks()->where('durum', 'Taslak')->get();

        // Taslak görevlerin listesi görünüm katmanına iletilir.
        return view('tasks.taslaklar', [
            'tasks' => $tasks,
            'count' => $tasks->count(),
        ]);
    }

    /**
     * Yeni görev eklemek için form sayfasını gösteren metot.
     * Kullanıcıya görev başlığı, tarih ve durum seçimi için giriş formunu sunar.
     */
    public function create()
    {
        // Yeni görev ekleme formunu görüntüler.
        return view('tasks.ekle');
    }

    /**
     * Yeni görevi doğrulayıp veritabanına kaydeden metot.
     * Formdan gelen verinin geçerli olup olmadığını kontrol eder ve ardından görevi kaydeder.
     */
    public function store(Request $request)
    {
        // Gelen form verisini kurallara göre doğrular.
        $request->validate([
            'baslik' => 'required|string|max:255',
            'tarih'  => 'required|date',
            'durum'  => 'required|in:yapilacak,yapilmis,ertelenmis,taslak',
        ]);

        // URL anahtarını, kullanıcıya gösterilecek gerçek durum adıyla eşler.
        $durumAdi = Task::$statusMap[$request->durum];

        // Yeni görev kaydı oluşturur ve giriş yapmış kullanıcıya bağlar.
        auth()->user()->tasks()->create([
            'baslik'   => $request->baslik,
            'aciklama' => $request->aciklama,
            'tarih'    => $request->tarih,
            'durum'    => $durumAdi,
        ]);

        // Kaydetme işleminden sonra ilgili durum sayfasına yönlendirir.
        $redirectMap = [
            'yapilacak'  => 'tasks/yapilacaklar',
            'yapilmis'   => 'tasks/yapilmislar',
            'ertelenmis' => 'tasks/ertelenenler',
            'taslak'     => 'tasks/taslaklar',
        ];

        return redirect($redirectMap[$request->durum]);
    }

    /**
     * Bir görevin durumunu güncelleyen metot.
     * URL: /tasks/{id}/status/{statusKey}
     *
     * @param int    $id        Görev ID
     * @param string $statusKey Durum anahtarı (yapilacak, yapilmis, ertelenmis, taslak)
     */
    public function updateStatus($id, $statusKey)
    {
        // Geçersiz durum anahtarı gelirse güvenli bir şekilde ana sayfaya yönlendirir.
        if (!isset(Task::$statusMap[$statusKey])) {
            return redirect('/');
        }

        // Verilen ID'ye ait görevi bulur ve kullanıcının kendi görevi olduğunu doğrular.
        $task = auth()->user()->tasks()->where('id', $id)->firstOrFail();

        // Görevin durumunu yeni değerle günceller.
        $task->update(['durum' => Task::$statusMap[$statusKey]]);

        // Güncellenen görev, ait olduğu durum sayfasına yönlendirilir.
        $redirectMap = [
            'yapilacak'  => 'tasks/yapilacaklar',
            'yapilmis'   => 'tasks/yapilmislar',
            'ertelenmis' => 'tasks/ertelenenler',
            'taslak'     => 'tasks/taslaklar',
        ];

        return redirect($redirectMap[$statusKey]);
    }
}
