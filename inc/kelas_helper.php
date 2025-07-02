<?php
/**
 * Helper functions untuk tracking naik kelas
 */

/**
 * Ambil status naik_kelas siswa berdasarkan NIS
 * @param mysqli $koneksi - koneksi database
 * @param string $nis - NIS siswa
 * @return int - status naik_kelas (0 atau 1)
 */
function getStatusNaikKelas($koneksi, $nis) {
    $query = mysqli_query($koneksi, "SELECT naik_kelas FROM tb_siswa WHERE nis = '$nis'");
    if ($query && $data = mysqli_fetch_assoc($query)) {
        return (int) $data['naik_kelas'];
    }
    return 0; // Default jika tidak ditemukan
}

/**
 * Update status naik kelas siswa
 * @param mysqli $koneksi - koneksi database  
 * @param string $nis - NIS siswa
 * @param int $status - status baru (0 atau 1)
 * @return bool - berhasil atau tidak
 */
function updateStatusNaikKelas($koneksi, $nis, $status) {
    $status = (int) $status; // Pastikan integer
    $query = mysqli_query($koneksi, "UPDATE tb_siswa SET naik_kelas = $status WHERE nis = '$nis'");
    return $query ? true : false;
}

/**
 * Get keterangan status kelas untuk tampilan
 * @param int $kelas_ke - status kelas (0 atau 1)
 * @return string - keterangan
 */
function getKeteranganKelas($kelas_ke) {
    return $kelas_ke == 0 ? 'Sebelum Naik Kelas' : 'Setelah Naik Kelas';
}
?>