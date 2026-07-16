<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * User modeli için varsayılan rastgele veri şablonunu belirler.
     * `User::factory()->create()` çağrıldığında ve özel veri belirtilmediğinde buradaki kurallar işler.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // fake() fonksiyonu Faker kütüphanesini çağırır ve bize sahte veriler üretir.
            
            // Rastgele bir insan ismi üretir (örn: John Doe)
            'name' => fake()->name(),
            
            // Benzersiz ve geçerli formata sahip rastgele bir e-posta üretir (örn: x@example.org)
            'email' => fake()->unique()->safeEmail(),
            
            // E-postanın doğrulandığı tarihi şu anki zaman (now()) olarak ayarlar
            'email_verified_at' => now(),
            
            // Şifreyi belirler. Eğer daha önce belirlenmişse onu kullanır, yoksa 'password' kelimesini şifreleyerek atar
            'password' => static::$password ??= Hash::make('password'),
            
            // "Beni Hatırla" özelliği için rastgele 10 karakterlik bir token oluşturur
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
