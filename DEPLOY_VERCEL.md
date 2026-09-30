# Deploy VIF Operational System ke Vercel

Dokumen ini menjelaskan deployment aplikasi Laravel ke Vercel dengan database MySQL online.
Aplikasi ini menggunakan Laravel 12 sesuai dependency pada `composer.json`.

## 1. Prasyarat

- Repository project sudah di-push ke GitHub.
- Akun Vercel sudah terhubung ke GitHub.
- Database MySQL online tersedia, misalnya Railway, Aiven, PlanetScale-compatible provider,
  atau MySQL server lain yang bisa diakses dari internet.
- `APP_KEY` dibuat sebelum deployment.

Vercel memakai filesystem serverless yang tidak permanen. Jangan mengandalkan file yang dibuat
di folder `storage` untuk disimpan antar-request.

## 2. Deploy dari GitHub

1. Push project ke repository GitHub.
2. Buka https://vercel.com/new.
3. Pilih **Import Git Repository**, lalu pilih repository VIF.
4. Biarkan Root Directory berada di root repository, yaitu folder yang berisi `vercel.json`.
5. Klik **Deploy** setelah Environment Variables diisi.
6. Setelah deployment selesai, salin URL Vercel dan masukkan ke `APP_URL`.

File `vercel.json` dan `api/index.php` sudah mengarahkan semua request ke Laravel.
Vercel akan memakai runtime PHP untuk file `api/index.php`.

## 3. Environment Variables di Vercel

Masuk ke **Project Settings > Environment Variables**, lalu tambahkan variabel berikut untuk
environment **Production**. Jangan commit nilai rahasia ke GitHub.

### Aplikasi

```text
APP_NAME=VIF Operational System
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:ISI_DENGAN_HASIL_php_artisan_key_generate_--show
APP_URL=https://nama-project.vercel.app
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=id
APP_FALLBACK_LOCALE=en
VERCEL=1
```

Buat `APP_KEY` secara lokal dengan:

```bash
php artisan key:generate --show
```

### Database MySQL

Gunakan salah satu cara berikut. Cara terpisah lebih mudah untuk debugging:

```text
DB_CONNECTION=mysql
DB_HOST=host-mysql-dari-provider
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username_database
DB_PASSWORD=password_database
```

Jika provider memberikan satu connection string, Laravel juga dapat memakai:

```text
DB_CONNECTION=mysql
DB_URL=mysql://username:password@host:3306/nama_database
```

Jangan mengisi username atau password database secara hardcode di source code.

### Session, cache, dan log

Project ini sudah memakai driver database untuk session dan cache secara default. Nilai berikut
boleh ditambahkan secara eksplisit di Vercel:

```text
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
LOG_CHANNEL=stderr
LOG_LEVEL=error
```

Tabel `sessions` dan `cache` dibuat oleh migration bawaan project.

## 4. Migration dan seeder

Migration dijalankan dari komputer lokal atau CI yang dapat terhubung ke database online.
Pastikan environment database yang aktif adalah database production, lalu jalankan:

```bash
php artisan migrate --force
```

Jika data awal diperlukan:

```bash
php artisan db:seed --force
```

Jangan menjalankan migration pada setiap request Vercel. Migration hanya dijalankan sekali
sebagai langkah deployment/database release.

## 5. Storage dan foto absensi

Vercel menyediakan filesystem sementara. Folder `/tmp` dapat dipakai selama satu invocation,
tetapi file di sana tidak dijamin tetap ada pada request berikutnya.

Konfigurasi project menyediakan disk sementara `vercel_tmp` dan compiled Blade diarahkan ke
`/tmp` saat `VERCEL=1`. Disk ini hanya cocok untuk file sementara, bukan arsip upload.

Foto absensi pada project ini dikirim sebagai data URI JPEG dan disimpan langsung pada kolom
`attendances.foto` bertipe `longText`, sehingga tidak membutuhkan `storage:link` atau folder
upload permanen di Vercel.

Jika nanti ada upload file permanen, gunakan object storage eksternal seperti Amazon S3,
Cloudflare R2, atau DigitalOcean Spaces. Atur disk Laravel ke S3 dengan environment variables
provider tersebut, bukan menyimpan file permanen di `storage/app` Vercel.

## 6. Setelah mengubah environment variables

Lakukan redeploy dari tab **Deployments** dengan memilih **Redeploy**, atau push commit baru ke
branch yang terhubung. Pastikan deployment menggunakan Environment **Production**.

Jika halaman menampilkan error konfigurasi lama, lakukan redeploy tanpa cache setelah memastikan
`APP_KEY`, `APP_URL`, dan semua `DB_*` sudah benar.

## 7. Pemeriksaan setelah deploy

1. Buka `/login` dan uji login.
2. Buka `/attendance` dan uji kamera hanya melalui HTTPS atau localhost.
3. Uji `/jadwal-mobil`, booking Marketing, dan approval Admin.
4. Cek Vercel Runtime Logs jika terjadi error koneksi database atau environment.

Catatan: kamera browser di HP akan diblokir jika situs dibuka melalui HTTP biasa menggunakan
alamat IP lokal. Gunakan URL HTTPS Vercel untuk pengujian dari HP.