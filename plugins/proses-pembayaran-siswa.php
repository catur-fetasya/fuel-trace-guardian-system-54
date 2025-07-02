<?php
header('Content-Type: application/json');
include '../inc/koneksi.php';
include "../inc/rupiah.php";

$nis = $_POST['nis'];

// Ambil id_kelas, tahun masuk, dan jenis_kelas siswa
$qSiswa = mysqli_query($koneksi, "SELECT id_kelas, th_masuk FROM tb_siswa WHERE nis='$nis'");
$dSiswa = mysqli_fetch_assoc($qSiswa);
$id_kelas = isset($dSiswa['id_kelas']) ? $dSiswa['id_kelas'] : null;
$th_masuk = isset($dSiswa['th_masuk']) ? $dSiswa['th_masuk'] : null;

$jenis_kelas = '';
if($id_kelas){
    $qKelas = mysqli_query($koneksi, "SELECT jenis_kelas FROM tb_kelas WHERE id_kelas='$id_kelas'");
    $dKelas = mysqli_fetch_assoc($qKelas);
    $jenis_kelas = isset($dKelas['jenis_kelas']) ? $dKelas['jenis_kelas'] : '';
}

// --- SPP ---
$jenis_spp = ($jenis_kelas == "PLUS") ? "SPP PLUS" : "SPP REGULER";
$notif_spp = '';
$jumlah_spp = 0;
if($th_masuk){
    $qSpp = mysqli_query($koneksi, "SELECT jumlah FROM tb_spp WHERE jenis_spp='$jenis_spp' AND tahun_spp='$th_masuk' LIMIT 1");
    $dSpp = mysqli_fetch_assoc($qSpp);
    if($dSpp) {
        $jumlah_spp = $dSpp['jumlah'];
    } else {
        $notif_spp = "Besaran SPP untuk $jenis_spp tahun $th_masuk tidak ada pada master data!";
    }
} else {
    $notif_spp = "Tahun masuk siswa tidak ditemukan!";
}

// Ambil status naik kelas siswa saat ini
$qNaikKelas = mysqli_query($koneksi, "SELECT naik_kelas FROM tb_siswa WHERE nis='$nis'");
$dNaikKelas = mysqli_fetch_assoc($qNaikKelas);
$status_kelas = $dNaikKelas['naik_kelas'] ? $dNaikKelas['naik_kelas'] : 0;

// Total setoran SPP siswa berdasarkan status kelas saat ini
$qSetorSpp = mysqli_query($koneksi, "SELECT SUM(setor_spp) as total FROM tb_setor_spp WHERE nis='$nis' AND kelas_ke='$status_kelas'");
$dSetorSpp = mysqli_fetch_assoc($qSetorSpp);
$total_spp = $dSetorSpp['total'] ? $dSetorSpp['total'] : 0;

// Kekurangan SPP
$kurang_spp = $jumlah_spp - $total_spp;
if($kurang_spp < 0) $kurang_spp = 0;

// --- KEGIATAN ---
$jenis_kegiatan = ($jenis_kelas == "PLUS") ? "KEGIATAN PLUS" : "KEGIATAN REGULER";
$notif_kegiatan = '';
$jumlah_kegiatan = 0;
if($th_masuk){
    $qKegiatan = mysqli_query($koneksi, "SELECT jumlah FROM tb_kegiatan WHERE jenis_kegiatan='$jenis_kegiatan' AND tahun_kegiatan='$th_masuk' LIMIT 1");
    $dKegiatan = mysqli_fetch_assoc($qKegiatan);
    if($dKegiatan) {
        $jumlah_kegiatan = $dKegiatan['jumlah'];
    } else {
        $notif_kegiatan = "Besaran Kegiatan untuk $jenis_kegiatan tahun $th_masuk tidak ada pada master data!";
    }
} else {
    $notif_kegiatan = "Tahun masuk siswa tidak ditemukan!";
}

// Total setoran kegiatan siswa berdasarkan status kelas saat ini
$qSetorKegiatan = mysqli_query($koneksi, "SELECT SUM(setor_kegiatan) as total FROM tb_setor_kegiatan WHERE nis='$nis' AND kelas_ke='$status_kelas'");
$dSetorKegiatan = mysqli_fetch_assoc($qSetorKegiatan);
$total_kegiatan = $dSetorKegiatan['total'] ? $dSetorKegiatan['total'] : 0;

// Kekurangan kegiatan
$kurang_kegiatan = $jumlah_kegiatan - $total_kegiatan;
if($kurang_kegiatan < 0) $kurang_kegiatan = 0;

$selisih_spp = $total_spp - $jumlah_spp;
$selisih_kegiatan = $total_kegiatan - $jumlah_kegiatan;

// Output JSON
echo json_encode([
    "jenis_kelas" => $jenis_kelas,
    "total_spp" => rupiah($total_spp),
    "kurang_spp" => rupiah($kurang_spp),
    "total_kegiatan" => rupiah($total_kegiatan),
    "kurang_kegiatan" => rupiah($kurang_kegiatan),
    "selisih_spp" => $selisih_spp,
    "selisih_kegiatan" => $selisih_kegiatan,
    "notif_spp" => $notif_spp,
    "notif_kegiatan" => $notif_kegiatan
]);
?>