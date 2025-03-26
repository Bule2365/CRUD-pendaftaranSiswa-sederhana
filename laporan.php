<?php
require('fpdf/fpdf.php'); // Pastikan file ini ada
include 'koneksi.php';

// Cek apakah file FPDF tersedia sebelum diload
if (!file_exists('fpdf/fpdf.php')) {
    die('Error: File FPDF tidak ditemukan! Pastikan sudah diunggah dengan benar.');
}


// Buat instance PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'Laporan Data Siswa', 0, 1, 'C');
$pdf->Ln(5);

// Header tabel
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(10, 10, 'No', 1);
$pdf->Cell(50, 10, 'Nama', 1);
$pdf->Cell(50, 10, 'Email', 1);
$pdf->Cell(30, 10, 'Telepon', 1);
$pdf->Cell(50, 10, 'Jenis Kelamin', 1);
$pdf->Ln();

// Isi tabel
$pdf->SetFont('Arial', '', 12);
$sql = "SELECT * FROM siswa";
$result = $conn->query($sql);
$no = 1;

while ($row = $result->fetch_assoc()) {
    $pdf->Cell(10, 10, $no++, 1);
    $pdf->Cell(50, 10, $row['nama'], 1);
    $pdf->Cell(50, 10, $row['email'], 1);
    $pdf->Cell(30, 10, $row['telepon'], 1);
    $pdf->Cell(50, 10, $row['jenis_kelamin'], 1);
    $pdf->Ln();
}

// Output PDF
$pdf->Output();
?>

<?php if ($_SESSION['role'] == 'admin'): ?>
<a href="download.php" class="btn btn-success">Download PDF</a>
<?php endif; ?>