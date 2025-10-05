<?php
session_start();

// Jika sudah login, arahkan sesuai role
if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'petugas') {
        header("Location: dashboard.php");
        exit;
    } elseif ($_SESSION['role'] == 'pelanggan') {
        header("Location: transaksi/sewa_user.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>DoffRent - Sistem Informasi Rental Mobil</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container" style="max-width: 600px; margin: auto; padding: 40px; text-align: center;">
        <h2>DoffRent - Sistem Informasi Rental Mobil</h2>
        <p>Silakan login atau daftar untuk mulai menggunakan sistem.</p>
        <br>
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn">Daftar Sebagai Pelanggan</a>
    </div>
</body>
</html>