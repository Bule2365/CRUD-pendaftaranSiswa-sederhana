<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Gunakan prepared statement untuk menghindari SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah username ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            echo "<script>alert('Login berhasil!'); window.location='index.php';</script>";
            exit;
        } else {
            echo "<script>alert('Password salah!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!'); window.history.back();</script>";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            max-width: 450px;
            width: 100%;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h2 {
            color: #0d6efd;
            font-weight: 600;
        }

        .form-floating {
            margin-bottom: 1rem;
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem;
            font-size: 1.1rem;
        }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
        }

        .login-brand {
            font-size: 2rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }

        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="login-container">
                    <div class="login-header">
                        <div class="login-brand">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h2>Masuk</h2>
                        <p class="text-muted">Silakan masukkan kredensial Anda</p>
                    </div>

                    <form method="POST" action="">
                        <div class="form-floating mb-3">
                            <input type="text" name="username" class="form-control" id="username" placeholder="Username"
                                required>
                            <label for="username"><i class="bi bi-person me-2"></i>Username</label>
                        </div>

                        <div class="form-floating mb-3 position-relative">
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Password" required>
                            <label for="password"><i class="bi bi-key me-2"></i>Password</label>
                            <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">Ingat saya</label>
                            </div>
                            <a href="#" class="text-primary">Lupa password?</a>
                        </div>

                        <button type="submit" class="btn btn-primary btn-login">Masuk</button>
                    </form>

                    <div class="login-footer">
                        <p>Belum punya akun? <a href="register.php" class="text-primary">Daftar di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function () {
            const password = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePassword');

            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                password.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        });
    </script>
</body>

</html>