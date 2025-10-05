<?php
include '../config/koneksi.php';
$q = mysqli_query($conn, "SELECT * FROM mobil ORDER BY id_mobil DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Mobil - DoffRent</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            padding: 40px;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #dee2e6;
            text-align: center;
        }

        table th {
            background-color: #f1f1f1;
        }

        img {
            width: 100px;
            border-radius: 6px;
        }

        .actions a {
            text-decoration: none;
            padding: 6px 12px;
            margin: 0 4px;
            border-radius: 4px;
            color: white;
            font-size: 14px;
        }

        .actions .edit {
            background-color: #f39c12;
        }

        .actions .delete {
            background-color: #e74c3c;
        }

        .actions .edit:hover {
            background-color: #d68910;
        }

        .actions .delete:hover {
            background-color: #c0392b;
        }

        .back-btn {
            margin-top: 30px;
            display: inline-block;
            background-color: #7f8c8d;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
        }

        .back-btn:hover {
            background-color: #636e72;
        }
    </style>
</head>
<body>

    <h2><i class="fas fa-car"></i> Data Mobil</h2>

    <a href="tambah.php" class="btn"><i class="fas fa-plus"></i> Tambah Mobil</a>

    <table>
        <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Merk</th>
            <th>No Polisi</th>
            <th>Harga</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($q)) { ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><img src="../uploads/<?= $row['foto'] ?>"></td>
            <td><?= $row['merk'] ?></td>
            <td><?= $row['no_polisi'] ?></td>
            <td>Rp <?= number_format($row['harga_perhari']) ?></td>
            <td><?= ucfirst($row['status']) ?></td>
            <td class="actions">
                <a href="edit.php?id=<?= $row['id_mobil'] ?>" class="edit"><i class="fas fa-pen"></i> Edit</a>
                <a href="hapus.php?id=<?= $row['id_mobil'] ?>" class="delete" onclick="return confirm('Yakin ingin menghapus mobil ini?')"><i class="fas fa-trash"></i> Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <a href="../dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>

</body>
</html>