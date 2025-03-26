<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'koneksi.php';

$mail = new PHPMailer(true); // Inisialisasi PHPMailer sekali

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Validasi Foto
    $target_dir = "uploads/";
    $foto = basename($_FILES["foto"]["name"]);
    $target_file = $target_dir . time() . "_" . $foto;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
        echo "<script>alert('Hanya file JPG, JPEG, dan PNG yang diperbolehkan!'); window.history.back();</script>";
        exit;
    }

    if ($_FILES["foto"]["size"] > 2000000) { // Batas ukuran 2MB
        echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB.'); window.history.back();</script>";
        exit;
    }

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        $foto = $target_file;
    } else {
        $foto = "uploads/default.jpg";
    }

    // Mencegah SQL Injection
    $nama = $conn->real_escape_string($nama);
    $alamat = $conn->real_escape_string($alamat);
    $email = $conn->real_escape_string($email);
    $telepon = $conn->real_escape_string($telepon);
    $foto = $conn->real_escape_string($foto);

    // Perbaikan Query
    $sql = "INSERT INTO siswa (nama, alamat, tanggal_lahir, jenis_kelamin, email, telepon, foto) 
            VALUES ('$nama', '$alamat', '$tanggal_lahir', '$jenis_kelamin', '$email', '$telepon', '$foto')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Pendaftaran berhasil!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    // Kirim Email Konfirmasi
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'emailkamu@gmail.com';
        $mail->Password = 'passwordemail';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('emailkamu@gmail.com', 'Admin Sekolah');
        $mail->addAddress($email, $nama);
        $mail->Subject = 'Pendaftaran Sukses';
        $mail->Body = "Halo $nama, pendaftaran Anda berhasil!";
        
        $mail->send();
    } catch (Exception $e) {
        echo "Email gagal dikirim. Error: {$mail->ErrorInfo}";
    }
}
?>