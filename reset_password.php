<?php
include 'koneksi.php'; // Pastikan koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);

    // Cek apakah username ada dalam database
    $check_sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // Buat password baru (8 karakter random)
        $new_password = substr(md5(time()), 0, 8);
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password di database
        $update_sql = "UPDATE users SET password='$hashed_password' WHERE username='$username'";
        if ($conn->query($update_sql) === TRUE) {
            echo "<script>alert('Password berhasil direset! Password baru Anda: $new_password');</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat mereset password.');</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center">Reset Password</h2>
        <form method="POST" class="mt-3">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username Anda" required>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
        <p class="mt-3">Kembali ke <a href="login.php">Login</a></p>
    </div>
</body>

</html>