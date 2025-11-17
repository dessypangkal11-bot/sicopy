<?php
session_start();
include 'koneksi.php';

// Pastikan hanya mahasiswa yang bisa akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit();
}

// Ambil data dari session
$user_id = $_SESSION['user_id'];
$nama = $_SESSION['nama'];
$email = $_SESSION['email'];
$jurusan = $_SESSION['jurusan'];

// Proses upload
if (isset($_POST['upload'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    // File upload
    $file = $_FILES['file']['name'];
    $tmp_name = $_FILES['file']['tmp_name'];
    $folder = "uploads/" . basename($file);

    // Validasi file
    $allowed_extensions = ['pdf', 'doc', 'docx', 'jpg', 'png'];
    $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_extensions)) {
        echo "<script>alert('Format file tidak diizinkan! (pdf, doc, docx, jpg, png)');</script>";
    } elseif ($_FILES['file']['size'] > 5000000) {
        echo "<script>alert('Ukuran file terlalu besar! Maksimal 5MB.');</script>";
    } else {
        if (move_uploaded_file($tmp_name, $folder)) {
            $query = "INSERT INTO dokumen (user_id, nama, email, jurusan, judul, keterangan, file, status, tanggal_upload) 
                      VALUES ('$user_id', '$nama', '$email', '$jurusan', '$judul', '$keterangan', '$file', 'Menunggu Verifikasi', NOW())";
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Dokumen berhasil diunggah!'); window.location='dashboard_mahasiswa.php';</script>";
            } else {
                echo "<script>alert('Gagal menyimpan data ke database.');</script>";
            }
        } else {
            echo "<script>alert('Gagal mengunggah file!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Dokumen - SICOPY</title>
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
            transition: 0.3s;
        }

        .navbar a:hover {
            background: #818CF8;
        }

        .container {
            max-width: 600px;
            background: white;
            margin: 50px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            padding: 10px;
            border: 1px solid #DDD;
            border-radius: 8px;
            font-size: 15px;
        }

        textarea {
            resize: none;
            height: 100px;
        }

        button {
            background: #4F46E5;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #3730A3;
        }

        .back {
            text-align: center;
            margin-top: 15px;
        }

        .back a {
            color: #4F46E5;
            text-decoration: none;
            font-size: 14px;
        }

        .back a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Upload Dokumen - SICOPY</h1>
        <a href="dashboard_mahasiswa.php">Kembali</a>
    </div>

    <div class="container">
        <h2>Unggah Dokumen Mahasiswa</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="judul" placeholder="Judul Dokumen" required>
            <textarea name="keterangan" placeholder="Request pesanan..." required></textarea>
            <input type="file" name="file" required>
            <button type="submit" name="upload">Unggah Sekarang</button>
        </form>

        <div class="back">
            <a href="dashboard_mahasiswa.php">← Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>
