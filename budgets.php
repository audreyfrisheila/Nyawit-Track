<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION["status"]) || $_SESSION['status'] !== 'login') {
    echo "<script> alert('Please log in first!'); 
            location.href = 'login.php';
        </script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aksi = $_POST['aksi'];

    if ($aksi == 'tambah') {
        $categoriesID = $_POST['categoriesID'];
        $budget_limit = $_POST['budget_limit'];
        $bulan = $_POST['bulan'];
        mysqli_query($koneksi, "INSERT INTO budgets(categoriesID, budget_limit, bulan) VALUES ('$categoriesID', '$budget_limit', '$bulan')");
    }

    if ($aksi == 'edit') {
        $budgetID = $_POST['budgetID'];
        $budget_limit = $_POST['budget_limit'];
        $bulan = $_POST['bulan'];
        $categoriesID = $_POST['catgoriesID'];
        mysqli_query($koneksi, "UPDATE budgets SET budget_limit = '$budget_Limit', $bulan = '$bulan', catgeoriesID='$categoriesID' where budgetID = '$budgetID'");
    }

    if (+($aksi == 'hapus')) {
        $budgetID = $_POST['budgetID'];
        mysqli_query($koneksi, "DLEETE FROM budgets WHERE budgetID='$budgetID'");
    }

    header("Location: budgets.php");
    exit;
}

$bulan_filter = isset($_GET['bulan'] ? $_GET['bulan'] : date('Y-m'));
$bulan_sql = $bulan_filter . '-01'; // 01 disini berfungsi sbg dummy ajaa, utk melengkapi template sql 

// nampilin daftar budget per kategori di bulan tertentu dan menghitung brp uang yg udh kepake
// jadi, haislnya nanti akan kategori: makan, budget_limit: 150.000, spent: 100rb
$data = mysqli_query($koneksi, "SELECT b.budgetID, c.nama_kategori, c.categoriesID, 
b.budget_limit, b.bulan, COALESCE((SELECT SUM(t.jumlah) FROM transaction t WHERE t.categoriesID = b.categoriesID 
AND DATE_FORMAT(t.tanggal, '%Y-%m') = '$bulan_filter' AND t.jenis = 'expense'), 0) AS spent FROM budgets b JOIN categories c on 
b.categoriesID = c.categoriesID where DATE_FORMAT(b.bulan, '%Y-%m')='$bulan_filter' ORDER BY b.budgetID ASC");

$categories = mysqli_query($koneksi, "SELECT * FROM categories ORDER BY nama_kategori ASC");

function formatRp($angka)
{
    return "Rp " . number_format($angka, 0, ',', '.');
}

$icon_map = [
    'Makan & Minum' => ['bi-basket-fill', 'text-warning', 'bg-warning'],
    'Transportasi' => ['bi-car-front-fill', 'text-success', 'bg-success'],
    'Hiburan' => ['bi-controller', 'text-danger', 'bg-danger'],
    'Tagihan & Listrik' => ['bi-receipt', 'text-danger', 'bg-danger'],
    'Belanja Harian' => ['bi-bag-fill', 'text-warning', 'bg-warning'],
    'Lainnya' => ['bi-three-dots', 'text-secondary', 'bg-secondary']
];

function getIcon($nama, $icon_map)
{
    return $icon_map[$nama] ?? ['bi-tag-fill', 'text-primary', 'bg-primary'];
}

function getProgressColor($persen)
{
    if ($persen >= 95)
        return 'bg-danger';
    if ($persen >= 75)
        return 'bg-warning';
    return 'bg-success';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budgets</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
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
            width: calc(100% - 250px);
        }

        .nav-link {
            color: #6c757d !important;
            border-radius: 10px;
            margin: 5px 15px;
            padding: 10px 15px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            border: none !important;
        }

        .nav-link.active {
            background-color: #ecfdf5 !important;
            color: #059669 !important;
            font-weight: 600;
        }

        .nav-link:hover:not(.active):not(.text-danger) {
            background-color: #ecfdf5 !important;
            color: #059669 !important;
            transform: none !important;
        }

        .nav-link.text-danger {
            color: #dc2626 !important;
        }

        .nav-link.text-danger:hover {
            background-color: #fef2f2 !important;
            color: #b91c1c !important;
            transform: none !important;
        }

        .nav-link i {
            font-size: 1.1rem;
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
            <li>
                <a href="dashboard.php" class="nav-link active">
                    <i class="bi bi-grid-fill me-3"></i>Dashboard
                </a>
            </li>
            <li>
                <a href="transactions.php" class="nav-link">
                    <i class="bi bi-arrow-left-right me-3"></i>Transactions
                </a>
            </li>
            <li>
                <a href="budgets.php" class="nav-link">
                    <i class="bi bi-pie-chart-fill me-3"></i>Budgets
                </a>
            </li>
            <li>
                <a href="goalss.php" class="nav-link">
                    <i class="bi bi-trophy-fill me-3"></i>Goals
                </a>
            </li>
        </ul>

        <hr class="mx-3">
        <ul class="nav nav-pills flex-column mb-4">
            <li>
                <a href="profile.php" class="nav-link">
                    <i class="bi bi-person-circle me-3"></i>Profile
                </a>
            </li>
            <li class="mt-2">
                <a href="logout.php" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-right me-3"></i>Logout
                </a>
            </li>
        </ul>
    </div>
    <!-- end navbar -->

    
</body>

</html>