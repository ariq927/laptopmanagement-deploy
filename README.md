
## 📌 Laptop Management System

Sebuah aplikasi berbasis Laravel untuk mengelola peminjaman laptop.
Proyek ini dibuat dengan tujuan mempermudah pencatatan, pengelolaan, dan pelaporan peminjaman laptop agar lebih terstruktur dan efisien.

🚀 Fitur Utama
- Manajemen Laptop – Tambah, edit, dan hapus data laptop.
- Peminjaman – Catat peminjaman laptop oleh pengguna.
- Pengembalian – Tracking laptop yang sudah dikembalikan.
- Riwayat Peminjaman – Monitoring siapa yang meminjam dan kapan dikembalikan.

🛠️ Teknologi yang Digunakan
- Laravel 10
- Bootstrap 5
- MySQL
- React.js untuk beberapa komponen interaktif
  
## Installation ⚒️

Installing and running Sneat is super easy, please follow the steps below and you will be ready to rock 🤘

1. Clone repository ini

```bash
git clone https://github.com/ariq927/laptopManagement-plnips.git
cd laptopManagement-plnips
```

2. Install dependencies

```bash
composer install
npm install
```
3. Copy file .env.example menjadi .env dan atur konfigurasi database

4. Generate key

```bash
php artisan key:generate
```

5. Migrasi Database

```bash
php artisan migrate --seed
```

6. Jalankan web

```bash
php artisan serve
```


