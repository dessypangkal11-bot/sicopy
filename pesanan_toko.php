<?php
session_start();
include 'koneksi.php';

// Cek login mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit();
}

// Ambil ID toko dari URL
if (!isset($_GET['id_toko'])) {
    echo "<p style='text-align:center;margin-top:50px;'>Pilih toko terlebih dahulu. <a href='dashboard_mahasiswa.php'>Kembali</a></p>";
    exit();
}

$id_toko = intval($_GET['id_toko']);

// Ambil data toko
$toko_query = mysqli_query($conn, "SELECT * FROM toko WHERE toko_id = $id_toko");
$toko = mysqli_fetch_assoc($toko_query);
if (!$toko) {
    echo "<p style='text-align:center;margin-top:50px;'>Toko tidak ditemukan. <a href='dashboard_mahasiswa.php'>Kembali</a></p>";
    exit();
}

// Ambil semua pesanan di toko ini
$pesanan_query = mysqli_query($conn, "
    SELECT p.*, u.nama, u.email 
    FROM pesanan p 
    JOIN users u ON p.user_id = u.user_id
    WHERE p.toko_id = $id_toko
    ORDER BY p.tanggal DESC
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pesanan - <?= htmlspecialchars($toko['nama_toko']); ?></title>
<style>
body { font-family: Arial, sans-serif; margin:20px; background:#f4f4f4; }
h2 { text-align:center; margin-bottom:20px; }
a { text-decoration:none; color:#0d6efd; }
table { width:100%; border-collapse:collapse; background:white; }
th, td { padding:10px; border:1px solid #ccc; text-align:left; }
th { background:#eee; }
</style>
</head>
<body>

<h2>Pesanan di <?= htmlspecialchars($toko['nama_toko']); ?></h2>
<p style="text-align:center;"><a href="dashboard_mahasiswa.php">← Kembali ke Dashboard</a></p>

<table>
    <tr>
        <th>ID Pesanan</th>
        <th>Nama Mahasiswa</th>
        <th>Email</th>
        <th>Dokumen</th>
        <th>Jumlah Copy</th>
        <th>Status</th>
        <th>Tanggal</th>
    </tr>

    <?php if(mysqli_num_rows($pesanan_query) > 0): ?>
        <?php while($pesanan = mysqli_fetch_assoc($pesanan_query)): ?>
        <tr>
            <td><?= $pesanan['pesanan_id']; ?></td>
            <td><?= htmlspecialchars($pesanan['nama']); ?></td>
            <td><?= htmlspecialchars($pesanan['email']); ?></td>
            <td><?= htmlspecialchars($pesanan['dokumen']); ?></td>
            <td><?= $pesanan['jumlah']; ?></td>
            <td><?= htmlspecialchars($pesanan['status']); ?></td>
            <td><?= date("d-m-Y H:i", strtotime($pesanan['tanggal'])); ?></td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="7" style="text-align:center;">Belum ada pesanan di toko ini.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>
