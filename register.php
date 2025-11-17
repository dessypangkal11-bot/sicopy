<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $jurusan = $_POST['jurusan'];

    $query = "INSERT INTO users (nama, email, password, role, jurusan, created_at)
              VALUES ('$nama', '$email', '$password', '$role', '$jurusan', NOW())";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Registrasi berhasil!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Gagal registrasi!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun SICOPY</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #e0f7fa, #fce4ec);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #ffffffcc;
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            animation: fadeIn 0.6s ease-in-out;
        }

        h2 {
            text-align: center;
            color: #37474f;
            margin-bottom: 25px;
            font-weight: 600;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input, select {
            padding: 12px;
            border: 1px solid #cfd8dc;
            border-radius: 10px;
            outline: none;
            transition: 0.3s;
            font-size: 14px;
            background: #fafafa;
        }

        input:focus, select:focus {
            border-color: #00acc1;
            box-shadow: 0 0 8px rgba(0,172,193,0.3);
            background: #fff;
        }

        button {
            background: linear-gradient(135deg, #26c6da, #7e57c2);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 500;
            transition: 0.3s;
        }

        button:hover {
            background: linear-gradient(135deg, #00bcd4, #5e35b1);
            transform: translateY(-2px);
        }

        p {
            text-align: center;
            color: #455a64;
            font-size: 14px;
        }

        a {
            color: #5e35b1;
            text-decoration: none;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-10px);}
            to {opacity: 1; transform: translateY(0);}
        }

        /* Responsif untuk layar kecil */
        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registrasi Akun SICOPY</h2>
        <form method="post">
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="">Pilih Role</option>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="admin">Admin</option>
            </select>
            <input type="text" name="jurusan" placeholder="Jurusan" required>
            <button type="submit" name="register">Daftar</button>
            <p>Sudah punya akun? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>
