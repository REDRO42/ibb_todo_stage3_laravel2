<?php

namespace App\Http\Controllers;

use App\Models\Task;

class HomeController extends Controller
{
    /**
     * Kullanıcının giriş yaptıktan sonra gördüğü Ana Sayfa / Görev Panosu (Dashboard) metodu.
     * Bu metot, kullanıcının görev istatistiklerini (hangi durumdan kaç görev var) hesaplar.
     */
    public function index()
    {
        // Task modelinde kendi yazdığımız static getStatusCounts() metodunu çağırıyoruz.
        // Bu metot, giriş yapmış kullanıcının görevlerini veritabanından sayar ve bize
        // ['Yapılacak' => 5, 'Yapılmış' => 2, ...] gibi bir dizi (array) döndürür.
        $counts = Task::getStatusCounts();

        // resources/views/home/index.blade.php isimli görünüm (HTML) dosyasını ekrana basıyoruz.
        // compact('counts') fonksiyonu, $counts değişkenini view içerisine yollamanın kısa yoludur.
        // Artık blade dosyasında {{$counts['Yapılacak']}} şeklinde bu sayılara ulaşabiliriz.
        return view('home.index', compact('counts'));
    }
}
