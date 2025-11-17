<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - SICOPY</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #e0f7fa, #ede7f6);
            margin: 0;
        }

        .logout-box {
            background: #ffffffcc;
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            animation: fadeIn 0.6s ease-in-out;
        }

        h2 {
            color: #37474f;
            margin-bottom: 15px;
            font-weight: 600;
        }

        p {
            color: #455a64;
            font-size: 14px;
            margin-bottom: 25px;
        }

        a {
            background: linear-gradient(135deg, #7e57c2, #26c6da);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        a:hover {
            background: linear-gradient(135deg, #5e35b1, #00bcd4);
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-10px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>
    <div class="logout-box">
        <h2>Anda telah keluar</h2>
        <p>Terima kasih telah menggunakan aplikasi <strong>SICOPY</strong>.</p>
        <a href="login.php">Kembali ke Halaman Login</a>
    </div>
</body>
</html>
