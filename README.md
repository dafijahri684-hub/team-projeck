# DataDesk — Contoh Tugas Frontend + Backend PHP

Aplikasi contoh yang berisi:
- Login menggunakan PHP session dan `password_verify`
- Dashboard dengan ringkasan data dari MySQL
- Form input untuk menyimpan data ke database
- Validasi frontend dan backend
- PDO prepared statements dan token CSRF
- Tampilan responsif dengan HTML, CSS, dan JavaScript

## Menjalankan di Laragon
1. Ekstrak folder `website_tugas_php` ke `C:\laragon\www\`.
2. Jalankan Laragon, lalu start Apache dan MySQL.
3. Buka `http://localhost/phpmyadmin`.
4. Pilih tab **Import**, lalu impor file `database.sql`.
5. Buka `http://localhost/website_tugas_php/setup.php` untuk membuat akun demo.
6. Login di `http://localhost/website_tugas_php/login.php`.

Akun demo:
- Email: `admin@example.com`
- Password: `Admin12345!`

Setelah setup berhasil, hapus `setup.php`. Ganti password demo sebelum aplikasi digunakan sungguhan.

## Struktur folder
- `config.php`: koneksi PDO, session, helper, dan CSRF
- `login.php`: halaman login dan autentikasi
- `dashboard.php`: ringkasan serta tabel data
- `form.php`: validasi dan simpan data
- `logout.php`: mengakhiri session
- `database.sql`: struktur database
- `assets/style.css`: desain halaman
- `assets/app.js`: interaksi frontend

## Jika koneksi database gagal
Periksa `DB_HOST`, `DB_NAME`, `DB_USER`, dan `DB_PASS` di `config.php`. Pengaturan standar Laragon umumnya `root` dengan password kosong.
