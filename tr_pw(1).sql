-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Jan 2025 pada 14.40
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tr_pw`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `nama_pemesan` varchar(100) NOT NULL,
  `tanggal_checkin` date NOT NULL,
  `tanggal_checkout` date NOT NULL,
  `status` enum('pending','confirmed','canceled','verified') DEFAULT 'pending',
  `total_harga` decimal(10,2) NOT NULL,
  `jumlah_kamar` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bookings`
--

INSERT INTO `bookings` (`id`, `room_id`, `nama_pemesan`, `tanggal_checkin`, `tanggal_checkout`, `status`, `total_harga`, `jumlah_kamar`) VALUES
(29, 24, 'eeee', '2024-11-23', '2024-11-24', 'confirmed', 975000.00, 1),
(30, 24, 'tteess', '2024-11-23', '2024-11-25', 'verified', 1950000.00, 1),
(31, 10, 'ooooo', '2024-11-23', '2024-11-24', 'canceled', 1500000.00, 1),
(33, 10, 'Hendi', '2024-12-02', '2024-12-03', 'confirmed', 1500000.00, 1),
(34, 25, 'testing 2', '2024-12-02', '2024-12-03', 'confirmed', 250000.00, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `tanggal_pembayaran` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `metode_pembayaran`, `tanggal_pembayaran`) VALUES
(15, 29, 'dana', '2024-11-23 15:19:26'),
(16, 33, 'dana', '2024-12-02 03:24:57'),
(17, 34, 'dana', '2024-12-02 03:26:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `nama_promosi` varchar(255) NOT NULL,
  `tipe_diskon` enum('persentase','nominal') NOT NULL,
  `jumlah_diskon` decimal(10,2) NOT NULL,
  `kamar_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `promotions`
--

INSERT INTO `promotions` (`id`, `nama_promosi`, `tipe_diskon`, `jumlah_diskon`, `kamar_id`) VALUES
(3, 'testing', 'persentase', 35.00, 24);

-- --------------------------------------------------------

--
-- Struktur dari tabel `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `nomor_kamar` varchar(10) NOT NULL,
  `tipe_kamar` varchar(50) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `fasilitas` text DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rooms`
--

INSERT INTO `rooms` (`id`, `nomor_kamar`, `tipe_kamar`, `harga`, `fasilitas`, `stok`) VALUES
(10, 'AC-1', 'Standard', 1500000.00, 'WiFi, TV, AC, Breakfast', 2),
(12, 'AC-2', 'Suite', 20000.00, 'WiFi, AC', 3),
(13, 'CA-41', 'Standard', 250000.00, 'WiFi, AC', 1),
(16, 'TC1', 'Deluxe', 200000.00, 'WiFi, TV, Breakfast', 1),
(24, 'TC911', 'Suite', 1500000.00, 'WiFi, TV, AC, Breakfast', 148),
(25, 'hari ini', 'Standard', 1000.00, 'WiFi, TV, AC, Breakfast', 10);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indeks untuk tabel `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kamar_id` (`kamar_id`);

--
-- Indeks untuk tabel `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `promotions`
--
ALTER TABLE `promotions`
  ADD CONSTRAINT `promotions_ibfk_1` FOREIGN KEY (`kamar_id`) REFERENCES `rooms` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
