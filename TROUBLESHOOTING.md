# Troubleshooting - SIP Perpustakaan

> Panduan cepat mengatasi error yang umum muncul saat setup project Laravel.

---

## Jalankan SETUP OTOMATIS dulu

Sebelum troubleshoot manual, jalankan:

```bash
chmod +x setup.sh
./setup.sh
```

Script ini akan otomatis install dependency, generate APP_KEY, migrate database, dan seed data.

---

## 1. Error: "No application encryption key has been specified"

**Penyebab:** APP_KEY belum di-generate.

**Solusi:**
```bash
php artisan key:generate
php artisan config:clear
```

---

## 2. Error: "The .env file does not exist"

**Penyebab:** File .env belum dibuat dari .env.example.

**Solusi:**
```bash
cp .env.example .env
php artisan key:generate
```

---

## 3. Error merah saat buka localhost:8000

Kemungkinan penyebab & solusi:

### a) Vendor belum terinstall
```bash
composer install
```

### b) APP_KEY belum ada
```bash
php artisan key:generate
```

### c) Config cache corrupt
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### d) Permission folder storage
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 4. Error: "SQLSTATE[HY000] [2002] Connection refused"

**Penyebab:** MySQL/MariaDB belum jalan.

**Solusi:**
- **XAMPP**: Buka XAMPP Control Panel → Start MySQL
- **Laragon**: Start Apache & MySQL
- **Manual**: `sudo systemctl start mysql` (Linux)

Cek juga file `.env`:
```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_perpustakaan
DB_USERNAME=root
DB_PASSWORD=
```

> Untuk XAMPP default password kosong.
> Untuk MAMP default password `root`.

---

## 5. Error: "SQLSTATE[HY000] [1049] Unknown database 'db_perpustakaan'"

**Penyebab:** Database belum dibuat.

**Solusi:**
1. Buka **http://localhost/phpmyadmin**
2. Klik tab **Database**
3. Buat database baru:
   - Name: `db_perpustakaan`
   - Collation: `utf8mb4_unicode_ci`
4. Klik **Create**
5. Jalankan:
```bash
php artisan migrate --seed
```

---

## 6. Error: "SQLSTATE[HY000] [1045] Access denied for user"

**Penyebab:** Username/password database salah.

**Solusi:** Edit `.env`:
```env
DB_USERNAME=root       # ganti dengan user MySQL kamu
DB_PASSWORD=           # isi password MySQL kamu
```
Lalu:
```bash
php artisan config:clear
```

---

## 7. Error: "Class 'str_slug' not found"

**Penyebab:** (Sudah diperbaiki di versi terbaru) `str_slug` deprecated di Laravel 8+.

**Solusi:** Pastikan pakai versi project terbaru, atau edit `config/database.php`:
```php
// Ganti:
'prefix' => env('REDIS_PREFIX', str_slug(env('APP_NAME', 'laravel'), '_').'_database_'),
// Menjadi:
'prefix' => env('REDIS_PREFIX', \Illuminate\Support\Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
```

---

## 8. Halaman login muncul tapi redirect loop

**Penyebab:** Middleware redirect salah.

**Solusi:** Pastikan `bootstrap/app.php` tidak punya baris:
```php
$middleware->redirectUsersTo('/login');
```
Hapus baris tersebut.

---

## 9. DataTables tidak muncul / tabel kosong

**Penyebab:** jQuery belum load atau script error.

**Solusi:**
1. Buka Developer Tools (F12) → tab Console → cek error
2. Pastikan koneksi internet aktif (DataTables via CDN)
3. Cek urutan script di `resources/views/layouts/app.blade.php`:
   - jQuery harus **sebelum** DataTables
   - DataTables core harus **sebelum** Buttons extension

---

## 10. Tabel kosong / tidak ada data

**Penyebab:** Seeder belum dijalankan.

**Solusi:**
```bash
php artisan migrate:fresh --seed
```

Setelah ini akun default:
- `admin@perpus.id` / `admin123`
- `pustakawan@perpus.id` / `pustakawan123`

---

## 11. Error 403 Forbidden saat akses /users

**Penyebab:** Bukan bug. Anda login sebagai Pustakawan (bukan Admin).

**Solusi:** Login dengan akun admin:
- Email: `admin@perpus.id`
- Password: `admin123`

---

## 12. Error: "500 Server Error" tanpa pesan jelas

**Solusi Cek error log:**
```bash
# Lihat log terbaru
cat storage/logs/laravel.log | tail -50

# Atau aktifkan debug:
# Edit .env, set:
APP_DEBUG=true
APP_ENV=local
```

Lalu refresh browser.

---

## 13. Halaman blank putih

**Kemungkinan:**
- PHP version kurang dari 8.2 (Laravel 12 butuh PHP >= 8.2)
  ```bash
  php -v
  ```
- Extension PHP kurang. Pastikan aktif di `php.ini`:
  - `pdo_mysql`
  - `mbstring`
  - `openssl`
  - `tokenizer`
  - `xml`
  - `ctype`
  - `json`
  - `bcmath`
  - `fileinfo`
  - `curl`

---

## 14. Tampilan rusak / CSS tidak load

**Kemungkinan:**
- Internet mati (CSS via CDN)
- URL APP salah

**Solusi:**
- Cek `APP_URL` di `.env`:
```env
APP_URL=http://localhost:8000
```
- Pastikan internet aktif untuk load CDN Bootstrap & DataTables

---

## 15. Migration error "Class not found"

**Solusi:**
```bash
composer dump-autoload
php artisan migrate:fresh --seed
```

---

## Langkah Paling Pasti: Reset Total

Kalau semua sudah dicoba tetap error:

```bash
# 1. Hapus vendor & cache
rm -rf vendor bootstrap/cache/* storage/framework/cache/data/* storage/framework/sessions/* storage/framework/views/*

# 2. Install ulang
composer install --no-interaction --prefer-dist

# 3. Setup ulang .env
cp .env.example .env
php artisan key:generate

# 4. Pastikan database db_perpustakaan sudah dibuat di phpMyAdmin

# 5. Migrate
php artisan migrate:fresh --seed

# 6. Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 7. Jalankan server
php artisan serve
```

---

## Butuh Bantuan?

Cek file log detail:
```bash
cat storage/logs/laravel.log
```

Buka issue di GitHub kelompok dengan copy-paste log error terbaru.

---

&copy; Kelompok AF - Pemrograman Web Tingkat Mahir
