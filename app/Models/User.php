<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Toplu atamaya izin verilen alanlar.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * JSON/dizi dönüşümlerinde gizlenen alanlar.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Otomatik tip dönüşümü yapılacak alanlar.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Bu kullanıcıya ait görevleri döndüren ilişki.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
