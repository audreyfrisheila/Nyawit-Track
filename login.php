<?php
    session_start();
    require "koneksi.php";

   if(isset($_POST['login'])){
    $username = strtolower($_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    $cek_jumlah = mysqli_num_rows($query);

    if($cek_jumlah>0){
        $data = mysqli_fetch_array($query); //data diubah dlm bentuk array
        $_SESSION['user'] = $data['username'];
        $_SESSION['status'] = 'login';
        
        echo "<script> alert('Anda Berhasil Login!'); 
        location.href='dashboard.php';</script>";
    }else{
        echo "<script> alert('Username atau Password Tidak Cocok!'); </script>";
    }

   }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="style1.css">
   <style>
    /* Definisikan variabel warna bernuansa hijau dan putih */
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
    margin-top: 10px !important; 
}


.form-control:focus {
    border-color: var(--primary-green);
    box-shadow: 0 0 0 0.25rem rgba(46, 139, 87, 0.25);
}

/
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
    <div class="title text-center mb-4">
        <h1 style="color: var(--secondary-color);">
            
        </h1>
        <h2 style="color: var(--hightlight-color); margin-top: 100px;">Login</h2>
        <p class="text-muted">Enter your details to continue</p>
    </div>


    <form action="login.php" method="POST">
        
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="username" id="floatingUsername" placeholder="Username" required>
            <label for="floatingUsername">Username</label>
        </div>

        <div class="form-floating mb-3">
            <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password" required>
            <label for="floatingPassword">Password</label>
        </div> 

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <a href="#" class="text-decoration-none small">Need Help?</a>
        </div>

        <button type="submit" name="login" class="btn btn-primary w-100 py-2 fw-bold">Login</button>
        
    </form> <div class="text-center my-3 text-muted small">Belum Punya Akun?</div>
    <button type="button" class="btn btn-outline-dark w-100 mb-3" >
        <a href="regist.php">Sign Up</a>
    </button>
    
</div>
</body>
</html>