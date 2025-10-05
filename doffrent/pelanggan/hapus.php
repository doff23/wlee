<?php
include '../config/koneksi.php';

$id = $_GET['id'];
$hapus = mysqli_query($conn, "DELETE FROM pelanggan WHERE id_pelanggan=$id");

if ($hapus) {
    header("Location: index.php");
} else {
    echo "Gagal menghapus data";
}