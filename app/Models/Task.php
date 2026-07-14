<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Bu alanlar görev kayıtı oluşturulurken veritabanına yazılabilir.
    // Yani kullanıcıdan gelen başlık, açıklama, tarih ve durum bilgileri burada izin verilen alanlar arasındadır.
    protected $fillable = ['user_id', 'baslik', 'aciklama', 'tarih', 'durum'];


    /**
     * URL anahtarlarını kullanıcı dostu Türkçe statü adlarına dönüştüren eşleme tablosu.
     * Örneğin "yapilacak" anahtarı, kullanıcıya görünen "Yapılacak" metnine çevrilir.
     * Bu yapı, URL'lerde Türkçe karakter problemleri yaşamamak için kullanılır.
     */
    public static $statusMap = [
        'yapilacak'  => 'Yapılacak',
        'yapilmis'   => 'Yapılmış',
        'ertelenmis' => 'Ertelenmiş',
        'taslak'     => 'Taslak',
    ];

    /**
     * Bu görevin ait olduğu kullanıcıyı döndüren ilişki.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Giriş yapmış kullanıcının görev durumlarının toplam sayısını hesaplayıp döndüren metot.
     * Bu metot, ana sayfada kaç görevün hangi kategoride olduğunu göstermek için kullanılır.
     * @return array ['Yapılacak' => 3, 'Yapılmış' => 2, ...]
     */
    public static function getStatusCounts()
    {
        $userId = auth()->id();

        return [
            'Yapılacak'  => self::where('user_id', $userId)->where('durum', 'Yapılacak')->count(),
            'Yapılmış'   => self::where('user_id', $userId)->where('durum', 'Yapılmış')->count(),
            'Ertelenmiş' => self::where('user_id', $userId)->where('durum', 'Ertelenmiş')->count(),
            'Taslak'     => self::where('user_id', $userId)->where('durum', 'Taslak')->count(),
        ];
    }
}
