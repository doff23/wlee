<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['login']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
    header("Location: ../login.php");
    exit;
}

$users = mysqli_query($conn, "SELECT * FROM users WHERE role = 'pelanggan'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Pelanggan - DoffRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body style="background-color: #f9f9f9; font-family: 'Segoe UI', sans-serif;">

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📋 Data Pelanggan</h2>
        <a href="tambah.php" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i>Tambah Pelanggan
        </a>
    </div>

    <div class="table-responsive shadow-sm rounded bg-white p-3">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($users)) { ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= $row['nama_lengkap'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td class="text-center">
                        <?php if ($row['foto']) { ?>
                            <img src="../uploads/<?= $row['foto'] ?>" width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                        <?php } else { echo '-'; } ?>
                    </td>
                    <td class="text-center">
                        <a href="edit.php?id=<?= $row['id_user'] ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <a href="../dashboard.php" class="btn btn-secondary mt-4">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

</body>
</html>