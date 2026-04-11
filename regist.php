<?php
    require "koneksi.php";
    $pesan = "";

    if(isset($_POST['register'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm = $_POST['confirm_password'];

        if($password !== $confirm){
            $message = "<script> 'Konfirmasi kata sandi tidak cocok.'</script>";
        }else{
            // cek user udh ada di db blm
            $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
            $cek_jumlah = mysqli_num_rows($query);

            if($cek_jumlah > 0){
                echo "<script>'Sign Un Berhasil!'</script>";
                echo ""
            }else{
                echo
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

    <form action="proses_register.php" method="POST">
        
        <label>Username</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>

        <label>Konfirmasi Password</label><br>
        <input type="password" name="confirm_password" required><br><br>

        <button type="submit" name="register">Daftar</button>
    
        </form>
</body>
</html>