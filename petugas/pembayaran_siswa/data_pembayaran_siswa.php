<!-- Content Header (Page header) -->
<?php
$nis = $_POST["nis"];

// Ambil nama siswa
$qNama = $koneksi->query("SELECT nama_siswa FROM tb_siswa WHERE nis='$nis'");
$dNama = $qNama->fetch_assoc();
$nama_siswa = $dNama ? $dNama['nama_siswa'] : '';
?>

<section class="content-header">
    <h1>
        Info Pembayaran Siswa:
        <?php echo $nis . " - " . $nama_siswa; ?>
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="index.php">
                <i class="fa fa-home"></i>
                <b>eTABS</b>
            </a>
        </li>
    </ol>
</section>

<section class="content">


<a href="petugas/pembayaran_siswa/cetak-pembayaran-siswa.php?nis=<?php echo $nis; ?>" 
   target="_blank" 
   class="btn btn-success" 
   style="margin: 10px auto; display: block; width: fit-content;"
   id="btn-cetak-summary">
    <i class="fa fa-print"></i> Cetak Rangkuman Pembayaran
</a>


<!-- Summary Pembayaran (AJAX) -->
<div class="box box-info">
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
      <!-- <li class="list-group-item">
        <strong>Keterangan SPP:</strong>
        <span class="pull-right" id="ket_spp">-</span>
      </li> -->
      <li class="list-group-item">
        <strong>Total Setoran Kegiatan:</strong>
        <span class="pull-right label label-success" id="total_kegiatan">Rp0</span>
      </li>
      <li class="list-group-item">
        <strong>Kekurangan Kegiatan:</strong>
        <span class="pull-right label label-danger" id="kurang_kegiatan">Rp0</span>
      </li>
      <!-- <li class="list-group-item">
        <strong>Keterangan Kegiatan:</strong>
        <span class="pull-right" id="ket_kegiatan">-</span>
      </li> -->
    </ul>
  </div>
</div>


    <!-- Tabel Pembayaran Kegiatan -->
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Pembayaran Kegiatan</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive" id="example1_wrapper">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Setoran Kegiatan</th>
                            <th>Tanggal</th>
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
                            <td><?php echo $data['nis']; ?></td>
                            <td><?php echo $data['nama_siswa']; ?></td>
                            <td align="right"><?php echo rupiah($data['setor_kegiatan']); ?></td>
                            <td><?php echo date("d/M/Y", strtotime($data['tgl'])); ?></td>
                            <td><?php echo $data['petugas']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Pembayaran SPP -->
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Pembayaran SPP</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive" id="example2_wrapper">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Setoran SPP</th>
                            <th>Tanggal</th>
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
                            <td><?php echo $data['nis']; ?></td>
                            <td><?php echo $data['nama_siswa']; ?></td>
                            <td align="right"><?php echo rupiah($data['setor_spp']); ?></td>
                            <td><?php echo date("d/M/Y", strtotime($data['tgl'])); ?></td>
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
                        return;
                    }

                    $('#jenis_kelas').text(data.jenis_kelas);
                    $('#total_spp').text(data.total_spp);
                    $('#kurang_spp').text(data.kurang_spp);
                    $('#total_kegiatan').text(data.total_kegiatan);
                    $('#kurang_kegiatan').text(data.kurang_kegiatan);

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

                    $('#btn-cetak-summary').attr('href', 'petugas/pembayaran_siswa/cetak-pembayaran-siswa.php?' + params);
                }
            });
        }
    });
</script>