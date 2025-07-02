<?php
include "../../inc/koneksi.php";
include "../../inc/rupiah.php";

$nis = $_GET['nis'];

// Ambil data siswa + kelas
$qSiswa = $koneksi->query("SELECT s.*, k.kelas FROM tb_siswa s LEFT JOIN tb_kelas k ON s.id_kelas = k.id_kelas WHERE s.nis='$nis'");
$dSiswa = $qSiswa->fetch_assoc();
$kelas = $dSiswa['kelas'] ?? '-';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cetak Rangkuman Pembayaran Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 90%; margin: 0 auto; }
        th, td { border: 1px solid #000; padding: 6px 10px; }
        th { background: #eee; }
        .center { text-align: center; }
        .no-border { border: none; }
        .summary { width: 60%; margin: 0 auto 20px auto; }
        .summary td { border: none; padding: 4px 8px; }
        .label-success { color: #fff; background: #5cb85c; padding: 2px 8px; border-radius: 4px; }
        .label-danger { color: #fff; background: #d9534f; padding: 2px 8px; border-radius: 4px; }
        .text-primary { color: #337ab7; }
    </style>
</head>
<body>
    <center>
        <h2>Rangkuman Pembayaran Siswa</h2>
        <h3>TK ABA PANTI PUTRA</h3>
        <p>JL. Jetak, Ringin Harjo, Bantul, Mandingan, Ringinharjo, Kec. Bantul, YK</p>
		<p>Rajin Pangkal Pandai. Hemat Pangkal Kaya.</p>
        <p>___________________________________________________________________</p>
        <table class="no-border summary">
            <tr>
                <td width="120">NIS</td>
                <td width="10">:</td>
                <td><?php echo $dSiswa['nis']; ?></td>
            </tr>
            <tr>
                <td>Nama Siswa</td>
                <td>:</td>
                <td><?php echo $dSiswa['nama_siswa']; ?></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td><?php echo $kelas; ?></td>
            </tr>
        </table>

        <!-- Tampilkan summary pembayaran jika ada -->
        <?php if (isset($_GET['jenis_kelas'])): ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="glyphicon glyphicon-list-alt"></i> Ringkasan Pembayaran</h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th>Jenis Kelas</th>
                            <td class="text-primary"><?php echo htmlspecialchars($_GET['jenis_kelas']); ?></td>
                        </tr>
                        <tr>
                            <th>Total SPP 12 Bulan</th>
                            <td>
                                <strong>
                                <span class="label text-primary">
                                    <?php
                                    function parseRupiah($str) {
                                        $str = str_replace(['Rp', ' ', '.'], '', $str);    // Hilangkan simbol & titik ribuan
                                        $str = str_replace(',', '.', $str);                // Ganti koma jadi titik
                                        return (float)$str;                                // Convert ke float
                                    }

                                    $total_spp = isset($_GET['total_spp']) ? parseRupiah($_GET['total_spp']) : 0;
                                    $kurang_spp = isset($_GET['kurang_spp']) ? parseRupiah($_GET['kurang_spp']) : 0;
                                    
                                    function formatRupiah($angka) {
                                        return 'Rp ' . number_format($angka, 0, ',', '.');
                                    }
                                    $total_semua_spp = $total_spp + $kurang_spp;
                                    echo formatRupiah($total_semua_spp);
                                    // echo htmlspecialchars($total_spp + $kurang_spp);
                                    ?>
                                </span>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Total Setoran SPP</th>
                            <td><span class="label text-primary"><?php echo htmlspecialchars($_GET['total_spp']); ?></span></td>
                        </tr>
                        <tr>
                            <th>Kekurangan SPP</th>
                            <td><span class="label text-primary"><?php echo htmlspecialchars($_GET['kurang_spp']); ?></span></td>
                        </tr>
                        <!-- <tr>
                            <th>Keterangan SPP</th>
                            <td><?php echo htmlspecialchars($_GET['ket_spp']); ?></td>
                        </tr> -->
                        <tr>
                            <th>Total Kegiatan 12 Bulan</th>
                            <td>
                                <strong>
                                <span class="label text-primary">
                                    <?php

                                    $total_kegiatan = isset($_GET['total_kegiatan']) ? parseRupiah($_GET['total_kegiatan']) : 0;
                                    $kurang_kegiatan = isset($_GET['kurang_kegiatan']) ? parseRupiah($_GET['kurang_kegiatan']) : 0;
                                    
                                    $total_semua_kegiatan = $total_kegiatan + $kurang_kegiatan;
                                    echo formatRupiah($total_semua_kegiatan);
                                    // echo htmlspecialchars($total_kegiatan + $kurang_kegiatan);
                                    ?>
                                </span>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Total Setoran Kegiatan</th>
                            <td><span class="label text-primary"><?php echo htmlspecialchars($_GET['total_kegiatan']); ?></span></td>
                        </tr>
                        <tr>
                            <th>Kekurangan Kegiatan</th>
                            <td><span class="label text-primary"><?php echo htmlspecialchars($_GET['kurang_kegiatan']); ?></span></td>
                        </tr>
                        <!-- <tr>
                            <th>Keterangan Kegiatan</th>
                            <td><?php echo htmlspecialchars($_GET['ket_kegiatan']); ?></td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- <table class="no-border summary">
            <tr>
                <td><strong>Jenis Kelas</strong></td>
                <td>:</td>
                <td class="text-primary"><?php echo htmlspecialchars($_GET['jenis_kelas']); ?></td>
            </tr>
            <tr>
                <td><strong>Total Setoran SPP</strong></td>
                <td>:</td>
                <td><span class="label-success"><?php echo htmlspecialchars($_GET['total_spp']); ?></span></td>
            </tr>
            <tr>
                <td><strong>Kekurangan SPP</strong></td>
                <td>:</td>
                <td><span class="label-danger"><?php echo htmlspecialchars($_GET['kurang_spp']); ?></span></td>
            </tr>
            <tr>
                <td><strong>Keterangan SPP</strong></td>
                <td>:</td>
                <td><?php echo htmlspecialchars($_GET['ket_spp']); ?></td>
            </tr>
            <tr>
                <td><strong>Total Setoran Kegiatan</strong></td>
                <td>:</td>
                <td><span class="label-success"><?php echo htmlspecialchars($_GET['total_kegiatan']); ?></span></td>
            </tr>
            <tr>
                <td><strong>Kekurangan Kegiatan</strong></td>
                <td>:</td>
                <td><span class="label-danger"><?php echo htmlspecialchars($_GET['kurang_kegiatan']); ?></span></td>
            </tr>
            <tr>
                <td><strong>Keterangan Kegiatan</strong></td>
                <td>:</td>
                <td><?php echo htmlspecialchars($_GET['ket_kegiatan']); ?></td>
            </tr>
        </table> -->
        <?php endif; ?>

        <h4>Pembayaran SPP</h4>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Setoran SPP</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = $koneksi->query("SELECT * FROM tb_setor_spp WHERE nis='$nis' ORDER BY tgl ASC");
                $no = 1;
                while ($data = $sql->fetch_assoc()) {
                ?>
                <tr>
                    <td class="center"><?php echo $no++; ?></td>
                    <td><?php echo date("d/M/Y", strtotime($data['tgl'])); ?></td>
                    <td align="right"><?php echo rupiah($data['setor_spp']); ?></td>
                    <td><?php echo $data['petugas']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>

        <h4>Pembayaran Kegiatan</h4>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Setoran Kegiatan</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = $koneksi->query("SELECT * FROM tb_setor_kegiatan WHERE nis='$nis' ORDER BY tgl ASC");
                $no = 1;
                while ($data = $sql->fetch_assoc()) {
                ?>
                <tr>
                    <td class="center"><?php echo $no++; ?></td>
                    <td><?php echo date("d/M/Y", strtotime($data['tgl'])); ?></td>
                    <td align="right"><?php echo rupiah($data['setor_kegiatan']); ?></td>
                    <td><?php echo $data['petugas']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </center>
    <script>
        window.print();
    </script>
</body>
</html>