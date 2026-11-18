# Aplikasi Akademik

Aplikasi web akademik sederhana berbasis **Laravel 12**. Proyek ini merupakan demo alur autentikasi dan otorisasi berdasarkan role: admin, dosen, dan mahasiswa. Halaman menggunakan Blade dan Bootstrap 5 melalui CDN.

## Fitur

- Login dan logout menggunakan session Laravel.
- Dashboard dengan judul dan konten berbeda sesuai halaman.
- Proteksi route menggunakan middleware `auth` dan `role`.
- Panel navigasi role serta panel simulasi untuk mencoba respons akses `403 Forbidden`.
- Data akun uji yang dapat dibuat melalui database seeder.

> **Catatan:** Data materi, jadwal, dan nilai pada halaman adalah data dummy. Tombol tambah, hapus, upload, edit, simpan, dan download hanya tampilan simulasi; fitur tersebut belum menyimpan atau mengelola data.

## Teknologi dan kebutuhan

- PHP **8.2 atau lebih baru**
- Composer
- Laravel **12**
- Database SQLite (default) atau MySQL
- Browser modern dan koneksi internet untuk memuat Bootstrap 5 CDN

## Instalasi dan menjalankan aplikasi

Buka terminal pada folder proyek (folder yang berisi `artisan` dan `composer.json`), kemudian jalankan:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
```

Jika file `.env` sudah ada, jangan menimpanya dengan `.env.example`; cukup jalankan `php artisan key:generate` bila `APP_KEY` belum terisi.

### Database SQLite (default)

Pastikan `.env` menggunakan:

```dotenv
DB_CONNECTION=sqlite
```

Pastikan file database tersedia, kemudian jalankan migrasi dan seeder:

```powershell
if (!(Test-Path database\database.sqlite)) { New-Item -ItemType File database\database.sqlite }
php artisan migrate --seed
```

### Database MySQL di XAMPP (opsional)

Jalankan layanan **MySQL** melalui XAMPP, lalu buat database kosong—misalnya `akademik_app`—di phpMyAdmin. Atur bagian database di `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=akademik_app
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan nama database, username, dan password dengan konfigurasi lokal Anda. Lalu jalankan:

```powershell
php artisan migrate --seed
```

Jangan commit file `.env` atau kredensial database ke GitHub.

### Jalankan server lokal

```powershell
php artisan serve
```

Buka [http://127.0.0.1:8000](http://127.0.0.1:8000). Halaman utama akan mengarahkan ke `/login`.

## Akun uji

Semua akun uji menggunakan password **`password`**.

| Role | Email |
|---|---|
| Admin | `admin@kampus.ac.id` |
| Dosen | `dosen@kampus.ac.id` |
| Mahasiswa | `mahasiswa@kampus.ac.id` |

Setelah login, aplikasi mengarahkan pengguna ke dashboard sesuai role. Seeder menggunakan `updateOrCreate`, sehingga akun dengan email tersebut akan dibuat atau diperbarui ketika `php artisan db:seed` dijalankan.

## Halaman dan hak akses

| URL | Hak akses | Konten |
|---|---|---|
| `/admin` | Admin | Daftar pengguna dan simulasi aksi tambah/hapus |
| `/manajemen-pengguna` | Admin | Manajemen pengguna |
| `/dosen` | Dosen | Ringkasan mata kuliah dan jadwal mengajar |
| `/mahasiswa` | Mahasiswa | Profil, semester aktif, dan IPK sementara |
| `/nilai-pribadi` | Mahasiswa | Transkrip/nilai pengguna yang sedang login |
| `/kelola-materi` | Admin, Dosen | Form upload dummy dan daftar materi |
| `/kelola-nilai` | Admin, Dosen | Daftar nilai mahasiswa |
| `/lihat-materi` | Admin, Dosen, Mahasiswa | Daftar materi read-only |

Dashboard menampilkan dua kelompok navigasi:

1. **Menu Sesuai Role Anda** — hanya menu yang diizinkan untuk role pengguna aktif.
2. **Panel Uji Middleware / Simulasi Akses Semua Route** — semua tautan beserta role yang diizinkan. Membuka tautan di luar hak akses role aktif akan menghasilkan respons **403 Forbidden**; ini perilaku yang diharapkan.

## Struktur bagian penting

```text
app/
  Http/
    Controllers/
      AuthController.php       # Login, logout, dan pengalihan berdasarkan role
      DashboardController.php  # Data/halaman dashboard per route
    Middleware/
      CheckRole.php            # Memeriksa role pengguna
  Models/
    User.php
bootstrap/
  app.php                      # Alias middleware 'role'
database/
  migrations/                  # Skema tabel users dan kolom role
  seeders/
    DatabaseSeeder.php         # Akun uji
resources/
  views/
    auth/login.blade.php
    dashboard.blade.php
routes/
  web.php                      # Route dan proteksi middleware
tests/
  Feature/
```

## Menjalankan test

```powershell
php artisan test
```

Test fitur mencakup halaman login, autentikasi, pengalihan role, logout, hak akses route, konten halaman, dan akun seeder.

## Perintah database yang berguna

Jalankan migrasi baru dan seeder tanpa menghapus data yang ada:

```powershell
php artisan migrate --seed
```

Untuk membangun ulang seluruh tabel dan mengisi ulang akun uji:

```powershell
php artisan migrate:fresh --seed
```

> **Peringatan:** `migrate:fresh` menghapus semua tabel dan data pada database yang sedang dikonfigurasi. Gunakan hanya untuk database lokal/demo yang aman dihapus.
