<?php

namespace App\Http\Controllers;

use App\Models\Task;

class HomeController extends Controller
{
    /**
     * Ana sayfayı hazırlayan metot.
     * Bu metot, görevlerin durum bazlı sayılarını veritabanından alır ve ana sayfadaki görünümde gösterilmek üzere view'e gönderir.
     */
    public function index()
    {
        // Task modelinden durum sayılarını çekip ana sayfa için hazırlar.
        $counts = Task::getStatusCounts();

        // Ana sayfa görünümüne sayıları taşıyarak sayfayı oluşturur.
        return view('home.index', compact('counts'));
    }
}
