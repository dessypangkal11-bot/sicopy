<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query hanya akun admin
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah akun admin ditemukan
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $row['password'])) {

            // Simpan session admin
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = "admin";

            // Arahkan ke dashboard admin
            header("Location: admin_dashboard.php");
            exit();

        } else {
            echo "<script>alert('Password salah!');</script>";
        }

    } else {
        echo "<script>alert('Akun admin tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - SICOPY</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #FFECEC;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
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
            color: #DC3545;
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
            background: #DC3545;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #E4606D;
        }
        p {
            text-align: center;
            margin-top: 12px;
        }
        a {
            color: #DC3545;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login Admin</h2>
    <form method="post">
        <input type="email" name="email" placeholder="Email Admin" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Masuk</button>
    </form>
</div>

</body>
</html>
