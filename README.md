# SIP - Sistem Informasi Perpustakaan

> **Projek UTS Pemrograman Web Tingkat Mahir** - Kelompok AF
> Aplikasi web berbasis Laravel 12 untuk mengelola buku, kategori, anggota, peminjaman, dan pengembalian.

## Deskripsi Aplikasi

**SIP (Sistem Informasi Perpustakaan)** adalah aplikasi web yang dibangun menggunakan Laravel 12, MySQL, Blade template, dan Bootstrap 5 (template SB Admin 2). Aplikasi ini dirancang untuk mempermudah pengelolaan aktivitas perpustakaan, mulai dari pencatatan koleksi buku, kategori, data anggota, hingga transaksi peminjaman dan pengembalian.

Aplikasi mengimplementasikan arsitektur **MVC (Model-View-Controller)**, **Eloquent ORM** untuk operasi database, **migration & seeder** untuk struktur dan data awal, serta **middleware** untuk pembatasan akses berbasis role.

---

## Fitur Utama

| No | Modul             | Fitur                                                                                       |
|----|-------------------|---------------------------------------------------------------------------------------------|
| 1  | Dashboard         | Ringkasan statistik (total buku, anggota, peminjaman, denda) + peminjaman terbaru & top buku |
| 2  | Kategori Buku     | CRUD lengkap + **DataTables.js** (search, sort, pagination, export Copy/CSV/Excel/PDF/Print) |
| 3  | Buku              | CRUD + relasi ke kategori + DataTables + filter dropdown kategori                           |
| 4  | Anggota           | CRUD + DataTables + filter status                                                           |
| 5  | Peminjaman        | CRUD + DataTables + filter status + perhitungan denda otomatis (Rp 1.000/hari)              |
| 6  | Pengembalian      | Tombol aksi "Proses Pengembalian" yang mengembalikan stok buku & menghitung denda           |
| 7  | Manajemen User    | CRUD user + DataTables + filter role + atur role (khusus Admin)                             |
| 8  | Authentication    | Login, Logout, Remember Me                                                                  |
| 9  | Authorization     | 2 role (Admin & Pustakawan) dengan middleware terpisah                                       |
| 10 | Protected Routes  | Semua route selain `/login` dilindungi middleware `auth`; route user dilindungi `admin`      |

> **Library frontend:** DataTables.js 1.13.7 + Buttons extension (HTML5 export)
> dengan styling Bootstrap 5. Semua dimuat via CDN — tidak perlu build asset.

### Ketentuan Teknis Terpenuhi

- [x] Laravel 12 + MySQL/MariaDB
- [x] Arsitektur MVC (Model, View, Controller)
- [x] Eloquent ORM untuk operasi database
- [x] Migration & Seeder
- [x] Blade + Bootstrap 5 (SB Admin 2)
- [x] Validasi input via FormRequest + pesan kesalahan dalam Bahasa Indonesia
- [x] Password disimpan sebagai hash (bcrypt) - **bukan teks biasa**
- [x] Minimal 4 tabel berhubungan (users, categories, books, members, loans)
- [x] Relasi one-to-many: kategori-buku, buku-peminjaman, anggota-peminjaman
- [x] **Search, filter, sorting, pagination** menggunakan DataTables.js (client-side, support export Copy/CSV/Excel/PDF/Print)
- [x] 2 role dengan hak akses berbeda
- [x] Middleware & protected route

---

## Role & Hak Akses

| Role          | Hak Akses                                                                 |
|---------------|---------------------------------------------------------------------------|
| **Admin**     | Semua modul + Manajemen User (CRUD user, atur role)                       |
| **Pustakawan**| Dashboard, Kategori, Buku, Anggota, Peminjaman (TIDAK bisa kelola user)   |

Implementasi:
- Middleware `admin` membatasi route `/users/*` hanya untuk admin
- Sidebar adaptif: menu "Manajemen User" hanya muncul untuk admin
- Mencoba akses langsung ke `/users/*` sebagai pustakawan = **HTTP 403**

---

## Tabel Database

Lihat **[ERD_DATABASE.md](ERD_DATABASE.md)** untuk skema lengkap & diagram ERD.

Ringkasan:
- `users` - akun sistem (admin/pustakawan)
- `categories` - kategori buku
- `books` - data buku (FK: category_id)
- `members` - data anggota perpustakaan
- `loans` - transaksi peminjaman (FK: member_id, book_id, user_id)
- `password_reset_tokens`, `sessions` - tabel pendukung Laravel

---

## Instalasi

### Prasyarat

