<?php
include '../config/koneksi.php';

$id = $_GET['id'];
$mobil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mobil WHERE id_mobil = $id"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $merk  = $_POST['merk'];
    $nopol = $_POST['no_polisi'];
    $harga = $_POST['harga_perhari'];

    if (!empty($_FILES['foto']['name'])) {
        if (!empty($mobil['foto']) && file_exists("../uploads/" . $mobil['foto'])) {
            unlink("../uploads/" . $mobil['foto']);
        }

        $file = $_FILES['foto']['name'];
        $tmp  = $_FILES['foto']['tmp_name'];
        $filename = uniqid() . "_" . $file;
        move_uploaded_file($tmp, "../uploads/" . $filename);

        mysqli_query($conn, "UPDATE mobil SET 
            merk = '$merk', 
            no_polisi = '$nopol', 
            harga_perhari = '$harga', 
            foto = '$filename' 
            WHERE id_mobil = $id");
    } else {
        mysqli_query($conn, "UPDATE mobil SET 
            merk = '$merk', 
            no_polisi = '$nopol', 
            harga_perhari = '$harga' 
            WHERE id_mobil = $id");
    }

    echo "<script>alert('Data mobil berhasil diperbarui.'); location.href='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Mobil - DoffRent</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            padding: 40px;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            margin-top: 14px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            background-color: #27ae60;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #219150;
        }

        .img-preview {
            display: block;
            margin-top: 10px;
            border-radius: 6px;
        }

        .back-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #555;
        }

        .back-btn:hover {
            color: #000;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2><i class="fas fa-edit"></i> Edit Mobil</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="merk">Merk Mobil:</label>
            <input type="text" name="merk" id="merk" value="<?= $mobil['merk'] ?>" required>

            <label for="no_polisi">Nomor Polisi:</label>
            <input type="text" name="no_polisi" id="no_polisi" value="<?= $mobil['no_polisi'] ?>" required>

            <label for="harga_perhari">Harga per Hari (Rp):</label>
            <input type="number" name="harga_perhari" id="harga_perhari" value="<?= $mobil['harga_perhari'] ?>" required>

            <label for="foto">Foto Mobil (Opsional):</label>
            <input type="file" name="foto" id="foto" accept=".jpg,.jpeg,.png">
            <?php if ($mobil['foto']) { ?>
                <img class="img-preview" src="../uploads/<?= $mobil['foto'] ?>" width="180" alt="Foto Mobil">
            <?php } ?>

            <button type="submit"><i class="fas fa-save"></i> Update Mobil</button>
        </form>

        <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Data Mobil</a>
    </div>

</body>
</html>