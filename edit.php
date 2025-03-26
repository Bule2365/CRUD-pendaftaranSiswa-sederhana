<?php
include 'koneksi.php';

include 'middleware.php';
checkAdmin();

$id = $_GET['id'];
$id = $conn->real_escape_string($id);

$sql = "SELECT * FROM siswa WHERE id = '$id'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
} else {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<?php include 'navbar.php'; ?>

<body>
    <div class="container mt-4">
        <h2 class="text-center">Edit Siswa</h2>
        <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="<?= $row['nama'] ?>" required>
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" required><?= $row['alamat'] ?></textarea>
            </div>
            <div class="mb-3">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="<?= $row['tanggal_lahir'] ?>"
                    required>
            </div>
            <div class="mb-3">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control">
                    <option value="Laki-laki" <?= $row['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki
                    </option>
                    <option value="Perempuan" <?= $row['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan
                    </option>
                </select>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= $row['email'] ?>" required>
            </div>
            <div class="mb-3">
                <label>Telepon</label>
                <input type="text" name="telepon" class="form-control" value="<?= $row['telepon'] ?>" required>
            </div>
            <div class="mb-3">
                <label>Foto Saat Ini</label><br>
                <?php if (!empty($row['foto']) && file_exists($row['foto'])): ?>
                <img src="<?= $row['foto'] ?>" alt="Foto Siswa" width="150"><br><br>
                <?php else: ?>
                <img src="uploads/default.jpg" alt="Foto Default" width="150"><br><br>
                <?php endif; ?>
                <label>Ganti Foto</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>