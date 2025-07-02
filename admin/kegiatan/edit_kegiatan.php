<?php

    if(isset($_GET['kode'])){
        $sql_cek = "SELECT * FROM tb_kegiatan WHERE no='".$_GET['kode']."'";
        $query_cek = mysqli_query($koneksi, $sql_cek);
        $data_cek = mysqli_fetch_array($query_cek,MYSQLI_BOTH);
    }
?>

<section class="content-header">
    <h1>
        Master Data
        <small>KEGIATAN</small>
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
                    <h3 class="box-title">Ubah kegiatan</h3>
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
                        <input type='hidden' class="form-control" name="no" value="<?php echo $data_cek['no']; ?>" readonly/>

                        <div class="form-group">
                            <label>Jenis kegiatan</label>
                            <select name="jenis_kegiatan" class="form-control" required>
                                <option value="">-- Pilih Jenis Kegiatan --</option>
                                <option value="KEGIATAN REGULER" <?php if($data_cek['jenis_kegiatan']=='KEGIATAN REGULER'){echo 'selected';} ?>>KEGIATAN REGULER</option>
                                <option value="KEGIATAN PLUS" <?php if($data_cek['jenis_kegiatan']=='KEGIATAN PLUS'){echo 'selected';} ?>>KEGIATAN PLUS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tahun kegiatan</label>
                            <input type="number" class="form-control" name="tahun_kegiatan" value="<?php echo $data_cek['tahun_kegiatan']; ?>" min="2000" max="2099" required/>
                        </div>
                        <div class="form-group">
                            <label>Besaran (Rupiah)</label>
                            <input type="number" class="form-control" name="jumlah" value="<?php echo $data_cek['jumlah']; ?>" min="0" required/>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <input type="submit" name="Ubah" value="Ubah" class="btn btn-success">
                        <a href="?page=MyApp/data_kegiatan" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
            <!-- /.box -->
</section>

<?php

if (isset ($_POST['Ubah'])){
    //mulai proses ubah
    $sql_ubah = "UPDATE tb_kegiatan SET
        jenis_kegiatan='".$_POST['jenis_kegiatan']."',
        tahun_kegiatan='".$_POST['tahun_kegiatan']."',
        jumlah='".$_POST['jumlah']."'
        WHERE no='".$_POST['no']."'";
    $query_ubah = mysqli_query($koneksi, $sql_ubah);

    if ($query_ubah) {
        echo "<script>
        Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_kegiatan';
            }
        })</script>";
        }else{
        echo "<script>
        Swal.fire({title: 'Ubah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_kegiatan';
            }
        })</script>";
    }
}

