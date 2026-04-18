<?php
    session_start();
    require "koneksi.php";

    
    if(!isset($_SESSION['userID'])){
        header("location: login.php");
        exit;
    }

    if(isset($_POST['simpan'])){
        $userID = $_SESSION['userID']; 
        $jenis = $_POST['jenis']; 
        $jumlah = $_POST['jumlah']; 
        $keterangan = $_POST['keterangan'];
        $tanggal = date('Y-m-d'); 

        $query = "INSERT INTO transactions (userID, jenis, jumlah, keterangan, tanggal) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param($stmt, "isiss", $userID, $jenis, $jumlah, $keterangan, $tanggal);

        if(mysqli_stmt_execute($stmt)){
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

<div class="form-container">
    <div class="title text-center mb-4">
        <h2 style="color: var(--secondary-color); margin-top: 10px;">Transactions</h2>
        <!-- <p class="text-muted">Add your </p> -->
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

<!-- div jenis belum ada -->


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
    
</div>

</body>
</html>