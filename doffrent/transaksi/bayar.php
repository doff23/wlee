<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$trx = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT t.*, m.merk, m.no_polisi 
    FROM transaksi t 
    JOIN mobil m ON t.id_mobil = m.id_mobil 
    WHERE t.id_transaksi = $id
"));

if (!$trx) {
    echo "<p style='color:red;'>Transaksi tidak ditemukan.</p>";
    exit;
}

$expired_time = date('Y-m-d H:i:s', strtotime($trx['created_at'] . ' +1 hour'));
$status = $trx['status_pembayaran'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($status != 'Belum Dibayar') {
        echo "<script>alert('Transaksi ini tidak bisa dibayar.');</script>";
    } else {
        $file = $_FILES['bukti']['name'];
        $tmp  = $_FILES['bukti']['tmp_name'];
        $ext  = pathinfo($file, PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

        if (!in_array(strtolower($ext), $allowed)) {
            echo "<script>alert('Format file tidak valid!');</script>";
        } else {
            $filename = uniqid() . "_" . $file;
            if (!is_dir("../uploads")) mkdir("../uploads");
            move_uploaded_file($tmp, "../uploads/" . $filename);

            mysqli_query($conn, "UPDATE transaksi SET 
                bukti_pembayaran = '$filename',
                status_pembayaran = 'Menunggu Verifikasi'
                WHERE id_transaksi = $id");

            echo "<script>alert('Bukti pembayaran berhasil dikirim!'); location.href='riwayat_user.php';</script>";
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Sewa Mobil</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            padding: 20px;
        }

        .card-box {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .info-payment p {
            margin: 6px 0;
            font-size: 15px;
        }

        .countdown {
            font-size: 15px;
            font-weight: 600;
            color: #e67e22;
            margin-top: 10px;
        }

        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-top: 10px;
        }

        button[type="submit"] {
            margin-top: 15px;
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
        }

        button[type="submit"]:hover {
            background-color: #27ae60;
        }

        .btn-back {
            background-color: #3498db;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            display: inline-block;
            text-decoration: none;
            margin-top: 20px;
        }

        .btn-back:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
<div class="container" style="max-width: 600px;">
    <h2><i class="fas fa-money-check-alt"></i> Pembayaran Sewa Mobil</h2>

    <div class="card-box info-payment">
        <h5 class="mb-3">Transfer Pembayaran Ke:</h5>
        <p><strong><i class="fas fa-university"></i> Bank:</strong> BCA</p>
        <p><strong><i class="fas fa-credit-card"></i> No. Rekening:</strong> 1280752017</p>
        <p><strong><i class="fas fa-user"></i> Atas Nama:</strong> Daffa Shadqi</p>
        <hr>
        <p><strong><i class="fas fa-car"></i> Mobil:</strong> <?= $trx['merk'] ?> - <?= $trx['no_polisi'] ?></p>
        <p><strong><i class="fas fa-wallet"></i> Total:</strong> Rp <?= number_format($trx['total_harga']) ?></p>
        <p id="timer" class="countdown">⏳ Menghitung waktu...</p>
        <small class="text-muted">Silakan lakukan transfer dalam waktu 1 jam dan unggah bukti pembayaran di bawah.</small>
    </div>

    <div class="card-box" id="form-pembayaran">
        <form method="post" enctype="multipart/form-data">
            <label><strong>Upload Bukti Pembayaran (jpg/png/pdf):</strong></label>
            <input type="file" name="bukti" required>
            <button type="submit"><i class="fas fa-upload"></i> Kirim Bukti</button>
        </form>
    </div>

    <a href="riwayat_user.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<script>
function startCountdown(expiredTime) {
    const countdown = document.getElementById("timer");
    const formBox = document.getElementById("form-pembayaran");

    function update() {
        const now = new Date().getTime();
        const target = new Date(expiredTime.replace(' ', 'T')).getTime();
        const distance = target - now;

        if (distance <= 0) {
            countdown.innerHTML = "<span style='color:red;'>⛔ Waktu pembayaran habis!</span>";
            if (formBox) formBox.style.display = "none";
        } else {
            const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((distance % (1000 * 60)) / 1000);
            countdown.innerHTML = `⏳ Sisa waktu bayar: <strong>${m}m ${s}s</strong>`;
            setTimeout(update, 1000);
        }
    }

    update();
}

window.onload = function() {
    startCountdown("<?= $expired_time ?>");
};
</script>

</body>
</html>