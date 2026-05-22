# Soto Pak Tio

Aplikasi pemesanan dan laporan keuangan sederhana untuk Soto Pak Tio. Dibuat dengan PHP + MySQL agar mudah dipasang di Hostinger shared hosting.

## Fitur

- Halaman pemesanan publik.
- Menu sesuai spanduk Soto Pak Tio.
- Total pesanan otomatis di browser.
- Simpan order ke MySQL.
- Admin login sederhana.
- Laporan omzet, jumlah pesanan, rata-rata nota, menu terlaris, dan update status pesanan.

## Menjalankan Lokal

1. Salin `.env.example` menjadi `.env`.
2. Isi konfigurasi database jika MySQL lokal tersedia.
3. Jalankan:

```bash
php -S localhost:8000 -t public
```

4. Buka `http://localhost:8000`.

Tanpa database, halaman publik tetap berjalan dalam mode demo, tetapi order tidak disimpan.

## Database

Import file `database/schema.sql` ke database MySQL.

Contoh via phpMyAdmin Hostinger:

1. Buka hPanel Hostinger.
2. Buat database MySQL baru.
3. Buka phpMyAdmin untuk database tersebut.
4. Import `database/schema.sql`.
5. Isi `.env` dengan host, nama database, username, dan password dari Hostinger.

## Deploy Hostinger Shared Hosting

Gunakan hPanel → Websites → Dashboard → Advanced → Git untuk deploy repo ini ke `public_html`.

Project ini sudah dibuat agar `index.php`, `admin.php`, `.htaccess`, dan `assets/` berada di root repo, sehingga cocok ketika isi repo langsung masuk ke `public_html`.

Setelah Git deploy, buat file `.env` melalui File Manager di folder yang sama dengan `index.php`. Jangan commit file `.env`.

## Admin

Admin URL:

```text
/admin.php
```

Default dari `.env.example`:

```text
username: admin
password: ubah-password-ini
```

Ganti password admin sebelum deploy.
