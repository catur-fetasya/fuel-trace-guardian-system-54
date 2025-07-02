<!-- Content Header (Page header) -->
<?php 
$data_nama = $_SESSION["ses_nama"];
?>

<section class="content-header">
    <h1>
        Infaq -
        <small>Kesanggupan Infaq Siswa</small>
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="index.php">
                <i class="fa fa-home"></i>
                <b>eBASTAPA</b>
            </a>
        </li>
    </ol>
</section>
<!-- Main content -->

<section class="content">

    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Daftar Kesanggupan Infaq Siswa Aktif</h3>
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
                            <th>Uang Kesanggupan Infaq</th>
                            <th>Petugas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        // Ambil semua siswa aktif
                        $sql_siswa = $koneksi->query("SELECT * FROM tb_siswa WHERE status='Aktif' ORDER BY nama_siswa ASC");
                        while ($siswa = $sql_siswa->fetch_assoc()) {
                            // Cek apakah sudah ada isian kesanggupan infaq
                            $sql_infaq = $koneksi->query("SELECT * FROM tb_infaq_kesanggupan WHERE nis='".$siswa['nis']."' ORDER BY tgl DESC LIMIT 1");
                            $infaq = $sql_infaq->fetch_assoc();

                            $tgl = isset($infaq['tgl']) ? date("d/M/Y", strtotime($infaq['tgl'])) : '<span class="label label-warning">Belum diisi</span>';
                            $uang = isset($infaq['infaq_kesanggupan']) ? rupiah($infaq['infaq_kesanggupan']) : '<span class="label label-warning">Belum diisi</span>';
                            $petugas = isset($infaq['petugas']) && $infaq['petugas'] != '' ? $infaq['petugas'] : '<span class="label label-warning">Belum diisi</span>';
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $siswa['nis']; ?></td>
                            <td><?php echo $siswa['nama_siswa']; ?></td>
                            <td><?php echo $tgl; ?></td>
                            <td align="right"><?php echo $uang; ?></td>
                            <td><?php echo $petugas; ?></td>
                            <td>
                                <a href="?page=edit_kesanggupan_infaq&nis=<?php echo $siswa['nis']; ?>" title="Ubah Kesanggupan"
                                 class="btn btn-success btn-sm">
                                    <i class="glyphicon glyphicon-edit"></i> Ubah
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