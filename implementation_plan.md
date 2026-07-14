# Laravel UI ile Kullanıcı Girişi Sistemi Oluşturma Planı

Bu plan, halihazırda projeye eklediğimiz `laravel/ui` paketini kullanarak projeye kayıt olma (register) ve giriş yapma (login) sistemini entegre etmeyi amaçlamaktadır. Ayrıca, şu anda sahipsiz (herkese açık) olarak oluşturulan Görevlerin (Tasks) doğrudan giriş yapan kullanıcıya bağlanmasını sağlayacaktır.

## ⚠️ User Review Required (İnceleme Gerektirenler)
- **Mevcut Tasarımın Korunması:** `laravel/ui` normalde kendi görünüm şablonunu dayatır. Ancak sizin `resources/views/layouts/app.blade.php` dosyanızda çok şık bir Bootstrap 5 tasarımınız halihazırda var. Bu plan, **sizin mevcut tasarımınızı bozmadan** Login/Register/Logout butonlarını navbar'a (menüye) sağ üst köşeye ekleyecek şekilde hazırlandı. 

## ❓ Open Questions (Açık Sorular)
> [!IMPORTANT]
> Lütfen aşağıdaki soruları yanıtlayın, kararlarınıza göre uygulamaya geçeceğim:
> 1. **Eski Görevler Ne Olacak?** Veritabanında daha önceden eklenmiş olan ve bir kullanıcıya (User) ait olmayan görevler (tasks) olabilir. Yeni `user_id` sütununu eklediğimizde bu eski görevlerin veritabanından silinmesini mi istersiniz, yoksa sistemde ilk kayıt olan kullanıcıya mı atayalım? (Ya da geçici olarak `user_id` sütununu `nullable` (boş bırakılabilir) mi yapalım?)
> 2. **Kayıt Sistemi Açık Mı Kalsın?** Dışarıdan herkesin sisteme kayıt olup kendi yapılacaklar listesini oluşturabilmesini mi istiyorsunuz, yoksa sadece belirli kişilerin mi hesabı olmalı?

## 🛠 Proposed Changes (Önerilen Değişiklikler)

### 1. Scaffolding (Hazır Yapının Kurulması)
- `php artisan ui bootstrap --auth` komutunu çalıştırarak gerekli Auth Controller'larını, Login/Register view'larını ve `routes/web.php` içindeki `Auth::routes()` rotalarını otomatik oluşturacağız.
- Oluşan görünümleri sizin mevcut `layouts/app.blade.php` dosyanızdan faydalanacak (extend edecek) şekilde güncelleyeceğim.

### 2. Veritabanı ve Modeller (Database & Models)
#### [MODIFY] `database/migrations/2026_07_10_074408_create_tasks_table.php`
- `tasks` tablosuna `unsignedBigInteger('user_id')` sütunu eklenecek ve `users` tablosuna `foreign key` ile bağlanacak. (Bunun için eski tabloyu silip yeniden migrate edebiliriz veya yeni bir migration yazabiliriz).

#### [MODIFY] `app/Models/Task.php`
- `$fillable` dizisine `user_id` eklenecek.
- `public function user()` ilişkisi (BelongsTo) eklenecek.
- `getStatusCounts()` metodundaki sorgular `auth()->id()` ile sadece giriş yapan kullanıcının sayılarını alacak şekilde güncellenecek.

#### [MODIFY] `app/Models/User.php`
- `public function tasks()` ilişkisi (HasMany) eklenecek.

### 3. Kontrolcüler ve Rotalar (Controllers & Routes)
#### [MODIFY] `app/Http/Controllers/TaskController.php`
- Sınıfın kurucu metoduna (constructor) `$this->middleware('auth');` eklenerek **giriş yapmayanların görevleri görmesi engellenecek**.
- Görev çekme sorgularına (örn: `Task::where('durum', '...')->get()`) kullanıcının kendi ID'si eklenecek: `auth()->user()->tasks()->where('durum', '...')->get()`.
- Görev oluşturma (store) aşamasında, formdan gelen veri kaydedilirken göreve `user_id` olarak giriş yapan kişinin ID'si (`auth()->id()`) atanacak.

#### [MODIFY] `routes/web.php`
- Anasayfa (`/`) rotası sadece giriş yapmış kullanıcılara (auth) gösterilecek şekilde sınırlandırılacak veya giriş yapmamışsa otomatik Login sayfasına yönlendirilecek.

### 4. Görünümler (Views)
#### [MODIFY] `resources/views/layouts/app.blade.php`
- Navbar'a (menü) sağ üst tarafa kontrol bloğu eklenecek:
  - Eğer kullanıcı giriş yapmamışsa: **"Giriş Yap"** ve **"Kayıt Ol"** butonları.
  - Eğer kullanıcı giriş yapmışsa: Kullanıcının ismi ve **"Çıkış Yap"** butonu.

## 🧪 Verification Plan (Doğrulama Planı)

### Manuel Doğrulama
1. Siteye girildiğinde Login ekranına yönlendirdiğinden emin olunacak.
2. Yeni bir hesap (örn: ahmet@example.com) açılıp giriş yapılacak. Görev listesinin boş olduğu görülecek.
3. Yeni bir görev eklenecek. Veritabanında o görevin `user_id`'sinin doğru şekilde kaydedildiği doğrulanacak.
4. Başka bir hesapla (örn: mehmet@example.com) giriş yapıldığında, Ahmet'in eklediği görevlerin Mehmet'in sayfasında görünmediği (herkesin sadece kendi verisini gördüğü) test edilecek.
