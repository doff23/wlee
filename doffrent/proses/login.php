<?php
session_start();
include '../config/koneksi.php';

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
$data = mysqli_fetch_assoc($query);

// TANPA HASH untuk testing lokal
if ($data && password_verify($password, $data['password'])) {
    $_SESSION['login'] = true;
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];
    $_SESSION['id_user'] = $data['id_user']; // penting!

    // Redirect semua role ke dashboard
    header("Location: ../dashboard.php");
    exit;
} else {
    echo "<script>alert('Username atau password salah!'); location.href='../login.php';</script>";
}