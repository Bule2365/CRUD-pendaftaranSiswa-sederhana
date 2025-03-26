<?php
include 'koneksi.php'; // Menghubungkan ke database
$base_url = "http://localhost:8080/pendaftaran_siswa/";

session_start();
if (!isset($_SESSION['login'])) {
    echo "<script>alert('Anda harus login terlebih dahulu!'); window.location='login.php';</script>";
    exit; // Pastikan script berhenti setelah redirect
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$sql = "SELECT * FROM siswa";
if ($search != "") {
    $sql .= " WHERE nama LIKE '%" . $conn->real_escape_string($search) . "%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Siswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<?php include 'navbar.php'; ?>

<body>
    <div class="container mt-4">
        <h2 class="text-center">Daftar Siswa</h2>
        <form method="GET" action="" class="mb-3">
            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama..."
                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        </form>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
                    <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['telepon']) ?></td>
                    <td>
                        <?php if (!empty($row['foto']) && file_exists($row['foto'])): ?>
                        <img src="<?= $row['foto'] ?>" alt="Foto Siswa" width="150"><br><br>
                        <?php else: ?>
                        <img src="uploads/default.jpg" alt="Foto Default" width="150"><br><br>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">Belum ada data siswa.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>