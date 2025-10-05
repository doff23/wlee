<?php
include '../config/koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID transaksi tidak ditemukan.";
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($conn, "
    SELECT t.*, m.merk, m.no_polisi, u.nama_lengkap, u.username 
    FROM transaksi t
    JOIN mobil m ON t.id_mobil = m.id_mobil
    JOIN users u ON t.id_pelanggan = u.id_user
    WHERE t.id_transaksi = '$id'
");

$data = mysqli_fetch_assoc($query);
if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: auto;
            padding: 20px;
        }
        h2 { text-align: center; }
        .info { margin-bottom: 20px; }
        .info table { width: 100%; }
        .info td { padding: 5px 0; }
        .total { font-size: 20px; font-weight: bold; text-align: right; }
        .print-btn {
            display: none;
        }
        @media print {
            .print-btn { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <h2>INVOICE SEWA MOBIL</h2>

    <div class="info">
        <table>
            <tr><td><strong>No. Transaksi</strong></td><td>: <?= $data['id_transaksi'] ?></td></tr>
            <tr><td><strong>Nama Pelanggan</strong></td><td>: <?= $data['nama_lengkap'] ?> (<?= $data['username'] ?>)</td></tr>
            <tr><td><strong>Mobil</strong></td><td>: <?= $data['merk'] ?> (<?= $data['no_polisi'] ?>)</td></tr>
            <tr><td><strong>Tanggal Pinjam</strong></td><td>: <?= $data['tanggal_pinjam'] ?></td></tr>
            <tr><td><strong>Tanggal Kembali</strong></td><td>: <?= $data['tanggal_kembali'] ?></td></tr>
            <tr><td><strong>Status Transaksi</strong></td><td>: <?= $data['status_transaksi'] ?></td></tr>
            <tr><td><strong>Status Pembayaran</strong></td><td>: <?= $data['status_pembayaran'] ?></td></tr>
        </table>
    </div>

    <p class="total">Total Bayar: Rp <?= number_format($data['total_harga']) ?></p>

    <hr>
    <p>Terima kasih telah menggunakan layanan rental mobil DoffRent.</p>
    <br>
    <p style="text-align:right;">Petugas, <br><br><br>_</p>

</body>
</html>