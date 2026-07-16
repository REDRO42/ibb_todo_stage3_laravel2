<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Uygulamanın ana seeder (veritabanı doldurucu) metodu.
     * `php artisan db:seed` komutu çalıştırıldığında ilk olarak bu metod çalışır.
     * Görevi, projede oluşturduğumuz diğer tüm alt seeder dosyalarını sırasıyla çağırmaktır.
     */
    public function run(): void
    {
        // call() fonksiyonu içine verilen seeder sınıflarını sırasıyla çalıştırır.
        $this->call([
            // Önce kullanıcıları oluşturuyoruz ki, görevler eklendiğinde bağlanacak bir kullanıcı olsun.
            UserSeeder::class,
            
            // Kullanıcı oluştuktan sonra, görevleri veritabanına ekliyoruz.
            TaskSeeder::class,
        ]);
    }
}
