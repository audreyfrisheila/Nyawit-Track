<?php
session_start();
require "koneksi.php";


if (!isset($_SESSION["status"]) || $_SESSION['status'] !== 'login') {
    echo "<script> alert('Anda Belum Login, Silakan Login Terlebih Dahulu!'); 
            location.href = 'login.php';
        </script>";
}

if (isset($_POST['simpan'])) {
    $userID = $_SESSION['userID'];
    $jenis = $_POST['jenis'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];
    $tanggal = date('Y-m-d');

    $query = "INSERT INTO transactions (userID, jenis, jumlah, keterangan, tanggal) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "isiss", $userID, $jenis, $jumlah, $keterangan, $tanggal);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script> 
                alert('Transaksi berhasil dicatat!'); 
                location.href='dashboard.php';
            </script>";
    } else {
        echo "<script> alert('Gagal menyimpan data!'); </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="style1.css">
     
</head>

<body>

    <div class="sidebar d-flex flex-column shadow-sm">
        <div class="p-4 mb-2">
            <h4 class="text-success fw-bold"><i class="bi bi-wallet2 me-2"></i>Nyawit Track</h4>
        </div>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="dashboard.php" class="nav-link"><i class="bi bi-grid-fill me-3"></i>Dashboard</a></li>
            <li><a href="transactions.php" class="nav-link active"><i class="bi bi-arrow-left-right me-3"></i>Transactions</a>
            </li>
            <li><a href="budgets.php" class="nav-link"><i class="bi bi-pie-chart-fill me-3"></i>Budgets</a></li>
            <li><a href="goals.php" class="nav-link"><i class="bi bi-trophy-fill me-3"></i>Goals</a></li>
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

    <!-- 
<div class="form-container">
    <div class="title text-center mb-4">
        <h2 style="color: var(--secondary-color); margin-top: 10px;">Transactions</h2>
        
    </div>

    <form action="" method="POST">
        
        <div class="form-floating mb-3">
            <select class="form-select" name="jenis" id="floatingSelect" required>
                <option value="" selected disabled>Choose</option>
                <option value="Pemasukan">Income</option>
                <option value="Pengeluaran">Outcome</option>
            </select>
            <label for="floatingSelect">Category (Income/Outcome)</label>
        </div>

        <div class="form-floating mb-3">
            <input type="number" class="form-control" name="jumlah" id="floatingJumlah" placeholder="Jumlah" min="1" required>
            <label for="floatingJumlah">Rp.</label>
        </div>




        <div class="form-floating mb-4">
            <input type="text" class="form-control" name="keterangan" id="floatingKeterangan" placeholder="Keterangan" required>
            <label for="floatingKeterangan">Notes</label>
        </div> 

        <button type="submit" name="simpan" class="btn btn-primary w-100 py-2 fw-bold">
            <i class="bi bi-check-circle me-2"></i>Save
        </button>
        
    </form> 
    
    <button type="button" class="btn btn-outline-dark w-100 mt-3" onclick="location.href='dashboard.php'">
        <i class="bi bi-arrow-left me-2"></i>Undo and Back
    </button>
    
</div> -->

</body>

</html>