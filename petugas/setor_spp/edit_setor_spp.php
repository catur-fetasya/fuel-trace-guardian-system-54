<?php

if(isset($_GET['kode'])){
    $sql_cek = "SELECT sk.*, s.nama_siswa FROM tb_setor_spp sk 
        LEFT JOIN tb_siswa s ON sk.nis=s.nis 
        WHERE sk.id_setor_spp='".$_GET['kode']."'";
    $query_cek = mysqli_query($koneksi, $sql_cek);
    $data_cek = mysqli_fetch_array($query_cek,MYSQLI_BOTH);
}

$tanggal = date("Y-m-d");
?>

<section class="content-header">
    <h1>
        Transaksi
        <small>Ubah Setoran Uang SPP</small>
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
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Setoran Uang SPP</h3>
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

                        <input type="hidden" class="form-control" name="id_setor_spp" value="<?php echo $data_cek['id_setor_spp']; ?>" readonly/>

                        <div class="form-group">
                            <label>Siswa</label>
                            <select name="nis" id="nis" class="form-control select2" style="width: 100%;" required>
                                <option value="">-- Pilih --</option>
                                <?php
                                $query = "select * from tb_siswa";
                                $hasil = mysqli_query($koneksi, $query);
                                while ($row = mysqli_fetch_array($hasil)) {
                                ?>
                                <option value="<?php echo $row['nis'] ?>" <?=$data_cek['nis']==$row['nis'] ? "selected" : null ?>>
                                    <?php echo $row['nama_siswa'] ?>
                                </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Setoran Uang SPP</label>
                            <input type="text" class="form-control" id="setor_spp" name="setor_spp" value="Rp <?php echo number_format(($data_cek['setor_spp']),0,'','.')?>"/>
                        </div>

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <input type="submit" name="Ubah" value="Ubah" class="btn btn-success">
                        <a href="?page=data_setor_spp" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
            <!-- /.box -->
</section>

<?php

if (isset ($_POST['Ubah'])){
    $setor_spp = preg_replace("/[^0-9]/", "", $_POST['setor_spp']);

    $sql_ubah = "UPDATE tb_setor_spp SET
        nis='".$_POST['nis']."',
        setor_spp='".$setor_spp."',
        tgl='".$tanggal."'
        WHERE id_setor_spp='".$_POST['id_setor_spp']."'";
    $query_ubah = mysqli_query($koneksi, $sql_ubah);
    mysqli_close($koneksi);

    if ($query_ubah) {
        echo "<script>
        Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_setor_spp';
            }
        })</script>";
    }else{
        echo "<script>
        Swal.fire({title: 'Ubah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_setor_spp';
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