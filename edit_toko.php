<?php
include 'koneksi.php';

// Ambil ID toko dari URL
$id = $_GET['id'];

// Ambil data toko berdasarkan ID
$result = mysqli_query($conn, "SELECT * FROM toko WHERE toko_id = '$id'");
$data = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan
if (!$data) {
    die("Data toko tidak ditemukan!");
}

// Proses ketika form disubmit
if (isset($_POST['submit'])) {

    $nama_toko = $_POST['nama_toko'];
    $layanan = $_POST['layanan'];
    $lokasi_lat = $_POST['lokasi_lat'];
    $lokasi_lng = $_POST['lokasi_lng'];

    // Gambar baru (jika ada)
    $gambar_baru = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    // Cek folder
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Jika gambar baru diupload
    if (!empty($gambar_baru)) {
        move_uploaded_file($tmp, 'uploads/' . $gambar_baru);
        $gambar_final = $gambar_baru;

        // Hapus gambar lama jika ada
        if (!empty($data['gambar']) && file_exists("uploads/" . $data['gambar'])) {
            unlink("uploads/" . $data['gambar']);
        }

    } else {
        // Jika tidak upload gambar baru, pakai gambar lama
        $gambar_final = $data['gambar'];
    }

    // Update data ke database
    $update = mysqli_query($conn, "UPDATE toko SET 
                nama_toko = '$nama_toko',
                layanan = '$layanan',
                lokasi_lat = '$lokasi_lat',
                lokasi_lng = '$lokasi_lng',
                gambar = '$gambar_final'
                WHERE toko_id = '$id'");

    if ($update) {
        echo "<script>alert('Data toko berhasil diperbarui'); window.location='admin_dashboard.php';</script>";
    } else {
        die("MYSQL ERROR: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Toko</title>

<style>
    body {
        font-family: Arial;
        background: #f4f4f4;
        padding: 20px;
    }

    .container {
        background: white;
        padding: 20px;
        width: 450px;
        margin: auto;
        border-radius: 8px;
        box-shadow: 0 0 10px #ccc;
    }

    label {
        font-weight: bold;
    }

    input, textarea {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        border: 1px solid #aaa;
        border-radius: 6px;
    }

    button {
        padding: 10px 16px;
        background: #ffc107;
        color: black;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    img {
        width: 120px;
        margin-top: 8px;
        border-radius: 6px;
    }
</style>

</head>
<body>

<div class="container">
    <h2>Edit Toko</h2>

    <form action="" method="POST" enctype="multipart/form-data">

        <label>Nama Toko:</label>
        <input type="text" name="nama_toko" value="<?= $data['nama_toko']; ?>" required>

        <label>Layanan:</label>
        <textarea name="layanan" required><?= $data['layanan']; ?></textarea>

        <label>Latitude:</label>
        <input type="text" name="lokasi_lat" value="<?= $data['lokasi_lat']; ?>" required>

        <label>Longitude:</label>
        <input type="text" name="lokasi_lng" value="<?= $data['lokasi_lng']; ?>" required>

        <label>Gambar Saat Ini:</label><br>
        <?php if (!empty($data['gambar'])) { ?>
            <img src="uploads/<?= $data['gambar']; ?>">
        <?php } else { ?>
            <p>(Tidak ada gambar)</p>
        <?php } ?>

        <br><br>
        <label>Ganti Gambar (optional):</label>
        <input type="file" name="gambar">

        <br><br>
        <button type="submit" name="submit">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>
