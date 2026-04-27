<?php
session_start();
// julieeeee
if (!isset($_SESSION["status"]) || $_SESSION['status'] !== 'login') {
    echo "<script> alert('Anda Belum Login, Silakan Login Terlebih Dahulu!'); 
            location.href = 'login.php';
        </script>";
}
// komen
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style1.css">
</head>

<body class="d-flex bg-light">
    <div class="sidebar d-flex flex-column shadow-sm">
        <div class="p-4 mb-2">
            <h4 class="text-success fw-bold"><i class="bi bi-wallet2 me-2"></i>Nyawit Track</h4>
        </div>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="dashboard.php" class="nav-link text-dark"><i class="bi bi-grid-fill me-3"></i>Dashboard</a>
            </li>
            <li><a href="transactions.php" class="nav-link text-dark"><i
                        class="bi bi-arrow-left-right me-3"></i>Transactions</a></li>
            <li><a href="budgets.php" class="nav-link text-dark"><i class="bi bi-pie-chart-fill me-3"></i>Budgets</a>
            </li>
            <li><a href="goals.php" class="nav-link text-dark"><i class="bi bi-trophy-fill me-3"></i>Goals</a></li>
            <li><a href="reports.php" class="nav-link text-dark"><i class="bi bi-graph-up-arrow me-3"></i>Reports</a>
            </li>
        </ul>
        <hr class="mx-3">
        <ul class="nav nav-pills flex-column mb-4">
            <li><a href="profile.php" class="nav-link active bg-success"><i
                        class="bi bi-person-circle me-3"></i>Profile</a></li>
            <li class="mt-2">
                <a href="logout.php" class="text-danger nav-link"><i class="bi bi-box-arrow-right me-3"></i>Logout</a>
            </li>
        </ul>
    </div>
    <div class="main-content">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Profile</h2>
                <p class="text-muted">Manage your account information</p>
                <hr>
            </div>

        </header>
        <div class="card d-flex justify-content-between align-items-center mb-4" >
            <div class="card-body">
                <h5 class="card-title">Profile Information</h5>
                <form>
                    <div class="mb-3">
                        <label for="exampleInputName1" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="exampleInputName1" >
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputAddres1" class="form-label">Address</label>
                        <input type="text" class="form-control" id="exampleInputAddres1" >
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">I have read and agree to the Privacy Policy</label>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>

            </div>
        </div>


    </div>

</body>

</html>