<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

$id_pelanggan = $_SESSION['id_user'];

$query = mysqli_query($conn, "
    SELECT t.*, m.merk, m.no_polisi 
    FROM transaksi t 
    JOIN mobil m ON t.id_mobil = m.id_mobil 
    WHERE t.id_pelanggan = '$id_pelanggan' 
    ORDER BY t.id_transaksi DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Sewa Saya</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
        }
        .table-status {
            font-weight: bold;
            text-transform: capitalize;
        }
        .status-belum {
            color: #e74c3c;
        }
        .status-verifikasi {
            color: #f39c12;
        }
        .status-ditolak {
            color: #c0392b;
        }
        .status-lunas {
            color: #27ae60;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center mb-4"><i class="fas fa-clock-rotate-left"></i> Riwayat Sewa Mobil</h2>

    <div class="table-responsive">
    <table class="table table-bordered table-hover table-striped align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>No</th>
                <th>Mobil</th>
                <th>Plat</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Total</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($query)) {
            $status = $row['status_pembayaran'];
            $status_trans = $row['status_transaksi'];
            $keterangan = '';

            if ($status == 'Belum Dibayar') {
                $keterangan = 'Menunggu pembayaran oleh Anda';
            } elseif ($status == 'Menunggu Verifikasi') {
                $keterangan = 'Menunggu verifikasi admin';
            } elseif ($status == 'Ditolak') {
                $keterangan = 'Pembayaran Anda ditolak';
            } elseif ($status == 'Lunas') {
                if ($status_trans == 'Berlangsung') {
                    $keterangan = 'Sewa aktif, mobil sedang digunakan';
                } elseif ($status_trans == 'Selesai') {
                    $keterangan = 'Transaksi selesai ✔';
                } else {
                    $keterangan = 'Pembayaran diterima';
                }
            }

            // Warna status
            $statusClass = match ($status) {
                'Belum Dibayar' => 'status-belum',
                'Menunggu Verifikasi' => 'status-verifikasi',
                'Ditolak' => 'status-ditolak',
                'Lunas' => 'status-lunas',
                default => '',
            };

            echo "<tr class='text-center'>";
            echo "<td>{$no}</td>";
            echo "<td>{$row['merk']}</td>";
            echo "<td>{$row['no_polisi']}</td>";
            echo "<td>{$row['tanggal_pinjam']}</td>";
            echo "<td>{$row['tanggal_kembali']}</td>";
            echo "<td>Rp " . number_format($row['total_harga']) . "</td>";
            echo "<td class='table-status {$statusClass}'>{$status}</td>";
            echo "<td>{$keterangan}</td>";
            echo "<td>";
            if ($status == 'Belum Dibayar') {
                echo "<a href='bayar.php?id={$row['id_transaksi']}' class='btn btn-sm btn-success'><i class='fas fa-wallet'></i> Bayar</a>";
            } else {
                echo "<span class='text-muted'>-</span>";
            }
            echo "</td></tr>";
            $no++;
        }
        ?>
        </tbody>
    </table>
    </div>

    <a href="../dashboard.php" class="btn btn-primary mt-3"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

</body>
</html>