<?php
// session_start(); // Sudah dipanggil di index.php
include "inc/koneksi.php";

// $nis = $_SESSION["ses_nis"];
// $nama = $_SESSION["ses_nama"];

$nis = isset($_GET['nis']) ? $_GET['nis'] : (isset($_SESSION["ses_nis"]) ? $_SESSION["ses_nis"] : '');
$nama = isset($_GET['nama']) ? $_GET['nama'] : (isset($_SESSION["ses_nama"]) ? $_SESSION["ses_nama"] : '');

// --- Ambil data SPP ---
$spp = [];
$total_tagihan_spp = 0;
$total_bayar_spp = 0;
$sql_spp = $koneksi->query("SELECT * FROM tb_spp ORDER BY tahun_spp ASC");
while ($row = $sql_spp->fetch_assoc()) {
    $tahun = $row['tahun_spp'];
    $tagihan = $row['jumlah'];
    $sql_bayar = $koneksi->query("SELECT SUM(setor_spp) as bayar FROM tb_setor_spp WHERE nis='$nis' AND YEAR(tgl)='$tahun'");
    $bayar = 0;
    if ($data_bayar = $sql_bayar->fetch_assoc()) {
        $bayar = $data_bayar['bayar'] ?? 0;
    }
    $spp[] = [
        'tahun' => $tahun,
        'tagihan' => $tagihan,
        'bayar' => $bayar
    ];
    $total_tagihan_spp += $tagihan;
    $total_bayar_spp += $bayar;
}
$kurang_spp = $total_tagihan_spp - $total_bayar_spp;

// --- Ambil data Kegiatan ---
$kegiatan = [];
$total_tagihan_kegiatan = 0;
$total_bayar_kegiatan = 0;
$sql_kegiatan = $koneksi->query("SELECT * FROM tb_kegiatan ORDER BY tahun_kegiatan ASC");
while ($row = $sql_kegiatan->fetch_assoc()) {
    $nama_kegiatan = $row['jenis_kegiatan'];
    $tahun_kegiatan = $row['tahun_kegiatan'];
    $tagihan = $row['jumlah'];
    $sql_bayar = $koneksi->query("SELECT SUM(setor_kegiatan) as bayar FROM tb_setor_kegiatan WHERE nis='$nis' AND YEAR(tgl)='$tahun_kegiatan'");
    $bayar = 0;
    if ($data_bayar = $sql_bayar->fetch_assoc()) {
        $bayar = $data_bayar['bayar'] ?? 0;
    }
    $kegiatan[] = [
        'kegiatan' => $nama_kegiatan,
        'tahun' => $tahun_kegiatan,
        'tagihan' => $tagihan,
        'bayar' => $bayar
    ];
    $total_tagihan_kegiatan += $tagihan;
    $total_bayar_kegiatan += $bayar;
}
$kurang_kegiatan = $total_tagihan_kegiatan - $total_bayar_kegiatan;

