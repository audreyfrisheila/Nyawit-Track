<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "nyawit-track";

    $koneksi = mysqli_connect($hostname, $username, $password, $database);
    if($koneksi -> connect_error){
        die("Koneksi Gagal!: " . $koneksi -> connect_error);
    }
?>