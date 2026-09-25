# VIF Operational System

Website operasional internal **PT Victory International Futures - Cabang Ciputra World**.

Fitur utama:
1. Absensi karyawan via QR Code (tanpa login).
2. Peminjaman mobil operasional kantor.
3. Dashboard Admin & Kepala Cabang untuk monitoring absensi dan peminjaman.

---

## Teknologi

| Teknologi | Keterangan |
|---|---|
| Laravel 12 | Framework backend |
| PHP 8.2+ | Bahasa pemrograman |
| Blade | Template engine |
| Tailwind CSS (CDN) | Styling, tidak perlu proses build |
| Alpine.js (CDN) | Interaksi sidebar mobile |
| Chart.js (CDN) | Grafik dashboard Kepala Cabang |
| MySQL | Database |
| simplesoftwareio/simple-qrcode | Generate QR Code absensi |
| maatwebsite/excel | Export data ke Excel |

---

## 1. Persiapan Sebelum Instalasi

Pastikan sudah terinstall di komputer Anda:

- **PHP 8.2 atau lebih baru** beserta ekstensi umum (mbstring, openssl, pdo_mysql, gd, dsb) — biasanya sudah otomatis ada jika pakai **XAMPP** atau **Laragon**.
- **Composer** (https://getcomposer.org)
- **MySQL** (bawaan XAMPP/Laragon sudah cukup)
- **Node.js** (opsional, hanya jika ingin memakai Vite; project ini memakai Tailwind via CDN sehingga Node.js **tidak wajib**)
- **VS Code**

---

## 2. Cara Instalasi

### Langkah 1 — Extract project

Extract file ZIP ini, lalu buka foldernya di VS Code:

```bash
cd vif-operational-system
code .
```

### Langkah 2 — Install dependency PHP (Composer)

Buka terminal di VS Code (`Ctrl + ~`), lalu jalankan:

```bash
composer install
```

Tunggu sampai semua package (Laravel, Breeze, simple-qrcode, Laravel Excel) selesai terunduh.

### Langkah 3 — Salin file environment

```bash
cp .env.example .env
```

> Di Windows (bukan Git Bash), gunakan: `copy .env.example .env`

### Langkah 4 — Generate Application Key

```bash
php artisan key:generate
```

### Langkah 5 — Buat Database MySQL

Buka phpMyAdmin (`http://localhost/phpmyadmin`) atau HeidiSQL, lalu buat database baru bernama:

```
vif_operational_system
```

Tidak perlu membuat tabel manual — semua tabel akan dibuat otomatis lewat migration.

### Langkah 6 — Sesuaikan koneksi database di `.env`

Buka file `.env`, sesuaikan bagian ini jika perlu (default sudah cocok untuk XAMPP/Laragon):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vif_operational_system
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 7 — Jalankan Migration + Seeder

Perintah ini akan membuat semua tabel (users, teams, cars, attendances, borrowings, dll) sekaligus mengisi data awal (Team, Mobil, dan 3 akun demo):

```bash
php artisan migrate --seed
```

### Langkah 8 — Buat Symbolic Link Storage (opsional, untuk file publik)

```bash
php artisan storage:link
```

### Langkah 9 — Jalankan Server

```bash
php artisan serve
```

Buka browser ke: **http://localhost:8000**

---

## 3. Akun Demo (hasil Seeder)

| Role | Email | Password |
|---|---|---|
| Admin Operasional | admin@vif.com | password |
| Kepala Cabang | kepalacabang@vif.com | password |
| Marketing | marketing@vif.com | password |

> ⚠️ **Ganti password ini sebelum digunakan di lingkungan produksi.**

---

## 4. Alur Penggunaan Singkat

1. **Admin** login → buka menu **QR Code Absensi** → cetak/tampilkan QR di kantor.
2. Karyawan **scan QR** dengan HP → halaman `/attendance` terbuka otomatis (tanpa login) → isi nama & pilih team → submit.
3. **Marketing** login → menu **Form Peminjaman Mobil** → isi form → mobil otomatis berstatus "Dipinjam".
4. **Admin** login → menu **Peminjaman Mobil** → buka detail → klik **Kembalikan Mobil** → status otomatis kembali "Tersedia".
5. **Admin / Kepala Cabang** bisa melihat **History Absensi** & **History Peminjaman** lengkap dengan filter dan export Excel (khusus Admin).
6. **Kepala Cabang** memiliki dashboard monitoring dengan 3 grafik (absensi harian, peminjaman mingguan, penggunaan mobil per team).

---

## 5. Struktur Folder Penting

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/            → Controller khusus Admin (Team, Mobil, QR, History)
│   │   ├── KepalaCabang/     → Controller Dashboard Kepala Cabang
│   │   ├── Marketing/        → Controller Peminjaman & Dashboard Marketing
│   │   ├── Auth/             → Login, Register, Reset Password (Breeze)
│   │   └── AttendanceController.php  → Form absensi publik
│   ├── Middleware/
│   │   └── CheckRole.php     → Middleware pembatas akses per role
│   └── Requests/             → Form Request Validation semua form
├── Models/                   → User, Team, Car, Attendance, Borrowing
└── Exports/                  → AttendanceExport, BorrowingExport (Laravel Excel)

database/
├── migrations/               → Struktur tabel + foreign key
└── seeders/                  → Data awal Team, Mobil, User demo

resources/views/
├── layouts/                  → app.blade.php (sidebar+navbar), guest.blade.php
├── components/                → nav-link, stat-card, badge, input, button, icon/*
├── dashboard/                 → admin, kepala-cabang, marketing
├── team/, mobil/               → CRUD views
├── attendance/                 → form publik, qrcode, history
└── borrowing/                  → create, history, detail, my-history

routes/
├── web.php                   → Semua route utama, dikelompokkan per role
└── auth.php                  → Route Breeze (login, register, reset password)
```

---

## 6. Middleware Role — Cara Kerja

Middleware `role` didaftarkan di `bootstrap/app.php` dan dipakai di `routes/web.php`, contoh:

```php
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // ...route khusus admin
    });
```

Jika user dengan role `marketing` mencoba mengakses route `admin.*`, sistem akan menampilkan **403 Forbidden**.

---

## 7. Troubleshooting Umum

| Masalah | Solusi |
|---|---|
| `SQLSTATE[HY000] [1049] Unknown database` | Pastikan database `vif_operational_system` sudah dibuat di MySQL |
| `Class "SimpleSoftwareIO\QrCode\Facades\QrCode" not found` | Jalankan ulang `composer install` |
| QR Code tidak muncul | Pastikan ekstensi PHP `gd` aktif di `php.ini` |
| Halaman putih / error 500 | Cek `storage/logs/laravel.log`, pastikan `.env` sudah benar dan `key:generate` sudah dijalankan |
| Export Excel gagal | Pastikan folder `storage/framework/cache/laravel-excel` bisa ditulis (writable) |

---

Selamat mengembangkan! 🚀