// --- Ambil data Infaq ---
$infaq = [];
$total_kesanggupan = 0;
$total_bayar_infaq = 0;
$sql_infaq = $koneksi->query("SELECT * FROM tb_infaq_kesanggupan WHERE nis='$nis' ORDER BY tgl ASC");
while ($row = $sql_infaq->fetch_assoc()) {
    $bulan = date('F Y', strtotime($row['tgl']));
    $kesanggupan = $row['infaq_kesanggupan'];
    $sql_bayar = $koneksi->query("SELECT SUM(infaq_pembayaran) as bayar FROM tb_setor_infaq WHERE nis='$nis' AND MONTH(tgl)=MONTH('".$row['tgl']."') AND YEAR(tgl)=YEAR('".$row['tgl']."')");
    $bayar = 0;
    if ($data_bayar = $sql_bayar->fetch_assoc()) {
        $bayar = $data_bayar['bayar'] ?? 0;
    }
    $infaq[] = [
        'bulan' => $bulan,
        'kesanggupan' => $kesanggupan,
        'bayar' => $bayar
    ];
    $total_kesanggupan += $kesanggupan;
    $total_bayar_infaq += $bayar;
}
$lebih_infaq = $total_bayar_infaq - $total_kesanggupan;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <!-- <title>Info Pembayaran Siswa</title> -->
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f7f9fc; margin: 20px; color: #333; }
    h2 { background-color: #007bff; color: white; padding: 10px; border-radius: 5px; text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
    th { background-color: #f1f1f1; }
    .summary { font-weight: bold; padding: 8px; margin-top: -10px; text-align: right; }
    .lebih { color: green; }
    .kurang { color: red; }
    .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px; }
  </style>
</head>
<body>
  <h2>Info Pembayaran Ananda <b><?php  echo $nama; ?></b></h2>
  <div class="card">
    <!-- Content Header (Page header) -->
<?php

// Ambil nama siswa
$qNama = $koneksi->query("SELECT nama_siswa FROM tb_siswa WHERE nis='$nis'");
$dNama = $qNama->fetch_assoc();
$nama_siswa = $dNama ? $dNama['nama_siswa'] : '';
?>
<style>
@media print {
  .no-print {
    display: none !important;
  }
}
</style>
<a href="javascript:window.print()" 
   class="btn btn-outline-dark shadow-sm rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2 no-print"
   style="font-weight: 600; font-size: 16px; transition: all 0.3s ease; margin: 1px auto; display: block; width: fit-content;"
   onmouseover="this.style.backgroundColor='#343a40'; this.style.color='white';"
   onmouseout="this.style.backgroundColor='transparent'; this.style.color='inherit';"
>
    <i class="fa fa-print"></i> Cetak Laporan Pembayaran Siswa
</a>


<section class="content">


<!-- Summary Pembayaran (AJAX) -->
<!-- <div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="glyphicon glyphicon-list-alt"></i> Summary Pembayaran</h3>
  </div>
  <div class="box-body">
    <ul class="list-group">
      <li class="list-group-item">
        <strong>Jenis Kelas:</strong>
        <span class="pull-right text-primary" id="jenis_kelas">-</span>
      </li>
      <li class="list-group-item">
        <strong>Total Setoran SPP:</strong>
        <span class="pull-right label label-success" id="total_spp">Rp0</span>
      </li>
      <li class="list-group-item">
        <strong>Kekurangan SPP:</strong>
        <span class="pull-right label label-danger" id="kurang_spp">Rp0</span>
      </li>
      <li class="list-group-item">
        <strong>Keterangan SPP:</strong>
        <span class="pull-right" id="ket_spp">-</span>
      </li>
      <li class="list-group-item">
        <strong>Total Setoran Kegiatan:</strong>
        <span class="pull-right label label-success" id="total_kegiatan">Rp0</span>
      </li>
      <li class="list-group-item">
        <strong>Kekurangan Kegiatan:</strong>
        <span class="pull-right label label-danger" id="kurang_kegiatan">Rp0</span>
      </li>
      <li class="list-group-item">
        <strong>Keterangan Kegiatan:</strong>
        <span class="pull-right" id="ket_kegiatan">-</span>
      </li>
    </ul>
  </div>
</div> -->

<!-- Summary SPP -->
<div class="alert alert-info alert-dismissible">
    <h4>
        <i class="icon fa fa-info"></i> Info SPP
    </h4>
    <h4>
        Jenis Kelas: <span id="jenis_kelas">-</span><br>
        NIS: <?php echo $nis; ?><br>
        Nama: <?php echo $nama; ?>
    </h4>
    <hr>
    <h4>
        Biaya SPP dalam Satu Tahun: <span id="biaya_spp">Rp0</span>
    </h4>
    <h4>
        Total Setoran SPP: <span id="total_spp">Rp0</span>
    </h4>
    <h4>
        Kekurangan SPP: <span id="kurang_spp">Rp0</span>
    </h4>
</div>

    <!-- Tabel Pembayaran SPP -->
    <div class="box box-primary">
        <!-- <div class="box-header">
            <h3 class="box-title">Pembayaran SPP</h3>
        </div> -->
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
                        $no = 1;
                        $sql = $koneksi->query("SELECT ss.*, s.nama_siswa FROM tb_setor_spp ss LEFT JOIN tb_siswa s ON ss.nis = s.nis WHERE ss.nis='$nis' ORDER BY ss.tgl DESC, ss.id_setor_spp DESC");
                        while ($data = $sql->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date("d/M/Y", strtotime($data['tgl'])); ?></td>
                            <td align="right"><?php echo rupiah($data['setor_spp']); ?></td>
                            <td><?php echo $data['petugas']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Summary Kegiatan -->
<div class="alert alert-info alert-dismissible">
    <h4>
        <i class="icon fa fa-info"></i> Info Kegiatan
    </h4>
    <h4>
      Jenis Kelas: <span id="jenis_kelas">-</span><br>
        NIS: <?php echo $nis; ?><br>
        Nama: <?php echo $nama; ?>
    </h4>
    <hr>
    <h4>
        Biaya Kegiatan dalam Satu Tahun: <span id="biaya_kegiatan">Rp0</span>
    </h4>
    <h4>
        Total Setoran Kegiatan: <span id="total_kegiatan">Rp0</span>
    </h4>
    <h4>
        Kekurangan Kegiatan: <span id="kurang_kegiatan">Rp0</span>
    </h4>
</div>

    <!-- Tabel Pembayaran Kegiatan -->
    <div class="box box-primary">
        <!-- <div class="box-header">
            <h3 class="box-title">Pembayaran Kegiatan</h3>
        </div> -->
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
                        $no = 1;
                        $sql = $koneksi->query("SELECT sk.*, s.nama_siswa FROM tb_setor_kegiatan sk LEFT JOIN tb_siswa s ON sk.nis = s.nis WHERE sk.nis='$nis' ORDER BY sk.tgl DESC, sk.id_setor_kegiatan DESC");
                        while ($data = $sql->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date("d/M/Y", strtotime($data['tgl'])); ?></td>
                            <td align="right"><?php echo rupiah($data['setor_kegiatan']); ?></td>
                            <td><?php echo $data['petugas']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Tombol Cetak, tambahkan id untuk manipulasi JS -->
<!-- <a href="petugas/pembayaran_siswa/cetak-pembayaran-siswa.php?nis=<?php echo $nis; ?>" 
   target="_blank" 
   class="btn btn-success" 
   style="margin-bottom:10px;"
   id="btn-cetak-summary">
    <i class="fa fa-print"></i> Cetak Rangkuman Pembayaran
</a> -->

</section>
<!-- Content Header (Page header) -->
<?php

    // Ambil data siswa
    $sql_siswa = $koneksi->query("SELECT * FROM tb_siswa WHERE nis='$nis'");
    $siswa = $sql_siswa->fetch_assoc();

    // Total setoran infaq
    $sql = $koneksi->query("SELECT SUM(infaq_pembayaran) as total_setor FROM tb_setor_infaq WHERE nis='$nis'");
    $data = $sql->fetch_assoc();
    $total_setor = $data['total_setor'] ? $data['total_setor'] : 0;

    // Ambil nominal kesanggupan infaq terakhir
    $sql_kesanggupan = $koneksi->query("SELECT infaq_kesanggupan FROM tb_infaq_kesanggupan WHERE nis='$nis' ORDER BY tgl DESC, id_infaq_kesanggupan DESC LIMIT 1");
    $data_kesanggupan = $sql_kesanggupan->fetch_assoc();
    $nominal_kesanggupan = $data_kesanggupan ? $data_kesanggupan['infaq_kesanggupan'] : 0;
?>

<section class="content">

<div class="alert alert-info alert-dismissible">
    <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
        <h4>
            <i class="icon fa fa-info"></i> Info Infaq</h4>
        <h4>
            NIS: <?php echo $siswa['nis']; ?><br>
            Nama: <?php echo $siswa['nama_siswa']; ?>
        </h4>
        <hr>
        <h4>
            Kesanggupan Infaq: 
            <?php 
                if($nominal_kesanggupan > 0) {
                    echo rupiah($nominal_kesanggupan);
                } else {
                    echo '<span class="label label-warning">Belum mengisi kesanggupan infaq</span>';
                }
            ?>
        </h4>
        <h4>
            Total Setoran Infaq: <b><?php echo rupiah($total_setor); ?></b>
        </h4>
        <h4>
            <?php
                if($nominal_kesanggupan > 0) {
                    if($total_setor < $nominal_kesanggupan) {
                        echo 'Kekurangan Infaq: <b class="text">'.rupiah($nominal_kesanggupan - $total_setor).'</b>';
                    } elseif($total_setor > $nominal_kesanggupan) {
                        echo 'Kelebihan Infaq: <b class="text">'.rupiah($total_setor - $nominal_kesanggupan).'</b>';
                    } else {
                        echo '<span class="text-primary">Infaq sudah sesuai dengan kesanggupan.</span>';
                    }
                }
            ?>
        </h4>
    </div>

    <div class="box box-primary">
        <div class="box-header">

				                        <!-- <a href="petugas/info_infaq/print_infaq.php?nis=<?php echo $siswa['nis']; ?>" target="_blank" class="btn btn-success">
                            <i class="fa fa-print"></i> Cetak Infaq
                        </a> -->
						
            <!-- <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove">
                    <i class="fa fa-remove"></i>
                </button>
            </div> -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Setoran Infaq</th>
                            <th>Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $sql = $koneksi->query("SELECT * FROM tb_setor_infaq WHERE nis='$nis' ORDER BY tgl ASC, id_infaq_pembayaran ASC");
                        while ($data = $sql->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date("d/M/Y", strtotime($data['tgl'])); ?></td>
                            <td align="right"><?php echo rupiah($data['infaq_pembayaran']); ?></td>
                            <td><?php echo $data['petugas']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- jQuery (harus sebelum DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables JS dan CSS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />

<script>
$(document).ready(function() {
    $('#example1').DataTable({
        responsive: true,
        destroy: true
    });
    $('#example2').DataTable({
        responsive: true,
        destroy: true
    });
    // Debug: cek apakah DataTables aktif
    console.log($('#example2').DataTable().settings());
});
</script>

<script src="././bootstrap/lookup.js"></script>
<script>
    $(document).ready(function() {
        // Panggil AJAX summary saat halaman dibuka
        var nis = "<?php echo $nis; ?>";
        var summaryData = {}; // Simpan summary di sini

        if(nis) {
            $.ajax({
                url: "plugins/proses-pembayaran-siswa.php",
                method: "POST",
                dataType: "json",
                data: { nis: nis },
                success: function(data) {
                    summaryData = data; // Simpan data summary

                    if(data.notif_spp || data.notif_kegiatan) {
                        if(data.notif_spp) alert(data.notif_spp);
                        if(data.notif_kegiatan) alert(data.notif_kegiatan);

                        $('#jenis_kelas').text('');
                        $('#total_spp').text('');
                        $('#kurang_spp').text('');
                        $('#total_kegiatan').text('');
                        $('#kurang_kegiatan').text('');
                        $('#ket_spp').text('');
                        $('#ket_kegiatan').text('');
                        // Tambahan: update juga Info SPP & Kegiatan
                        $("span#jenis_kelas").text('');
                        $("span#total_spp").text('Rp0');
                        $("span#kurang_spp").text('Rp0');
                        $("span#ket_spp").text('-');
                        $("span#total_kegiatan").text('Rp0');
                        $("span#kurang_kegiatan").text('Rp0');
                        $("span#ket_kegiatan").text('-');
                        return;
                    }

                    $('#jenis_kelas').text(data.jenis_kelas);
                    $('#total_spp').text(data.total_spp);
                    $('#kurang_spp').text(data.kurang_spp);
                    $('#total_kegiatan').text(data.total_kegiatan);
                    $('#kurang_kegiatan').text(data.kurang_kegiatan);

                    // Hitung dan tampilkan Biaya SPP & Kegiatan dalam Satu Tahun
                    function parseRupiah(str) {
    // Hilangkan Rp, titik, spasi, dan koma
    return Number(str.replace(/[^0-9,-]+/g,"").replace(",","."));
}
function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID', {minimumFractionDigits: 2});
}
var biaya_spp = parseRupiah(data.total_spp) + parseRupiah(data.kurang_spp);
$('#biaya_spp').text(formatRupiah(biaya_spp));


var biaya_kegiatan = parseRupiah(data.total_kegiatan) + parseRupiah(data.kurang_kegiatan);
$('#biaya_kegiatan').text(formatRupiah(biaya_kegiatan));

                    if(data.selisih_spp > 0) {
                        $('#ket_spp').text('Kelebihan pembayaran SPP: Rp ' + Math.abs(data.selisih_spp).toLocaleString('id-ID'));
                    } else if(data.selisih_spp < 0) {
                        $('#ket_spp').text('Kekurangan pembayaran SPP: ' + data.kurang_spp);
                    } else {
                        $('#ket_spp').text('Pembayaran SPP sudah lunas');
                    }

                    if(data.selisih_kegiatan > 0) {
                        $('#ket_kegiatan').text('Kelebihan pembayaran Kegiatan: Rp ' + Math.abs(data.selisih_kegiatan).toLocaleString('id-ID'));
                    } else if(data.selisih_kegiatan < 0) {
                        $('#ket_kegiatan').text('Kekurangan pembayaran Kegiatan: ' + data.kurang_kegiatan);
                    } else {
                        $('#ket_kegiatan').text('Pembayaran Kegiatan sudah lunas');
                    }

                    // --- Tambahan: update Info SPP & Info Kegiatan ---
                    // Info SPP
                    $("span#jenis_kelas").text(data.jenis_kelas);
                    $("span#total_spp").text(data.total_spp);
                    $("span#kurang_spp").text(data.kurang_spp);
                    $("span#ket_spp").text($('#ket_spp').text());

                    // Info Kegiatan
                    $("span#total_kegiatan").text(data.total_kegiatan);
                    $("span#kurang_kegiatan").text(data.kurang_kegiatan);
                    $("span#ket_kegiatan").text($('#ket_kegiatan').text());

                    // Update tombol cetak dengan parameter summary
                    var params = new URLSearchParams({
                        nis: nis,
                        jenis_kelas: data.jenis_kelas,
                        total_spp: data.total_spp,
                        kurang_spp: data.kurang_spp,
                        ket_spp: $('#ket_spp').text(),
                        total_kegiatan: data.total_kegiatan,
                        kurang_kegiatan: data.kurang_kegiatan,
                        ket_kegiatan: $('#ket_kegiatan').text()
                    }).toString();

                    //$('#btn-cetak-summary').attr('href', 'petugas/pembayaran_siswa/cetak-pembayaran-siswa.php?' + params);
                }
            });
        }
    });
</script>
  </div>
</body>
</html>
