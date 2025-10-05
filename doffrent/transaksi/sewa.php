<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['login']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
    header("Location: ../login.php");
    exit;
}

$mobil = mysqli_query($conn, "SELECT * FROM mobil WHERE status = 'Tersedia'");
$pelanggan = mysqli_query($conn, "SELECT * FROM users WHERE role = 'pelanggan'");

if (isset($_POST['submit'])) {
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_mobil = $_POST['id_mobil'];
    $tgl_pinjam = $_POST['tanggal_pinjam'];
    $tgl_kembali = $_POST['tanggal_kembali'];

    $durasi = (strtotime($tgl_kembali) - strtotime($tgl_pinjam)) / 86400;
    if ($durasi <= 0) $durasi = 1;

    $data_mobil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mobil WHERE id_mobil = '$id_mobil'"));
    $total = $durasi * $data_mobil['harga_perhari'];

    mysqli_query($conn, "INSERT INTO transaksi (
        id_pelanggan, id_mobil, tanggal_pinjam, tanggal_kembali, total_harga, status_transaksi, status_pembayaran, created_at
    ) VALUES (
        '$id_pelanggan', '$id_mobil', '$tgl_pinjam', '$tgl_kembali', '$total', 'Berlangsung', 'Belum Dibayar', NOW()
    )");

    mysqli_query($conn, "UPDATE mobil SET status = 'Dipinjam' WHERE id_mobil = '$id_mobil'");

    echo "<script>alert('Transaksi berhasil ditambahkan!'); location.href='sewa.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Sewa - DoffRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body style="background-color:#f8f9fa">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4><i class="fas fa-car me-2"></i>Input Transaksi Sewa</h4>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Pilih Pelanggan</label>
                    <select name="id_pelanggan" class="form-select" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        <?php while ($p = mysqli_fetch_assoc($pelanggan)) { ?>
                            <option value="<?= $p['id_user'] ?>"><?= $p['nama_lengkap'] ?> (<?= $p['username'] ?>)</option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pilih Mobil</label>
                    <select name="id_mobil" class="form-select" required>
                        <option value="">-- Pilih Mobil --</option>
                        <?php while ($m = mysqli_fetch_assoc($mobil)) { ?>
                            <option value="<?= $m['id_mobil'] ?>"><?= $m['merk'] ?> - <?= $m['no_polisi'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" class="form-control" required>
                </div>

                <button type="submit" name="submit" class="btn btn-success">
                    <i class="fas fa-check-circle me-1"></i> Proses Booking
                </button>
                <a href="../dashboard.php" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </form>
        </div>
    </div>
</div>
</body>
</html>