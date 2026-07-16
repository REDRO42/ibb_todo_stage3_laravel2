<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * Toplu Atama (Mass Assignment) İzinleri
     * 
     * Laravel'de dışarıdan (örneğin formdan) gelen bir dizi veriyi tek seferde veritabanına kaydetmek
     * (örn: Task::create($request->all())) güvenlik riski taşır. Çünkü kötü niyetli biri forma 
     * sahte bir 'is_admin' alanı ekleyip kendini yetkilendirebilir.
     * $fillable dizisi, SADECE buraya yazdığımız sütunların otomatik doldurulmasına izin vererek bu açığı kapatır.
     */
    protected $fillable = ['user_id', 'baslik', 'aciklama', 'tarih', 'durum'];

    /**
     * Durum Eşleştirme Sözlüğü (Status Map)
     * 
     * Tarayıcı URL'lerinde Türkçe karakterler veya boşluklar sorun yaratabilir (Örn: /tasks/status/Yapılacak).
     * Bu yüzden URL'de 'yapilacak' gibi temiz (İngilizce karakterli) anahtarlar kullanıyoruz.
     * Bu dizi, URL'den gelen o temiz anahtarları veritabanındaki orijinal Türkçe metinlere çevirmemizi sağlar.
     */
    public static $statusMap = [
        'yapilacak'  => 'Yapılacak',
        'yapilmis'   => 'Yapılmış',
        'ertelenmis' => 'Ertelenmiş',
        'taslak'     => 'Taslak',
    ];

    /**
     * Veritabanı İlişkisi (Relationship): Görev -> Kullanıcı
     * 
     * Bu metot, her bir görevin SADECE BİR kullanıcıya ait olduğunu (belongsTo) belirtir.
     * Eloquent ORM sayesinde artık `$task->user->name` yazarak o görevi kimin oluşturduğunu bulabiliriz.
     * Laravel arka planda otomatik olarak `user_id` sütununu arar ve `users` tablosuyla eşleştirir.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Özel Yardımcı Metot: Durum Sayılarını Getir
     * 
     * Bu statik metot (Task::getStatusCounts() şeklinde direkt sınıf üzerinden çağrılabilir),
     * sadece sisteme giriş yapmış olan kullanıcının görev panosu (Dashboard) için istatistikleri hesaplar.
     * 
     * @return array ['Yapılacak' => 3, 'Yapılmış' => 2, ...] şeklinde bir dizi döner.
     */
    public static function getStatusCounts()
    {
        // Şu an oturum açmış kullanıcının ID'sini alırız. (Misafir ise null döner)
        $userId = auth()->id();

        return [
            // self:: bulunduğu modeli (Task) temsil eder.
            // "where('user_id', $userId)" ile başkalarının görevlerini saymayı engelliyoruz.
            // count() fonksiyonu eşleşen görevlerin toplam sayısını (rakam olarak) verir.
            'Yapılacak'  => self::where('user_id', $userId)->where('durum', 'Yapılacak')->count(),
            'Yapılmış'   => self::where('user_id', $userId)->where('durum', 'Yapılmış')->count(),
            'Ertelenmiş' => self::where('user_id', $userId)->where('durum', 'Ertelenmiş')->count(),
            'Taslak'     => self::where('user_id', $userId)->where('durum', 'Taslak')->count(),
        ];
    }
}
