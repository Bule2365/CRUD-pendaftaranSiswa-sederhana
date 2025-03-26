<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = trim($_POST['nama']);
    $alamat = trim($_POST['alamat']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);

    // Validasi Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Format email tidak valid!'); window.history.back();</script>";
        exit;
    }

    // Validasi Nomor Telepon (harus angka)
    if (!preg_match("/^[0-9]{10,15}$/", $telepon)) {
        echo "<script>alert('Nomor telepon harus 10-15 digit angka!'); window.history.back();</script>";
        exit;
    }

    // Ambil foto lama untuk dicek
    $result = $conn->query("SELECT foto FROM siswa WHERE id = '$id'");
    $row = $result->fetch_assoc();
    $fotoLama = $row['foto'];

    // Cek apakah ada file foto yang diunggah
    if (!empty($_FILES["foto"]["name"])) {
        $target_dir = "uploads/";
        $foto = basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . time() . "_" . $foto;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi ekstensi file
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
            echo "<script>alert('Hanya file JPG, JPEG, dan PNG yang diperbolehkan!'); window.history.back();</script>";
            exit;
        }

        // Validasi ukuran file (maksimal 2MB)
        if ($_FILES["foto"]["size"] > 2000000) {
            echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB.'); window.history.back();</script>";
            exit;
        }

        // Upload file baru
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            // Hapus foto lama jika bukan default
            if ($fotoLama != 'uploads/default.jpg' && file_exists($fotoLama)) {
                unlink($fotoLama);
            }
            $fotoBaru = $target_file;
        } else {
            echo "<script>alert('Gagal mengunggah foto!'); window.history.back();</script>";
            exit;
        }
    } else {
        $fotoBaru = $fotoLama; // Tetap gunakan foto lama jika tidak ada yang diunggah
    }

    // Mencegah SQL Injection
    $id = $conn->real_escape_string($id);
    $nama = $conn->real_escape_string($nama);
    $alamat = $conn->real_escape_string($alamat);
    $email = $conn->real_escape_string($email);
    $telepon = $conn->real_escape_string($telepon);

    // Update Query
    $sql = "UPDATE siswa SET 
            nama = '$nama', 
            alamat = '$alamat', 
            tanggal_lahir = '$tanggal_lahir', 
            jenis_kelamin = '$jenis_kelamin', 
            email = '$email', 
            telepon = '$telepon',
            foto = '$fotoBaru'
            WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>