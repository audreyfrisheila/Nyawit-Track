<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION["status"]) || $_SESSION['status'] !== 'login') {
    echo "<script> alert('Please log in first!'); 
            location.href = 'login.php';
        </script>";
    exit;
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $aksi = $_POST['aksi'];

    if($aksi == 'tambah'){
        $categoriesID = $_POST['categoriesID'];
        $budget_limit = $_POST['budget_limit'];
        $bulan = $_POST['bulan'];
        mysqli_query($koneksi, "INSERT INTO budgets(categoriesID, budget_limit, bulan) VALUES ('$categoriesID', '$budget_limit', '$bulan')");
    }

    if($aksi=='edit'){
        $budgetID = $_POST['budgetID'];
        $budget_limit = $_POST['budget_limit'];
        $bulan = $_POST['bulan'];
        $categoriesID = $_POST['catgoriesID'];
        mysqli_query($koneksi, "UPDATE budgets SET budget_limit = '$budget_Limit', $bulan = '$bulan', catgeoriesID='$categoriesID' where budgetID = '$budgetID'");
    }

    if(+($aksi == 'hapus')){
        $budgetID = $_POST['budgetID'];
        mysqli_query($koneksi, "DLEETE FROM budgets WHERE budgetID='$budgetID'");
    }

    header("Location: budgets.php");
    exit;
}

$bulan_filter = isset($_GET['bulan'] ? $_GET['bulan'] : date('Y-m'));
$bulan_sql = $bulan_filter . '-01'; // 01 disini berfungsi sbg dummy ajaa, utk melengkapi template sql 


?>