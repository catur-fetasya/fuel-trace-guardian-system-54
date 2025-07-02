<!-- Content Header (Page header) -->

<?php 
$data_nama = $_SESSION["ses_nama"];
date_default_timezone_set("Asia/Jakarta"); 
$tanggal = date("Y-m-d");

// Hitung saldo SPP
$sql2 = $koneksi->query("SELECT SUM(setor_spp) as setor_spp FROM tb_setor_spp");
$data2 = $sql2->fetch_assoc();
$setor_spp = $data2['setor_spp'] ?? 0;

$sql = $koneksi->query("SELECT SUM(jumlah_tarik_spp) as total FROM tb_penarikan_spp");
$data = $sql->fetch_assoc();
$total_tarik = $data['total'] ?? 0;

$saldo_spp = $setor_spp - $total_tarik;
?>


<section class="content-header">
    <h1>
        Transaksi
        <small>Penarikan Uang SPP</small>
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
                    <h3 class="box-title">Tambah Penarikan Uang SPP</h3>
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
                            <label>Petugas</label>
                            <input type="text" class="form-control" name="petugas" value="<?php echo $data_nama; ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Jumlah SPP Tersedia</label>
                            <input type="text" class="form-control" value="<?php echo rupiah($saldo_spp); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Penarikan SPP</label>
                            <input type="text" name="jumlah_tarik_spp" id="jumlah_tarik_spp" class="form-control" placeholder="Jumlah penarikan" required>
                        </div>

                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
                        </div>

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <input type="submit" name="Simpan" value="Tarik" class="btn btn-primary">
                        <a href="?page=data_tarik_spp" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
            <!-- /.box -->
</section>

<?php

if (isset ($_POST['Simpan'])){
    $jumlah_tarik_spp = preg_replace("/[^0-9]/", "", $_POST['jumlah_tarik_spp']);

    $sql_simpan = "INSERT INTO tb_penarikan_spp (nis, jumlah_tarik_spp, tgl, keterangan, petugas) VALUES (
        '-', -- NIS diisi '-' karena tidak relevan
        '".$jumlah_tarik_spp."',
        '".$tanggal."',
        '".$_POST['keterangan']."',
        '".$data_nama."')";
    $query_simpan = mysqli_query($koneksi, $sql_simpan);
    mysqli_close($koneksi);

    if ($query_simpan) {
        echo "<script>
        Swal.fire({title: 'Penarikan Uang SPP Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_tarik_spp';
            }
        })</script>";
    }else{
        echo "<script>
        Swal.fire({title: 'Penarikan Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=add_tarik_spp';
            }
        })</script>";
    }
}
?>

<script type="text/javascript">
    var tarik = document.getElementById('jumlah_tarik_spp');
    tarik.addEventListener('keyup', function (e) {
        tarik.value = formatTarik(this.value, 'Rp ');
    });
    function formatTarik(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            tarik = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            separator = sisa ? '.' : '';
            tarik += separator + ribuan.join('.');
        }
        tarik = split[1] != undefined ? tarik + ',' + split[1] : tarik;
        return prefix == undefined ? tarik : (tarik ? 'Rp ' + tarik : '');
    }
</script>