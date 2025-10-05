<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = $_POST['nama'];
    $alamat   = $_POST['alamat'];
    $telp     = $_POST['no_telepon'];
    $email    = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role     = 'pelanggan';

    $simpan_pelanggan = mysqli_query($conn, "INSERT INTO pelanggan (nama, alamat, no_telepon, email)
        VALUES ('$nama', '$alamat', '$telp', '$email')");

    $simpan_user = mysqli_query($conn, "INSERT INTO users (username, password, nama_lengkap, role)
        VALUES ('$username', '$password', '$nama', '$role')");

    if ($simpan_pelanggan && $simpan_user) {
        echo "<script>alert('Data pelanggan dan akun berhasil ditambahkan!'); location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!'); history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pelanggan - DoffRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif;">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4><i class="fas fa-user-plus me-2"></i>Tambah Pelanggan & Akun Login</h4>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="alamat" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Telepon</label>
                    <input type="text" name="no_telepon" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <hr>

                <h5 class="mb-3"><i class="fas fa-user-lock me-2"></i>Akun Login</h5>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>Simpan
                </button>
                <a href="index.php" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </form>
        </div>
    </div>
</div>
</body>
</html>