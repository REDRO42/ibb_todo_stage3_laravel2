<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TodoApp - Yapılacaklar Uygulaması</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<!-- Ana Kutu -->
<div class="container py-4">
<div class="main-box">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <i class="bi bi-check2-square text-primary fs-3 me-2"></i>
            <span class="fw-bold text-primary fs-4">TodoApp</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            @auth
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active btn btn-primary text-white rounded-pill px-3 mx-1' : 'mx-1' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door"></i> Anasayfa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tasks.create') ? 'active btn btn-primary text-white rounded-pill px-3 mx-1' : 'mx-1' }}" href="{{ route('tasks.create') }}">
                        <i class="bi bi-plus-circle"></i> Ekle
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tasks.yapilacaklar') ? 'active btn btn-primary text-white rounded-pill px-3 mx-1' : 'mx-1' }}" href="{{ route('tasks.yapilacaklar') }}">
                        <i class="bi bi-list-task"></i> Yapılacaklar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tasks.yapilmislar') ? 'active btn btn-primary text-white rounded-pill px-3 mx-1' : 'mx-1' }}" href="{{ route('tasks.yapilmislar') }}">
                        <i class="bi bi-check2"></i> Yapılmışlar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tasks.ertelenenler') ? 'active btn btn-primary text-white rounded-pill px-3 mx-1' : 'mx-1' }}" href="{{ route('tasks.ertelenenler') }}">
                        <i class="bi bi-clock-history"></i> Ertelenenler
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tasks.taslaklar') ? 'active btn btn-primary text-white rounded-pill px-3 mx-1' : 'mx-1' }}" href="{{ route('tasks.taslaklar') }}">
                        <i class="bi bi-file-earmark"></i> Taslaklar
                    </a>
                </li>

                <!-- Kullanıcı Adı ve Çıkış Yap -->
                <li class="nav-item dropdown ms-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-1"></i> Çıkış Yap
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
            @else
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link mx-1" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right"></i> Giriş Yap
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary text-white rounded-pill px-3 mx-1" href="{{ route('register') }}">
                        <i class="bi bi-person-plus"></i> Kayıt Ol
                    </a>
                </li>
            </ul>
            @endauth
        </div>
    </div>
</nav>

<!-- Sayfa İçeriği -->
<div class="main-content p-4">
    @yield('content')
</div>

</div><!-- /main-box -->
</div><!-- /container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
