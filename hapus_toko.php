<?php
session_start();
include 'koneksi.php';

// Cek login admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Pastikan ada ID yang dikirim
if (!isset($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan!'); window.location='admin_dashboard.php';</script>";
    exit();
}

$id = $_GET['id'];

// Ambil data toko untuk cek gambar
$cek = mysqli_query($conn, "SELECT gambar FROM toko WHERE toko_id = '$id'");
$data = mysqli_fetch_assoc($cek);

// Hapus file gambar jika ada
if ($data && $data['gambar'] != "") {
    $file_path = "../upload/toko/" . $data['gambar'];
    
    if (file_exists($file_path)) {
        unlink($file_path); 
    }
}

// Hapus data dari database
$hapus = mysqli_query($conn, "DELETE FROM toko WHERE toko_id = '$id'");

if ($hapus) {
    echo "<script>alert('Toko berhasil dihapus'); window.location='admin_dashboard.php';</script>";
} else {
    echo "MYSQL ERROR: " . mysqli_error($conn);
}
?>
