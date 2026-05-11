<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION["status"]) || $_SESSION['status'] !== 'login') {
    echo "<script> 
            alert('Anda Belum Login, Silakan Login Terlebih Dahulu!'); 
            location.href = 'login.php';
          </script>";
    exit;
}



if (isset($_POST['simpan'])) {
    $nama_user  = $_POST['nama'];
    $email_user = $_POST['emails'];

    if(isset($_SESSION['userID'])) {
        $id_user = $_SESSION['userID']; 

        $query = "UPDATE users SET nama = ?, email = ? WHERE userID = ?";
        $stmt  = mysqli_prepare($koneksi, $query);

        mysqli_stmt_bind_param($stmt, "ssi", $nama_user, $email_user, $id_user);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Data profil berhasil diperbarui!');</script>";
        } else {
            echo "<script>alert('Gagal memperbarui data: " . mysqli_error($koneksi) . "');</script>";
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error: ID User tidak ditemukan di sesi ini.');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Nyawit Track</title>
    <link rel="stylesheet" href="style1.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
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
            display: flex;
            align-items: center;
            transition: 0.2s;
        }
        .nav-link.active {
            background-color: #ecfdf5 !important;
            color: #059669 !important;
            font-weight: 600;
        }
        .nav-link:hover:not(.active):not(.text-danger) {
            background-color: #ecfdf5 !important;
            color: #059669 !important;
        }
        .nav-link.text-danger:hover {
            background-color: #fef2f2 !important;
        }
    </style>
</head>

<body>
   <div class="sidebar d-flex flex-column shadow-sm">
        <div class="p-4 mb-2">
            <h4 class="text-success fw-bold"><i class="bi bi-wallet2 me-2"></i>Nyawit Track</h4>
        </div>

        <ul class="nav nav-pills flex-column mb-auto">
            <li>
                <a href="dashboard.php" class="nav-link ">
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
                <a href="profile.php" class="nav-link active">
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

    <!-- Main Content -->
    <main class="main-content">
        <header class="mb-4">
            <h2 class="fw-bold mb-0">Profile</h2>
            <p class="text-muted">Manage your account information</p>
            <hr>
        </header>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">Profile Information</h5>
                        
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Full Name</label>
                                <input type="text" name="nama" class="form-control" id="nama"  placeholder="Enter your name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="emails" class="form-label">Email Address</label>
                                <input type="email" name="emails" class="form-control" id="emails" placeholder="name@example.com" required>
                                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                            </div>
                            
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="agree" required>
                                <label class="form-check-label" for="agree">
                                    I have read and agree to the Privacy Policy
                                </label>
                            </div>
                            
                            <button type="submit" name="simpan" class="btn btn-success px-4">
                                <i class="bi bi-check-circle me-2"></i>Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>