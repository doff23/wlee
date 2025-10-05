<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - DoffRent</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #ecf0f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .dashboard-container {
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;
            max-width: 600px;
        }

        h2 {
            margin-top: 0;
            color: #2c3e50;
        }

        .badge {
            background-color: #3498db;
            padding: 5px 12px;
            border-radius: 12px;
            color: white;
            font-size: 12px;
        }

        .menu {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .menu a {
            display: inline-block;
            text-decoration: none;
            color: white;
            background-color: #2c3e50;
            padding: 12px;
            border-radius: 8px;
            transition: background-color 0.3s;
            font-weight: bold;
        }

        .menu a:hover {
            background-color: #34495e;
        }

        .logout {
            margin-top: 30px;
            display: inline-block;
            text-decoration: none;
            color: white;
            background-color: #e74c3c;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
        }

        .logout:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2>Selamat Datang, <?= ucfirst($username) ?>!</h2>
    <p>Anda login sebagai <span class="badge"><?= ucfirst($role) ?></span></p>

    <div class="menu">
        <?php if ($role == 'admin' || $role == 'petugas'): ?>
            <a href="mobil/index.php"><i class="fas fa-car"></i> Kelola Mobil</a>
            <a href="pelanggan/index.php"><i class="fas fa-users"></i> Kelola Pelanggan</a>
            <a href="transaksi/sewa.php"><i class="fas fa-handshake"></i> Transaksi Sewa</a>
            <a href="transaksi/riwayat.php"><i class="fas fa-history"></i> Riwayat Transaksi</a>
        <?php elseif ($role == 'pelanggan'): ?>
            <a href="transaksi/sewa_user.php"><i class="fas fa-car"></i> Sewa Mobil</a>
            <a href="transaksi/riwayat_user.php"><i class="fas fa-clock-rotate-left"></i> Riwayat Sewa</a>
            <a href="profil_user.php"><i class="fas fa-user"></i> Profil Saya</a>
        <?php endif; ?>
    </div>

    <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

</body>
</html>