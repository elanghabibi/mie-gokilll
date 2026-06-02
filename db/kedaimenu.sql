-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 02, 2026 at 07:02 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kedaimenu`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `harga` int NOT NULL,
  `kategori` enum('makanan','minuman','cemilan') NOT NULL,
  `deskripsi` text,
  `foto` varchar(255) NOT NULL,
  `tersedia` tinyint(1) NOT NULL DEFAULT '1',
  `terlaris` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `harga`, `kategori`, `deskripsi`, `foto`, `tersedia`, `terlaris`, `created_at`) VALUES
(1, 'Mie Gokil Original', 12000, 'makanan', 'Mie polos dengan bumbu rahasia yang gurihnya bikin geleng-geleng kepala. Level aman tanpa cabai.', '', 1, 0, '2026-05-30 08:16:52'),
(2, 'Mie Gokil Level Coreng', 14000, 'makanan', 'Level 1. Pedas tipis-tipis, pas buat kamu yang baru mau coba nakal.', '1780328159_a99ca1355d867dee19fb4f379c6af02d.jpg', 1, 1, '2026-05-30 08:16:52'),
(3, 'Mie Gokil Level Tonjok', 15000, 'makanan', 'Level 3. Pedasnya mulai mukul lidah, bikin keringat dingin mulai netes!', '', 1, 0, '2026-05-30 08:16:52'),
(4, 'Mie Gokil Level K.O.', 17000, 'makanan', 'Level 5. Jangan coba-coba kalau belum bikin surat wasiat. Pedasnya bikin telinga keluar asap!', '', 1, 0, '2026-05-30 08:16:52'),
(5, 'Mie Nyemek Keju Brutal', 20000, 'makanan', 'Mie dengan kuah kental sedikit, disiram saus keju lumer yang melimpah ruah.', '', 1, 1, '2026-05-30 08:16:52'),
(6, 'Mie Goreng Hitam Putih', 19000, 'makanan', 'Unik! Mienya berwarna hitam (dari tinta cumi gurih) disajikan di atas piring putih. Pas sama tema kedai!', '', 1, 1, '2026-05-30 08:16:52'),
(7, 'Mie Kuah Menggelegar', 16000, 'makanan', 'Mie kuah kaldu sapi pedas yang disajikan panas-panas. Cocok dimakan pas hujan badai.', '', 1, 0, '2026-05-30 08:16:52'),
(8, 'Mie Ayam Jamur Sakti', 18000, 'makanan', 'Mie dengan topping potongan ayam dan jamur melimpah yang rasanya magis banget.', '', 1, 0, '2026-05-30 08:16:52'),
(9, 'Mie Ramen Kare Gokilll', 22000, 'makanan', 'Adaptasi mie jepang dengan kearifan lokal. Kuah karenya kental dan rempahnya kuat.', '', 1, 0, '2026-05-30 08:16:52'),
(10, 'Mie Yamin Manis Manja', 14000, 'makanan', 'Buat yang benci pedas tapi cinta manis. Kecapnya meresap sampai ke sanubari mienya.', '', 1, 0, '2026-05-30 08:16:52'),
(11, 'Mie Carbonara Drama', 23000, 'makanan', 'Perpaduan mie lokal dengan saus creamy susu dan potongan sosis. Mewah tapi murah.', '', 1, 0, '2026-05-30 08:16:52'),
(12, 'Mie Ijo Selalu Optimis', 15000, 'makanan', 'Mie sehat warna hijau dari sari sawi murni, tapi bumbunya tetap micin gokil!', '', 1, 0, '2026-05-30 08:16:52'),
(13, 'Es Teh Manis Santai', 5000, 'minuman', 'Penyelamat semua umat manusia selepas kepedasan. Manisnya pas, gak lebay.', '', 1, 1, '2026-05-30 08:16:52'),
(14, 'Es Susu Pemadam Kebakaran', 10000, 'minuman', 'Suku putih murni dingin. Wajib dipesan kalau kamu nekat makan Mie Level K.O.', '', 1, 1, '2026-05-30 08:16:52'),
(15, 'Es Jeruk Asam Kehidupan', 7000, 'minuman', 'Jeruk peras asli yang asam manisnya menyegarkan jiwa yang merana.', '', 1, 0, '2026-05-30 08:16:52'),
(16, 'Soda Gembira Ria', 12000, 'minuman', 'Campuran sirup merah, susu kental manis, dan soda yang bikin hati riang gembira.', '', 1, 0, '2026-05-30 08:16:52'),
(17, 'Es Kopi Hitam Pekat Komik', 8000, 'minuman', 'Kopi robusta hitam pekat tanpa ampun, sehitam garis border neubrutalism.', '', 1, 0, '2026-05-30 08:16:52'),
(18, 'Es Milo Dinosaur', 13000, 'minuman', 'Es milo kental yang di atasnya ditaburi bubuk milo segunung sampai tumpah-tumpah.', '', 1, 0, '2026-05-30 08:16:52'),
(19, 'Teh Tarik Ulur Hubungan', 9000, 'minuman', 'Teh susu yang ditarik berulang kali sampai berbusa. Kayak hubungan kamu yang digantung.', '', 0, 0, '2026-05-30 08:16:52'),
(20, 'Es Lemon Tea Segar Bugat', 8000, 'minuman', 'Kombinasi teh dan perasan lemon yang bikin mata melek seketika.', '', 1, 0, '2026-05-30 08:16:52'),
(21, 'Air Mineral Pasrah', 4000, 'minuman', 'Air putih biasa dalam kemasan, buat kamu yang uang saku-nya mepet.', '', 1, 0, '2026-05-30 08:16:52'),
(22, 'Es Sirup Merah Membara', 6000, 'minuman', 'Sirup cocopandan merah darah berlimpah es batu. Manis dan dingin pol.', '', 1, 0, '2026-05-30 08:16:52'),
(23, 'Pangsit Goreng Kriuk', 8000, 'cemilan', 'Isi 4 biji. Pangsit isi ayam yang digoreng garing, kalau digigit bunyinya: KRIUK!', '', 1, 1, '2026-05-30 08:16:52'),
(24, 'Siomay Kukus Pasrah', 10000, 'cemilan', 'Siomay ayam lembut yang dikukus hangat, disajikan dengan saus sambal cocol.', '', 1, 0, '2026-05-30 08:16:52'),
(25, 'Ceker Setan Melotot', 12000, 'cemilan', 'Ceker ayam bumbu balado merah membara yang empuknya sampai lepas dari tulang.', '', 1, 1, '2026-05-30 08:16:52'),
(26, 'Kentang Doodle Goreng', 10000, 'cemilan', 'Kentang goreng potongan lurus, ditaburi bumbu jagung bakar gurih.', '', 1, 0, '2026-05-30 08:16:52'),
(27, 'Tahu Bakso Jedag-Jedug', 9000, 'cemilan', 'Tahu isi adonan bakso sapi yang digoreng tepung renyah.', '', 1, 0, '2026-05-30 08:16:52'),
(28, 'Dimsum Ayam Bahagia', 12000, 'cemilan', 'Isi 3 biji. Dimsum premium berukuran jumbo yang bikin perut bahagia.', '', 1, 0, '2026-05-30 08:16:52'),
(29, 'Tempe Mendoan Raksasa', 7000, 'cemilan', 'Isi 2 lembar tempe lebar yang digoreng setengah matang dengan daun bawang melimpah.', '', 0, 0, '2026-05-30 08:16:52'),
(30, 'Kulit Ayam Krispi Krisis', 11000, 'cemilan', 'Kulit ayam goreng tepung super renyah. Sekali coba gak bakal bisa berhenti ngunyah.', '', 1, 0, '2026-05-30 08:16:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$QxuCXMmMX7RK2Qzjp7zHEe4YpprNpQXq54bOJtgofEWMatEnrrQAi'),
(2, 'jokowi', '$2y$10$K32y/nLpyCFWnG9QeM2zvuqA4/DZUzalDKUncuNVcyQqkU4buCQve');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
