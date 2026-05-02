<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION["status"]) || $_SESSION['status'] !== 'login') {
    echo "<script> alert('Please log in first!'); 
            location.href = 'login.php';
        </script>";
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aksi = $_POST['aksi'];

    if ($aksi == 'tambah') {
        $nama = $_POST['nama_goal'];
        $target = $_POST['target_nominal'];
        $deadline = $_POST['deadline'];
        if ($deadline == "") {
            $deadline_val = "NULL";
        } else {
            $deadline_val = "'$deadline'";
        }

        mysqli_query($koneksi, "INSERT INTO goals(nama_goal, target_nominal, deadline, terkumpul) VALUES ('$nama', '$target', $deadline_val, 0)");
    }

    if ($aksi == 'edit') {
        $id = $_POST['goalsID'];
        $nama = $_POST['nama_goal'];
        $target = $_POST['target_nominal'];
        $deadline = $_POST['deadline'];
        if ($deadline == "") {
            $deadline_val = "NULL";
        } else {
            $deadline_val = "'$deadline'";
        }

        mysqli_query($koneksi, "UPDATE goals SET nama_goal='$nama', target_nominal='$target', deadline=$deadline_val WHERE goalsID = '$id'");
    }

    if ($aksi == 'hapus') {
        $id = $_POST['goalsID'];
        mysqli_query($koneksi, "DELETE FROM goals where goalsID = '$id'");
    }

    if ($aksi == 'topup') {
        $id = $_POST['goalsID'];
        $jumlah = $_POST['jumlah'];

        if($jumlah<=0){
            header("Location: goalss.php");
            exit;
        }

        // ketika topup melebihi sisa
        if($jumlah>$sisa){
            header("Location: goalss.php?error=melebihi");
            exit;
        }
        mysqli_query($koneksi, "UPDATE goals set terkumpul = terkumpul + $jumlah WHERE goalsID = '$id'");

    }

    header("Location: goalss.php");
    exit;
}

// ambil data dari database
$data = mysqli_query($koneksi, "SELECT * FROM goals");

function formatRp($angka)
{
    return "Rp " . number_format($angka, 0, ',', '.');
}

function hitungPersen($terkumpul, $target)
{
    if ($target == 0)
        return 0;
    return min(100, round(($terkumpul / $target) * 100));
}

function hitungSisa($terkumpul, $target)
{
    return max(0, $target - $terkumpul);
}

