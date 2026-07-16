<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // HasFactory: Veritabanına test verisi basmak (seeding/factory) için gerekli özellikleri ekler.
    // Notifiable: Kullanıcıya e-posta, SMS gibi bildirimler gönderebilmek için gerekli özellikleri ekler.
    use HasFactory, Notifiable;

    /**
     * Toplu Atama (Mass Assignment) İzinleri
     * Kayıt olurken veya güncellenirken dışarıdan (formdan) direkt olarak veritabanına 
     * yazılmasına izin verilen sütunların listesidir. Güvenlik için kullanılır.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Gizli Tutulacak Alanlar
     * Kullanıcı verisi API üzerinden JSON olarak çekildiğinde veya ekrana basıldığında
     * görünmemesi, gizlenmesi gereken kritik sütunları belirler.
     * Şifrelerin ve oturum tokenlarının dışarı sızmasını engeller.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tip Dönüşümleri (Casting)
     * Veritabanından veriler string (metin) olarak gelse bile, Laravel'in onları
     * PHP tarafında hangi formata dönüştüreceğini belirler.
     */
    protected function casts(): array
    {
        return [
            // E-posta doğrulama tarihi veritabanından çekilirken otomatik olarak bir Carbon (Tarih/Zaman) objesine dönüşür.
            'email_verified_at' => 'datetime',
            
            // Şifreler kaydedilirken otomatik olarak güvenli bir şekilde hashlenir (şifrelenir).
            'password' => 'hashed',
        ];
    }

    /**
     * Veritabanı İlişkisi (Relationship): Kullanıcı -> Görevler
     * 
     * Bu metot, bir kullanıcının BİRDEN FAZLA göreve (hasMany) sahip olabileceğini belirtir.
     * `auth()->user()->tasks` şeklinde kullanıldığında Laravel otomatik olarak `tasks` tablosuna gider
     * ve `user_id` sütunu bu kullanıcının ID'sine eşit olan tüm kayıtları getirir.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
