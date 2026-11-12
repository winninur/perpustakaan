# Setup Windows (XAMPP + PowerShell) - SIP Perpustakaan

> Panduan lengkap setup project di Windows dengan XAMPP.

---

## Masalah Umum di Windows

| Masalah | Solusi |
|---------|--------|
| `chmod not recognized` | `chmod` command Linux. Di Windows langsung jalankan `setup.bat` |
| `vendor/autoload.php not found` | Belum jalankan `composer install`. Lihat step di bawah |
| `php artisan` tidak jalan | PHP belum di PATH. Tambah `D:\xampp\php` ke environment variable |
| `composer not recognized` | Composer belum terinstall. Lihat panduan install di bawah |

---

## PRASYARAT

### 1. Install XAMPP
- Download dari https://www.apachefriends.org/
- Install di `D:\xampp` (default)
- Saat install, centang: **Apache**, **MySQL**, **PHP**

### 2. Install Composer (PENTING!)
Jika `composer` belum terdeteksi:

1. Download dari https://getcomposer.org/download/
2. Jalankan **Composer-Setup.exe**
3. Saat ditanya **"Choose command-line PHP"**, pilih:
   ```
   D:\xampp\php\php.exe
   ```
4. Saat ditanya **"Use proxy"**, pilih **NO** (kecuali kamu pakai proxy)
5. Klik **Install** → tunggu sampai selesai
6. **TUTUP PowerShell yang sekarang**, buka PowerShell baru
7. Tes: ketik `composer --version`

### 3. Tambah PHP ke PATH (Opsional, kalau `php` tidak dikenali)
1. Tekan `Win + R`, ketik `sysdm.cpl`, Enter
2. Tab **Advanced** → klik **Environment Variables**
3. Di **System variables**, cari `Path` → klik **Edit**
4. Klik **New** → ketik `D:\xampp\php`
5. OK, OK, OK
6. **TUTUP PowerShell**, buka baru

---

## SETUP (3 langkah)

### Step 1: Setup Database di phpMyAdmin

1. Jalankan **XAMPP Control Panel**
2. Klik **Start** di baris **Apache** dan **MySQL**
3. Buka browser: **http://localhost/phpmyadmin**
4. Klik tab **Database**
5. Buat database baru:
   - Name: `db_perpustakaan`
   - Collation: `utf8mb4_unicode_ci`
6. Klik **Create**

### Step 2: Setup Project

Buka **PowerShell** di folder project (klik kanan folder → **Open in Terminal**):

```powershell
# Cara 1: Pakai script otomatis (RECOMMENDED)
.\setup.bat

# Cara 2: Manual satu-satu
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan config:clear
```

### Step 3: Jalankan Server

```powershell
php artisan serve
```

Buka browser: **http://localhost:8000**

Login: `admin@perpus.id / admin123`

---

## Troubleshooting Windows

### Error 1: `vendor/autoload.php not found`

**Sebab:** Belum jalankan `composer install`.

**Solusi:**
```powershell
composer install
```

Tunggu sampai selesai (2-5 menit tergantung koneksi internet).

### Error 2: `composer not recognized`

**Sebab:** Composer belum terinstall atau belum restart PowerShell.

**Solusi:**
1. Install Composer (lihat **PRASYARAT** di atas)
2. **Tutup PowerShell**, buka baru
3. Tes: `composer --version`

### Error 3: `php not recognized`

**Sebab:** PHP belum di PATH environment.

**Solusi:** Lihat **PRASYARAT #3** di atas.

### Error 4: `SQLSTATE[HY000] [2002] Connection refused`

**Sebab:** MySQL belum jalan.

**Solusi:**
1. Buka **XAMPP Control Panel**
2. Klik **Start** di baris **MySQL**
3. Pastikan warnanya hijau

### Error 5: `SQLSTATE[HY000] [1049] Unknown database 'db_perpustakaan'`

**Sebab:** Database belum dibuat.

**Solusi:** Lihat **Step 1** di atas.

### Error 6: `SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost'`

**Sebab:** Password MySQL tidak sesuai.

**Solusi:** Edit file `.env` di folder project:
```
DB_USERNAME=root
DB_PASSWORD=
```

Untuk XAMPP default:
- Username: `root`
- Password: (kosong)

Lalu jalankan:
```powershell
php artisan config:clear
```

### Error 7: Halaman blank putih

**Cek log:**
```powershell
type storage\logs\laravel.log
```

Atau buka file `storage/logs/laravel.log` dengan Notepad.

### Error 8: Error 500 / Server Error

Aktifkan debug mode:
1. Buka `.env` dengan Notepad/VSCode
2. Pastikan:
```
APP_DEBUG=true
APP_ENV=local
```
3. Clear cache:
```powershell
php artisan config:clear
```

### Error 9: `The stream or file could not be opened`

**Sebab:** Permission folder storage.

**Solusi:**
1. Klik kanan folder `storage` → Properties
2. Tab Security → Edit → pilih Users → centang **Full Control**
3. Lakukan sama untuk folder `bootstrap\cache`

### Error 10: Port 8000 sudah dipakai

**Solusi:** Jalankan di port lain:
```powershell
php artisan serve --port=8080
```

Buka: **http://localhost:8080**

---

## Cek Environment dengan check.php

Kalau mau tau persis apa yang error, buka browser:
```
http://localhost:8000/check.php
```

Halaman ini akan menampilkan:
- ✅/❌ PHP version
- ✅/❌ Required extensions
- ✅/❌ Folder permission
- ✅/❌ Database connection

---

## Reset Total (kalau error parah)

```powershell
# Hapus vendor & cache
Remove-Item -Recurse -Force vendor
Remove-Item -Recurse -Force bootstrap\cache\*
Remove-Item -Recurse -Force storage\framework\cache\data\*
Remove-Item -Recurse -Force storage\framework\sessions\*
Remove-Item -Recurse -Force storage\framework\views\*

# Install ulang
composer install

# Setup ulang
copy .env.example .env
php artisan key:generate

# Pastikan database db_perpustakaan sudah dibuat di phpMyAdmin

# Migrate
php artisan migrate:fresh --seed

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Jalankan server
php artisan serve
```

---

## Checklist Setup Lengkap

Print ini dan centang satu-satu:

- [ ] XAMPP terinstall di `D:\xampp`
- [ ] Composer terinstall (`composer --version` jalan)
- [ ] PHP di PATH (`php -v` jalan)
- [ ] Apache & MySQL jalan di XAMPP Control Panel
- [ ] Database `db_perpustakaan` dibuat di phpMyAdmin
- [ ] `composer install` berhasil di folder project
- [ ] `.env` file ada (copy dari `.env.example`)
- [ ] `php artisan key:generate` berhasil
- [ ] `php artisan migrate --seed` berhasil
- [ ] Buka `http://localhost:8000` → halaman login muncul
- [ ] Login dengan `admin@perpus.id / admin123` → masuk dashboard
- [ ] Hapus `public/check.php` setelah semua jalan

---

&copy; Kelompok AF - Pemrograman Web Tingkat Mahir
