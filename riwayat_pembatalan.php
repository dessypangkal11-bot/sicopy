<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login dan mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil riwayat pembatalan pesanan
$riwayat = mysqli_query($conn, "
    SELECT r.id, r.alasan, r.tanggal, d.`kode book`
    FROM riwayat_pembatalan r
    JOIN dokumen d ON r.dokumen_id = d.dokumen_id
    WHERE r.user_id = '$user_id'
    ORDER BY r.tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Pembatalan Pesanan</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f6fa;
        margin: 0;
        padding: 0;
    }

    .navbar {
        background-color: #4F46E5;
        color: white;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .navbar a {
        background-color: white;
        color: #4F46E5;
        text-decoration: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 500;
    }

    .navbar a:hover {
        background-color: #E0E7FF;
    }

    .container {
        max-width: 800px;
        margin: 40px auto;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 20px;
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    th, td {
        padding: 10px 8px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
    }

    th {
        background-color: #eef2ff;
        color: #333;
        font-weight: 600;
    }

    td {
        color: #444;
    }

    tr:hover td {
        background-color: #f9fafb;
    }

    .no-data {
        text-align: center;
        color: #6B7280;
        padding: 15px;
        font-style: italic;
    }

    footer {
        text-align: center;
        font-size: 13px;
        color: #6B7280;
        padding: 15px 0;
        margin-top: 30px;
    }
</style>
</head>
<body>

<div class="navbar">
    <h1 style="font-size:18px; margin:0;">📜 Riwayat Pembatalan Pesanan</h1>
    <a href="pembatalan.php">Kembali</a>
</div>

<div class="container">
    <h2>Daftar Riwayat Pembatalan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Booking</th>
                <th>Alasan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($riwayat && mysqli_num_rows($riwayat) > 0) {
                $no = 1;
                while ($row = mysqli_fetch_assoc($riwayat)) {
                    echo "<tr>
                            <td>{$no}</td>
                            <td><b>{$row['kode book']}</b></td>
                            <td>{$row['alasan']}</td>
                            <td>" . date('d M Y, H:i', strtotime($row['tanggal'])) . "</td>
                          </tr>";
                    $no++;
                }
            } else {
                echo "<tr><td colspan='4' class='no-data'>Belum ada riwayat pembatalan.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<footer>
    © 2025 SICOPY | Sistem Copy Dokumen Mahasiswa
</footer>

</body>
</html>
