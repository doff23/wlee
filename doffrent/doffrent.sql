-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Jul 2025 pada 14.51
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `doffrent`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `mobil`
--

CREATE TABLE `mobil` (
  `id_mobil` int(11) NOT NULL,
  `merk` varchar(100) DEFAULT NULL,
  `tipe` varchar(50) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `no_polisi` varchar(20) DEFAULT NULL,
  `status` enum('Tersedia','Dipinjam') DEFAULT NULL,
  `harga_perhari` decimal(10,2) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mobil`
--

INSERT INTO `mobil` (`id_mobil`, `merk`, `tipe`, `tahun`, `no_polisi`, `status`, `harga_perhari`, `gambar`, `foto`) VALUES
(4, 'Toyota Alphard', NULL, NULL, 'D 4 FFA', 'Dipinjam', 1000000.00, NULL, '6850a062a4cce_pngwing.com (33).png'),
(5, 'Honda Brio', NULL, NULL, 'B 123 ABC', 'Tersedia', 300000.00, NULL, '6850a05a339fe_pngwing.com (34).png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `alamat`, `no_telepon`, `email`) VALUES
(10, 'Daffa Shadqi', 'JL. Pengadegan Selatan VIII', '085946048191', 'daffashadqi23@gmail.com'),
(12, 'Hartuti', 'JL. Bersama', '091388254018', 'hartuti14@gmail.com'),
(13, 'Zein Rizaldi', 'JL. Kuburan', '081299418160', 'zeinrizaldi18@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_mobil` int(11) DEFAULT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `total_harga` decimal(12,2) DEFAULT NULL,
  `status_transaksi` enum('Berlangsung','Selesai','Dibatalkan') DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `status_pembayaran` varchar(50) NOT NULL DEFAULT '`Belum Dibayar`',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_pelanggan`, `id_mobil`, `tanggal_pinjam`, `tanggal_kembali`, `total_harga`, `status_transaksi`, `bukti_pembayaran`, `status_pembayaran`, `created_at`) VALUES
(38, 2, 4, '2025-06-18', '2025-06-28', 10000000.00, 'Selesai', '6850a74b49f1f_foto 2.jpg', 'Lunas', '2025-06-17 06:22:21'),
(39, 2, 5, '2025-06-10', '2025-06-13', 900000.00, 'Selesai', '6850a80465cca_foto 2.jpg', 'Lunas', '2025-06-17 06:25:47'),
(40, 2, 4, '2025-06-25', '2025-06-28', 3000000.00, 'Selesai', '6850af43b771c_foto 2.jpg', 'Ditolak', '2025-06-17 06:50:56'),
(41, 2, 4, '2025-07-26', '2025-06-28', 1000000.00, 'Selesai', '685bae9c4000d_foto 1.jpg', 'Ditolak', '2025-06-25 15:06:54'),
(42, 2, 5, '2025-06-01', '2025-06-07', 1800000.00, 'Selesai', '685baea45c044_foto 1.jpg', 'Lunas', '2025-06-25 15:07:59'),
(43, 2, 4, '2025-06-01', '2025-06-07', 6000000.00, 'Selesai', NULL, 'Belum Dibayar', '2025-06-25 15:11:08'),
(44, 2, 4, '2025-07-01', '2025-07-05', 4000000.00, 'Selesai', '68602e3884318_WhatsApp Image 2025-06-29 at 01.02.10_3c79d754.jpg', 'Lunas', '2025-06-29 01:01:28'),
(45, 2, 4, '2025-07-01', '2025-07-05', 4000000.00, 'Berlangsung', '6860458c3394d_WhatsApp Image 2025-06-29 at 01.02.10_3c79d754.jpg', 'Menunggu Verifikasi', '2025-06-29 02:36:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `role` enum('admin','petugas','pelanggan') NOT NULL,
  `foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `no_hp`, `password`, `nama_lengkap`, `role`, `foto`) VALUES
(1, 'admin', NULL, NULL, '$2y$10$1iQL/MMt6uy657F/ZIGe0.z93GRwjssNIf6e4eYYMdwTFySMFjOIu', 'Admin DoffRent', 'admin', NULL),
(2, 'doff', 'daffashadqi23@gmail.com', NULL, '$2y$10$DAA7yr5ocNr6VM0PSbO1JeDdilQ9w6dw1cXKgzOGEZJ1ihx7RYMva', 'Daffa Shadqi', 'pelanggan', '685098d0a9901_foto 2.jpg'),
(11, 'tuti', 'hartuti14@gmail.com', NULL, '$2y$10$5jwcJFRkWenWcdTdsCKtIebYXNy38zWA1Z1RsSD.Zt5XGaWLC2UwG', 'Hartuti', 'pelanggan', NULL),
(12, 'zein', 'zeinrizaldi18@gmail.com', NULL, '$2y$10$scpeSrKY2klldbPut3vcjuMVU7.0jw32zBQGPVg4GOqw.oV1yQ4C6', 'Zein Rizaldi', 'pelanggan', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `mobil`
--
ALTER TABLE `mobil`
  ADD PRIMARY KEY (`id_mobil`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `mobil`
--
ALTER TABLE `mobil`
  MODIFY `id_mobil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
