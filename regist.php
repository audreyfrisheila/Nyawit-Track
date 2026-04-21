<?php
session_start();
require "koneksi.php";

if (isset($_POST['register'])) {
    $username = strtolower($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        echo "<script>alert('Konfirmasi Kata Sandi Tidak Cocok!'); </script>";
    } else {
        // cek apakah username udh ada di database. 
        // kl cek_jumlah lebih dari 0, brti username udh ada di database
        $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
        $cek_jumlah = mysqli_num_rows($cek_user);

        if ($cek_jumlah > 0) {
            echo "<script> alert ('Username Sudah Terdaftar'); </script>";
        } else {
            //kalau aman, username masuk ke database. pake insert into
            $insert = mysqli_query($koneksi, "INSERT INTO users (username, password) VALUES('$username', '$password')");

            if ($insert) {
                echo "<script> alert('Sign Up berhasil! Kembali ke Halaman Login'); 
                        location.href='login.php';
                        </script>";
            } else {
                echo "<script> alert('Sign Up Gagal, Sialkan Coba Lagi'); </script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style1.css">

    <style>
        :root {
            --primary-green: #2E8B57;
            --primary-hover: #1e663e;
            --light-green: #eef7f0;
            --white: #ffffff;
            --secondary-color: #2E8B57;
            --hightlight-color: #185231;
        }


        body {
            background-color: var(--light-green);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: var(--white);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(46, 139, 87, 0.15);
            width: 100%;
            max-width: 420px;
        }

        .form-container h2 {
            margin: 10px 0px !important;
            padding-bottom: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.25rem rgba(46, 139, 87, 0.25);
        }

        .btn-primary {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-outline-dark {
            border-color: var(--primary-green);
            color: var(--primary-green);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-outline-dark:hover {
            background-color: var(--light-green);
            color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-outline-dark a {
            color: inherit;
            text-decoration: none;
            display: block;
            width: 100%;
        }

        a.text-decoration-none {
            color: var(--primary-green);
            transition: color 0.3s ease;
        }

        a.text-decoration-none:hover {
            color: var(--primary-hover);
            text-decoration: underline !important;
        }

        .form-check-input:checked {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }
    </style>
</head>

<body>
    <div class="form-container">

        <h2 style="color: var(--hightlight-color); margin-top: 100px; font-weight: bold;">Registrasi</h2>

        <form action="regist.php" method="POST">

            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password"
                    required>
                <label for="password">Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                    placeholder="Konfirmasi password" required>
                <label for="confirm_password">Konfirmasi Password</label>
            </div>

            <button type="submit" name="register" class="btn btn-primary w-100 py-2 fw-bold">Daftar Sekarang</button>
        </form>
        <div class="text-center my-3 text-muted small">Sudah Punya Akun?</div>
        <button type="button" class="btn btn-outline-dark w-100 mb-3">
            <a href="login.php">Login di sini</a>
        </button>
    </div>
</body>

</html>