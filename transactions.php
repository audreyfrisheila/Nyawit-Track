<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION["status"]) || $_SESSION['status'] !== 'login') {
    echo "<script> 
            alert('Anda Belum Login, Silakan Login Terlebih Dahulu!'); 
            window.location.href = 'login.php';
          </script>";
    exit; 
}

$userID = $_SESSION['userID'];

//create
if (isset($_POST['simpan'])) {
    $jenis = $_POST['jenis'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];
    $tanggal = date('Y-m-d');
    $categoriesID = (!empty($_POST['kategori'])) ? $_POST['kategori'] : NULL;

    $query = "INSERT INTO transactions (userID, jenis, categoriesID, jumlah, keterangan, tanggal) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "isiiss", $userID, $jenis, $categoriesID, $jumlah, $keterangan, $tanggal);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Transaksi berhasil dicatat!'); window.location.href='transactions.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data!');</script>";
    }
}

//update
if (isset($_POST['update'])) {
    $transactionID = $_POST['transactionID']; // Pastikan nama primary key sesuai di DB
    $jenis = $_POST['jenis'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];
    $categoriesID = (!empty($_POST['kategori'])) ? $_POST['kategori'] : NULL;

    // Menambahkan $userID di WHERE clause untuk keamanan (agar user hanya bisa edit datanya sendiri)
    $query = "UPDATE transactions SET jenis = ?, categoriesID = ?, jumlah = ?, keterangan = ? WHERE transactionID = ? AND userID = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "siisii", $jenis, $categoriesID, $jumlah, $keterangan, $transactionID, $userID);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Transaksi berhasil diupdate!'); window.location.href='transactions.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data!');</script>";
    }
}

//delete
if (isset($_POST['hapus'])) {
    $transactionID = $_POST['transactionID'];

    $query = "DELETE FROM transactions WHERE transactionID = ? AND userID = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ii", $transactionID, $userID);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Transaksi berhasil dihapus!'); window.location.href='transactions.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!');</script>";
    }
}

//read
$queryKategori = "SELECT * FROM categories";
$resultKategori = mysqli_query($koneksi, $queryKategori);

$queryTransaksi = "SELECT t.*, c.nama_kategori 
                   FROM transactions t 
                   LEFT JOIN categories c ON t.categoriesID = c.categoriesID 
                   WHERE t.userID = ? 
                   ORDER BY t.tanggal DESC";
$stmtTransaksi = mysqli_prepare($koneksi, $queryTransaksi);
mysqli_stmt_bind_param($stmtTransaksi, "i", $userID);
mysqli_stmt_execute($stmtTransaksi);
$resultTransaksi = mysqli_stmt_get_result($stmtTransaksi);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - Nyawit Track</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style1.css">
    <style>
        body { overflow-x: hidden; }
        .sidebar { min-height: 100vh; width: 250px; position: fixed; background: white; z-index: 1000; }
        .main-content { margin-left: 250px; padding: 2rem; width: calc(100% - 250px); }
    </style>
</head>
<body class="d-flex bg-light">
    
    <div class="sidebar d-flex flex-column shadow-sm">
        <div class="p-4 mb-2">
            <h4 class="text-success fw-bold"><i class="bi bi-wallet2 me-2"></i>Nyawit Track</h4>
        </div>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="dashboard.php" class="nav-link text-dark"><i class="bi bi-grid-fill me-3"></i>Dashboard</a></li>
            <li><a href="transactions.php" class="nav-link active bg-success"><i class="bi bi-arrow-left-right me-3"></i>Transactions</a></li>
            <li><a href="budgets.php" class="nav-link text-dark"><i class="bi bi-pie-chart-fill me-3"></i>Budgets</a></li>
            <li><a href="goals.php" class="nav-link text-dark"><i class="bi bi-trophy-fill me-3"></i>Goals</a></li>
            <li><a href="reports.php" class="nav-link text-dark"><i class="bi bi-graph-up-arrow me-3"></i>Reports</a></li>
        </ul>
        <hr class="mx-3">
        <ul class="nav nav-pills flex-column mb-4">
            <li><a href="settings.php" class="nav-link text-dark"><i class="bi bi-gear-fill me-3"></i>Settings</a></li>
            <li><a href="profile.php" class="nav-link text-dark"><i class="bi bi-person-circle me-3"></i>Profile</a></li>
            <li class="mt-2">
                <a href="logout.php" class="text-danger nav-link"><i class="bi bi-box-arrow-right me-3"></i>Logout</a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Transaction</h2>
                <p class="text-muted">Set Your Money Transaction Here</p>
            </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#transactionModal"
                style="background-color: #2E8B57; padding: 4px 12px; border: 0px; border-radius: 4px; color: white;"><b>+
                    Add
                    Transactions</b></button>
        </header>

       <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Kategori</th>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($resultTransaksi) > 0) : ?>
                                <?php while($row = mysqli_fetch_assoc($resultTransaksi)) : ?>
                                    <tr>
                                        <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                                        <td>
                                            <?php if($row['jenis'] == 'pemasukan') : ?>
                                                <span class="badge bg-success bg-opacity-10 text-success">Pemasukan</span>
                                            <?php else : ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger">Pengeluaran</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $row['nama_kategori'] ?? '-' ?></td>
                                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                                        <td class="fw-bold <?= $row['jenis'] == 'pemasukan' ? 'text-success' : 'text-danger' ?>">
                                            <?= $row['jenis'] == 'pemasukan' ? '+' : '-' ?> Rp <?= number_format($row['jumlah'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data transaksi.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- form add goal yg ngepop up -->
    <div class="modal fade" id="transactionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="transactions.php" method="POST">
                    <div class="modal-body">

                        <div class="mb-3">
                            <!-- jenisssssss -->
                            <label for="nama_transaksi">Transaction</label>
                            <input type="text" name="nama_transaksi" id="nama_transaksi" class="form-control" required>
                        </div>
                        <select class="form-select" id="jenis" name="jenis" required>
                                <option value="pemasukan">Pemasukan</option>
                                <option value="pengeluaran">Pengeluaran</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label fw-semibold">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori">
                                <option value="">Pilih Kategori...</option>
                                <?php while($kat = mysqli_fetch_assoc($resultKategori)) : ?>
                                    <option value="<?= $kat['categoriesID'] ?>"><?= $kat['nama_kategori'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label fw-semibold">Jumlah (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" min="0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label fw-semibold">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="simpan" class="btn btn-success px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>