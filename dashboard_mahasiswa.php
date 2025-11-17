<?php
session_start();
include 'koneksi.php';

// Cek apakah pengguna sudah login dan role-nya mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit();
}

// Ambil data user dari session
$nama = $_SESSION['nama'];
$jurusan = $_SESSION['jurusan'];
$email = $_SESSION['email'];

// Ambil data toko dari database
$toko_query = mysqli_query($conn, "SELECT * FROM toko WHERE status='Aktif'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Mahasiswa - SICOPY</title>
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

        .profile-info p {
            margin: 4px 0;
            color: #555;
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

        /* === Bagian Daftar Toko === */
        .toko-section {
            margin-top: 50px;
        }

        .toko-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
        }

        .toko-card {
            background: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .toko-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .toko-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }

        .toko-info {
            padding: 15px;
        }

        .toko-info h4 {
            margin: 8px 0;
            color: #4F46E5;
        }

        .toko-info p {
            font-size: 13px;
            color: #555;
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
        <h1>SICOPY Mahasiswa</h1>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Selamat Datang, <?php echo htmlspecialchars($nama); ?> 👋</h2>

        <div class="profile-card">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile">
            <div class="profile-info">
                <h3><?php echo htmlspecialchars($nama); ?></h3>
                <p><b>Jurusan:</b> <?php echo htmlspecialchars($jurusan); ?></p>
                <p><b>Email:</b> <?php echo htmlspecialchars($email); ?></p>
            </div>
        </div>

        <div class="menu">
            <a href="upload_dokumen.php" class="menu-card">
                <h4>📤 Upload Copy</h4>
                <p>Unggah dokumen atau data copy yang diperlukan.</p>
            </a>

            <a href="status.php" class="menu-card">
                <h4>🔍 Lihat Status</h4>
                <p>Periksa status pengajuan copy atau dokumen kamu.</p>
            </a>

            <a href="riwayat.php" class="menu-card">
                <h4>📜 Riwayat Transaksi</h4>
                <p>Lihat daftar pengajuan sebelumnya dengan detail.</p>
            </a>

            <a href="pembatalan.php" class="menu-card">
                <h4>🚫 Pembatalan Pesanan</h4>
                <p>Lihat daftar pembatalan pesanan.</p>
            </a>
        </div>

        <!-- Bagian Daftar Toko -->
        <div class="toko-section">
            <h2>🏪 Pilih Toko Fotokopi</h2>
            <div class="toko-grid">
                <?php while ($toko = mysqli_fetch_assoc($toko_query)) { ?>
                    <div class="toko-card">
                        <img src="<?php echo $toko['foto'] ? $toko['foto'] : 'https://cdn-icons-png.flaticon.com/512/679/679720.png'; ?>" alt="Toko">
                        <div class="toko-info">
                            <h4><?php echo htmlspecialchars($toko['nama_toko']); ?></h4>
                            <p><?php echo htmlspecialchars($toko['alamat']); ?></p>
                            <a href="upload_dokumen.php?id_toko=<?php echo $toko['toko_id']; ?>" style="display:inline-block;margin-top:10px;padding:6px 14px;background:#4F46E5;color:white;text-decoration:none;border-radius:6px;font-size:14px;">Pilih Toko</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <footer>
        © 2025 SICOPY | Sistem Copy Dokumen Mahasiswa
    </footer>
</body>
</html>
