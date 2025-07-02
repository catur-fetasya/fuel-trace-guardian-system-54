<!-- Content Header (Page header) -->

<?php 
$data_nama = $_SESSION["ses_nama"];
date_default_timezone_set("Asia/Jakarta"); 
$tanggal = date("Y-m-d");
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

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Setoran Infaq</h3>
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
                                // Hanya siswa yang sudah mengisi kesanggupan
                                $query = "SELECT s.nis, s.nama_siswa FROM tb_siswa s
                                    INNER JOIN tb_infaq_kesanggupan k ON s.nis = k.nis
                                    WHERE s.status='Aktif'
                                    GROUP BY s.nis
                                    ORDER BY s.nama_siswa ASC";
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
                            <label>Setoran Infaq</label>
                            <input type="text" name="infaq_pembayaran" id="infaq_pembayaran" class="form-control" placeholder="Jumlah setoran" required>
                        </div>

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <input type="submit" name="Simpan" value="Setor" class="btn btn-primary">
                        <a href="?page=data_setor_infaq" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
            <!-- /.box -->
</section>

<?php

if (isset ($_POST['Simpan'])){
    $infaq_pembayaran = preg_replace("/[^0-9]/", "", $_POST['infaq_pembayaran']);

    $sql_simpan = "INSERT INTO tb_setor_infaq (nis, infaq_pembayaran, tgl, petugas) VALUES (
        '".$_POST['nis']."',
        '".$infaq_pembayaran."',
        '".$tanggal."',
        '".$data_nama."')";
    $query_simpan = mysqli_query($koneksi, $sql_simpan);

    if ($query_simpan) {
        echo "<script>
        Swal.fire({title: 'Setoran Infaq Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_setor_infaq';
            }
        })</script>";
    }else{
        echo "<script>
        Swal.fire({title: 'Setoran Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=add_setor_infaq';
            }
        })</script>";
    }
}
?>

<script type="text/javascript">
    var setor = document.getElementById('infaq_pembayaran');
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