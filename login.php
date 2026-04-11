<?php
    session_start();

    $username = $_POST["username"];
    $password = $_POST["password"];

    if($username == "jul123" && $password == "123"){
        $_SESSION["user"] = $username;
        header("Location: dashboard.php");
    }else{
        echo "Username atau password Anda salah!";
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
   
</head>
<body>



<div class="form-container">
    <div class="title text-center mb-4">
        <h1 style="color: var(--secondary-color);">
            
        </h1>
        <h2 style="color: var(--hightlight-color); margin-top: 100px;">Login</h2>
        <p class="text-muted">Enter your details to continue</p>
    </div>


    <form action="dashboard.php" method="POST">
        
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

        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Login</button>
        
    </form> <div class="text-center my-3 text-muted small">OR</div>
    <button type="button" class="btn btn-outline-dark w-100 mb-3" >
        <i class="bi bi-google"></i> Sign In
    </button>
    
</div>


</body>
</html>