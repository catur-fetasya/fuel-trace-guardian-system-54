<!-- Content Header (Page header) -->
<?php 
$data_nama = $_SESSION["ses_nama"];
?>

<section class="content-header">
    <h1>
        Transaksi
        <small>Setoran Uang Kegiatan</small>
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

    <!-- /.box-header -->

    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4>
            <i class="icon fa fa-info"></i> Total Setoran Uang Kegiatan</h4>
        <?php
            $sql = $koneksi->query("SELECT SUM(setor_kegiatan) as total FROM tb_setor_kegiatan");
            $data = $sql->fetch_assoc();
          ?>
        <h3>
            <?php echo rupiah($data['total']); ?>
        </h3>
    </div>

    <div class="box box-primary">
        <div class="box-header">
            <a href="?page=add_setor_kegiatan" class="btn btn-primary">
                <i class="glyphicon glyphicon-plus"></i> Tambah Setoran Uang Kegiatan</a>
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
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Setoran Uang Kegiatan</th>
                            <th>Petugas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            $sql = $koneksi->query("SELECT sk.*, s.nama_siswa FROM tb_setor_kegiatan sk 
                                LEFT JOIN tb_siswa s ON sk.nis = s.nis 
                                ORDER BY sk.tgl DESC, sk.id_setor_kegiatan DESC");
                            while ($data = $sql->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $data['nis']; ?></td>
                            <td><?php echo $data['nama_siswa']; ?></td>
                            <td><?php echo date("d/M/Y", strtotime($data['tgl'])); ?></td>
                            <td align="right"><?php echo rupiah($data['setor_kegiatan']); ?></td>
                            <td><?php echo $data['petugas']; ?></td>
                            <td>
                                <a href="?page=edit_setor_kegiatan&kode=<?php echo $data['id_setor_kegiatan']; ?>" title="Ubah"
                                 class="btn btn-success btn-sm">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <a href="?page=del_setor_kegiatan&kode=<?php echo $data['id_setor_kegiatan']; ?>" onclick="return confirm('Apakah anda yakin hapus data ini ?')"
                                 title="Hapus" class="btn btn-danger btn-sm">
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