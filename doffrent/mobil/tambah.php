<?php
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $merk = $_POST['merk'];
    $nopol = $_POST['no_polisi'];
    $harga = $_POST['harga_perhari'];

    $file = $_FILES['foto']['name'];
    $tmp  = $_FILES['foto']['tmp_name'];
    $folder = "../uploads/";
    $filename = uniqid() . "_" . $file;

    if (!is_dir($folder)) mkdir($folder);
    move_uploaded_file($tmp, $folder . $filename);

    mysqli_query($conn, "INSERT INTO mobil (merk, no_polisi, harga_perhari, status, foto) 
    VALUES ('$merk', '$nopol', '$harga', 'Tersedia', '$filename')");

    echo "<script>alert('Mobil berhasil ditambahkan!'); location.href='index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Mobil - DoffRent</title>
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
            background-color: #3498db;
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
            background-color: #2980b9;
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
        <h2><i class="fas fa-plus-circle"></i> Tambah Mobil</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="merk">Merk Mobil:</label>
            <input type="text" name="merk" id="merk" required>

            <label for="no_polisi">Nomor Polisi:</label>
            <input type="text" name="no_polisi" id="no_polisi" required>

            <label for="harga_perhari">Harga per Hari (Rp):</label>
            <input type="number" name="harga_perhari" id="harga_perhari" required>

            <label for="foto">Foto Mobil:</label>
            <input type="file" name="foto" id="foto" accept=".jpg,.jpeg,.png" required>

            <button type="submit"><i class="fas fa-save"></i> Simpan Mobil</button>
        </form>

        <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Data Mobil</a>
    </div>

</body>
</html>