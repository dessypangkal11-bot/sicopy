<?php
// Konfigurasi koneksi ke database
$host = "localhost";       // Nama host server (biasanya localhost)
$user = "root";            // Username default phpMyAdmin
$pass = "";                // Password phpMyAdmin (kosong kalau default)
$db   = "db_sicopy";          // Nama database

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Mengecek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Jika koneksi berhasil (opsional, bisa kamu hapus setelah tes)
# echo "Koneksi ke database berhasil!";
?>