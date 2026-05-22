# Checklist Deploy Hostinger

## 1. Buat Database

Di hPanel Hostinger:

1. Masuk ke `Databases` lalu pilih `MySQL Databases`.
2. Buat database, user, dan password.
3. Simpan informasi ini:
   - DB host
   - DB name
   - DB username
   - DB password
4. Masuk ke phpMyAdmin.
5. Import `database/schema.sql`.

## 2. Buat File `.env`

Salin `.env.example` menjadi `.env`, lalu isi:

```env
APP_NAME="Soto Pak Tio"
APP_ENV=production
APP_URL=https://domain-kamu.com

DB_HOST=isi-host-dari-hostinger
DB_PORT=3306
DB_DATABASE=isi-nama-database
DB_USERNAME=isi-user-database
DB_PASSWORD=isi-password-database

ADMIN_USERNAME=admin
ADMIN_PASSWORD=ganti-password-kuat
```

## 3. Upload File

Struktur paling aman:

```text
home/
  app/
  .env
  public_html/
    index.php
    admin.php
    assets/
    .htaccess
```

Jika memakai struktur aman di atas, sesuaikan `require_once` di `public_html/index.php` dan `public_html/admin.php` hanya jika posisi folder berubah dari struktur project ini.

## 4. Tes Setelah Upload

1. Buka halaman utama.
2. Buat satu pesanan test.
3. Buka `/admin.php`.
4. Login admin.
5. Pastikan pesanan muncul.
6. Ubah status menjadi `selesai`.
7. Cek omzet bertambah.

## 5. Keamanan Minimum

- Ganti `ADMIN_PASSWORD`.
- Jangan commit file `.env`.
- Jangan upload folder `.git`.
- Pastikan directory listing mati. File `public/.htaccess` sudah menonaktifkan listing.
