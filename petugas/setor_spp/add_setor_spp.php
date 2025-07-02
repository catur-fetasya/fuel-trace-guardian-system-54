<!-- Content Header (Page header) -->

<?php 
$data_nama = $_SESSION["ses_nama"];
date_default_timezone_set("Asia/Jakarta"); 
$tanggal = date("Y-m-d");
?>

<section class="content-header">
    <h1>
        Transaksi
        <small>Setoran Uang SPP</small>
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
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Setoran Uang SPP</h3>
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
                <!-- form start -->
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="box-body">

                        <div class="form-group">
                            <label>Siswa</label>
                            <select name="nis" id="nis" class="form-control select2" style="width: 100%;" required>
                                <option selected="selected">-- Pilih --</option>
                                <?php
                                $query = "select * from tb_siswa where status='Aktif'";
                                $hasil = mysqli_query($koneksi, $query);
                                while ($row = mysqli_fetch_array($hasil)) {
                                ?>
                                <option value="<?php echo $row['nis'] ?>">
                                    <?php echo $row['nis'] ?> - <?php echo $row['nama_siswa'] ?>
                                </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Setoran Uang SPP</label>
                            <input type="text" name="setor_spp" id="setor_spp" class="form-control" placeholder="Jumlah setoran" required>
                        </div>

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <input type="submit" name="Simpan" value="Setor" class="btn btn-primary">
                        <a href="?page=data_setor_spp" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
            <!-- /.box -->
</section>

<?php
include "inc/kelas_helper.php";

if (isset ($_POST['Simpan'])){
    $setor_spp = preg_replace("/[^0-9]/", "", $_POST['setor_spp']);
    
    // Ambil status naik kelas siswa
    $kelas_ke = getStatusNaikKelas($koneksi, $_POST['nis']);

    $sql_simpan = "INSERT INTO tb_setor_spp (nis, setor_spp, tgl, petugas, kelas_ke) VALUES (
        '".$_POST['nis']."',
        '".$setor_spp."',
        '".$tanggal."',
        '".$data_nama."',
        '".$kelas_ke."')";
    $query_simpan = mysqli_query($koneksi, $sql_simpan);
    mysqli_close($koneksi);

    if ($query_simpan) {
        echo "<script>
        Swal.fire({title: 'Setoran Uang SPP Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_setor_spp';
            }
        })</script>";
    }else{
        echo "<script>
        Swal.fire({title: 'Setoran Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=add_setor';
            }
        })</script>";
    }
}
?>

<script type="text/javascript">
    var setor = document.getElementById('setor_spp');
    setor.addEventListener('keyup', function (e) {
        setor.value = formatsetor(this.value, 'Rp ');
    });
    function formatsetor(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            setor = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            separator = sisa ? '.' : '';
            setor += separator + ribuan.join('.');
        }
        setor = split[1] != undefined ? setor + ',' + split[1] : setor;
        return prefix == undefined ? setor : (setor ? 'Rp ' + setor : '');
    }
</script>