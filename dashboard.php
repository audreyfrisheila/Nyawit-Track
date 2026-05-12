<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION["status"]) || $_SESSION['status'] !== 'login') {
    echo "<script> alert('Please log in first!'); 
            location.href = 'login.php';
        </script>";
}

$sisaSaldo = mysqli_query($koneksi, "SELECT SUM(CASE WHEN jenis='pemasukan' THEN jumlah ELSE 0 END)- SUM(CASE WHEN jenis='pengeluaran' THEN jumlah ELSE 0 END) as balance FROM transactions WHERE userID = {$_SESSION['userID']}");
$dataSaldo = mysqli_fetch_assoc($sisaSaldo)['balance'] ?? 0;

$sisaSaldo2 = mysqli_query($koneksi, "SELECT SUM(jumlah) AS monthly FROM transactions WHERE jenis='pengeluaran' AND MONTH(tanggal) = MONTH(NOW()) AND YEAR(tanggal) = YEAR(NOW()) AND userID = {$_SESSION['userID']}");
$monthly = mysqli_fetch_assoc($sisaSaldo2)['monthly'] ?? 0;


// ambil data dari transactions
$qryTrans = mysqli_query($koneksi, "SELECT * FROM transactions where userID = {$_SESSION['userID']} order by tanggal DESC limit 10");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard</title>

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

    <div class="main-content">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Welcome Back, <?php echo strtoupper($_SESSION['user']); ?>!</h2>
                <p class="text-muted">What's happening with Your money today?</p>
            </div>
            <span class="badge g-light text-dark border p-2 px-3 shadow-sm rounded-pill">
                <i class="bi bi-calendar3 me-2"></i><?php echo date('d M, Y'); ?>
            </span>
        </header>

        <div class="row g-4 mb-4" style="display: flex; justify-content: center; align-items: center;">
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Total Balance</h6>
                        <h3 class="fw-bold text-success mb-0">Rp <?= number_format($dataSaldo, 0, ',', '.') ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Expenses</h6>
                        <h3 class="fw-bold text-danger mb-0">Rp <?= number_format($monthly, 0, ',', '.') ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <h5>Recent Activities</h5>
            <!-- mau tampilin aktivitas terbaru, stepnya: 
             1. ambil data dari table transactions
             2. tampilin di dashboard pake while
             3. bandingkan tgl di db sm tgl skrg real time -->

            <?php
            if (mysqli_num_rows($qryTrans) == 0) {
                echo "<p class='text-muted'>You haven't made any transactions yet.</p>";
            } else {
                while ($data = mysqli_fetch_assoc($qryTrans)) {
                    $warna = $data['jenis'] == 'pemasukan' ? " #1D9E75" : "#E24B4A";
                    $tanda = $data['jenis'] == 'pemasukan' ? "+" : "-";

                    $tanggalDb = $data['tanggal'];
                    $today = date('Y-m-d');
                    $yesterday = date('Y-m-d', strtotime('-1 day'));

                    if ($tanggalDb == $today) {
                        $labelTgl = 'Today';
                    } else if ($tanggalDb == $yesterday) {
                        $labelTgl = 'Yesterday';
                    } else {
                        $labelTgl = date('d M', strtotime($tanggalDb));
                    }

                    echo "
                        <div style='display:flex; gap:10px; align-items:flex-start; padding:10px 0; border-bottom:1px solid #eee;'>
                            <div style='width:10px; height:10px; border-radius:50%; background:$warna; margin-top:4px; flex-shrink:0;'></div>
                            <div style='flex:1;'>
                                <div>{$data['keterangan']}</div>
                                <div style='color: gray; font-size:12px;'>$labelTgl</div>
                            </div>
                            <div style='font-weight:500; color:$warna;'>$tanda Rp " . number_format($data['jumlah'], 0, ',', '.') . "</div>
                        </div>
                    ";
                }
            }
            ?>

        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>