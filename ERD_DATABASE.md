# Skema Database & ERD - SIP Perpustakaan

> Dokumentasi struktur tabel, relasi, dan Entity Relationship Diagram (ERD)
> untuk aplikasi **Sistem Informasi Perpustakaan**.

---

## Daftar Tabel

| No | Nama Tabel                | Deskripsi                                                  |
|----|---------------------------|------------------------------------------------------------|
| 1  | `users`                   | Akun sistem (admin & pustakawan)                           |
| 2  | `password_reset_tokens`   | Token reset password (default Laravel)                     |
| 3  | `sessions`                | Sesi pengguna (default Laravel)                            |
| 4  | `categories`              | Kategori buku                                              |
| 5  | `books`                   | Data buku                                                  |
| 6  | `members`                 | Data anggota perpustakaan                                  |
| 7  | `loans`                   | Transaksi peminjaman & pengembalian                        |

---

## Struktur Detail Tabel

### 1. `users`

| Kolom                | Tipe           | Keterangan                              |
|----------------------|----------------|-----------------------------------------|
| `id`                 | BIGINT, PK, AI | Primary key                             |
| `name`               | VARCHAR(100)   | Nama lengkap user                       |
| `email`              | VARCHAR(100)   | Email unik                              |
| `password`           | VARCHAR(255)   | Password (hash bcrypt)                  |
| `role`               | ENUM           | `admin` / `pustakawan` (default: pustakawan) |
| `phone`              | VARCHAR(20)    | Nomor telepon (nullable)                |
| `email_verified_at`  | TIMESTAMP      | Verifikasi email (nullable)             |
| `remember_token`     | VARCHAR(100)   | Token remember me                       |
| `created_at`         | TIMESTAMP      | Tanggal dibuat                          |
| `updated_at`         | TIMESTAMP      | Tanggal diperbarui                      |

### 2. `categories`

| Kolom            | Tipe           | Keterangan                              |
|------------------|----------------|-----------------------------------------|
| `id`             | BIGINT, PK, AI | Primary key                             |
| `kode_kategori`  | VARCHAR(20)    | Kode unik kategori (mis. KTG001)        |
| `nama_kategori`  | VARCHAR(100)   | Nama kategori                           |
| `deskripsi`      | TEXT           | Deskripsi kategori (nullable)           |
| `created_at`     | TIMESTAMP      | Tanggal dibuat                          |
| `updated_at`     | TIMESTAMP      | Tanggal diperbarui                      |

### 3. `books`

| Kolom             | Tipe           | Keterangan                                          |
|-------------------|----------------|-----------------------------------------------------|
| `id`              | BIGINT, PK, AI | Primary key                                         |
| `kode_buku`       | VARCHAR(20)    | Kode unik buku (mis. BK001)                         |
| `judul`           | VARCHAR(150)   | Judul buku                                          |
| `penulis`         | VARCHAR(100)   | Penulis                                             |
| `penerbit`        | VARCHAR(100)   | Penerbit (nullable)                                 |
| `tahun_terbit`    | YEAR           | Tahun terbit (nullable)                             |
| `isbn`            | VARCHAR(20)    | Nomor ISBN (nullable)                               |
| `category_id`     | BIGINT, FK     | **FK** → `categories.id` (relasi one-to-many)       |
| `jumlah`          | INT            | Total eksemplar                                     |
| `stok_tersedia`   | INT            | Stok yang masih bisa dipinjam                       |
| `deskripsi`       | TEXT           | Deskripsi buku (nullable)                           |
| `created_at`      | TIMESTAMP      | Tanggal dibuat                                      |
| `updated_at`      | TIMESTAMP      | Tanggal diperbarui                                  |

### 4. `members`

| Kolom             | Tipe           | Keterangan                                |
|-------------------|----------------|-------------------------------------------|
| `id`              | BIGINT, PK, AI | Primary key                               |
| `kode_anggota`    | VARCHAR(20)    | Kode unik anggota (mis. AG001)            |
| `nama`            | VARCHAR(100)   | Nama anggota                              |
| `nik_nim`         | VARCHAR(30)    | NIK / NIM (nullable)                      |
| `jenis_kelamin`   | ENUM           | `L` (Laki-laki) / `P` (Perempuan)         |
| `alamat`          | TEXT           | Alamat (nullable)                         |
| `telepon`         | VARCHAR(20)    | Nomor telepon (nullable)                  |
| `email`           | VARCHAR(100)   | Email (nullable)                          |
| `status`          | ENUM           | `aktif` / `nonaktif` (default: aktif)     |
| `created_at`      | TIMESTAMP      | Tanggal dibuat                            |
| `updated_at`      | TIMESTAMP      | Tanggal diperbarui                        |

### 5. `loans`

