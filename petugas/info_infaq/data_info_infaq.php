<!-- Content Header (Page header) -->
<?php
    // Ambil NIS dari GET (karena tombol "Lihat Detail Pembayaran" mengirim via URL)
    $nis = isset($_GET["nis"]) ? $_GET["nis"] : '';
    if ($nis == '') {
        echo "<script>window.location='index.php?page=view_info_infaq';</script>";
        exit;
    }

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

<section class="content-header">
    <h1>
        Infaq
        <small>Detail Pembayaran Siswa</small>
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
<!-- Main content -->

<section class="content">

    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
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
                        echo 'Kekurangan Infaq: <b class="text-danger">'.rupiah($nominal_kesanggupan - $total_setor).'</b>';
                    } elseif($total_setor > $nominal_kesanggupan) {
                        echo 'Kelebihan Infaq: <b class="text-success">'.rupiah($total_setor - $nominal_kesanggupan).'</b>';
                    } else {
                        echo '<span class="text-primary">Infaq sudah sesuai dengan kesanggupan.</span>';
                    }
                }
            ?>
        </h4>
    </div>

    <div class="box box-primary">
        <div class="box-header">
            <a href="?page=info_pembayaran_infaq" class="btn btn-primary">
                <i class="glyphicon glyphicon-arrow-left"></i> Kembali</a>
				                        <a href="petugas/info_infaq/print_infaq.php?nis=<?php echo $siswa['nis']; ?>" target="_blank" class="btn btn-success">
                            <i class="fa fa-print"></i> Cetak
                        </a>
						
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove">
                    <i class="fa fa-remove"></i>
                </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
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