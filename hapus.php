<?php
include 'koneksi.php';

include 'middleware.php';
checkAdmin(); // Hanya admin yang bisa mengakses halaman ini

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM siswa WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "<script>if(confirm('Apakah Anda yakin ingin menghapus data ini?')) { window.location='proses_hapus.php?id=".$id."';} else {window.history.back();} </script>";
}
?>