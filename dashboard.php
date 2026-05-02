<?php
session_start();

if (!isset($_SESSION["status"]) || $_SESSION['status'] !== 'login') {
    echo "<script> alert('Please log in first!'); 
            location.href = 'login.php';
        </script>";
}

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

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Total Balance</h6>
                        <h3 class="fw-bold text-success mb-0">Rp </h3>
                        <small class="text-success"><i class="bi bi-arrow-up"></i>..% from last month</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Monthly Income</h6>
                        <h3 class="fw-bold mb-0">Rp </h3>
                        <small class="text-primary"><i class="bi bi-arrow-up"></i>Target: Rp...</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Monthly Expenses</h6>
                        <h3 class="fw-bold text-danger mb-0">Rp </h3>
                        <small class="text-danger"><i class="bi bi-arrow-up"></i>..% higher</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-7">
                <div class="card shadow-sm p-3">
                    <h5 class="fw-bold p-3">Income vs Expenses</h5>
                    <div class="card-body d-flex justify-content-center align-items-center"
                        style="height: 250px; background: #fbfbfb; border-radius: 10px;">
                        <p class="text-muted">Chart Bar akan muncul di sini</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card shadow-sm p-3">
                    <h5 class="fw-bold p-3">Spending Overview</h5>
                    <div class="card-body d-flex justify-content-center align-items-center"
                        style="height: 250px; background: #fbfbfb; border-radius: 10px;">
                        <p class="text-muted">Donut Chart akan muncul di sini</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>