| Kolom                   | Tipe           | Keterangan                                              |
|-------------------------|----------------|---------------------------------------------------------|
| `id`                    | BIGINT, PK, AI | Primary key                                             |
| `kode_pinjam`           | VARCHAR(20)    | Kode unik peminjaman (mis. PJ-241105-001)               |
| `member_id`             | BIGINT, FK     | **FK** → `members.id` (relasi one-to-many)              |
| `book_id`               | BIGINT, FK     | **FK** → `books.id` (relasi one-to-many)                |
| `user_id`               | BIGINT, FK     | **FK** → `users.id` (petugas yang menangani)            |
| `tanggal_pinjam`        | DATE           | Tanggal peminjaman                                      |
| `tanggal_jatuh_tempo`   | DATE           | Batas waktu pengembalian                                |
| `tanggal_kembali`       | DATE           | Tanggal aktual pengembalian (nullable)                  |
| `status`                | ENUM           | `dipinjam` / `dikembalikan` / `terlambat`               |
| `denda`                 | INT            | Denda rupiah (Rp 1.000/hari keterlambatan)              |
| `catatan`               | TEXT           | Catatan transaksi (nullable)                            |
| `created_at`            | TIMESTAMP      | Tanggal dibuat                                          |
| `updated_at`            | TIMESTAMP      | Tanggal diperbarui                                      |

---

## Relasi Antar Tabel

```
┌────────────┐         ┌──────────────┐
│   users    │ 1      M│    loans     │
│────────────│◄────────│──────────────│
│ id (PK)    │         │ user_id (FK) │
│ role       │         │ member_id(FK)│
└────────────┘         │ book_id (FK) │
                       └──────────────┘
                              │   │
                              │   │
              ┌───────────────┘   └───────────────┐
              │ M                                1│
       ┌──────▼──────┐                     ┌──────▼──────┐
       │   members   │                     │    books    │
       │─────────────│                     │─────────────│
       │ id (PK)     │ 1               M   │ id (PK)     │
       │ kode_anggota│◄────────────────────│ kode_buku   │
       │ nama        │                     │ judul       │
       │ status      │                     │ category_id │
       └─────────────┘                     └──────┬──────┘
                                                  │ M
                                                  │
                                          ┌───────▼────────┐
                                          │  categories    │
                                          │────────────────│
                                          │ id (PK)        │ 1
                                          │ kode_kategori  │
                                          │ nama_kategori  │
                                          └────────────────┘
```

### Daftar Relasi

| Dari (Many)  | Ke (One)      | Tipe           | Jumlah             |
|--------------|---------------|----------------|--------------------|
| `books`      | `categories`  | Many-to-One    | 1 kategori - banyak buku |
| `loans`      | `members`     | Many-to-One    | 1 anggota - banyak pinjam |
| `loans`      | `books`       | Many-to-One    | 1 buku - banyak pinjam    |
| `loans`      | `users`       | Many-to-One    | 1 user - banyak transaksi |
| `categories` | `books`       | One-to-Many    | inverse of books → categories |

**Catatan:** Semua FK menggunakan `onDelete('restrict')` agar tidak bisa hapus parent yang masih punya child.

---

## Aturan Bisnis

### 1. Stok Buku
- `stok_tersedia` dimulai sama dengan `jumlah` saat buku dibuat.
- Saat peminjaman dibuat, `stok_tersedia` dikurangi 1.
- Saat pengembalian, `stok_tersedia` ditambah 1.
- Buku dengan `stok_tersedia = 0` tidak bisa dipilih saat buat peminjaman.

### 2. Peminjaman
- Anggota harus berstatus `aktif`.
- Buku harus punya `stok_tersedia > 0`.
- `tanggal_jatuh_tempo` harus `>= tanggal_pinjam`.
- Status awal: `dipinjam`.
- Petugas (`user_id`) diisi otomatis dari user yang login.

### 3. Pengembalian & Denda
- Tombol "Proses Pengembalian" ada di index & detail peminjaman berstatus `dipinjam`.
- Dihitung otomatis:
  - Jika `tanggal_kembali <= tanggal_jatuh_tempo` → status `dikembalikan`, denda 0.
  - Jika lewat jatuh tempo → status `terlambat`, denda = (hari terlambat × Rp 1.000).

### 4. Hapus Data
- Kategori yang masih punya buku tidak bisa dihapus.
- Buku yang masih dipinjam (`status='dipinjam'`) tidak bisa dihapus.
- Anggota yang masih punya peminjaman aktif tidak bisa dihapus.
- User tidak bisa hapus diri sendiri.
- Admin terakhir tidak bisa dihapus.

---

## SQL Import Manual (Alternatif phpMyAdmin)

Untuk yang ingin import manual via phpMyAdmin **tanpa migration Laravel**,
jalankan ini di tab SQL:

