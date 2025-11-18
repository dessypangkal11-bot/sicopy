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
    <title>Logout Admin - SICOPY</title>
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
            background: linear-gradient(135deg, #ffe0b2, #e1bee7);
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
            background: linear-gradient(135deg, #8e24aa, #ff7043);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        a:hover {
            background: linear-gradient(135deg, #6a1b9a, #f4511e);
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
        <h2>Admin berhasil logout</h2>
        <p>Anda telah keluar dari dashboard <strong>Admin SICOPY</strong>.</p>
        <a href="login_admin.php">Kembali ke Login Admin</a>
    </div>
</body>
</html>
