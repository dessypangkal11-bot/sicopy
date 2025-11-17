<?php
session_start();
include '../koneksi.php';

// Cek apakah pengguna sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Ambil data user dari session
$nama = $_SESSION['nama'];
$email = $_SESSION['email'];

// Ambil data statistik
$total_toko = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM toko"))['total'];
$total_mahasiswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='mahasiswa'"))['total'];
$total_transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi"))['total'];
$total_pembatalan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pembatalan"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - SICOPY</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
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

        .navbar h1 {
            font-size: 20px;
            letter-spacing: 1px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            background: #6366F1;
            padding: 8px 16px;
            border-radius: 6px;
            transition: 0.3s;
        }

        .navbar a:hover {
            background: #818CF8;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        .profile-card {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: #EEF2FF;
            border-radius: 10px;
        }

        .profile-card img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #6366F1;
        }

        .profile-info h3 {
            margin: 0;
            font-size: 22px;
            color: #333;
        }

        .menu {
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .menu-card {
            background: #F9FAFB;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .menu-card h4 {
            margin-top: 10px;
            color: #4F46E5;
        }

        .menu-card p {
            font-size: 14px;
            color: #666;
        }

        footer {
            text-align: center;
            padding: 15px;
            margin-top: 40px;
            color: #777;
            background: #F3F4F6;
            border-top: 1px solid #E5E7EB;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>SICOPY Admin Dashboard</h1>
        <a href="../logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Selamat Datang, <?php echo htmlspecialchars($nama); ?> 👋</h2>

        <div class="profile-card">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Admin">
            <div class="profile-info">
                <h3><?php echo htmlspecialchars($nama); ?></h3>
                <p><b>Email:</b> <?php echo htmlspecialchars($email); ?></p>
                <p><b>Role:</b> Admin</p>
            </div>
        </div>

        <div class="menu">
            <a href="kelola_toko.php" class="menu-card">
                <h4>🏪 Kelola Toko</h4>
                <p>Tambah, ubah, dan hapus data toko fotokopi.</p>
            </a>

            <a href="kelola_mahasiswa.php" class="menu-card">
                <h4>🎓 Kelola Mahasiswa</h4>
                <p>Lihat dan kelola akun mahasiswa.</p>
            </a>

            <a href="kelola_transaksi.php" class="menu-card">
                <h4>📄 Kelola Transaksi</h4>
                <p>Monitoring dan verifikasi transaksi copy.</p>
            </a>

            <a href="kelola_pembatalan.php" class="menu-card">
                <h4>🚫 Kelola Pembatalan</h4>
                <p>Data pesanan yang dibatalkan pengguna.</p>
            </a>
        </div>

        <div class="menu" style="margin-top:40px;">
            <div class="menu-card">
                <h4>📊 Total Toko</h4>
                <p><?php echo $total_toko; ?> toko terdaftar</p>
            </div>

            <div class="menu-card">
                <h4>👥 Total Mahasiswa</h4>
                <p><?php echo $total_mahasiswa; ?> pengguna aktif</p>
            </div>

            <div class="menu-card">
                <h4>🧾 Total Transaksi</h4>
                <p><?php echo $total_transaksi; ?> transaksi</p>
            </div>

            <div class="menu-card">
                <h4>❌ Total Pembatalan</h4>
                <p><?php echo $total_pembatalan; ?> pembatalan</p>
            </div>
        </div>
    </div>

    <footer>
        © 2025 SICOPY | Sistem Copy Dokumen Mahasiswa
    </footer>
</body>
</html>