```sql
-- Buat database
CREATE DATABASE IF NOT EXISTS db_perpustakaan
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE db_perpustakaan;

-- Tabel users
CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','pustakawan') NOT NULL DEFAULT 'pustakawan',
  phone VARCHAR(20) NULL,
  email_verified_at TIMESTAMP NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

-- Tabel categories
CREATE TABLE categories (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_kategori VARCHAR(20) NOT NULL UNIQUE,
  nama_kategori VARCHAR(100) NOT NULL,
  deskripsi TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

-- Tabel books
CREATE TABLE books (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_buku VARCHAR(20) NOT NULL UNIQUE,
  judul VARCHAR(150) NOT NULL,
  penulis VARCHAR(100) NOT NULL,
  penerbit VARCHAR(100) NULL,
  tahun_terbit YEAR NULL,
  isbn VARCHAR(20) NULL,
  category_id BIGINT UNSIGNED NOT NULL,
  jumlah INT NOT NULL DEFAULT 1,
  stok_tersedia INT NOT NULL DEFAULT 1,
  deskripsi TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_books_category FOREIGN KEY (category_id)
    REFERENCES categories(id) ON DELETE RESTRICT
);

-- Tabel members
CREATE TABLE members (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_anggota VARCHAR(20) NOT NULL UNIQUE,
  nama VARCHAR(100) NOT NULL,
  nik_nim VARCHAR(30) NULL,
  jenis_kelamin ENUM('L','P') NULL,
  alamat TEXT NULL,
  telepon VARCHAR(20) NULL,
  email VARCHAR(100) NULL,
  status ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

-- Tabel loans
CREATE TABLE loans (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_pinjam VARCHAR(20) NOT NULL UNIQUE,
  member_id BIGINT UNSIGNED NOT NULL,
  book_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  tanggal_pinjam DATE NOT NULL,
  tanggal_jatuh_tempo DATE NOT NULL,
  tanggal_kembali DATE NULL,
  status ENUM('dipinjam','dikembalikan','terlambat') NOT NULL DEFAULT 'dipinjam',
  denda INT NOT NULL DEFAULT 0,
  catatan TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_loans_member FOREIGN KEY (member_id)
    REFERENCES members(id) ON DELETE RESTRICT,
  CONSTRAINT fk_loans_book FOREIGN KEY (book_id)
    REFERENCES books(id) ON DELETE RESTRICT,
  CONSTRAINT fk_loans_user FOREIGN KEY (user_id)
    REFERENCES users(id) ON DELETE RESTRICT
);

-- Insert user default (password: admin123 / pustakawan123 - sudah di-hash bcrypt)
INSERT INTO users (name, email, password, role, phone, created_at, updated_at)
VALUES
('Administrator', 'admin@perpus.id',
 '$2y$12$g7Q3Z8z1WQ3nE2bUuY.qEOWR7v1VoH3rRcR7j2KqTQ8QXg1VQyIu',
 'admin', '081234567890', NOW(), NOW()),
('Siti Pustakawan', 'pustakawan@perpus.id',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'pustakawan', '081234567891', NOW(), NOW());
```

> **PENTING:** Hash bcrypt di atas adalah contoh. Lebih disarankan menggunakan
> `php artisan migrate --seed` agar hash sesuai dengan konfigurasi Laravel.

---

## Diagram ERD (Visual)

```
   ┌─────────────────┐
   │      users      │
   │─────────────────│
   │ PK  id          │
   │     name        │
   │     email       │
   │     password    │
   │     role        │
   │     phone       │
   └────────┬────────┘
            │
            │ 1
            │
            │ M
   ┌────────▼────────┐         ┌──────────────────┐
   │      loans      │         │     members      │
   │─────────────────│         │──────────────────│
   │ PK  id          │ M     1 │ PK  id           │
   │     kode_pinjam │◄────────│     kode_anggota │
   │ FK  member_id   │         │     nama         │
   │ FK  book_id     │ M     1 │     status       │
   │ FK  user_id     │◄────────│                  │
   │     tanggal_... │         └──────────────────┘
   │     status      │
   │     denda       │ M     1 ┌──────────────────┐
   └────────┬────────┘◄────────│      books       │
            │                   │──────────────────│
            │ M                 │ PK  id           │
            │                   │     kode_buku    │
                                │     judul        │
                                │ FK  category_id  │ M
                                │     jumlah       │
                                │     stok_tersedia│
                                └────────┬─────────┘
                                         │
                                         │ 1
                                         │
                                         │ M
                                ┌────────▼─────────┐
                                │    categories    │
                                │──────────────────│
                                │ PK  id           │
                                │     kode_kategori│
                                │     nama_kategori│
                                └──────────────────┘
```

---

&copy; Kelompok AF - Pemrograman Web Tingkat Mahir
