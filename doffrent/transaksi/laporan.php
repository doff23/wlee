<?php
session_start();
include '../config/koneksi.php';

$tgl_awal = $_GET['tgl_awal'] ?? '';
$tgl_akhir = $_GET['tgl_akhir'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi - DoffRent</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Laporan Transaksi</h2>
    <form method="get">
        <label>Dari:</label>
        <input type="date" name="tgl_awal" value="<?= $tgl_awal ?>">
        <label>Sampai:</label>
        <input type="date" name="tgl_akhir" value="<?= $tgl_akhir ?>">
        <button type="submit">Tampilkan</button>
    </form>

    <table border="1" cellpadding="10" cellspacing="0" style="margin-top:20px; width:100%;">
        <tr>
            <th>No</th>
            <th>Pelanggan</th>
            <th>Mobil</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Total Harga</th>
            <th>Status</th>
        </tr>

        <?php
        $no = 1;
        $sql = "SELECT t.*, p.nama, m.merk 
                FROM transaksi t
                JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                JOIN mobil m ON t.id_mobil = m.id_mobil";

        if ($tgl_awal && $tgl_akhir) {
            $sql .= " WHERE t.tanggal_pinjam BETWEEN '$tgl_awal' AND '$tgl_akhir'";
        }

        $sql .= " ORDER BY t.tanggal_pinjam DESC";
        $q = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($q)) {
            echo "<tr>
                <td>{$no}</td>
                <td>{$row['nama']}</td>
                <td>{$row['merk']}</td>
                <td>{$row['tanggal_pinjam']}</td>
                <td>{$row['tanggal_kembali']}</td>
                <td>Rp " . number_format($row['total_harga']) . "</td>
                <td>{$row['status_transaksi']}</td>
            </tr>";
            $no++;
        }
        ?>
    </table>
    <br>
    <a href="../dashboard.php" class="btn">Kembali ke Dashboard</a>
</div>
</body>
</html>