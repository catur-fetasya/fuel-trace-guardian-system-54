<?php
include "inc/kelas_helper.php";

if(isset($_GET['nis'])) {
    $nis = $_GET['nis'];
    
    // Ambil data siswa
    $qSiswa = mysqli_query($koneksi, "SELECT * FROM tb_siswa WHERE nis='$nis'");
    $dSiswa = mysqli_fetch_assoc($qSiswa);
    
    if(!$dSiswa) {
        echo "<script>
        Swal.fire({title: 'Siswa tidak ditemukan',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_siswa';
            }
        })</script>";
        exit;
    }
    
    $status_sekarang = $dSiswa['naik_kelas'];
    $status_baru = $status_sekarang == 0 ? 1 : 0;
    $pesan = $status_baru == 1 ? 'menaikkan ke kelas berikutnya' : 'menurunkan ke kelas sebelumnya';
    
    if(isset($_POST['konfirmasi'])) {
        if(updateStatusNaikKelas($koneksi, $nis, $status_baru)) {
            $pesan_sukses = $status_baru == 1 ? 'Siswa berhasil naik kelas' : 'Status kelas siswa berhasil direset';
            echo "<script>
            Swal.fire({title: '$pesan_sukses',text: '',icon: 'success',confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value) {
                    window.location = 'index.php?page=MyApp/data_siswa';
                }
            })</script>";
        } else {
            echo "<script>
            Swal.fire({title: 'Gagal mengubah status kelas',text: '',icon: 'error',confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value) {
                    window.location = 'index.php?page=MyApp/data_siswa';
                }
            })</script>";
        }
    }
?>

<section class="content-header">
    <h1>
        Master Data
        <small>Naik Kelas Siswa</small>
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
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Konfirmasi Naik Kelas</h3>
                </div>
                <div class="box-body">
                    <div class="alert alert-info">
                        <h4><i class="icon fa fa-info"></i> Informasi Siswa</h4>
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>NIS</strong></td>
                                <td>: <?php echo $dSiswa['nis']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nama</strong></td>
                                <td>: <?php echo $dSiswa['nama_siswa']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status Kelas</strong></td>
                                <td>: <span class="label <?php echo $status_sekarang == 0 ? 'label-warning' : 'label-success'; ?>">
                                    <?php echo getKeteranganKelas($status_sekarang); ?>
                                </span></td>
                            </tr>
                            <tr>
                                <td><strong>Tahun Masuk</strong></td>
                                <td>: <?php echo $dSiswa['th_masuk']; ?></td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h4><i class="icon fa fa-warning"></i> Peringatan!</h4>
                        <p>Anda akan <strong><?php echo $pesan; ?></strong> untuk siswa <strong><?php echo $dSiswa['nama_siswa']; ?></strong>.</p>
                        <p>Setelah perubahan status kelas:</p>
                        <ul>
                            <li>Transaksi baru akan dicatat dengan status kelas yang baru</li>
                            <li>Data laporan akan terpisah berdasarkan status kelas</li>
                            <li>Perubahan ini akan mempengaruhi perhitungan SPP dan Kegiatan</li>
                        </ul>
                        <p><strong>Apakah Anda yakin ingin melanjutkan?</strong></p>
                    </div>
                    
                    <form method="post">
                        <div class="box-footer">
                            <button type="submit" name="konfirmasi" class="btn btn-warning">
                                <i class="fa fa-check"></i> Ya, <?php echo $pesan; ?>
                            </button>
                            <a href="index.php?page=MyApp/data_siswa" class="btn btn-default">
                                <i class="fa fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
} else {
    echo "<script>window.location = 'index.php?page=MyApp/data_siswa';</script>";
}
?>