<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Veritabanına başlangıç verilerini ekleyen metod.
     * `php artisan db:seed` komutu çalıştırıldığında bu metod tetiklenir.
     */
    public function run(): void
    {
        // User modelinin Factory'sini kullanarak veritabanına yeni bir kayıt oluşturuyoruz.
        // Factory'nin varsayılan olarak ürettiği rastgele verilerin (örneğin rastgele isim, email) yerine
        // kendi belirttiğimiz sabit verilerin kaydedilmesi için dizi içinde bu değerleri eziyoruz.
        \App\Models\User::factory()->create([
            'name' => 'Test Kullanıcı', // Kullanıcının adı
            'email' => 'test@example.com', // Giriş yaparken kullanılacak e-posta adresi
            
            // bcrypt() fonksiyonu, şifreyi veritabanına kaydetmeden önce güvenli bir şekilde şifreler (hashler).
            // Düz metin olarak "password" kelimesini şifreledik, böylece giriş formunda "password" yazıldığında eşleşme sağlanacak.
            'password' => bcrypt('password'),
        ]);
    }
}
