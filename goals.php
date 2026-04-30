<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION["status"]) || $_SESSION['status'] !== 'login') {
    echo "<script> alert('Anda Belum Login, Silakan Login Terlebih Dahulu!'); 
            location.href = 'login.php';
        </script>";
    exit;
}
// tambah goal
if(isset($_POST['submit'])){
    $nama = $_POST['nama_goal'];
    $target = $_POST['target_nominal'];
    $deadline = $_POST['deadline'];

    $query = mysqli_query($koneksi, "INSERT INTO goals (nama_goal, target_nominal, deadline, terkumpul) VALUES ('$nama', '$target', '$deadline', 0)");
    if($query){
        echo "<script>alert('Perubahan Berhasil Disimpan!');</script>";
    }
}

// tambahh pemasukan 
if(isset($_POST['topup'])){
    $id = (int) $_POST['id'];
    $jumlah = (int) $_POST['jumlah'];

    $queryTopup = mysqli_query($koneksi, "UPDATE goals SET terkumpul = terkumpul+ $jumlah WHERE goalsID=$id");
}

if(isset($_POST['edit'])){
    $id = (int) $_POST['goalsID'];
    $nama = $_POST['nama_goal'];
    $target = $_POST['target_nominal'];
    $deadline = $_POST['deadline'];

    $queryEdit = mysqli_query($koneksi, "UPDATE goals set nama_goals = '$nama', $target_nominal = '$target', deadline = '$deadline' where goalsID =' $id'");
}

// hapus
if(isset($_POST['hapus'])){
    $id= (int) $_GET['hapus'];
    $queryHapus = mysqli_query($koneksi, "DELETE FROM goals WHERE goalsID = $id");
}

// ambil data
$dataGoals = mysqli_query($koneksi, "SELECT * from goals order by goalsID desc");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOALS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-green: #2E8B57;
            --primary-hover: #1e663e;
            --light-green: #eef7f0;
            --white: #ffffff;
            --secondary-color: #2E8B57;
            --hightlight-color: #185231;
        }
        body{
            background-color: white;
        }
        .sidebar{
            width: 220px;
            height: 100vh;
            position: fixed;
            background-color: white;
            padding: 20px;
        }
        .main{
            margin-left: 240px;
            padding: 30px;
        }
        .progress{
            height: 8px;
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
            <li><a href="dashboard.php" class="nav-link"><i class="bi bi-grid-fill me-3"></i>Dashboard</a></li>
            <li><a href="transactions.php" class="nav-link"><i class="bi bi-arrow-left-right me-3"></i>Transactions</a>
            </li>
            <li><a href="budgets.php" class="nav-link"><i class="bi bi-pie-chart-fill me-3"></i>Budgets</a></li>
            <li><a href="goals.php" class="nav-link active"><i class="bi bi-trophy-fill me-3"></i>Goals</a></li>
        </ul>
        <hr class="mx-3">
        <ul class="nav nav-pills flex-column mb-4">
            <li><a href="profile.php" class="nav-link"><i class="bi bi-person-circle me-3"></i>Profile</a></li>
            <li class="mt-2">
                <a href="logout.php" class="text-danger nav-link"><i class="bi bi-box-arrow-right me-3"></i>Logout</a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Goals</h2>
                <p class="text-muted">Set financial goals and track your progress</p>
            </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#goalModal"
                style="background-color: #2E8B57; padding: 4px 12px; border: 0px; border-radius: 4px; color: white;"><b>+
                    Add
                    Goals</b></button>
        </header>

        <div class="row g-4 mb-4">
            
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Konser Lany</h6>
                        <h3 class="fw-bold text-success mb-0">Rp </h3>
                        <small class="text-success"><i class="bi bi-arrow-up"></i>..% from last month</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Make up dior</h6>
                        <h3 class="fw-bold text-success mb-0">Rp </h3>
                        <small class="text-success"><i class="bi bi-arrow-up"></i>..% from last month</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- form add goal yg ngepop up -->
    <div class="modal fade" id="goalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Goal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="goals.php" method="post">
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="nama_goal">Nama Goal</label>
                            <input type="text" name="nama_goal" id="nama_goal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="target_nominal">Target Nominal</label>
                            <input type="number" id="target_nominal" name="target_nominal" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="deadline">Deadline</label>
                            <input type="date" id="deadline" name="deadline" class="form-control" required>
                        </div>
                        
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>