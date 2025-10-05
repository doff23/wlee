<?php
session_start();
include '../config/koneksi.php';

// Hanya untuk admin atau petugas
if (!isset($_SESSION['login']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
    header("Location: ../login.php");
    exit;
}

// Ambil ID dan status dari URL
$id = $_GET['id'] ?? 0;
$status = $_GET['status'] ?? '';

$valid = ['Dibayar', 'Ditolak'];

if (!in_array($status, $valid)) {
    echo "<script>alert('Status tidak valid!'); history.back();</script>";
    exit;
}

// Update status pembayaran
mysqli_query($conn, "UPDATE transaksi SET status_pembayaran = '$status' WHERE id_transaksi = $id");

echo "<script>alert('Status pembayaran berhasil diperbarui menjadi $status.'); location.href='riwayat.php';</script>";