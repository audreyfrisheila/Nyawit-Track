<?php
    session_start();
    require "koneksi.php";

    if(isset($_POST['register'])){
        $username = strtolower($_POST['username']);
        $password = $_POST['password'];
        $confirm = $_POST['confirm_password'];

        if($password !== $confirm){
            echo "<script>alert('Konfirmasi Kata Sandi Tidak Cocok!'; </script>";
        }else{
            // cek apakah username udh ada di database. 
            // kl cek_jumlah lebih dari 0, brti username udh ada di database
            $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
            $cek_jumlah = mysqli_num_rows($cek_user);

            if($cek_jumlah > 0){
                echo "<script> alert ('Username Sudah Terdaftar'); </script>";
            }else{
                //kalau aman, username masuk ke database. pake insert into
                $insert = mysqli_query($koneksi, "INSERT INTO users (username, password) VALUES('$username', '$password')");

                if($insert){
                    echo "<script> alert('Sign Up berhasil! Kembali ke Halaman Login'); 
                        location.href='login.php';
                        </script>";
                }else{
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
</head>
<body>
     <h2>Registrasi</h2>

    <form action="regist.php" method="POST">
        
        <label for="password">Username</label><br>
        <input type="text" name="username" id="username" placeholder="Masukkan username" required><br><br>

        <label for=""password>Password</label><br>
        <input type="password" name="password" id="password" placeholder="Masukkan password" required><br><br>

        <label for="confirm_password">Konfirmasi Password</label><br>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Ulangi password" required><br><br>

        <button type="submit" name="register">Daftar Sekarang</button>
    </form>
    <p>Sudah Punya Akun? <a href="login.php">Login di sini</a></p>
</body>
</html>