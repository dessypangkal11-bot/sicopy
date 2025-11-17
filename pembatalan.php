<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login dan berperan sebagai mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Simpan pilihan kode terakhir
$selected_kode = isset($_POST['kode_book']) ? $_POST['kode_book'] : ($_SESSION['selected_kode'] ?? '');
if (!empty($selected_kode)) {
    $_SESSION['selected_kode'] = $selected_kode;
}

// Jika form pembatalan dikirim
if (isset($_POST['batal'])) {
    $kode_book = mysqli_real_escape_string($conn, $_POST['kode_book']);
    $alasan = mysqli_real_escape_string($conn, $_POST['alasan']);

    // Cek apakah dokumen dengan kode itu milik user dan belum dibatalkan
    $cek = mysqli_query($conn, "
        SELECT * FROM dokumen 
        WHERE kode_book = '$kode_book' 
        AND user_id = '$user_id'
        AND status NOT IN ('Dibatalkan','Diterima','Ditolak')
    ");

    if ($cek && mysqli_num_rows($cek) > 0) {
        $dok = mysqli_fetch_assoc($cek);
        $dokumen_id = $dok['dokumen_id'];

        // Simpan ke tabel riwayat_pembatalan
        $insert = mysqli_query($conn, "
            INSERT INTO riwayat_pembatalan (user_id, dokumen_id, alasan, tanggal)
            VALUES ('$user_id', '$dokumen_id', '$alasan', NOW())
        ");

        if ($insert) {
            // Update status dokumen
            $update = mysqli_query($conn, "
                UPDATE dokumen 
                SET status = 'Dibatalkan' 
                WHERE dokumen_id = '$dokumen_id'
            ");

            if ($update) {
                unset($_SESSION['selected_kode']);
                echo "<script>alert('✅ Dokumen berhasil dibatalkan.'); window.location='pembatalan.php';</script>";
            } else {
                echo "<script>alert('❌ Gagal memperbarui status dokumen: " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('❌ Gagal menyimpan ke tabel riwayat_pembatalan: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('⚠️ Kode booking tidak ditemukan atau sudah tidak bisa dibatalkan.');</script>";
    }
}

// Ambil daftar dokumen aktif user (yang masih bisa dibatalkan)
$dokumen = mysqli_query($conn, "
    SELECT dokumen_id, kode_book, status 
    FROM dokumen 
    WHERE user_id = '$user_id' 
    AND status NOT IN ('Dibatalkan', 'Diterima', 'Ditolak')
    ORDER BY tanggal_upload DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pembatalan Pesanan - SICOPY</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
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
        color: #4F46E5;
        background: white;
        padding: 8px 14px;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 500;
        transition: 0.3s;
    }

    .navbar a:hover {
        background: #E0E7FF;
    }

    .main-container {
        max-width: 700px;
        margin: 60px auto;
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #4338CA;
        text-align: center;
        margin-bottom: 25px;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    label {
        font-weight: 600;
        color: #374151;
    }

    select, textarea, button {
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        padding: 10px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        outline: none;
        width: 100%;
    }

    select, textarea {
        background-color: #F9FAFB;
    }

    button {
        background: #4F46E5;
        color: white;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: 0.3s;
        padding: 12px;
        border-radius: 8px;
    }

    button:hover {
        background: #3730A3;
    }

    .view-history {
        display: block;
        text-align: center;
        margin-top: 25px;
        background: #E0E7FF;
        color: #4338CA;
        font-weight: 600;
        padding: 10px;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.3s;
    }

    .view-history:hover {
        background: #C7D2FE;
    }

    footer {
        text-align: center;
        padding: 15px;
        background: #F3F4F6;
        color: #6B7280;
        margin-top: 40px;
        font-size: 14px;
    }
</style>
</head>
<body>

<div class="navbar">
    <h1>🗂️ Pembatalan Pesanan</h1>
    <a href="dashboard_mahasiswa.php">Kembali ke Dashboard</a>
</div>

<div class="main-container">
    <h2>Batalkan Dokumen</h2>
    <form method="POST">
        <label for="kode_book">Pilih Kode Booking:</label>
        <select name="kode_book" id="kode_book" required>
            <option value="">-- Pilih Kode Booking --</option>
            <?php 
            if ($dokumen && mysqli_num_rows($dokumen) > 0) {
                while ($row = mysqli_fetch_assoc($dokumen)) {
                    $selected = ($selected_kode == $row['kode_book']) ? 'selected' : '';
                    echo "<option value='{$row['kode_book']}' $selected>
                            {$row['kode_book']} ({$row['status']})
                          </option>";
                }
            } else {
                echo "<option disabled>Tidak ada dokumen yang bisa dibatalkan.</option>";
            }
            ?>
        </select>

        <label for="alasan">Alasan Pembatalan:</label>
        <textarea name="alasan" id="alasan" placeholder="Tuliskan alasan pembatalan..." required></textarea>

        <button type="submit" name="batal">Batalkan Dokumen</button>
    </form>

    <a href="riwayat_pembatalan.php" class="view-history">📜 Lihat Riwayat Pembatalan</a>
</div>

<footer>
    © 2025 SICOPY | Sistem Copy Dokumen Mahasiswa
</footer>

</body>
</html>
