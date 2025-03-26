<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<?php include 'navbar.php'; ?>

<body>
    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar -->
            <?php if ($_SESSION['role'] == 'admin'): ?>
            <div class="col-md-3">
                <div class="list-group">
                    <a href="index.php" class="list-group-item list-group-item-action">Dashboard</a>
                    <a href="tambah.php" class="list-group-item list-group-item-action">Tambah Siswa</a>
                    <a href="laporan.php" class="list-group-item list-group-item-action">Laporan</a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Konten -->
            <div class="<?php echo ($_SESSION['role'] == 'admin') ? 'col-md-9' : 'col-md-12'; ?>">
                <h3>Selamat datang, <?= $_SESSION['username']; ?>!</h3>
                <p>Ini adalah halaman dashboard.</p>
            </div>
        </div>
    </div>
</body>

</html>