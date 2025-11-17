<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login dan mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data transaksi dari tabel transaksi berdasarkan user_id
$query = "
    SELECT t.kode_booking, d.judul, d.file, t.status, t.tanggal, t.batas_waktu, t.catatan
    FROM transaksi t
    JOIN dokumen d ON t.user_id = d.user_id
    WHERE t.user_id = '$user_id'
    ORDER BY t.tanggal DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pemesanan Dokumen - SICOPY</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #F9FAFB;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #4F46E5;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            background: #6366F1;
            padding: 8px 16px;
            border-radius: 6px;
        }

        .navbar a:hover {
            background: #818CF8;
        }

        .container {
            max-width: 900px;
            background: white;
            margin: 40px auto;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #E5E7EB;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4F46E5;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #F3F4F6;
        }

        .status {
            font-weight: bold;
            text-transform: capitalize;
        }

        .status.pending { color: #F59E0B; }
        .status.proses { color: #3B82F6; }
        .status.selesai { color: #10B981; }
        .status.batal { color: #EF4444; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Riwayat Pemesanan Dokumen</h1>
        <a href="dashboard_mahasiswa.php">Kembali</a>
    </div>

    <div class="container">
        <h2>Daftar Riwayat Transaksi Anda</h2>
        <table>
            <tr>
                <th>Kode Booking</th>
                <th>Judul Dokumen</th>
                <th>File</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Batas Waktu</th>
                <th>Catatan</th>
            </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['kode_booking']}</td>
                        <td>{$row['judul']}</td>
                        <td><a href='uploads/{$row['file']}' target='_blank'>Lihat File</a></td>
                        <td class='status {$row['status']}'>{$row['status']}</td>
                        <td>{$row['tanggal']}</td>
                        <td>" . ($row['batas_waktu'] ?: '-') . "</td>
                        <td>" . ($row['catatan'] ?: '-') . "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7' style='text-align:center;'>Belum ada riwayat transaksi.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
