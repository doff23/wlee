<?php
include '../config/koneksi.php';

$id = $_GET['id'];

$trx = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi = $id"));
$id_mobil = $trx['id_mobil'];

mysqli_query($conn, "UPDATE transaksi SET status_transaksi = 'Selesai' WHERE id_transaksi = $id");
mysqli_query($conn, "UPDATE mobil SET status = 'Tersedia' WHERE id_mobil = $id_mobil");

echo "<script>alert('Mobil berhasil dikembalikan!'); location.href='riwayat.php';</script>";