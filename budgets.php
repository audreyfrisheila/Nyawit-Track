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
        $bulan = $_POST['bulan'] . '-01';
        mysqli_query($koneksi, "INSERT INTO budgets(categoriesID, budget_limit, bulan) VALUES ('$categoriesID', '$budget_limit', '$bulan')");
    }

    if ($aksi == 'edit') {
        $budgetID = $_POST['budgetID'];
        $budget_limit = $_POST['budget_limit'];
        $bulan = $_POST['bulan'] . '-01';
        mysqli_query($koneksi, "UPDATE budgets SET budget_limit = '$budget_limit', bulan = '$bulan' where budgetID = '$budgetID'");
    }

    if ($aksi == 'hapus') {
        $budgetID = $_POST['budgetID'];
        mysqli_query($koneksi, "DELETE FROM budgets WHERE budgetID='$budgetID'");
    }
    header("Location: budgets.php");
    exit;
}

$bulan_filter = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');
$bulan_sql = $bulan_filter . '-01'; // 01 disini berfungsi sbg dummy ajaa, utk melengkapi template sql 

// nampilin daftar budget per kategori di bulan tertentu dan menghitung brp uang yg udh kepake
// jadi, haislnya nanti akan kategori: makan, budget_limit: 150.000, spent: 100rb
$data = mysqli_query($koneksi, "SELECT b.budgetID, c.nama_kategori, c.categoriesID, 
b.budget_limit, b.bulan, COALESCE((SELECT SUM(t.jumlah) FROM transactions t WHERE t.categoriesID = b.categoriesID 
AND DATE_FORMAT(t.tanggal, '%Y-%m') = '$bulan_filter' AND t.jenis = 'pengeluaran'), 0) AS spent FROM budgets b JOIN categories c on 
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

        .nav-link:hover:not(.active):not(.text-danger) {
            background-color: #ecfdf5 !important;
            color: #059669 !important;
        }
        .nav-link.text-danger { color: #dc2626 !important; }
        .nav-link.text-danger:hover {
            background-color: #fef2f2 !important;
            color: #b91c1c !important;
        }
        .progress { height: 8px; border-radius: 10px; }
        .icon-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.15;
            position: absolute;
        }
        .icon-wrap {
            position: relative;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body class="d-flex bg-light">
    <!-- navbar -->
    <div class="sidebar d-flex flex-column shadow-sm">
        <div class="p-4 mb-2">
            <h4 class="text-success fw-bold"><i class="bi bi-wallet2 me-2"></i>Nyawit Track</h4>
        </div>

        <ul class="nav nav-pills flex-column mb-auto">
            <li>
                <a href="dashboard.php" class="nav-link">
                    <i class="bi bi-grid-fill me-3"></i>Dashboard
                </a>
            </li>
            <li>
                <a href="transactions.php" class="nav-link">
                    <i class="bi bi-arrow-left-right me-3"></i>Transactions
                </a>
            </li>
            <li>
                <a href="budgets.php" class="nav-link active">
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
        <?php if (isset($_GET['error']) && $_GET['error'] == 'duplikat'): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                This category has already been set for this month!
                <button type="button" class="btn-close" data-bs-dismiss='alert'></button>
            </div>
        <?php endif; ?>

        <!-- header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Budgets</h2>
                <p class="text-muted">Plan and manage your monthly budgets</p>
            </div>

            <div class="d-flex gap-2 align-items-center">
                <input type="month" class="form-control rounded-3" id="bulanFilter" value="<?= $bulan_filter ?>"
                    onchange="window.location='budgets.php?bulan='+this.value">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-lg me-1"></i>Add Budget
                </button>
            </div>
        </div>
        <!-- end header -->

        <!-- table -->
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">Category</th>
                            <th>Budget Limit</th>
                            <th>Spent</th>
                            <th>Remaining</th>
                            <th>Progress</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (mysqli_num_rows($data) > 0):
                            while ($row = mysqli_fetch_assoc($data)):
                                $spent = $row['spent'];
                                $limit = $row['budget_limit'];
                                $remaining = max(0, $limit - $spent);
                                $persen = $limit > 0 ? min(100, round(($spent / $limit) * 100)) : 0;
                                $progressColor = getProgressColor($persen);
                                [$icon, $iconColor, $bgColor] = getIcon($row['nama_kategori'], $icon_map);
                                $bulan_input = date('Y-m', strtotime($row['bulan']));
                                ?>

                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="icon-wrap me-1">
                                                <div class="icon-circle <?= $bgColor ?>"></div>
                                                <i class="bi <?= $icon ?> <?= $iconColor ?>"
                                                    style="z-index:1; position:relative;"></i>
                                            </div>
                                            <?= htmlspecialchars($row['nama_kategori']) ?>
                                        </div>
                                    </td>
                                    <td><?= formatRp($limit) ?></td>
                                    <td><?= formatRp($spent) ?></td>
                                    <td><?= formatRp($remaining) ?></td>
                                    <td style="width: 180px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1">
                                                <div class="progress-bar <?= $progressColor ?>" style="width: <?= $persen ?>%">
                                                </div>
                                            </div>
                                            <small><?= $persen ?>%</small>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light me-1"
                                            onclick="bukaEdit('<?= $row['budgetID'] ?>', '<?= $limit ?>', '<?= $bulan_input ?>')">
                                            <i class="bi bi-pencil text-primary"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light"
                                            onclick="bukaHapus('<?= $row['budgetID'] ?>', '<?= htmlspecialchars($row['nama_kategori']) ?>')">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile;
                        else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-pie-chart" style="font-size:40px; color:#cbd5e1;"></i>
                                    <p class="mt-3">No budgets yet for this month.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- modal tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-tittle fw-bold">Add New Budget</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body pt-0">
                    <form action="budgets.php" method="post">
                        <input type="hidden" name="aksi" value="tambah">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="categoriesID" class="form-select rounded-3" required>
                                <option value="">--- Select Category ---</option>
                                <?php
                                mysqli_data_seek($categories, 0);
                                while ($cat = mysqli_fetch_assoc($categories)): ?>
                                    <option value="<?= $cat['categoriesID'] ?>">
                                        <?= htmlspecialchars($cat['nama_kategori']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Budget Limit (Rp)</label>
                            <input type="number" name="budget_limit" class="form-control rounded-3"
                                placeholder="ex: 3000000" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Month</label>
                            <input type="month" name="bulan" class="form-control rounded-3" value="<?= $bulan_filter ?>"
                                required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 rounded-3">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Edit Budget</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <form action="budgets.php" method="POST">
                        <input type="hidden" name="aksi" value="edit">
                        <input type="hidden" name="budgetID" id="editID">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Budget Limit (Rp)</label>
                            <input type="number" name="budget_limit" id="editLimit" class="form-control rounded-3"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Month</label>
                            <input type="month" name="bulan" id="editBulan" class="form-control rounded-3" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 rounded-3">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal hapus -->
    <div class="modal fade" id="modalHapus" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Delete Budget</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <p>Delete budget for <strong id="hapusNama"></strong>?</p>
                    <p class="text-danger small">Data cannot be restored.</p>
                    <form method="POST" action="budgets.php">
                        <input type="hidden" name="aksi" value="hapus">
                        <input type="hidden" name="budgetID" id="hapusID">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary rounded-3 w-50"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger rounded-3 w-50">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function bukaEdit(id, limit, bulan) {
            document.getElementById('editID').value = id;
            document.getElementById('editLimit').value = limit;
            document.getElementById('editBulan').value = bulan;
            new bootstrap.Modal(document.getElementById('modalEdit')).show();
        }
        function bukaHapus(id, nama) {
            document.getElementById('hapusID').value = id;
            document.getElementById('hapusNama').innerText = nama;
            new bootstrap.Modal(document.getElementById('modalHapus')).show();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>