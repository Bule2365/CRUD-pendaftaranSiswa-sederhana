<?php
session_start();

function checkAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        echo "<script>alert('Akses ditolak! Hanya admin yang bisa mengakses halaman ini.'); window.location='index.php';</script>";
        exit;
    }
}
?>