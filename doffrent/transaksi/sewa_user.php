<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

$id_pelanggan = $_SESSION['id_user'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_mobil = $_POST['id_mobil'];
    $tgl_pinjam = $_POST['tanggal_pinjam'];
    $tgl_kembali = $_POST['tanggal_kembali'];

    $durasi = (strtotime($tgl_kembali) - strtotime($tgl_pinjam)) / 86400;
    if ($durasi <= 0) $durasi = 1;

    $mobil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mobil WHERE id_mobil = $id_mobil"));
    $total = $durasi * $mobil['harga_perhari'];

    mysqli_query($conn, "INSERT INTO transaksi 
        (id_pelanggan, id_mobil, tanggal_pinjam, tanggal_kembali, total_harga, status_transaksi, status_pembayaran, created_at) 
        VALUES 
        ('$id_pelanggan', '$id_mobil', '$tgl_pinjam', '$tgl_kembali', '$total', 'Berlangsung', 'Belum Dibayar', NOW())");

    $id_transaksi = mysqli_insert_id($conn);

    mysqli_query($conn, "UPDATE mobil SET status = 'Dipinjam' WHERE id_mobil = $id_mobil");

    echo "<script>alert('Booking berhasil! Silakan lakukan pembayaran.'); location.href='bayar.php?id=$id_transaksi';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sewa Mobil - DoffRent</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f4f6f9;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 960px;
        margin: auto;
    }

    h2 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 30px;
    }

    .card {
        display: flex;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.07);
        margin-bottom: 20px;
        overflow: hidden;
        align-items: center;
    }

    .card img {
        width: 200px;
        height: auto;
        object-fit: cover;
        border-right: 1px solid #eee;
    }

    .card-body {
        padding: 20px;
        flex: 1;
    }

    .card-body h3 {
        margin: 0 0 10px 0;
        color: #34495e;
    }

    .card-body p {
        margin: 5px 0;
        font-size: 14px;
    }

    form {
        margin-top: 10px;
    }

    input[type="date"] {
        padding: 8px;
        margin: 6px 0;
        border: 1px solid #ccc;
        border-radius: 6px;
        width: 100%;
        font-size: 14px;
    }

    button[type="submit"] {
        background-color: #2ecc71;
        color: white;
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        margin-top: 10px;
    }

    button[type="submit"]:hover {
        background-color: #27ae60;
    }

    .btn-back {
        display: inline-block;
        margin-top: 30px;
        background-color: #3498db;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 6px;
    }

    .btn-back:hover {
        background-color: #2980b9;
    }

    @media (max-width: 768px) {
        .card {
            flex-direction: column;
            align-items: start;
        }

        .card img {
            width: 100%;
            height: auto;
            border-right: none;
            border-bottom: 1px solid #eee;
        }
    }
</style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-car"></i> Pilih Mobil untuk Disewa</h2>

    <?php
    $mob = mysqli_query($conn, "SELECT * FROM mobil WHERE status = 'Tersedia'");
    while ($m = mysqli_fetch_assoc($mob)) {
    ?>
    <div class="card">
        <img src="../uploads/<?= $m['foto'] ?>" alt="Mobil">
        <div class="card-body">
            <h3><?= $m['merk'] ?> - <?= $m['no_polisi'] ?></h3>
            <p><strong>Harga:</strong> Rp <?= number_format($m['harga_perhari']) ?> / hari</p>

            <form method="post">
                <input type="hidden" name="id_mobil" value="<?= $m['id_mobil'] ?>">
                <label>Tanggal Pinjam:</label>
                <input type="date" name="tanggal_pinjam" required>

                <label>Tanggal Kembali:</label>
                <input type="date" name="tanggal_kembali" required>

                <button type="submit"><i class="fas fa-handshake"></i> Sewa Mobil Ini</button>
            </form>
        </div>
    </div>
    <?php } ?>

    <a href="../dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
</div>

</body>
</html>