# Service Order
Service order adalah bagian dari sebuah microservice yang dibangun untuk membuat API aplikasi belajar online (kelas digital), pada service ini digunakan untuk menghandle segala sesuatu tentang order.

## Daftar Isi
1. [Prasyarat](#prasyarat)
2. [Teknologi yang Digunakan](#teknologi-yang-digunakan)
3. [Fitur-fitur](#fitur---fitur)
4. [Pemasangan](#pemasangan)

## Prasyarat
- [GIT](https://www.git-scm.com/downloads)
- [PHP 8.1](https://www.php.net/downloads.php)
- [Composer 2.x](https://getcomposer.org/download/)
- [MySQL 8.0](https://dev.mysql.com/downloads/installer/)

## Teknologi yang Digunakan
- Laravel 10
- GuzzleHTTP
- Midtrans

## Fitur - fitur
1. **Manajemen Order:**
    - Membuat order dan menerima payment url dari midtrans.
    - Handle midtrans notification.

## Pemasangan
Langkah-langkah untuk menginstall proyek ini.

Clone proyek
```bash
git clone https://github.com/DimasPondra/service-order.git
```

Masuk ke dalam folder proyek
```bash
cd service-order
```

Install depedencies
```bash
composer install
```

Buat konfigurasi file
```bash
cp .env-example .env
```

Rubah `.env` untuk konfigurasi sesuai variabel
- `DB_HOST` - Hostname atau alamat IP server MySQL.
- `DB_DATABASE` - Database yang dibuat untuk aplikasi, default adalah laravel.
- `DB_USERNAME` - Username untuk mengakses database.
- `DB_PASSWORD` - Password untuk mengakses database.
- `URL_SERVICE_USER` - Url untuk mengakses service user.
- `URL_SERVICE_COURSE` - Url untuk mengakses service course.
- `MIDTRANS_CLIENT_KEY` - Client key dari Midtrans untuk autentikasi API di sisi klien.
- `MIDTRANS_SERVER_KEY` - Server key dari Midtrans untuk autentikasi API di sisi server.
- `MIDTRANS_IS_PRODUCTION` - Menentukan apakah menggunakan environment produksi (true) atau sandbox (false).
- `MIDTRANS_IS_SANITIZED` - Mengaktifkan atau menonaktifkan fitur sanitasi data (true/false).
- `MIDTRANS_IS_3DS` - Mengaktifkan atau menonaktifkan fitur 3D Secure untuk transaksi (true/false).

Migrasi database tabel awal
```bash
php artisan migrate
```

Generate manual key
```bash
php artisan key:generate
```

Mulai server
```bash
php artisan serve --port=8083
```

Dengan mengikuti langkah-langkah di atas, Anda akan dapat menjalankan Service course dimana service tersebut bagian dari aplikasi belajar online (kelas digital) microservice.
