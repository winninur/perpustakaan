# QUICKSTART Windows (XAMPP + VSCode) — 5 Command Doang

> **Intinya cuma 5 command ini, copy-paste satu-satu ke PowerShell:**

```powershell
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Lalu buka browser → **http://localhost:8000** → login `admin@perpus.id / admin123`.

---

## Kenapa "Semua Merah" di VSCode?

Itu **BUKAN error kode**. Itu karena folder `vendor/` belum dibuat. Setelah kamu jalankan `composer install` (command #1), semua tanda merah di VSCode akan **hilang otomatis**.

---

## SEBELUM JALANIN 5 COMMAND DI ATAS, PASTIKAN:

### 1. Composer terinstall
Cek di PowerShell:
```powershell
composer --version
```

Kalau error `composer not recognized`:
1. Download dari https://getcomposer.org/download/
2. Jalankan **Composer-Setup.exe**
3. Saat ditanya PHP path → pilih `D:\xampp\php\php.exe`
4. **Tutup PowerShell**, buka PowerShell baru
5. Tes lagi `composer --version`

### 2. MySQL jalan di XAMPP
1. Buka **XAMPP Control Panel**
2. Klik **Start** di baris **Apache** dan **MySQL**
3. Pastikan warnanya **hijau**

### 3. Database sudah dibuat
1. Buka browser → **http://localhost/phpmyadmin**
2. Klik tab **Database**
3. Buat baru:
   - Name: `db_perpustakaan`
   - Collation: `utf8mb4_unicode_ci`
4. Klik **Create**

### 4. Cek environment sebelum setup (OPTIONAL tapi recommended)
Jalankan:
```powershell
php check.php
```

Ini akan otomatis ngecek: PHP version, extension, composer, MySQL jalan, database ada, dll. Kalau ada [FAIL], perbaiki dulu sesuai petunjuk yang muncul.

---

## 5 COMMAND PENJELASAN

| # | Command | Apa fungsinya |
|---|---------|---------------|
| 1 | `composer install` | Download semua library PHP ke folder `vendor/`. **Butuh internet, 2-5 menit.** |
| 2 | `copy .env.example .env` | Bikin file config `.env` dari template |
| 3 | `php artisan key:generate` | Generate APP_KEY untuk enkripsi session |
| 4 | `php artisan migrate:fresh --seed` | Bikin semua tabel database + isi data awal (admin, pustakawan) |
| 5 | `php artisan serve` | Jalankan server Laravel di http://localhost:8000 |

---

## KALAU `php artisan serve` SUDAH JALAN

Buka browser:
- **http://localhost:8000** → halaman login
- Login: `admin@perpus.id / admin123` atau `pustakawan@perpus.id / pustakawan123`

Kalau mau berhenti server: tekan **Ctrl+C** di PowerShell.

---

## ERROR UMUM & SOLUSI CEPAT

### Error: `composer not recognized`
→ Install Composer dulu (lihat di atas)

### Error: `php not recognized`
→ Tambah `D:\xampp\php` ke PATH environment variable Windows:
1. Win + R → ketik `sysdm.cpl` → Enter
2. Tab Advanced → Environment Variables
3. Di System variables, cari `Path` → Edit → New → ketik `D:\xampp\php`
4. OK semua, **tutup PowerShell**, buka baru

### Error: `vendor/autoload.php not found`
→ Jalankan `composer install` dulu (command #1)

### Error: `Access denied for user 'root'@'localhost'`
→ Edit file `.env` dengan Notepad/VSCode:
```
DB_USERNAME=root
DB_PASSWORD=
```
(XAMPP default: user=root, password=kosong)

### Error: `Unknown database 'db_perpustakaan'`
→ Database belum dibuat di phpMyAdmin (lihat step 3 di atas)

### Error: `Connection refused` MySQL
→ MySQL belum jalan. Start MySQL di XAMPP Control Panel.

### Error: `composer install` lama banget / hang
→ Tunggu aja, emang butuh 2-5 menit tergantung internet. Atau coba:
```powershell
composer install --prefer-dist --no-dev
```

---

## KALAU MASIH ERROR

Buka issue dengan copy-paste:

1. **Pesannya** (copy dari PowerShell, lengkap dari baris pertama sampai terakhir)
2. **Output `php check.php`** (jalankan ini dulu, copy semua outputnya)
3. **Isi file `.env` bagian DB_** (jangan include password asli)

Dengan info ini aku bisa bantu debug dengan tepat.

---

&copy; Kelompok AF
