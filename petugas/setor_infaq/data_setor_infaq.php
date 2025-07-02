<!-- Content Header (Page header) -->
<?php 
$data_nama = $_SESSION["ses_nama"];
?>

<section class="content-header">
    <h1>
        Transaksi
        <small>Setoran Infaq</small>
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
            <i class="icon fa fa-info"></i> Total Setoran Infaq</h4>
        <?php
            $sql = $koneksi->query("SELECT SUM(infaq_pembayaran) as total FROM tb_setor_infaq");
            $data = $sql->fetch_assoc();
          ?>
        <h3>
            <?php echo rupiah($data['total']); ?>
        </h3>
    </div>

    <div class="box box-primary">
        <div class="box-header">
            <a href="?page=add_setor_infaq" class="btn btn-primary">
                <i class="glyphicon glyphicon-plus"></i> Tambah Setoran Infaq</a>
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
                            <th>Setoran Infaq</th>
                            <th>Petugas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            $sql = $koneksi->query("SELECT si.*, s.nama_siswa FROM tb_setor_infaq si 
                                LEFT JOIN tb_siswa s ON si.nis = s.nis 
                                ORDER BY si.tgl DESC, si.id_infaq_pembayaran DESC");
                            while ($data = $sql->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $data['nis']; ?></td>
                            <td><?php echo $data['nama_siswa']; ?></td>
                            <td><?php echo date("d/M/Y", strtotime($data['tgl'])); ?></td>
                            <td align="right"><?php echo rupiah($data['infaq_pembayaran']); ?></td>
                            <td><?php echo $data['petugas']; ?></td>
                            <td>
                                <a href="?page=edit_setor_infaq&kode=<?php echo $data['id_infaq_pembayaran']; ?>" title="Ubah"
                                 class="btn btn-success btn-sm">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <a href="?page=del_setor_infaq&kode=<?php echo $data['id_infaq_pembayaran']; ?>" onclick="return confirm('Apakah anda yakin hapus data ini ?')"
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