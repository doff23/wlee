<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'pelanggan') {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'"));

if (isset($_POST['submit'])) {
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];
    $no_hp    = $_POST['no_hp'];

    if ($_FILES['foto']['name'] != '') {
        $foto = uniqid() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto);
        if ($user['foto'] != '') unlink('uploads/' . $user['foto']);
        mysqli_query($conn, "UPDATE users SET nama_lengkap='$nama', email='$email', username='$no_hp', foto='$foto' WHERE id_user='$id_user'");
    } else {
        mysqli_query($conn, "UPDATE users SET nama_lengkap='$nama', email='$email', username='$no_hp' WHERE id_user='$id_user'");
    }

    echo "<script>alert('Profil berhasil diperbarui'); location.href='profil_user.php';</script>";
}

if (isset($_POST['ganti_password'])) {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    $cek = mysqli_query($conn, "SELECT password FROM users WHERE id_user = '$id_user'");
    $passData = mysqli_fetch_assoc($cek);

    if (password_verify($password_lama, $passData['password'])) {
        if ($password_baru === $konfirmasi_password) {
            $hash = password_hash($password_baru, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password='$hash' WHERE id_user='$id_user'");
            echo "<script>alert('Password berhasil diubah'); location.href='profil_user.php';</script>";
        } else {
            echo "<script>alert('Konfirmasi password baru tidak cocok');</script>";
        }
    } else {
        echo "<script>alert('Password lama salah');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil Pengguna - DoffRent</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .form-label {
            font-weight: bold;
        }
        .profile-img {
            max-width: 100px;
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="text-center mb-4">👤 Profil Pengguna</h2>
    <div class="row g-4">

        <!-- Form Edit Profil -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Edit Profil</h5>
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" value="<?= $user['nama_lengkap'] ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="<?= $user['email'] ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username / No HP</label>
                            <input type="text" name="no_hp" value="<?= $user['username'] ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Profil</label><br>
                            <?php if ($user['foto']) { ?>
                                <img src="uploads/<?= $user['foto'] ?>" class="profile-img mb-2"><br>
                            <?php } ?>
                            <input type="file" name="foto" accept="image/*" class="form-control">
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary w-100"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Form Ganti Password -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Ganti Password</h5>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="password_lama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password_baru" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="konfirmasi_password" class="form-control" required>
                        </div>
                        <button type="submit" name="ganti_password" class="btn btn-warning w-100"><i class="fa-solid fa-key"></i> Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>
</div>

</body>
</html>