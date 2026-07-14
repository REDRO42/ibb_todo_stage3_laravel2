<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Varsayılan 8 görev — eski projedeki verilerle birebir aynı
     */
    public function run(): void
    {
        Task::insert([
            ['baslik' => 'Proje sunumu hazırla',       'tarih' => '2026-07-10', 'durum' => 'Yapılacak',  'created_at' => now(), 'updated_at' => now()],
            ['baslik' => 'Müşteri toplantısı',         'tarih' => '2026-07-12', 'durum' => 'Yapılacak',  'created_at' => now(), 'updated_at' => now()],
            ['baslik' => 'Bootstrap öğren',            'tarih' => '2026-07-15', 'durum' => 'Yapılacak',  'created_at' => now(), 'updated_at' => now()],
            ['baslik' => 'Raporu tamamla',             'tarih' => '2026-07-05', 'durum' => 'Yapılmış',   'created_at' => now(), 'updated_at' => now()],
            ['baslik' => 'Sunum yap',                  'tarih' => '2026-07-06', 'durum' => 'Yapılmış',   'created_at' => now(), 'updated_at' => now()],
            ['baslik' => 'Web sitesi güncellemesi',     'tarih' => '2026-07-20', 'durum' => 'Ertelenmiş', 'created_at' => now(), 'updated_at' => now()],
            ['baslik' => 'Yeni blog yazısı',           'tarih' => '2026-07-25', 'durum' => 'Taslak',     'created_at' => now(), 'updated_at' => now()],
            ['baslik' => 'API dokümantasyonu',          'tarih' => '2026-07-28', 'durum' => 'Taslak',     'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
