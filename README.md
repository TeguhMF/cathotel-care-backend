# CatHotel Care - Backend API

Repositori ini berisi layanan RESTful API untuk aplikasi web CatHotel Care yang dibangun menggunakan Laravel dan database MySQL. Layanan ini menangani manajemen data kamar, transaksi pemesanan (booking), profil pengguna, pembaruan status reservasi, serta integrasi webhook pembayaran.

## Arsitektur Sistem dan Teknologi

- **Framework:** Laravel 11.x
- **Bahasa Pemrograman:** PHP 8.2+
- **Database:** MySQL
- **Payment Gateway:** Midtrans Snap SDK & Webhook Notification
- **Autentikasi:** API-based / Token Authentication

## Fitur Utama

1. **Layanan API Data Kamar:** Menyediakan daftar katalog kamar, harga, dan ketersediaan.
2. **Sistem Siklus Pemesanan:** Mengolah reservasi kamar, kalkulasi otomatis durasi menginap, serta perhitungan Down Payment (DP) sebesar 30%.
3. **Penanganan Webhook Pembayaran:** Listener notifikasi asinkron dari Midtrans untuk sinkronisasi status pembayaran dan pemesanan secara otomatis.
4. **API Profil Pengguna:** Mengambil riwayat transaksi berdasarkan akun pengguna.
5. **API Manajemen Admin:** Mengambil seluruh data reservasi dan memperbarui status operasional booking (`pending`, `confirmed`, `checked_in`, `checked_out`, `cancelled`).

## Struktur Database

Struktur data utama terdiri dari tiga tabel:

- `users`: Menyimpan data kredensial pelanggan dan administrator.
- `rooms`: Menyimpan informasi detail kamar, tarif per malam, dan fasilitas.
- `bookings`: Menyimpan transaksi reservasi, tanggal check-in/out, data anabul, total biaya, status pembayaran (`unpaid`, `paid`, `failed`), dan status reservasi (`pending`, `confirmed`, `checked_in`, `checked_out`, `cancelled`).

## Daftar Endpoint API

### Endpoint Pelanggan / Publik
- `GET /api/rooms` - Mengambil seluruh kategori kamar.
- `POST /api/bookings` - Membuat pesanan baru dan menerima Snap Token dari Midtrans.
- `GET /api/bookings/user/{userId}` - Mengambil riwayat pemesanan pengguna tertentu.
- `POST /api/midtrans/notification` - Listener callback webhook untuk notifikasi status pembayaran Midtrans.

### Endpoint Admin
- `GET /api/admin/bookings` - Mengambil seluruh data pesanan dari semua pelanggan.
- `PATCH /api/admin/bookings/{id}/status` - Memperbarui status reservasi (`pending`, `confirmed`, `checked_in`, `checked_out`, `cancelled`).

## Konfigurasi Lingkungan (.env)

Buat file `.env` pada direktori utama proyek backend dan sesuaikan konfigurasi database serta kredensial Midtrans:

```env
APP_NAME=CatHotelCare
APP_URL=[http://127.0.0.1:8000](http://127.0.0.1:8000)

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cathotel_db
DB_USERNAME=root
DB_PASSWORD=

MIDTRANS_SERVER_KEY=KUNCI_SERVER_MIDTRANS_ANDA
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

```

### Kloning repositori:

-git clone [https://github.com/username-anda/cathotel-care-backend.git](https://github.com/TeguhMF/cathotel-care-backend.git)
cd cathotel-care-backend

-composer install

-php artisan key:generate

-php artisan migrate --seed

-php artisan serve