- PHP >= 8.2 (dengan ekstensi pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json, bcmath)
- Composer >= 2.x
- MySQL 8 / MariaDB 10.x
- Node.js & NPM (opsional, untuk build asset)

### Langkah Instalasi

```bash
# 1. Extract / clone project
cd perpustakaan-laravel

# 2. Install dependency PHP
composer install

# 3. Install dependency JS (opsional, asset via CDN sudah include)
npm install && npm run build   # atau skip karena pakai CDN

# 4. Salin .env
cp .env.example .env

# 5. Generate APP_KEY
php artisan key:generate

# 6. Buat database di MySQL/phpMyAdmin bernama: db_perpustakaan
#    (lihat panduan di bawah)

# 7. Sesuaikan koneksi database di file .env
#    DB_DATABASE=db_perpustakaan
#    DB_USERNAME=root
#    DB_PASSWORD=...

# 8. Jalankan migration + seeder
php artisan migrate --seed

# 9. Jalankan server development
php artisan serve
```

Buka browser: **http://localhost:8000**

---

## Konfigurasi Database via phpMyAdmin

1. Buka **phpMyAdmin** (umumnya: `http://localhost/phpmyadmin`).
2. Klik tab **Database** → buat database baru:
   - Nama database: `db_perpustakaan`
   - Collation: `utf8mb4_unicode_ci`
   - Klik **Create**.
3. Buka file `.env` di folder project, sesuaikan:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_perpustakaan
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. Jalankan di terminal:
   ```bash
   php artisan migrate --seed
   ```
5. Refresh phpMyAdmin, pilih database `db_perpustakaan`. Anda akan melihat tabel:
   - `users`, `categories`, `books`, `members`, `loans`
   - `password_reset_tokens`, `sessions`, `cache`, `jobs`, `migrations`

---

## Akun Default

Setelah `php artisan migrate --seed`, akun berikut sudah tersedia:

| Role          | Email                  | Password        |
|---------------|------------------------|-----------------|
| **Admin**     | `admin@perpus.id`      | `admin123`      |
| **Pustakawan**| `pustakawan@perpus.id` | `pustakawan123` |
| **Pustakawan**| `budi@perpus.id`       | `pustakawan123` |

> Password disimpan dalam bentuk hash bcrypt. Jangan pernah menyimpan password plain-text.

---

## Struktur Folder

```
perpustakaan-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/LoginController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── BookController.php
│   │   │   ├── MemberController.php
│   │   │   ├── LoanController.php
│   │   │   └── UserController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       ├── PustakawanMiddleware.php
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Category.php
│       ├── Book.php
│       ├── Member.php
│       └── Loan.php
├── bootstrap/app.php
├── config/ (app.php, auth.php, database.php)
├── database/
│   ├── migrations/   (5 file migration)
│   └── seeders/      (DatabaseSeeder, UserSeeder)
├── public/ (index.php, .htaccess)
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── partials/ (sidebar, navbar, footer, flash)
│   ├── auth/login.blade.php
│   ├── dashboard.blade.php
│   ├── categories/ (index, create, edit, show)
│   ├── books/ (index, create, edit, show)
│   ├── members/ (index, create, edit, show)
│   ├── loans/ (index, create, edit, show)
│   └── users/ (index, create, edit)
├── routes/web.php
├── .env.example
├── composer.json
└── README.md
```

---

## Pengujian Minimal

Sesuai ketentuan soal UTS, berikut skenario pengujian:

1. **Login valid** - Login dengan `admin@perpus.id / admin123` → berhasil masuk dashboard.
2. **Login invalid** - Login dengan email/password salah → muncul pesan error.
3. **Logout** - Klik menu User → Logout → diarahkan ke halaman login.
4. **Akses terlindungi** - Setelah logout, akses `/dashboard` → diarahkan ke `/login`.
5. **CRUD data** - Tambah, lihat, edit, hapus pada modul Kategori/Buku/Anggota/Peminjaman.
6. **Validasi** - Coba submit form kosong → muncul pesan kesalahan dalam Bahasa Indonesia.
7. **Search/Filter/Sort/Pagination** - Lakukan pencarian di kotak search DataTables, klik header kolom untuk sort, pakai dropdown filter status/role/kategori, ganti jumlah baris per halaman.
8. **Hak akses** - Login sebagai Pustakawan, akses langsung ke `/users` → **HTTP 403**.

---

## Lisensi

Projek edukasi untuk mata kuliah Pemrograman Web Tingkat Mahir.

&copy; {{ date('Y') }} - Kelompok AF
"# perpustakaan" 
"# perpustakaan" 
