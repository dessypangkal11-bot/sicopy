<?php
session_start();
include 'koneksi.php';

// Aktifkan error biar tidak blank
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek login admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['submit'])) {

    $nama      = $_POST['nama_toko'];
    $alamat    = $_POST['alamat'];
    $kontak    = $_POST['kontak'];
    $layanan   = $_POST['layanan'];
    $latitude  = $_POST['lokasi_lat'];
    $longitude = $_POST['lokasi_lng'];
    $jam_buka  = $_POST['jam_buka'];
    $jam_tutup = $_POST['jam_tutup'];

    // Upload foto
    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    // Pastikan folder upload ada
    if (!is_dir('../upload/toko')) {
        mkdir('../upload/toko', 0777, true);
    }

    if ($gambar != "") {
        $path_file = "../upload/toko/" . $gambar;
        move_uploaded_file($tmp, $path_file);
    } else {
        $gambar = "";
    }

    // Query simpan data
    $sql = "INSERT INTO toko (nama_toko, alamat, kontak, layanan, lokasi_lat, lokasi_lng, jam_buka, jam_tutup, gambar)
            VALUES ('$nama', '$alamat', '$kontak', '$layanan', '$latitude', '$longitude', '$jam_buka', '$jam_tutup', '$gambar')";

    $query = mysqli_query($conn, $sql);

    if ($query) {
        echo "<script>alert('Toko berhasil ditambahkan'); window.location='admin_dashboard.php';</script>";
    } else {
        die('MYSQL ERROR: ' . mysqli_error($conn) . "<br>QUERY: " . $sql);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Toko</title>

    <style>
        body { font-family: Arial; background:#f4f6f9; padding:20px; }
        .form-box {
            background:white; padding:20px; width:500px;
            margin:auto; border-radius:10px;
            box-shadow:0 3px 10px rgba(0,0,0,0.1);
        }
        input, textarea, select {
            width:100%; padding:10px; margin-bottom:12px;
            border:1px solid #ccc; border-radius:6px;
        }
        button {
            padding:10px; background:#4F46E5; color:white;
            border:none; width:100%; font-size:16px;
            border-radius:6px; cursor:pointer;
        }
        button:hover { opacity:0.9; }
        h2 { text-align:center; }
    </style>

</head>
<body>

<div class="form-box">
<h2>➕ Tambah Toko Fotokopi</h2>

<form action="" method="POST" enctype="multipart/form-data">

    <label>Nama Toko</label>
    <input type="text" name="nama_toko" required>

    <label>Alamat</label>
    <textarea name="alamat" required></textarea>

    <label>Kontak / No WA</label>
    <input type="text" name="kontak" required>

    <label>Layanan</label>
    <input type="text" name="layanan" required placeholder="Contoh: Fotokopi, Print, Jilid, dll">

    <label>Latitude (lokasi_lat)</label>
    <input type="text" name="lokasi_lat" placeholder="-2.12345" required>

    <label>Longitude (lokasi_lng)</label>
    <input type="text" name="lokasi_lng" placeholder="106.12345" required>

    <label>Jam Buka</label>
    <input type="time" name="jam_buka" required>

    <label>Jam Tutup</label>
    <input type="time" name="jam_tutup" required>

    <label>Foto Toko</label>
    <input type="file" name="gambar">

    <button type="submit" name="submit">Simpan Toko</button>
</form>
</div>

</body>
</html>
