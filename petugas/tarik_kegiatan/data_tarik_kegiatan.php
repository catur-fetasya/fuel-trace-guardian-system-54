<!-- Content Header (Page header) -->
<?php 
$data_nama = $_SESSION["ses_nama"];
// Total penarikan
$sql = $koneksi->query("SELECT SUM(jumlah_tarik_kegiatan) as total FROM tb_penarikan_kegiatan");
$data = $sql->fetch_assoc();
$total_tarik = $data['total'] ?? 0;

// Total Kegiatan
$sql2 = $koneksi->query("SELECT SUM(setor_kegiatan) as setor_kegiatan FROM tb_setor_kegiatan");
$data2 = $sql2->fetch_assoc();
$setor_kegiatan = $data2['setor_kegiatan'] ?? 0;

// Saldo Kegiatan
$saldo_kegiatan = $setor_kegiatan - $total_tarik;
?>

<section class="content-header">
    <h1>
        Transaksi
        <small>Penarikan Uang Kegiatan</small>
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
            <i class="icon fa fa-info"></i> Total Penarikan Uang Kegiatan</h4>
        <h3>
            <?php echo rupiah($total_tarik); ?>
        </h3>
        <h4>
            <i class="icon fa fa-info"></i> Total Jumlah Uang Kegiatan Tersedia</h4>
        <h3>
            <?php echo rupiah($saldo_kegiatan); ?>
        </h3>
    </div>

    <div class="box box-primary">
        <div class="box-header">
            <a href="?page=add_tarik_kegiatan" class="btn btn-primary">
                <i class="glyphicon glyphicon-plus"></i> Tambah Penarikan Uang Kegiatan</a>
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
                            <th>Jumlah Penarikan Kegiatan</th>
                            <th>Keterangan</th>
                            <th>Petugas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            $sql = $koneksi->query("SELECT * FROM tb_penarikan_kegiatan ORDER BY tgl DESC, id_penarikan_kegiatan DESC");
                            while ($data = $sql->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date("d/M/Y", strtotime($data['tgl'])); ?></td>
                            <td align="right"><?php echo rupiah($data['jumlah_tarik_kegiatan']); ?></td>
                            <td><?php echo $data['keterangan']; ?></td>
                            <td><?php echo $data['petugas']; ?></td>
                            <td>
                                <a href="?page=edit_tarik_kegiatan&kode=<?php echo $data['id_penarikan_kegiatan']; ?>" class="btn btn-success btn-sm" title="Edit">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <a href="?page=del_tarik_kegiatan&kode=<?php echo $data['id_penarikan_kegiatan']; ?>" onclick="return confirm('Yakin hapus data ini?')" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>