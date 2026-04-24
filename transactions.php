<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION["status"]) || $_SESSION['status'] !== 'login') {
    echo "<script> alert('Anda Belum Login, Silakan Login Terlebih Dahulu!'); 
            location.href = 'login.php';
        </script>";
    exit; 
}

if (isset($_POST['simpan'])) {
    $userID = $_SESSION['userID'];
    $jenis = $_POST['jenis'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];
    $tanggal = date('Y-m-d');
    
    
    $categoriesID = (isset($_POST['kategori']) && $_POST['kategori'] !== "") ? $_POST['kategori'] : NULL;

   
    $query = "INSERT INTO transactions (userID, jenis, categoriesID, jumlah, keterangan, tanggal) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    
    
    mysqli_stmt_bind_param($stmt, "isiiss", $userID, $jenis, $categoriesID, $jumlah, $keterangan, $tanggal);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script> 
                alert('Transaksi berhasil dicatat!'); 
                location.href='dashboard.php';
            </script>";
    } else {
        echo "<script> alert('Gagal menyimpan data!'); </script>";
    }
}
$queryKategori = "SELECT * FROM categories";
$resultKategori = mysqli_query($koneksi, $queryKategori);
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

<body class="d-flex bg-light">

    <div class="sidebar d-flex flex-column shadow-sm">
        <div class="p-4 mb-2">
            <h4 class="text-success fw-bold"><i class="bi bi-wallet2 me-2"></i>Nyawit Track</h4>
        </div>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="dashboard.php" class="nav-link"><i class="bi bi-grid-fill me-3"></i>Dashboard</a></li>
            <li><a href="transactions.php" class="nav-link active"><i class="bi bi-arrow-left-right me-3"></i>Transactions</a></li>
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

    <!-- mau dibuat option
    <div class="main-content flex-grow-1 p-4" style="margin-left: 250px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="bi bi-arrow-down-circle text-success me-2"></i>Catat Transaksi</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Form Pemasukan / Pengeluaran</h5>
                    </div>
                    <div class="card-body bg-light">
                        <form action="" method="POST">
                            
                            <div class="mb-3">
                                <label for="jenis" class="form-label fw-semibold">Jenis Transaksi</label>
                                <select class="form-select" id="jenis" name="jenis" onchange="toggleKategori()" required>
                                    <option value="Pemasukan" selected>Pemasukan</option>
                                    <option value="Pengeluaran">Pengeluaran</option>
                                </select>
                            </div>

                            <div class="mb-3" id="kategoriField" style="display: none;">
                                <label for="kategori" class="form-label fw-semibold">Kategori Pengeluaran</label>
                                <select class="form-select" id="kategori" name="kategori">
                                    <option value="" disabled selected>Pilih kategori...</option>
                                    <?php 
                                        
                                        while($row = mysqli_fetch_assoc($resultKategori)) {
                                            echo '<option value="' . $row['categoriesID'] . '">' . htmlspecialchars($row['nama_kategori']) . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="jumlah" class="form-label fw-semibold">Jumlah (Rp)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">Rp</span>
                                    <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Contoh: 1500000" min="1" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="keterangan" class="form-label fw-semibold">Keterangan / Sumber</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Contoh: Gaji bulan ini, Beli makan siang..." required></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" name="simpan" class="btn btn-success btn-lg" id="btnSimpan">
                                    <i class="bi bi-save me-2"></i>Simpan Transaksi
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function toggleKategori() {
            var jenis = document.getElementById("jenis").value;
            var kategoriField = document.getElementById("kategoriField");
            var btnSimpan = document.getElementById("btnSimpan");
            var dropdownKategori = document.getElementById("kategori");

            if (jenis === "Pengeluaran") {
                kategoriField.style.display = "block";
                dropdownKategori.setAttribute("required", "required"); 
                btnSimpan.classList.remove("btn-success");
                btnSimpan.classList.add("btn-danger");
                btnSimpan.innerHTML = '<i class="bi bi-save me-2"></i>Simpan Pengeluaran';
            } else {
                kategoriField.style.display = "none";
                dropdownKategori.removeAttribute("required");
                dropdownKategori.value = ""; 
                btnSimpan.classList.remove("btn-danger");
                btnSimpan.classList.add("btn-success");
                btnSimpan.innerHTML = '<i class="bi bi-save me-2"></i>Simpan Pemasukan';
            }
        }

        
        document.addEventListener("DOMContentLoaded", toggleKategori);
    </script> -->
</body>
</html>