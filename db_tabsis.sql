-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2025 at 02:33 PM
-- Server version: 8.0.42
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_tabsis`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_infaq_kesanggupan`
--

CREATE TABLE `tb_infaq_kesanggupan` (
  `id_infaq_kesanggupan` int NOT NULL,
  `nis` char(12) NOT NULL,
  `infaq_kesanggupan` int NOT NULL,
  `tgl` date NOT NULL,
  `petugas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_infaq_kesanggupan`
--

INSERT INTO `tb_infaq_kesanggupan` (`id_infaq_kesanggupan`, `nis`, `infaq_kesanggupan`, `tgl`, `petugas`) VALUES
(1, '01', 500062, '2025-06-25', 'Fetasya Ayu V');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kegiatan`
--

CREATE TABLE `tb_kegiatan` (
  `no` int NOT NULL,
  `jenis_kegiatan` varchar(50) NOT NULL,
  `tahun_kegiatan` year NOT NULL,
  `jumlah` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_kegiatan`
--

INSERT INTO `tb_kegiatan` (`no`, `jenis_kegiatan`, `tahun_kegiatan`, `jumlah`) VALUES
(1, 'KEGIATAN REGULER', '2025', 900000),
(2, 'KEGIATAN PLUS', '2025', 1000000);

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas`
--

CREATE TABLE `tb_kelas` (
  `id_kelas` int NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `jenis_kelas` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_kelas`
--

INSERT INTO `tb_kelas` (`id_kelas`, `kelas`, `jenis_kelas`) VALUES
(3, 'A1', 'REGULER'),
(4, 'A2', 'REGULER'),
(5, 'A3', 'REGULER'),
(6, 'B1', 'REGULER'),
(7, 'B2', 'REGULER'),
(8, 'B3', 'REGULER'),
(9, 'B4', 'REGULER'),
(10, 'B+1', 'PLUS'),
(11, 'B+2', 'PLUS');

-- --------------------------------------------------------

--
-- Table structure for table `tb_penarikan_kegiatan`
--

CREATE TABLE `tb_penarikan_kegiatan` (
  `id_penarikan_kegiatan` int NOT NULL,
  `nis` varchar(20) NOT NULL,
  `jumlah_tarik_kegiatan` int NOT NULL,
  `tgl` date NOT NULL,
  `keterangan` text,
  `petugas` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_penarikan_kegiatan`
--

INSERT INTO `tb_penarikan_kegiatan` (`id_penarikan_kegiatan`, `nis`, `jumlah_tarik_kegiatan`, `tgl`, `keterangan`, `petugas`, `created_at`) VALUES
(1, '-', 90000, '2025-06-25', 'makan ikan', 'Fetasya Ayu V', '2025-06-24 23:41:01');

-- --------------------------------------------------------

--
-- Table structure for table `tb_penarikan_spp`
--

CREATE TABLE `tb_penarikan_spp` (
  `id_penarikan_spp` int NOT NULL,
  `nis` varchar(20) NOT NULL,
  `jumlah_tarik_spp` int NOT NULL,
  `tgl` date NOT NULL,
  `keterangan` text,
  `petugas` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_penarikan_spp`
--

INSERT INTO `tb_penarikan_spp` (`id_penarikan_spp`, `nis`, `jumlah_tarik_spp`, `tgl`, `keterangan`, `petugas`, `created_at`) VALUES
(2, '-', 800000, '2025-06-25', 'uang raport kita', 'Fetasya Ayu V', '2025-06-24 23:36:57');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengguna`
--

CREATE TABLE `tb_pengguna` (
  `id_pengguna` int NOT NULL,
  `nama_pengguna` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(15) NOT NULL,
  `level` enum('Administrator','Petugas') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_pengguna`
--

INSERT INTO `tb_pengguna` (`id_pengguna`, `nama_pengguna`, `username`, `password`, `level`) VALUES
(1, 'Fetasya Ayu V', 'admin', 'admin', 'Administrator'),
(4, 'Imas', 'admin2', 'admin2', 'Petugas');

-- --------------------------------------------------------

--
-- Table structure for table `tb_profil`
--

CREATE TABLE `tb_profil` (
  `id_profil` int NOT NULL,
  `nama_sekolah` varchar(200) NOT NULL,
  `alamat` varchar(400) NOT NULL,
  `akreditasi` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_profil`
--

INSERT INTO `tb_profil` (`id_profil`, `nama_sekolah`, `alamat`, `akreditasi`) VALUES
(1, 'TK ABA PANTI PUTRA', 'JL. Jetak, Ringin Harjo, Bantul, Mandingan, Ringinharjo, Kec. Bantul, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55712', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `tb_setor_infaq`
--

CREATE TABLE `tb_setor_infaq` (
  `id_infaq_pembayaran` int NOT NULL,
  `nis` char(12) NOT NULL,
  `infaq_pembayaran` int NOT NULL,
  `tgl` date NOT NULL,
  `petugas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_setor_infaq`
--

INSERT INTO `tb_setor_infaq` (`id_infaq_pembayaran`, `nis`, `infaq_pembayaran`, `tgl`, `petugas`) VALUES
(1, '01', 6000, '2025-06-25', 'Fetasya Ayu V'),
(2, '01', 50000, '2025-06-25', 'Fetasya Ayu V');

-- --------------------------------------------------------

--
-- Table structure for table `tb_setor_kegiatan`
--

CREATE TABLE `tb_setor_kegiatan` (
  `id_setor_kegiatan` int NOT NULL,
  `nis` char(12) NOT NULL,
  `setor_kegiatan` int NOT NULL,
  `tgl` date NOT NULL,
  `petugas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_setor_kegiatan`
--

INSERT INTO `tb_setor_kegiatan` (`id_setor_kegiatan`, `nis`, `setor_kegiatan`, `tgl`, `petugas`) VALUES
(3, '01', 800000, '2025-06-24', 'Fetasya Ayu V'),
(4, '01', 70000, '2025-06-24', 'Fetasya Ayu V'),
(5, '323', 600000, '2025-06-25', 'Fetasya Ayu V');

-- --------------------------------------------------------

--
-- Table structure for table `tb_setor_spp`
--

CREATE TABLE `tb_setor_spp` (
  `id_setor_spp` int NOT NULL,
  `nis` char(12) NOT NULL,
  `setor_spp` int NOT NULL,
  `tgl` date NOT NULL,
  `petugas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_setor_spp`
--

INSERT INTO `tb_setor_spp` (`id_setor_spp`, `nis`, `setor_spp`, `tgl`, `petugas`) VALUES
(4, '01', 900000, '2025-06-23', 'Fetasya Ayu V'),
(5, '01', 10, '2025-06-24', 'Fetasya Ayu V'),
(6, '01', 1000000, '2025-06-24', 'Fetasya Ayu V');

-- --------------------------------------------------------

--
-- Table structure for table `tb_siswa`
--

CREATE TABLE `tb_siswa` (
  `nis` char(12) NOT NULL,
  `nama_siswa` varchar(40) NOT NULL,
  `jekel` enum('LK','PR') NOT NULL,
  `id_kelas` int NOT NULL,
  `status` enum('Aktif','Lulus','Pindah') NOT NULL,
  `th_masuk` year NOT NULL,
  `naik_kelas` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_siswa`
--

INSERT INTO `tb_siswa` (`nis`, `nama_siswa`, `jekel`, `id_kelas`, `status`, `th_masuk`, `naik_kelas`) VALUES
('01', 'Rayan', 'LK', 10, 'Aktif', '2025', 0),
('323', 'dyan', 'PR', 9, 'Aktif', '2025', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_spp`
--

CREATE TABLE `tb_spp` (
  `no` int NOT NULL,
  `jenis_spp` varchar(50) NOT NULL,
  `tahun_spp` year NOT NULL,
  `jumlah` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_spp`
--

INSERT INTO `tb_spp` (`no`, `jenis_spp`, `tahun_spp`, `jumlah`) VALUES
(1, 'SPP REGULER', '2025', 1080000),
(2, 'SPP PLUS', '2025', 3240000);

-- --------------------------------------------------------

--
-- Table structure for table `tb_tabungan`
--

CREATE TABLE `tb_tabungan` (
  `id_tabungan` int NOT NULL,
  `nis` char(12) NOT NULL,
  `setor` int NOT NULL,
  `tarik` int NOT NULL,
  `tgl` date NOT NULL,
  `jenis` enum('ST','TR') NOT NULL,
  `petugas` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_tabungan`
--

INSERT INTO `tb_tabungan` (`id_tabungan`, `nis`, `setor`, `tarik`, `tgl`, `jenis`, `petugas`) VALUES
(66, '01', 50000, 0, '2025-06-23', 'ST', 'Fetasya Ayu V'),
(67, '01', 90000, 0, '2025-06-24', 'ST', 'Fetasya Ayu V'),
(68, '01', 0, 50000, '2025-06-24', 'TR', 'Fetasya Ayu V');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_infaq_kesanggupan`
--
ALTER TABLE `tb_infaq_kesanggupan`
  ADD PRIMARY KEY (`id_infaq_kesanggupan`);

--
-- Indexes for table `tb_kegiatan`
--
ALTER TABLE `tb_kegiatan`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `tb_penarikan_kegiatan`
--
ALTER TABLE `tb_penarikan_kegiatan`
  ADD PRIMARY KEY (`id_penarikan_kegiatan`);

--
-- Indexes for table `tb_penarikan_spp`
--
ALTER TABLE `tb_penarikan_spp`
  ADD PRIMARY KEY (`id_penarikan_spp`);

--
-- Indexes for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- Indexes for table `tb_profil`
--
ALTER TABLE `tb_profil`
  ADD PRIMARY KEY (`id_profil`);

--
-- Indexes for table `tb_setor_infaq`
--
ALTER TABLE `tb_setor_infaq`
  ADD PRIMARY KEY (`id_infaq_pembayaran`);

--
-- Indexes for table `tb_setor_kegiatan`
--
ALTER TABLE `tb_setor_kegiatan`
  ADD PRIMARY KEY (`id_setor_kegiatan`);

--
-- Indexes for table `tb_setor_spp`
--
ALTER TABLE `tb_setor_spp`
  ADD PRIMARY KEY (`id_setor_spp`);

--
-- Indexes for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD PRIMARY KEY (`nis`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indexes for table `tb_spp`
--
ALTER TABLE `tb_spp`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `tb_tabungan`
--
ALTER TABLE `tb_tabungan`
  ADD PRIMARY KEY (`id_tabungan`),
  ADD KEY `nis` (`nis`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_infaq_kesanggupan`
--
ALTER TABLE `tb_infaq_kesanggupan`
  MODIFY `id_infaq_kesanggupan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_kegiatan`
--
ALTER TABLE `tb_kegiatan`
  MODIFY `no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  MODIFY `id_kelas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_penarikan_kegiatan`
--
ALTER TABLE `tb_penarikan_kegiatan`
  MODIFY `id_penarikan_kegiatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_penarikan_spp`
--
ALTER TABLE `tb_penarikan_spp`
  MODIFY `id_penarikan_spp` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_profil`
--
ALTER TABLE `tb_profil`
  MODIFY `id_profil` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_setor_infaq`
--
ALTER TABLE `tb_setor_infaq`
  MODIFY `id_infaq_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_setor_kegiatan`
--
ALTER TABLE `tb_setor_kegiatan`
  MODIFY `id_setor_kegiatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_setor_spp`
--
ALTER TABLE `tb_setor_spp`
  MODIFY `id_setor_spp` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_spp`
--
ALTER TABLE `tb_spp`
  MODIFY `no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_tabungan`
--
ALTER TABLE `tb_tabungan`
  MODIFY `id_tabungan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD CONSTRAINT `tb_siswa_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_tabungan`
--
ALTER TABLE `tb_tabungan`
  ADD CONSTRAINT `tb_tabungan_ibfk_1` FOREIGN KEY (`nis`) REFERENCES `tb_siswa` (`nis`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
