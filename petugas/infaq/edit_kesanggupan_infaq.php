<?php
// Pastikan koneksi dan session sudah tersedia
$data_nama = $_SESSION["ses_nama"];
date_default_timezone_set("Asia/Jakarta");
$tanggal = date("Y-m-d");

// Ambil data siswa berdasarkan NIS dari parameter GET
if (isset($_GET['nis'])) {
    $nis = $_GET['nis'];
    // Ambil data siswa
    $sql_siswa = $koneksi->query("SELECT * FROM tb_siswa WHERE nis='$nis'");
    $siswa = $sql_siswa->fetch_assoc();

    // Ambil data infaq terakhir (jika ada)
    $sql_infaq = $koneksi->query("SELECT id_infaq_kesanggupan, nis, infaq_kesanggupan, tgl, petugas FROM tb_infaq_kesanggupan WHERE nis='$nis' ORDER BY tgl DESC, id_infaq_kesanggupan DESC LIMIT 1");
    $infaq = $sql_infaq->fetch_assoc();
    $id_infaq = isset($infaq['id_infaq_kesanggupan']) ? $infaq['id_infaq_kesanggupan'] : null;
} else {
    echo "<script>window.location='index.php?page=data_infaq';</script>";
    exit;
}

// Proses simpan perubahan
if (isset($_POST['Simpan'])) {
    $infaq_kesanggupan = preg_replace("/[^0-9]/", "", $_POST['infaq_kesanggupan']);
    $tgl = $tanggal;

    if ($id_infaq) {
        // Update data terakhir
        $sql_simpan = "UPDATE tb_infaq_kesanggupan SET infaq_kesanggupan='$infaq_kesanggupan', tgl='$tgl', petugas='$data_nama' WHERE id_infaq_kesanggupan='$id_infaq'";
    } else {
        // Insert data baru jika belum ada
        $sql_simpan = "INSERT INTO tb_infaq_kesanggupan (nis, infaq_kesanggupan, tgl, petugas) VALUES (
            '$nis',
            '$infaq_kesanggupan',
            '$tgl',
            '$data_nama'
        )";
    }
    $query_simpan = mysqli_query($koneksi, $sql_simpan);

    if ($query_simpan) {
        echo "<script>
        Swal.fire({title: 'Kesanggupan Infaq Berhasil Disimpan',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_infaq';
            }
        })</script>";
    } else {
        echo "<script>
        Swal.fire({title: 'Kesanggupan Infaq Gagal Disimpan',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_infaq';
            }
        })</script>";
    }
}
?>

<section class="content-header">
    <h1>
        Ubah Kesanggupan Infaq
        <small><?php echo $siswa['nama_siswa']; ?> (<?php echo $siswa['nis']; ?>)</small>
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

<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Ubah Kesanggupan Infaq</h3>
                </div>
                <form action="" method="post">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Nama Siswa</label>
                            <input type="text" class="form-control" value="<?php echo $siswa['nama_siswa']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>NIS</label>
                            <input type="text" class="form-control" value="<?php echo $siswa['nis']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Uang Kesanggupan Infaq</label>
                            <input type="text" name="infaq_kesanggupan" id="infaq_kesanggupan" class="form-control" placeholder="Masukkan nominal" required value="<?php echo isset($infaq['infaq_kesanggupan']) ? 'Rp '.number_format($infaq['infaq_kesanggupan'],0,'','.') : ''; ?>">
                        </div>
                    </div>
                    <div class="box-footer">
                        <input type="submit" name="Simpan" value="Simpan" class="btn btn-success">
                        <a href="index.php?page=data_infaq" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    var infaq = document.getElementById('infaq_kesanggupan');
    infaq.addEventListener('keyup', function (e) {
        infaq.value = formatRupiah(this.value, 'Rp ');
    });
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
    }
</script>