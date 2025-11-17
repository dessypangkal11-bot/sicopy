<?php
session_start();
include 'koneksi.php';

// Pastikan hanya mahasiswa yang bisa akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data dokumen milik mahasiswa
$query = mysqli_query($conn, "SELECT * FROM dokumen WHERE user_id = '$user_id' ORDER BY tanggal_upload DESC");

// Fungsi untuk menampilkan kode booking acak (generate sekali saja)
function generateKodeBooking($length = 5) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $result .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $result;
}

// Pastikan kode_booking tidak berubah setelah dibuat
while ($row = mysqli_fetch_assoc($query)) {
    if (empty($row['kode_book'])) {
        $newKode = generateKodeBooking();
        mysqli_query($conn, "UPDATE dokumen SET kode_book='$newKode' WHERE dokumen_id=".$row['dokumen_id']);
    }
}

// Ambil ulang data dokumen setelah update (agar tampilan langsung fix)
$query = mysqli_query($conn, "SELECT * FROM dokumen WHERE user_id = '$user_id' ORDER BY tanggal_upload DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Status Dokumen - SICOPY</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #F6F7FB;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .navbar {
        background: #646FD4;
        color: white;
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .navbar a {
        color: white;
        text-decoration: none;
        background: rgba(255,255,255,0.2);
        padding: 8px 16px;
        border-radius: 8px;
        transition: 0.3s;
        font-weight: 500;
    }

    .navbar a:hover {
        background: rgba(255,255,255,0.35);
    }

    .container {
        max-width: 1100px;
        background: #FFFFFF;
        margin: 50px auto;
        padding: 40px 35px;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    h2 {
        text-align: center;
        color: #3F3D56;
        font-size: 22px;
        margin-bottom: 30px;
        font-weight: 600;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-radius: 12px;
        overflow: hidden;
    }

    th, td {
        border: none;
        padding: 14px 10px;
        text-align: center;
        font-size: 14px;
        vertical-align: middle;
    }

    th {
        background: #EAEAFF;
        color: #2E2E48;
        font-weight: 600;
    }

    tr:nth-child(even) {
        background: #F9FAFF;
    }

    tr:hover {
        background: #EEF0FF;
        transition: 0.2s;
    }

    .status-menunggu {
        background: #FFF3C7;
        color: #856404;
        padding: 8px 14px;
        border-radius: 8px;
        display: inline-block;
        width: 150px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-diterima {
        background: #D1FAE5;
        color: #065F46;
        padding: 8px 14px;
        border-radius: 8px;
        display: inline-block;
        width: 150px;
        font-weight: 600;
    }

    .status-ditolak {
        background: #FECACA;
        color: #991B1B;
        padding: 8px 14px;
        border-radius: 8px;
        display: inline-block;
        width: 150px;
        font-weight: 600;
    }

    .kode-booking {
        font-weight: 700;
        color: #4F46E5;
        font-size: 15px;
        letter-spacing: 1.5px;
        background: #EEF2FF;
        padding: 8px 14px;
        border-radius: 10px;
        display: inline-block;
        font-family: monospace;
        box-shadow: inset 0 0 2px rgba(0,0,0,0.08);
    }

    .btn-preview {
        background: #10B981;
        color: white;
        border: none;
        padding: 7px 14px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        transition: 0.3s;
        box-shadow: 0 2px 4px rgba(16,185,129,0.25);
    }

    .btn-preview:hover {
        background: #059669;
    }

    .btn-back {
        display: inline-block;
        margin-top: 25px;
        background: #E5E7EB;
        color: #333;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.3s;
        font-weight: 500;
    }

    .btn-back:hover {
        background: #D1D5DB;
    }

    footer {
        text-align: center;
        padding: 15px;
        margin-top: 50px;
        color: #777;
        background: #F3F4F6;
        border-top: 1px solid #E5E7EB;
        font-size: 13px;
    }
</style>
</head>
<body>
<div class="navbar">
    <h1>Status Dokumen - SICOPY</h1>
    <a href="dashboard_mahasiswa.php">Kembali</a>
</div>

<div class="container">
    <h2>Status Unggahan Dokumen Anda</h2>

    <table>
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 18%;">Kode Booking</th>
            <th style="width: 25%;">Judul Dokumen</th>
            <th style="width: 20%;">Tanggal Upload</th>
            <th style="width: 17%;">Status</th>
            <th style="width: 15%;">Keterangan Admin</th>
            <th style="width: 10%;">Preview</th>
        </tr>

        <?php 
        $no = 1;
        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $status_class = '';
                if ($row['status'] == 'Menunggu Verifikasi') $status_class = 'status-menunggu';
                elseif ($row['status'] == 'Diterima') $status_class = 'status-diterima';
                elseif ($row['status'] == 'Ditolak') $status_class = 'status-ditolak';

                echo "<tr>
                        <td>".$no++."</td>
                        <td><span class='kode-booking'>".$row['kode_book']."</span></td>
                        <td>".$row['judul']."</td>
                        <td>".$row['tanggal_upload']."</td>
                        <td><span class='$status_class'>".$row['status']."</span></td>
                        <td>".($row['catatan_admin'] ?? '-')."</td>
                        <td><a href='uploads/".$row['file']."' target='_blank'><button class='btn-preview'>Preview</button></a></td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Belum ada dokumen yang diunggah.</td></tr>";
        }
        ?>
    </table>

    <a href="dashboard_mahasiswa.php" class="btn-back">← Kembali ke Dashboard</a>
</div>

<footer>
    © 2025 SICOPY — Sistem Informasi Copy Center
</footer>
</body>
</html>
