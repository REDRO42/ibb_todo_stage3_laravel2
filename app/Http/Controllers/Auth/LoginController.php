<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * LoginController (Giriş Yapma Kontrolcüsü)
 * Bu sınıf, kullanıcıların sisteme e-posta ve şifreleriyle giriş yapmalarını sağlar.
 * Laravel UI paketi tarafından otomatik oluşturulmuştur.
 * "AuthenticatesUsers" trait'i (özelliği) sayesinde login işleminin tüm arka plan kodlarını (şifre doğrulama, oturum açma) kendi içinde halleder.
 */
class LoginController extends Controller implements HasMiddleware
{
    // Laravel'in hazır giriş yapma mekanizmalarını sınıfa dahil eder
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest', except: ['logout']),
            new Middleware('auth', only: ['logout']),
        ];
    }
}
