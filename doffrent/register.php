<?php
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = $_POST['nama'];
    $alamat   = $_POST['alamat'];
    $no_hp    = $_POST['no_hp'];
    $email    = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Simpan ke tabel pelanggan
    $pel = mysqli_query($conn, "INSERT INTO pelanggan (nama, alamat, no_telepon, email)
        VALUES ('$nama', '$alamat', '$no_hp', '$email')");

    if ($pel) {
        // Simpan ke tabel users
        mysqli_query($conn, "INSERT INTO users (username, email, password, nama_lengkap, role)
            VALUES ('$username', '$email', '$password', '$nama', 'pelanggan')");

        echo "<script>alert('Registrasi berhasil! Silakan login'); location.href='login.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal!'); history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi - DoffRent</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Tambahkan Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ecf0f1;
        }

        .register-box {
            background-color: white;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
        }

        .register-box h2 {
            margin-bottom: 25px;
            text-align: center;
            color: #2c3e50;
        }

        .register-box input[type="text"],
        .register-box input[type="email"],
        .register-box input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        .register-box button {
            width: 100%;
            padding: 12px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .register-box button:hover {
            background-color: #34495e;
        }

        .register-box p {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .register-box a {
            color: #2980b9;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2><i class="fas fa-user-plus"></i> Registrasi Pelanggan</h2>

    <form method="post">
        <input type="text" name="nama" placeholder="Nama Lengkap" required>
        <input type="text" name="alamat" placeholder="Alamat" required>
        <input type="text" name="no_hp" placeholder="No HP" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit"><i class="fas fa-user-plus"></i> Daftar</button>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>

</body>
</html>