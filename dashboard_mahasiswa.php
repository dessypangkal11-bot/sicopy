<?php
session_start();
include 'koneksi.php';

// Cek login dan role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit();
}

// Ambil data user dari session
$nama = $_SESSION['nama'];
$jurusan = $_SESSION['jurusan'];
$email = $_SESSION['email'];

// Ambil semua data toko yang aktif (dari admin)
$toko_query = mysqli_query($conn, "SELECT * FROM toko ORDER BY toko_id DESC");

// Ambil data rekomendasi (3 toko terbaru yang memiliki gambar)
$rekom_query = mysqli_query($conn, "SELECT * FROM toko WHERE gambar IS NOT NULL ORDER BY toko_id DESC LIMIT 3");

// Ambil semua toko untuk popup detail
$toko_data = [];
$fetch_all = mysqli_query($conn, "SELECT * FROM toko");
while ($row = mysqli_fetch_assoc($fetch_all)) {
    $toko_data[] = $row;
}
$jsonToko = json_encode($toko_data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Mahasiswa - SICOPY</title>
<style>
body { font-family:'Poppins', sans-serif; background:#f5f7fa; margin:0; }
.navbar { background:#4F46E5; padding:15px 30px; color:white; display:flex; justify-content:space-between; align-items:center; }
.navbar a { color:white; text-decoration:none; background:#6366F1; padding:8px 16px; border-radius:6px; }

.container { max-width:1100px; margin:40px auto; background:white; padding:30px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
h2 { text-align:center; margin-bottom:25px; color:#333; }

/* PROFILE */
.profile-card { display:flex; gap:20px; padding:20px; background:#EEF2FF; border-radius:12px; animation:fadeIn 1s ease; }
.profile-card img { width:90px; height:90px; border-radius:50%; border:3px solid #6366F1; object-fit:cover; }

/* MENU */
.menu { display:grid; grid-template-columns:repeat(auto-fit, minmax(220px,1fr)); gap:20px; margin-top:30px; }
.menu-card { padding:20px; background:#F9FAFB; border-radius:12px; text-align:center; box-shadow:0 2px 6px rgba(0,0,0,0.05); transition:.3s; }
.menu-card:hover { transform:translateY(-4px); }

/* TOKO GRID */
.toko-grid { margin-top:25px; display:grid; grid-template-columns:repeat(auto-fit, minmax(230px,1fr)); gap:25px; }
.toko-card { border-radius:12px; box-shadow:0 3px 8px rgba(0,0,0,0.1); overflow:hidden; background:white; cursor:pointer; transition:.3s; animation:fadeInUp .7s ease; }
.toko-card:hover { transform:translateY(-6px); }
.toko-card img { width:100%; height:150px; object-fit:cover; }
.toko-info { padding:15px; text-align:center; }

@keyframes fadeInUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }

/* POPUP */
.detail-popup { position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); display:none; justify-content:center; align-items:center; z-index:9999; }
.detail-content { background:white; width:370px; padding:25px; border-radius:14px; animation:fadeIn .4s ease; }
</style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <h1>SICOPY Mahasiswa</h1>
    <a href="logout.php">Logout</a>
</div>

<div class="container">

<h2>Selamat Datang, <?= htmlspecialchars($nama); ?> 👋</h2>

<!-- PROFILE -->
<div class="profile-card">
    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png">
    <div>
        <h3><?= htmlspecialchars($nama); ?></h3>
        <p><b>Jurusan:</b> <?= htmlspecialchars($jurusan); ?></p>
        <p><b>Email:</b> <?= htmlspecialchars($email); ?></p>
    </div>
</div>

<!-- MENU -->
<div class="menu">
    <a href="upload_dokumen.php" class="menu-card"><h4>📤 Upload Copy</h4><p>Unggah dokumen kamu.</p></a>
    <a href="status.php" class="menu-card"><h4>🔍 Lihat Status</h4><p>Cek status pesanan.</p></a>
    <a href="riwayat.php" class="menu-card"><h4>📜 Riwayat</h4><p>Lihat transaksi lama.</p></a>
    <a href="pembatalan.php" class="menu-card"><h4>🚫 Pembatalan</h4><p>Daftar pembatalan.</p></a>
</div>

<!-- REKOMENDASI -->
<h2 style="margin-top:50px;">✨ Rekomendasi Tempat Fotokopi</h2>
<div class="toko-grid">
<?php while ($rek = mysqli_fetch_assoc($rekom_query)) { ?>
    <div class="toko-card" onclick="showDetail(<?= $rek['toko_id']; ?>)">
        <img src="<?= !empty($rek['gambar']) ? 'uploads/'.$rek['gambar'] : 'https://cdn-icons-png.flaticon.com/512/679/679720.png'; ?>">
        <div class="toko-info">
            <h4><?= htmlspecialchars($rek['nama_toko']); ?></h4>
            <p><?= htmlspecialchars(substr($rek['alamat'],0,35)); ?>...</p>
        </div>
    </div>
<?php } ?>
</div>

<!-- SEMUA TOKO -->
<h2 style="margin-top:50px;">🏪 Semua Tempat Fotokopi</h2>
<div class="toko-grid">
<?php while ($toko = mysqli_fetch_assoc($toko_query)) { ?>
    <div class="toko-card" onclick="showDetail(<?= $toko['toko_id']; ?>)">
        <img src="<?= !empty($toko['gambar']) ? 'uploads/'.$toko['gambar'] : 'https://cdn-icons-png.flaticon.com/512/679/679720.png'; ?>">
        <div class="toko-info">
            <h4><?= htmlspecialchars($toko['nama_toko']); ?></h4>
            <p><?= htmlspecialchars(substr($toko['alamat'],0,45)); ?>...</p>
        </div>
    </div>
<?php } ?>
</div>

</div>

<!-- POPUP DETAIL -->
<div id="detailPopup" class="detail-popup">
    <div class="detail-content">
        <span onclick="closeDetail()" style="cursor:pointer;font-size:22px;float:right;">&times;</span>
        <h3 id="detailNama"></h3>
        <p><b>Alamat:</b> <span id="detailAlamat"></span></p>
        <p><b>Koordinat:</b> <span id="detailKoordinat"></span></p>
        <p><b>Layanan:</b></p>
        <div id="detailLayanan" style="margin-bottom:10px;"></div>
        <a id="btnPilih" href="#" style="padding:8px 16px;background:#4F46E5;color:white;border-radius:8px;text-decoration:none;">Pilih Toko Ini</a>
    </div>
</div>

<script>
let dataToko = <?= $jsonToko ?>;

function showDetail(id){
    let toko = dataToko.find(t => t.toko_id == id);
    if(!toko) return;
    document.getElementById("detailNama").innerText = toko.nama_toko;
    document.getElementById("detailAlamat").innerText = toko.alamat;
    document.getElementById("detailKoordinat").innerText = toko.lokasi_lat+", "+toko.lokasi_lng;

    let layananHTML = "";
    if(toko.layanan){
        toko.layanan.split(",").forEach(l => layananHTML += "• "+l.trim()+"<br>");
    }
    document.getElementById("detailLayanan").innerHTML = layananHTML;

    document.getElementById("btnPilih").href = "upload_dokumen.php?id_toko="+toko.toko_id;
    document.getElementById("detailPopup").style.display = "flex";
}

function closeDetail(){
    document.getElementById("detailPopup").style.display = "none";
}
</script>

</body>
</html>
