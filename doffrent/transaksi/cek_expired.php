<?php
include '../config/koneksi.php';

// Ambil semua transaksi yang belum dibayar dan belum dibatalkan
$q = mysqli_query($conn, "
    SELECT * FROM transaksi 
    WHERE status_pembayaran = 'Belum Dibayar' 
    AND status_transaksi = 'Berlangsung'
");

$now = date('Y-m-d H:i:s');

while ($row = mysqli_fetch_assoc($q)) {
    $created_at = $row['created_at'];
    $id_transaksi = $row['id_transaksi'];
    $id_mobil = $row['id_mobil'];

    // Hitung selisih waktu dalam detik
    $selisih = strtotime($now) - strtotime($created_at);

    if ($selisih >= 3600) { // 1 jam = 3600 detik
        // Update transaksi menjadi dibatalkan
        mysqli_query($conn, "
            UPDATE transaksi 
            SET status_transaksi = 'Dibatalkan', status_pembayaran = 'Kadaluarsa' 
            WHERE id_transaksi = $id_transaksi
        ");

        // Kembalikan status mobil jadi 'Tersedia'
        mysqli_query($conn, "
            UPDATE mobil 
            SET status = 'Tersedia' 
            WHERE id_mobil = $id_mobil
        ");
    }
}

echo "Cek expired selesai.";