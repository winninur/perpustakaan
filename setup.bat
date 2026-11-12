@echo off
REM =============================================================================
REM  SETUP - Sistem Informasi Perpustakaan (Laravel 12) - Windows Version
REM  Kelompok AF - Pemrograman Web Tingkat Mahir
REM =============================================================================
REM  Cara pakai:
REM    1. Klik kanan file ini -> Run as administrator (recommended)
REM  Atau:
REM    1. Buka CMD/PowerShell di folder project
REM    2. Ketik: setup.bat
REM
REM  Script ini akan otomatis:
REM    - Cek composer & PHP
REM    - Install dependency (composer install)
REM    - Copy .env.example ke .env
REM    - Generate APP_KEY
REM    - Buat folder storage yang dibutuhkan
REM    - Migrate database + seed
REM    - Clear cache
REM =============================================================================

setlocal enabledelayedexpansion

REM Warna (Windows 10+)
echo.
echo ==================================================
echo   SETUP - Sistem Informasi Perpustakaan
echo ==================================================
echo.

REM Step 0: Cek PHP
echo [0/8] Cek PHP...
where php >nul 2>nul
if errorlevel 1 (
    echo [ERROR] PHP tidak ditemukan di PATH!
    echo.
    echo Solusi untuk XAMPP:
    echo   1. Buka XAMPP Control Panel
    echo   2. Pastikan Apache sedang berjalan (Start)
    echo   3. Atau tambahkan D:\xampp\php ke PATH environment variable
    echo.
    pause
    exit /b 1
)
for /f "tokens=*" %%i in ('php -v') do set PHP_VERSION=%%i
echo OK - !PHP_VERSION!
echo.

REM Step 0.5: Cek Composer
echo [0.5/8] Cek Composer...
where composer >nul 2>nul
if errorlevel 1 (
    echo [ERROR] Composer tidak ditemukan di PATH!
    echo.
    echo Cara install Composer di Windows:
    echo   1. Download dari: https://getcomposer.org/download/
    echo   2. Jalankan Composer-Setup.exe
    echo   3. Saat ditanya "Use proxy", pilih NO
    echo   4. Saat ditanya path PHP, pilih: D:\xampp\php\php.exe
    echo   5. Setelah selesai, TUTUP PowerShell dan buka lagi
    echo.
    pause
    exit /b 1
)
echo OK - composer ditemukan
echo.

REM Step 1: composer install
echo [1/8] Install dependency PHP (composer install)...
echo Mungkin butuh beberapa menit, sabar ya...
call composer install --no-interaction --prefer-dist
if errorlevel 1 (
    echo [ERROR] composer install gagal!
    pause
    exit /b 1
)
echo Done.
echo.

REM Step 2: Copy .env
echo [2/8] Setup file .env...
if not exist .env (
    copy .env.example .env >nul
    echo File .env dibuat dari .env.example
) else (
    echo File .env sudah ada, lewati
)
echo.

REM Step 3: Generate APP_KEY
echo [3/8] Generate APP_KEY...
call php artisan key:generate --force
echo Done.
echo.

REM Step 4: Buat folder storage
echo [4/8] Setup folder storage...
if not exist storage\app\public mkdir storage\app\public
if not exist storage\app\private mkdir storage\app\private
if not exist storage\framework\cache\data mkdir storage\framework\cache\data
if not exist storage\framework\sessions mkdir storage\framework\sessions
if not exist storage\framework\views mkdir storage\framework\views
if not exist storage\logs mkdir storage\logs
if not exist bootstrap\cache mkdir bootstrap\cache
echo Done.
echo.

REM Step 5: Info database
echo [5/8] Cek database...
echo.
echo ==================================================
echo  PENTING - PASTIKAN INI SUDAH DILAKUKAN:
echo ==================================================
echo   1. Buka http://localhost/phpmyadmin
echo   2. Klik tab "Database"
echo   3. Buat database baru:
echo         Nama database : db_perpustakaan
echo         Collation     : utf8mb4_unicode_ci
echo   4. Klik "Create"
echo.
echo   Konfigurasi default di .env (XAMPP):
echo         DB_HOST     = 127.0.0.1
echo         DB_PORT     = 3306
echo         DB_DATABASE = db_perpustakaan
echo         DB_USERNAME = root
echo         DB_PASSWORD = (kosong)
echo.
echo   Tekan ENTER setelah database dibuat...
pause

REM Step 6: Migrate database
echo [6/8] Migrate database + seed...
call php artisan migrate:fresh --seed --force
if errorlevel 1 (
    echo [ERROR] Migrate gagal! Cek koneksi database di .env
    pause
    exit /b 1
)
echo Done.
echo.

REM Step 7: Clear cache
echo [7/8] Clear cache...
call php artisan config:clear
call php artisan cache:clear
call php artisan view:clear
call php artisan route:clear
echo Done.
echo.

REM Step 8: Selesai
echo [8/8] Setup selesai!
echo.
echo ==================================================
echo   AKUN LOGIN DEFAULT
echo ==================================================
echo   Admin      : admin@perpus.id       / admin123
echo   Pustakawan : pustakawan@perpus.id  / pustakawan123
echo   Pustakawan : budi@perpus.id        / pustakawan123
echo.
echo ==================================================
echo   CARA JALANKAN SERVER
echo ==================================================
echo   php artisan serve
echo.
echo   Lalu buka: http://localhost:8000
echo ==================================================
echo.
pause
endlocal
