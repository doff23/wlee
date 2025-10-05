<?php 
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_user = $id"));

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    if ($_FILES['foto']['name'] != '') {
        $foto = uniqid() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/' . $foto);

        if ($user['foto']) {
            unlink('../uploads/' . $user['foto']);
        }

        $query = "UPDATE users SET nama_lengkap='$nama', email='$email', username='$username', role='$role', foto='$foto' WHERE id_user=$id";
    } else {
        $query = "UPDATE users SET nama_lengkap='$nama', email='$email', username='$username', role='$role' WHERE id_user=$id";
    }

    mysqli_query($conn, $query);

    if (!empty($_POST['password_baru']) && !empty($_POST['konfirmasi_password'])) {
        $password_baru = $_POST['password_baru'];
        $konfirmasi_password = $_POST['konfirmasi_password'];

        if ($password_baru === $konfirmasi_password) {
            $hash = password_hash($password_baru, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password='$hash' WHERE id_user=$id");
            echo "<script>alert('Password berhasil diperbarui');</script>";
        } else {
            echo "<script>alert('Konfirmasi password tidak sesuai');</script>";
        }
    }

    echo "<script>alert('Data pelanggan berhasil diperbarui'); location.href='index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pelanggan - DoffRent</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f7f7;
            padding: 40px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        label {
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select,
        input[type="file"] {
            width: 100%;
            padding: 10px 12px;
            margin: 6px 0 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background-color: #27ae60;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #1e8449;
        }

        img {
            margin: 10px 0;
            border-radius: 8px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            text-decoration: none;
            color: #2c3e50;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        h3 {
            color: #444;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-user-edit"></i> Edit Pelanggan</h2>

    <form method="post" enctype="multipart/form-data">
        <label>Nama Lengkap:</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

        <label>Role:</label>
        <select name="role">
            <option value="pelanggan" <?= $user['role'] == 'pelanggan' ? 'selected' : '' ?>>Pelanggan</option>
            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="petugas" <?= $user['role'] == 'petugas' ? 'selected' : '' ?>>Petugas</option>
        </select>

        <label>Ganti Foto (opsional):</label>
        <?php if ($user['foto']) { ?>
            <img src="../uploads/<?= $user['foto'] ?>" width="120">
        <?php } ?>
        <input type="file" name="foto" accept=".jpg,.jpeg,.png">

        <h3><i class="fas fa-lock"></i> Ganti Password (Opsional)</h3>
        <label>Password Baru:</label>
        <input type="password" name="password_baru">

        <label>Konfirmasi Password Baru:</label>
        <input type="password" name="konfirmasi_password">

        <button type="submit" name="submit"><i class="fas fa-save"></i> Simpan</button>
    </form>

    <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Data Pelanggan</a>
</div>

</body>
</html>