function formatTanggal($tanggal)
{
    if ($tanggal == "0000-00-00" || $tanggal == "" || $tanggal == null)
        return "-";
    return date("d M Y", strtotime($tanggal));
}

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

        .card-goal {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
        }

        .card-goal:hover {
            transform: translateY(-5px);
        }

        .info-bawah {
            font-size: 14px;
            color: #6c757d;
        }

        .progress {
            height: 8px;
            border-radius: 10px;
        }

        .progress-bar {
            background-color: #10b981;
        }

        .badge-persen {
            background-color: #ecfdf5;
            color: #059669;
            padding: 4px 10px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-aksi {
            flex: 1;
            border: none;
            padding: 8px;
            border-radius: 10px;
            font-size: 13px;
            transition: 0.2s;
        }

        .btn-topup {
            background-color: #ecfdf5;
            color: #059669;
        }

        .btn-topup:hover {
            background-color: #d1fae5;
        }

        .btn-edit {
            background-color: #eff6ff;
            color: #2563eb;
        }

        .btn-edit:hover {
            background-color: #dbeafe;
        }

        .btn-hapus {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .btn-hapus:hover {
            background-color: #fecaca;
        }

        .kosong {
            text-align: center;
            padding: 60px 0;
            color: #94a3b8;
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
                <a href="budgets.php" class="nav-link">
                    <i class="bi bi-pie-chart-fill me-3"></i>Budgets
                </a>
            </li>
            <li>
                <a href="goalss.php" class="nav-link active">
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

        <?php if (isset($_GET['error']) && $_GET['error'] == 'melebihi'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Jumlah top up melebihi sisa target!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
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
            <?php
            if (mysqli_num_rows($data) > 0):
                $i = 0;

                while ($dataGoal = mysqli_fetch_assoc($data)):
                    $id = $dataGoal['goalsID'];
                    $nama = $dataGoal['nama_goal'];
                    $target = $dataGoal['target_nominal'];
                    $terkumpul = $dataGoal['terkumpul'];
                    $deadline = $dataGoal['deadline'];

                    $persen = hitungPersen($terkumpul, $target);
                    $sisa = hitungSisa($terkumpul, $target);
                    $tanggal = formatTanggal($deadline);

                    $deadline_input = ($deadline == "0000-00-00" || !$deadline) ? "" : $deadline;
                    $i++;

                    ?>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card-goal">

                            <!-- nama dan target -->
                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($nama) ?></h6>
                            <p class="info-bawah mb-3">Target: <strong><?= formatRp($target) ?></strong></p>

                            <!-- progress bar -->
                            <div class="progress mb-2">
                                <div class="progress-bar" style="width: <?= $persen ?>%"></div>
                            </div>

                            <!-- persen + terkumpul -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge-persen"><?= $persen ?>%</span>
                                <span class="info-bawah"><?= formatRp($terkumpul) ?></span>
                            </div>

                            <!-- tanggal dan sisa -->
                            <div class="info-bawah mb-3">
                                <div class="d-flex justify-content-between">
                                    <span><i class="bi bi-calendar3 me-1"></i>Target Date</span>
                                    <span><?= $tanggal ?></span>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <span><i class="bi bi-coin me-1"></i>Remainder</span>
                                    <span><?= formatRp($sisa) ?></span>
                                </div>
                            </div>

                            <!-- button -->
                            <div class="d-flex gap-2">
                                <button class="btn-aksi btn-topup <?= $persen >= 100 ? 'disabled' : ''?>"
                                <?= $persen >= 100 ? 'disabled' : ''?>
                                    onclick="bukaTopup('<?= $id ?>', '<?= htmlspecialchars($nama) ?>')">
                                    <i class="bi bi-plus-circle-fill me-1"></i>Top Up
                                </button>
                                <button class="btn-aksi btn-edit"
                                    onclick="bukaEdit('<?= $id ?>', '<?= htmlspecialchars($nama) ?>', '<?= $target ?>', '<?= $deadline_input ?>')">
                                    <i class="bi bi-pencil-fill me-1"></i>Edit
                                </button>
                                <button class="btn-aksi btn-hapus"
                                    onclick="bukaHapus('<?= $id ?>', '<?= htmlspecialchars($nama) ?>')">
                                    <i class="bi bi-trash-fill me-1"></i>Delete
                                </button>
                            </div>

                        </div>
                    </div>

                <?php endwhile;
            else: ?>
                <div class="col-12 kosong">
                    <i class="bi bi-trophy" style="font-size: 48px; color: #cbd5e1;"></i>
                    <p class="mt-3">No goals yet. Add your first goal!</p>
                </div>

            <?php endif; ?>
        </div>
    </div>

    <!-- modal tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Add New Goal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body pt-0">
                    <form action="goalss.php" method="POST">
                        <input type="hidden" name="aksi" value="tambah">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Goal Name</label>
                            <input type="text" name="nama_goal" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Target (Rp)</label>
                            <input type="number" name="target_nominal" class="form-control rounded-3"
                                placeholder="ex: 50000" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Deadline <span
                                    class="text-muted">(optional)</span></label>
                            <input type="date" name="deadline" class="form-control rounded-3">
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
                    <h5 class="modal-title fw-bold">Edit Goal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body pt-0">
                    <form action="goalss.php" method="POST">
                        <input type="hidden" name="aksi" value="edit">
                        <input type="hidden" name="goalsID" id="editID">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Goal Name</label>
                            <input type="text" name="nama_goal" id="editNama" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Target (Rp)</label>
                            <input type="number" name="target_nominal" id="editTarget" class="form-control rounded-3"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Deadline</label>
                            <input type="date" name="deadline" id="editDeadline" class="form-control rounded-3">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-3">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal top up -->
    <div class="modal fade" id="modalTopup" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Top Up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <p class="text-muted">Add savings for: <strong id="topupNama"></strong></p>
                    <form method="POST" action="goalss.php">
                        <input type="hidden" name="aksi" value="topup">
                        <input type="hidden" name="goalsID" id="topupID">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Top Up Amount (Rp)</label>
                            <input type="number" name="jumlah" class="form-control rounded-3" placeholder="cth: 500000"
                                required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 rounded-3">Top Up Now</button>
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
                    <h5 class="modal-title fw-bold">Delete Goal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <p>Delete <strong id="hapusNama"></strong>?</p>
                    <p class="text-danger small">Data cannot be restored.</p>
                    <form method="POST" action="goalss.php">
                        <input type="hidden" name="aksi" value="hapus">
                        <input type="hidden" name="goalsID" id="hapusID">
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

    <!-- js -->
    <script>
        function bukaTopup(id, nama) {
            document.getElementById('topupID').value = id;
            document.getElementById('topupNama').innerText = nama;
            new bootstrap.Modal(document.getElementById('modalTopup')).show();
        }
        function bukaEdit(id, nama, target, deadline) {
            document.getElementById('editID').value = id;
            document.getElementById('editNama').value = nama;
            document.getElementById('editTarget').value = target;
            document.getElementById('editDeadline').value = deadline;
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