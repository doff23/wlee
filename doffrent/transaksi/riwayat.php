<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['login']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
    header("Location: ../login.php");
    exit;
}

// PROSES Aksi:
if (isset($_GET['verifikasi'])) {
    $id = $_GET['verifikasi'];
    mysqli_query($conn, "UPDATE transaksi SET status_pembayaran = 'Lunas' WHERE id_transaksi = $id");
    header("Location: riwayat.php");
    exit;
}
if (isset($_GET['tolak'])) {
    $id = $_GET['tolak'];
    mysqli_query($conn, "UPDATE transaksi SET status_pembayaran = 'Ditolak' WHERE id_transaksi = $id");
    header("Location: riwayat.php");
    exit;
}
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi = $id");
    header("Location: riwayat.php");
    exit;
}
if (isset($_GET['kembali'])) {
    $id = $_GET['kembali'];
    $get = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_mobil FROM transaksi WHERE id_transaksi = $id"));
    $id_mobil = $get['id_mobil'];
    mysqli_query($conn, "UPDATE mobil SET status = 'Tersedia' WHERE id_mobil = $id_mobil");
    mysqli_query($conn, "UPDATE transaksi SET status_transaksi = 'Selesai' WHERE id_transaksi = $id");
    header("Location: riwayat.php");
    exit;
}

$query = mysqli_query($conn, "
    SELECT t.*, m.merk, m.no_polisi, u.username, u.nama_lengkap 
    FROM transaksi t 
    JOIN mobil m ON t.id_mobil = m.id_mobil 
    JOIN users u ON t.id_pelanggan = u.id_user 
    ORDER BY t.id_transaksi DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Transaksi</h5>
            <a href="../dashboard.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-secondary">
                    <tr>
                        <th>No</th>
                        <th>Pelanggan</th>
                        <th>Mobil</th>
                        <th>Plat</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Total</th>
                        <th>Status Bayar</th>
                        <th>Status Transaksi</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nama_lengkap'] ?><br><small class="text-muted">(<?= $row['username'] ?>)</small></td>
                        <td><?= $row['merk'] ?></td>
                        <td><?= $row['no_polisi'] ?></td>
                        <td><?= $row['tanggal_pinjam'] ?></td>
                        <td><?= $row['tanggal_kembali'] ?></td>
                        <td>Rp <?= number_format($row['total_harga']) ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                $row['status_pembayaran'] == 'Lunas' ? 'success' : (
                                $row['status_pembayaran'] == 'Ditolak' ? 'danger' : (
                                $row['status_pembayaran'] == 'Menunggu Verifikasi' ? 'warning text-dark' : 'secondary')) ?>">
                                <?= $row['status_pembayaran'] ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-<?= 
                                $row['status_transaksi'] == 'Selesai' ? 'success' : (
                                $row['status_transaksi'] == 'Berlangsung' ? 'info' : 'secondary') ?>">
                                <?= $row['status_transaksi'] ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($row['bukti_pembayaran']) { ?>
                                <a href="../uploads/<?= $row['bukti_pembayaran'] ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> Lihat</a>
                            <?php } else { echo "-"; } ?>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap justify-content-center gap-1">
                                <?php if ($row['status_pembayaran'] == 'Menunggu Verifikasi') { ?>
                                    <a href="?verifikasi=<?= $row['id_transaksi'] ?>" class="btn btn-success btn-sm" onclick="return confirm('Verifikasi pembayaran ini?')">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="?tolak=<?= $row['id_transaksi'] ?>" class="btn btn-warning btn-sm" onclick="return confirm('Tolak transaksi ini?')">
                                        <i class="fas fa-ban"></i>
                                    </a>
                                <?php } ?>
                                <?php if ($row['status_transaksi'] == 'Berlangsung') { ?>
                                    <a href="?kembali=<?= $row['id_transaksi'] ?>" class="btn btn-info btn-sm" onclick="return confirm('Kembalikan mobil ini?')">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                <?php } ?>
                                <a href="cetak_invoice.php?id=<?= $row['id_transaksi'] ?>" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                <a href="?hapus=<?= $row['id_transaksi'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus transaksi ini?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>