# Pendaftaran Siswa (PHP 7.4)

Sistem pendaftaran siswa berbasis web menggunakan **PHP Native** dan **MySQL**.

## 🚀 Fitur
- Menampilkan daftar siswa
- Pencarian siswa berdasarkan nama
- Menambah, mengedit, dan menghapus data siswa
- Sistem login sederhana dengan sesi
- Tampilan responsif menggunakan Bootstrap 5

## 🛠 Persyaratan
- **PHP** versi 7.4 atau lebih tinggi
- **MySQL** (MariaDB)
- **XAMPP/Laragon** (jika dijalankan di localhost)

## 📥 Instalasi
1. **Clone atau Download Proyek:**
   ```sh
   git clone https://github.com/username/pendaftaran_siswa.git
   cd pendaftaran_siswa
   ```
2. **Pindahkan Folder ke `htdocs` (jika menggunakan XAMPP):**
   - Pindahkan folder `pendaftaran_siswa/` ke `C:\xampp\htdocs\`

3. **Buat Database:**
   - Buka **phpMyAdmin** di `http://localhost/phpmyadmin`
   - Buat database baru dengan nama `pendaftaran_siswa`
   - Import file `database.sql` ke dalam database

4. **Konfigurasi Database:**
   - Buka file `koneksi.php`
   - Sesuaikan konfigurasi database jika diperlukan:
     ```php
     $host = "localhost";
     $user = "root";  // Default XAMPP
     $pass = "";       // Biarkan kosong jika tanpa password
     $dbname = "pendaftaran_siswa";
     ```

5. **Jalankan Proyek:**
   - Jika menggunakan XAMPP, buka browser dan akses:
     ```
     http://localhost/pendaftaran_siswa/
     ```
   - Jika menggunakan PHP built-in server:
     ```sh
     php -S localhost:8080
     ```
     Lalu buka browser dan akses `http://localhost:8080`

## 🖥 Struktur Folder
```
pendaftaran_siswa/
├── database.sql        # File database
├── index.php           # Halaman utama daftar siswa
├── login.php           # Halaman login
├── dashboard.php       # Halaman dashboard pengguna
├── tambah.php          # Form tambah siswa
├── edit.php            # Form edit siswa
├── hapus.php           # Proses hapus siswa
├── koneksi.php         # File koneksi database
├── navbar.php          # Navigasi utama
├── assets/             # CSS, JS, dan gambar
└── uploads/            # Folder untuk menyimpan foto siswa
```

## 📌 Catatan
- Gunakan **password hashing (`password_hash()`)** jika ingin meningkatkan keamanan login.
- Disarankan menggunakan **PDO** daripada `mysqli` untuk koneksi database agar lebih aman.
- Untuk fitur lebih lanjut, bisa menggunakan **Laravel** sebagai framework PHP.

## 📄 Lisensi
Proyek ini dibuat untuk pembelajaran dan dapat digunakan secara bebas. 😊

