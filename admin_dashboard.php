<?php
include 'koneksi.php';

// Ambil semua data toko
$query = mysqli_query($conn, "SELECT * FROM toko ORDER BY toko_id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen Toko</title>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background: #f4f4f4;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    /* Tombol Logout */
    .logout-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: #dc3545;
        padding: 10px 16px;
        border-radius: 6px;
        color: white;
        text-decoration: none;
        font-weight: bold;
    }

    a.btn-add {
        display: inline-block;
        margin-bottom: 15px;
        background: #0d6efd;
        color: white;
        padding: 10px 16px;
        border-radius: 6px;
        text-decoration: none;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    th, td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: left;
        vertical-align: top;
    }

    th {
        background: #eee;
    }

    img.thumbnail {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    /* Tombol edit/hapus/kelola */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        color: white;
        font-size: 14px;
        text-align: center;
        display: inline-block;
    }

    .btn-edit {
        background: #ffc107;
        color: black;
    }

    .btn-hapus {
        background: #dc3545;
    }

    .btn-pesanan {
        background: #198754;
    }

</style>

</head>
<body>

<!-- Tombol Logout -->
<a href="logout_admin.php" class="logout-btn">Logout</a>

<h2>Daftar Toko</h2>

<a href="tambah_toko.php" class="btn-add">+ Tambah Toko</a>

<table>
    <tr>
        <th>ID</th>
        <th>Nama Toko</th>
        <th>Layanan</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Gambar</th>
        <th>Aksi</th>
    </tr>

    <?php while ($toko = mysqli_fetch_assoc($query)) { ?>
        <tr>
            <td><?= $toko['toko_id']; ?></td>
            <td><?= $toko['nama_toko']; ?></td>
            <td><?= $toko['layanan']; ?></td>
            <td><?= $toko['lokasi_lat']; ?></td>
            <td><?= $toko['lokasi_lng']; ?></td>

            <td>
                <?php if (!empty($toko['gambar'])) { ?>
                    <img src="uploads/<?= $toko['gambar']; ?>" class="thumbnail">
                <?php } else { ?>
                    <span style="color: #888;">Tidak ada</span>
                <?php } ?>
            </td>

            <td>
                <div class="action-buttons">
                    <a href="edit_toko.php?id=<?= $toko['toko_id']; ?>" class="btn btn-edit">Edit</a>
                    <a href="hapus_toko.php?id=<?= $toko['toko_id']; ?>"
                       class="btn btn-hapus"
                       onclick="return confirm('Yakin ingin menghapus toko ini?');">Hapus</a>
                    <a href="pesanan_toko.php?id_toko=<?= $toko['toko_id']; ?>" class="btn btn-pesanan">Kelola Pesanan</a>
                </div>
            </td>
        </tr>
    <?php } ?>

</table>

</body>
</html>
