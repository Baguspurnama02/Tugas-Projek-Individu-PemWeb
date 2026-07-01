-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 24 Jun 2026 pada 09.42
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
-- Database: `furniturery_store`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
(1, 'Pencahayaan'),
(2, 'Sofa & Kursi'),
(3, 'Dekorasi'),
(4, 'Lemari'),
(5, 'Meja'),
(6, 'Kamar Tidur');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan_kontak`
--

CREATE TABLE `pesan_kontak` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pesan` text NOT NULL,
  `tanggal` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ditangani_oleh` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `dikelola_oleh` int(11) NOT NULL,
  `harga` int(15) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(100) NOT NULL,
  `material` varchar(100) NOT NULL,
  `dimensi` varchar(50) NOT NULL,
  `warna` varchar(50) NOT NULL,
  `stok` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `nama`, `kategori_id`, `dikelola_oleh`, `harga`, `deskripsi`, `gambar`, `material`, `dimensi`, `warna`, `stok`) VALUES
(1, 'Lampu Lantai Rotan Kayu Jati', 1, 1, 850000, 'Lampu lantai dengan kaki tripod berbahan kayu jati solid dan tudung kain berwarna krem yang lembut. Cocok untuk melengkapi ruang tamu, ruang baca, maupun kamar tidur.', 'produk-1.jpg', 'Kayu jati, kain linen', '40 x 40 x 150 cm', 'Natural, krem', 'Tersedia'),
(2, 'Kursi Lounge Modern', 2, 1, 2800000, 'Kursi lounge dengan desain modern, jok empuk berwarna abu-abu dan kaki besi minimalis. Nyaman untuk bersantai di ruang keluarga.', 'produk-2.jpg', 'Besi, kain boucle', '70 x 75 x 80 cm', 'Putih, abu-abu', 'Tersedia'),
(3, 'Jam Berdiri Wooden Klasik', 3, 1, 5950000, 'Jam berdiri klasik berbahan kayu solid dengan sentuhan warna tembaga, memberikan kesan elegan dan mewah pada ruangan.', 'produk-3.jpg', 'Kayu solid, logam', '30 x 20 x 120 cm', 'Cokelat tua', 'Tersedia'),
(4, 'Rak Buku Minimalis', 4, 1, 2150000, 'Rak buku dengan desain minimalis berbahan kayu, cocok untuk menyimpan buku dan pajangan dekorasi.', 'produk-4.jpg', 'Kayu MDF', '80 x 30 x 180 cm', 'Cokelat natural', 'Tersedia'),
(5, 'Sofa Minimalis', 2, 1, 5250000, 'Sofa minimalis dengan bahan kain nyaman, cocok untuk ruang tamu modern.', 'kategori-sofa.jpg', 'Kayu, kain katun', '180 x 85 x 80 cm', 'Abu-abu', 'Tersedia'),
(6, 'Meja Kayu Jati Klasik', 5, 1, 3800000, 'Meja makan berbahan kayu jati solid dengan desain klasik dan tahan lama.', 'kategori-meja.jpg', 'Kayu jati solid', '150 x 90 x 75 cm', 'Cokelat natural', 'Tersedia'),
(7, 'Lemari Pakaian Wooden Klasik', 4, 1, 4950000, 'Lemari pakaian 3 pintu berbahan kayu dengan ruang simpan yang luas.', 'kategori-lemari.jpg', 'Kayu solid', '120 x 60 x 200 cm', 'Cokelat kayu', 'Tersedia'),
(8, 'Set Kamar Tidur Modern', 6, 1, 16200000, 'Satu set perlengkapan kamar tidur modern lengkap.', 'kategori-kamar.jpg', 'Kayu, kain katun', 'Bervariasi per item', 'Putih, cokelat natural', 'Tersedia');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesan_kontak`
--
ALTER TABLE `pesan_kontak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ditangani_oleh` (`ditangani_oleh`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`),
  ADD KEY `dikelola_oleh` (`dikelola_oleh`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pesan_kontak`
--
ALTER TABLE `pesan_kontak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pesan_kontak`
--
ALTER TABLE `pesan_kontak`
  ADD CONSTRAINT `pesan_kontak_ibfk_1` FOREIGN KEY (`ditangani_oleh`) REFERENCES `admin` (`id`);

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`),
  ADD CONSTRAINT `produk_ibfk_2` FOREIGN KEY (`dikelola_oleh`) REFERENCES `admin` (`id`);
-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang`
--

CREATE TABLE `keranjang` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_penerima` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `metode_bayar` varchar(50) NOT NULL,
  `catatan` text DEFAULT NULL,
  `total` int(15) NOT NULL,
  `status` varchar(50) NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan_detail`
--

CREATE TABLE `pesanan_detail` (
  `id` int(11) NOT NULL,
  `pesanan_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `harga` int(15) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Indeks untuk tabel yang ditambahkan
--

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pesanan_detail`
  ADD PRIMARY KEY (`id`);


--
-- AUTO_INCREMENT untuk tabel yang ditambahkan
--

ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pesanan_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
