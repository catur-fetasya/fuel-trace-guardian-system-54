<?php
include "../../inc/koneksi.php";
include "../../inc/rupiah.php";

$nis = $_GET['nis'];

// Data siswa
$sql_siswa = $koneksi->query("SELECT * FROM tb_siswa WHERE nis='$nis'");
$siswa = $sql_siswa->fetch_assoc();

// Total setoran infaq
$sql = $koneksi->query("SELECT SUM(infaq_pembayaran) as total_setor FROM tb_setor_infaq WHERE nis='$nis'");
$data = $sql->fetch_assoc();
$total_setor = $data['total_setor'] ? $data['total_setor'] : 0;

// Kesanggupan infaq
$sql_kesanggupan = $koneksi->query("SELECT infaq_kesanggupan FROM tb_infaq_kesanggupan WHERE nis='$nis' ORDER BY tgl DESC, id_infaq_kesanggupan DESC LIMIT 1");
$data_kesanggupan = $sql_kesanggupan->fetch_assoc();
$nominal_kesanggupan = $data_kesanggupan ? $data_kesanggupan['infaq_kesanggupan'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cetak Infaq Siswa</title>
    <style>
        body { font-family: Arial; font-size: 14px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background: #eee; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <center>
        <h2>Rekapitulasi Infaq Siswa</h2>
        <h3>TK ABA PANTI PUTRA</h3>
        <p>JL. Jetak, Ringin Harjo, Bantul, Mandingan, Ringinharjo, Kec. Bantul, YK</p>
        <p>Rajin Pangkal Pandai. Hemat Pangkal Kaya.</p>
        <hr>
        <table style="width:60%;margin-bottom:10px;">
            <tr>
                <td width="30%">NIS</td>
                <td width="5%">:</td>
                <td><?php echo $siswa['nis']; ?></td>
            </tr>
            <tr>
                <td>Nama Siswa</td>
                <td>:</td>
                <td><?php echo $siswa['nama_siswa']; ?></td>
            </tr>
            <tr>
                <td>Kesanggupan Infaq</td>
                <td>:</td>
                <td><?php echo $nominal_kesanggupan > 0 ? rupiah($nominal_kesanggupan) : '-'; ?></td>
            </tr>
            <tr>
                <td>Total Setoran Infaq</td>
                <td>:</td>
                <td><?php echo rupiah($total_setor); ?></td>
            </tr>
            <tr>
                <td>Kekurangan/Kelebihan</td>
                <td>:</td>
                <td>
                    <?php
                    if($nominal_kesanggupan > 0) {
                        if($total_setor < $nominal_kesanggupan) {
                            echo 'Kekurangan: '.rupiah($nominal_kesanggupan - $total_setor);
                        } elseif($total_setor > $nominal_kesanggupan) {
                            echo 'Kelebihan: '.rupiah($total_setor - $nominal_kesanggupan);
                        } else {
                            echo 'Sesuai Kesanggupan';
                        }
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
            </tr>
        </table>

        <b>Riwayat Setoran Infaq</b>
        <table border="1" cellspacing="0">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Setoran Infaq</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_tampil = $koneksi->query("SELECT * FROM tb_setor_infaq WHERE nis='$nis' ORDER BY tgl ASC, id_infaq_pembayaran ASC");
                $no=1;
                while ($data = $sql_tampil->fetch_assoc()) {
                ?>
                <tr>
                    <td class="center"><?php echo $no++; ?></td>
                    <td class="center"><?php echo date("d/M/Y", strtotime($data['tgl'])); ?></td>
                    <td align="right"><?php echo rupiah($data['infaq_pembayaran']); ?></td>
                    <td class="center"><?php echo $data['petugas']; ?></td>
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