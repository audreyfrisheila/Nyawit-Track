<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION["status"]) || $_SESSION['status'] !== 'login') {
    echo "<script> alert('Anda Belum Login, Silakan Login Terlebih Dahulu!'); 
            location.href = 'login.php';
        </script>";
    exit;
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $aksi = $_POST['aksi'];

    if($aksi = 'tambah'){
        $nama = $_POST['nama_goal'];
        $target = $_POST['target_nominal'];
        $deadline = $_POST['deadline'];
        if($deadline==""){
            $deadline_val = "NULL";
        }else{
            $deadline_val = "'$deadline'";
        }

        mysqli_query($koneksi, "INSERT INTO goals(nama_goal, target_goal, deadline, terkumpul) VALUES ('$nama', '$target', '$deadline_val', 0)");
    }

    if($aksi == 'edit'){
        $id = $_POST['goalsID'];
        $nama = $_POST['nama_goal'];
        $target = $_POST['target_nominal'];
        $deadline = $_POST['deadline'];
        if($deadline==""){
            $deadline_val = "NULL";
        }else{
            $deadline_val = "'$deadline'";
        }

        mysqli_query($koneksi, "UPDATE goals SET nama_goal='$nama', target_nominal='$target' deadline='$deadline_val' WHERE goalsID = '$id'");
    }

    if($aksi == 'hapus'){
        $id = $_POST['goalsID'];
        mysqli_query($koneksi, "DELETE FROM goals where goalsID = '$id'");
    }

    if($aksi == 'topup'){
        $id = $_POST['goalsID'];
        $jumlah = $_POST['jumlah'];
        mysqli_query($koneksi, "UPDATE goals set terkumpul = terkumpul + '$jumlah' WHERE goalsID = '$id'");

    }

    header("Location: goalss.php");
    exit;
}

// ambil data dari database
$data = mysqli_query($koneksi, "SELECT * FROM goals");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        <style>body {
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            width: 250px;
            background-color: white;
            border-right: 1px solid #dee2e6;
            position: fixed;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        .nav-link {
            color: #6c757d;
            border-radius: 10px;
            margin: 5px 15px;
            transition: 0.3s;
        }

        .nav-link.active {
            background-color: #ecfdf5 !important;
            /* Warna Emerald muda */
            color: #059669 !important;
            /* Warna Emerald tua */
            font-weight: 600;
        }

        .nav-link:hover:not(.active) {
            background-color: #f1f5f9;
        }
    </style>
</head>

<body>
    <!-- navbar -->
    <div class="sidebar d-flex flex-column shadow-sm">
        <div class="p-4 mb-2">
            <h4 class="text-success fw-bold"><i class="bi bi-wallet2 me-2"></i>Nyawit Track</h4>
        </div>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="dashboard.php" class="nav-link active"><i class="bi bi-grid-fill me-3"></i>Dashboard</a></li>
            <li><a href="transactions.php" class="nav-link"><i class="bi bi-arrow-left-right me-3"></i>Transactions</a>
            </li>
            <li><a href="budgets.php" class="nav-link"><i class="bi bi-pie-chart-fill me-3"></i>Budgets</a></li>
            <li><a href="goalss.php" class="nav-link"><i class="bi bi-trophy-fill me-3"></i>Goals</a></li>
            <li><a href="reports.php" class="nav-link"><i class="bi bi-graph-up-arrow me-3"></i>Reports</a></li>
        </ul>
        <hr class="mx-3">
        <ul class="nav nav-pills flex-column mb-4">
            <li><a href="settings.php" class="nav-link"><i class="bi bi-gear-fill me-3"></i>Settings</a></li>
            <li><a href="profile.php" class="nav-link"><i class="bi bi-person-circle me-3"></i>Profile</a></li>
            <li class="mt-2">
                <a href="logout.php" class="text-danger nav-link"><i class="bi bi-box-arrow-right me-3"></i>Logout</a>
            </li>
        </ul>
    </div>
    <!-- end navbar -->

    <div class="main-content">

        <!-- header -->
         <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Goals</h2>
                <p class="text-muted">Set financial goals and track your progress</p>
            </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg me-1"></i>Add Goal
            </button>
         </div>

         <!-- goals cards -->
        <div class="row g-4">
            
        </div>



    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>