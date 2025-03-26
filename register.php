<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validasi username
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        echo "<script>alert('Username hanya boleh mengandung huruf, angka, dan underscore (3-20 karakter)'); window.history.back();</script>";
        exit;
    }

    // Validasi kekuatan password
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        echo "<script>alert('Password minimal 8 karakter, mengandung huruf besar, kecil, angka, dan simbol!'); window.history.back();</script>";
        exit;
    }

    // Cek konfirmasi password
    if ($password !== $confirm_password) {
        echo "<script>alert('Konfirmasi password tidak cocok!'); window.history.back();</script>";
        exit;
    }

    // Cek CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "<script>alert('Permintaan tidak valid!'); window.history.back();</script>";
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Gunakan prepared statement
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .register-container {
            max-width: 500px;
            width: 100%;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-header h2 {
            color: #0d6efd;
            font-weight: 600;
        }

        .form-floating {
            margin-bottom: 1rem;
        }

        .btn-register {
            width: 100%;
            padding: 0.75rem;
            font-size: 1.1rem;
        }

        .register-footer {
            text-align: center;
            margin-top: 1.5rem;
        }

        .register-brand {
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

        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .password-requirements {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        .requirement-item {
            position: relative;
            padding-left: 20px;
            margin-bottom: 0.2rem;
        }

        .requirement-item::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #dee2e6;
        }

        .requirement-item.valid::before {
            background-color: #198754;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="register-container">
                    <div class="register-header">
                        <div class="register-brand">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <h2>Buat Akun Baru</h2>
                        <p class="text-muted">Silakan isi formulir di bawah ini</p>
                    </div>

                    <form method="POST" action="" id="registerForm">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                        <div class="form-floating mb-3">
                            <input type="text" name="username" class="form-control" id="username" placeholder="Username"
                                required>
                            <label for="username"><i class="bi bi-person me-2"></i>Username</label>
                            <div class="form-text">Username hanya boleh mengandung huruf, angka, dan underscore (3-20
                                karakter)</div>
                        </div>

                        <div class="form-floating mb-2 position-relative">
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Password" required>
                            <label for="password"><i class="bi bi-key me-2"></i>Password</label>
                            <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                        </div>

                        <div class="password-strength bg-secondary opacity-25 mb-2"></div>

                        <div class="password-requirements mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="requirement-item" id="req-length">Minimal 8 karakter</div>
                                    <div class="requirement-item" id="req-uppercase">Minimal 1 huruf besar</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="requirement-item" id="req-lowercase">Minimal 1 huruf kecil</div>
                                    <div class="requirement-item" id="req-number">Minimal 1 angka</div>
                                </div>
                                <div class="col-12">
                                    <div class="requirement-item" id="req-special">Minimal 1 karakter khusus (@$!%*?&)
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-4 position-relative">
                            <input type="password" name="confirm_password" class="form-control" id="confirm_password"
                                placeholder="Konfirmasi Password" required>
                            <label for="confirm_password"><i class="bi bi-key-fill me-2"></i>Konfirmasi Password</label>
                            <i class="bi bi-eye-slash toggle-password" id="toggleConfirmPassword"></i>
                        </div>

                        <button type="submit" class="btn btn-primary btn-register" id="submitBtn"
                            disabled>Daftar</button>
                    </form>

                    <div class="register-footer">
                        <p>Sudah punya akun? <a href="login.php" class="text-primary">Login di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(toggle => {
            toggle.addEventListener('click', function () {
                const passwordInput = this.previousElementSibling;
                const toggleIcon = this;

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('bi-eye-slash');
                    toggleIcon.classList.add('bi-eye');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('bi-eye');
                    toggleIcon.classList.add('bi-eye-slash');
                }
            });
        });

        // Password strength validation
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm_password');
        const strengthBar = document.querySelector('.password-strength');
        const submitBtn = document.getElementById('submitBtn');
        const usernameInput = document.getElementById('username');

        // Password requirements
        const reqLength = document.getElementById('req-length');
        const reqUppercase = document.getElementById('req-uppercase');
        const reqLowercase = document.getElementById('req-lowercase');
        const reqNumber = document.getElementById('req-number');
        const reqSpecial = document.getElementById('req-special');

        // Username validation
        usernameInput.addEventListener('input', function () {
            const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
            if (usernameRegex.test(this.value)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
            validateForm();
        });

        // Password validation
        passwordInput.addEventListener('input', function () {
            const password = this.value;
            let strength = 0;

            // Length validation
            if (password.length >= 8) {
                reqLength.classList.add('valid');
                strength += 1;
            } else {
                reqLength.classList.remove('valid');
            }

            // Uppercase validation
            if (/[A-Z]/.test(password)) {
                reqUppercase.classList.add('valid');
                strength += 1;
            } else {
                reqUppercase.classList.remove('valid');
            }

            // Lowercase validation
            if (/[a-z]/.test(password)) {
                reqLowercase.classList.add('valid');
                strength += 1;
            } else {
                reqLowercase.classList.remove('valid');
            }

            // Number validation
            if (/[0-9]/.test(password)) {
                reqNumber.classList.add('valid');
                strength += 1;
            } else {
                reqNumber.classList.remove('valid');
            }

            // Special character validation
            if (/[@$!%*?&]/.test(password)) {
                reqSpecial.classList.add('valid');
                strength += 1;
            } else {
                reqSpecial.classList.remove('valid');
            }

            // Update strength bar
            strengthBar.style.width = '100%';

            if (strength === 0) {
                strengthBar.className = 'password-strength bg-secondary opacity-25';
            } else if (strength < 3) {
                strengthBar.className = 'password-strength bg-danger';
            } else if (strength < 5) {
                strengthBar.className = 'password-strength bg-warning';
            } else {
                strengthBar.className = 'password-strength bg-success';
            }

            validateForm();
        });

        // Confirm password validation
        confirmInput.addEventListener('input', function () {
            if (this.value === passwordInput.value) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
            validateForm();
        });

        // Form validation
        function validateForm() {
            const username = usernameInput.value;
            const password = passwordInput.value;
            const confirmPassword = confirmInput.value;

            const usernameValid = /^[a-zA-Z0-9_]{3,20}$/.test(username);
            const passwordValid = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(password);
            const confirmValid = password === confirmPassword;

            if (usernameValid && passwordValid && confirmValid && password.length > 0) {
                submitBtn.removeAttribute('disabled');
            } else {
                submitBtn.setAttribute('disabled', 'disabled');
            }
        }

        // Form submission animation
        document.getElementById('registerForm').addEventListener('submit', function () {
            submitBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mendaftar...';
            submitBtn.disabled = true;
        });
    </script>
</body>

</html>