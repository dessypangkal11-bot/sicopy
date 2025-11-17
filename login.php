<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Ambil data berdasarkan email
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            // Simpan data user ke dalam session
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['jurusan'] = $row['jurusan'];
            $_SESSION['role'] = $row['role'];

            // Arahkan berdasarkan role
            if ($row['role'] === 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_mahasiswa.php");
            }
            exit;
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Email tidak terdaftar!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SICOPY</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #EEF2FF;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #4F46E5;
            margin-bottom: 20px;
        }
        input, button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        button {
            background: #4F46E5;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #6366F1;
        }
        p {
            text-align: center;
            margin-top: 12px;
        }
        a {
            color: #4F46E5;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="login-box">
    <h2>Login SICOPY</h2>
    <form method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Masuk</button>
        <p>Belum punya akun? <a href="register.php">Daftar</a></p>
    </form>
</div>
</body>
</html>
