<?php
include '../config/koneksi.php';

$id = $_GET['id'] ?? 0;
$mobil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mobil WHERE id_mobil = $id"));

if ($mobil) {
    if (!empty($mobil['foto']) && file_exists("../uploads/" . $mobil['foto'])) {
        unlink("../uploads/" . $mobil['foto']);
    }

    mysqli_query($conn, "DELETE FROM mobil WHERE id_mobil = $id");
}

echo "<script>alert('Data mobil berhasil dihapus.'); location.href='index.php';</script>";
exit;