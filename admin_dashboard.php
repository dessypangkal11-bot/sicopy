<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil data untuk dashboard
$total_pengguna = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='mahasiswa'"))['total'];
$total_toko = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM toko"))['total'];
$total_transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM transaksi"))['total'];
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_transaksi) AS total FROM laporan"))['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SICOPY</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            display: flex;
            background: #f3e5f5;
            color: #333;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            background: linear-gradient(135deg, #7e57c2, #26c6da);
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
            padding: 20px;
            margin: 0;
            background: rgba(255,255,255,0.1);
            letter-spacing: 1px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 15px 20px;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.2);
            padding-left: 25px;
        }

        .sidebar-footer {
            padding: 15px;
            text-align: center;
            background: rgba(255,255,255,0.1);
        }

        /* Main */
        .main-content {
            margin-left: 240px;
            padding: 30px;
            flex: 1;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h1 {
            font-size: 22px;
            color: #4a148c;
        }

        .topbar a {
            background: #26c6da;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.3s;
        }

        .topbar a:hover {
            background: #0097a7;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin: 0;
            color: #7e57c2;
        }

        .card p {
            font-size: 24px;
            font-weight: 600;
            color: #4a148c;
        }

        footer {
            margin-top: 40px;
            text-align: center;
            color: #888;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div>
            <h2>SICOPY Admin</h2>
            <a href="dashboard_admin.php">🏠 Dashboard</a>
            <a href="kelola_toko.php">🏪 Kelola Toko</a>
            <a href="kelola_pengguna.php">👥 Kelola Pengguna</a>
            <a href="kelola_transaksi.php">💰 Transaksi</a>
            <a href="laporan_admin.php">📊 Laporan</a>
        </div>
        <div class="sidebar-footer">
            <a href="logout.php" style="color:#fff;">🚪 Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h1>Selamat Datang, <?php echo $_SESSION['nama']; ?> 👋</h1>
            <a href="logout.php">Logout</a>
        </div>

        <div class="cards">
            <div class="card">
                <h3>Total Pengguna</h3>
                <p><?php echo $total_pengguna; ?></p>
            </div>
            <div class="card">
                <h3>Total Toko</h3>
                <p><?php echo $total_toko; ?></p>
            </div>
            <div class="card">
                <h3>Total Transaksi</h3>
                <p><?php echo $total_transaksi; ?></p>
            </div>
            <div class="card">
                <h3>Total Pendapatan</h3>
                <p>Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></p>
            </div>
        </div>

        <footer>
            © 2025 SICOPY | Sistem Informasi Copy Center Mahasiswa
        </footer>
    </div>
</body>
</html>
