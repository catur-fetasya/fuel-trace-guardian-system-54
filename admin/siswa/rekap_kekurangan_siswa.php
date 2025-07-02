<?php
//include "../../inc/koneksi.php";
//include "../../inc/rupiah.php";
?>

<section class="content-header">
    <h1>
        Rekap Kekurangan Pembayaran Siswa Aktif
    </h1>
</section>

<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Daftar Siswa Aktif & Kekurangan Pembayaran</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Jenis Kelas</th>
                            <th>Kekurangan SPP</th>
                            <th>Kekurangan Kegiatan</th>
                            <th>Kekurangan Infaq</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        // Ambil semua siswa aktif beserta kelasnya
                        $sql = $koneksi->query("SELECT s.nis, s.nama_siswa, k.jenis_kelas, s.th_masuk 
                            FROM tb_siswa s 
                            INNER JOIN tb_kelas k ON s.id_kelas = k.id_kelas 
                            WHERE s.status='Aktif'
                            ORDER BY k.jenis_kelas, s.nama_siswa ASC");
                        while ($siswa = $sql->fetch_assoc()) {
                            $nis = $siswa['nis'];
                            $nama_siswa = $siswa['nama_siswa'];
                            $jenis_kelas = $siswa['jenis_kelas'];
                            $th_masuk = $siswa['th_masuk'];

                            // --- Kekurangan SPP ---
                            $jenis_spp = ($jenis_kelas == "PLUS") ? "SPP PLUS" : "SPP REGULER";
                            $qSpp = $koneksi->query("SELECT jumlah FROM tb_spp WHERE jenis_spp='$jenis_spp' AND tahun_spp='$th_masuk' LIMIT 1");
                            $dSpp = $qSpp->fetch_assoc();
                            $jumlah_spp = $dSpp ? $dSpp['jumlah'] : 0;
                            $qSetorSpp = $koneksi->query("SELECT SUM(setor_spp) as total FROM tb_setor_spp WHERE nis='$nis'");
                            $dSetorSpp = $qSetorSpp->fetch_assoc();
                            $total_spp = $dSetorSpp['total'] ? $dSetorSpp['total'] : 0;
                            $kurang_spp = $jumlah_spp - $total_spp;
                            if ($kurang_spp < 0) $kurang_spp = 0;

                            // --- Kekurangan Kegiatan ---
                            $jenis_kegiatan = ($jenis_kelas == "PLUS") ? "KEGIATAN PLUS" : "KEGIATAN REGULER";
                            $qKegiatan = $koneksi->query("SELECT jumlah FROM tb_kegiatan WHERE jenis_kegiatan='$jenis_kegiatan' AND tahun_kegiatan='$th_masuk' LIMIT 1");
                            $dKegiatan = $qKegiatan->fetch_assoc();
                            $jumlah_kegiatan = $dKegiatan ? $dKegiatan['jumlah'] : 0;
                            $qSetorKegiatan = $koneksi->query("SELECT SUM(setor_kegiatan) as total FROM tb_setor_kegiatan WHERE nis='$nis'");
                            $dSetorKegiatan = $qSetorKegiatan->fetch_assoc();
                            $total_kegiatan = $dSetorKegiatan['total'] ? $dSetorKegiatan['total'] : 0;
                            $kurang_kegiatan = $jumlah_kegiatan - $total_kegiatan;
                            if ($kurang_kegiatan < 0) $kurang_kegiatan = 0;

                            // --- Kekurangan Infaq ---
                            // Ambil kesanggupan terakhir
                            $qKesanggupan = $koneksi->query("SELECT infaq_kesanggupan FROM tb_infaq_kesanggupan WHERE nis='$nis' ORDER BY tgl DESC, id_infaq_kesanggupan DESC LIMIT 1");
                            $dKesanggupan = $qKesanggupan->fetch_assoc();
                            if ($dKesanggupan) {
                                $infaq_kesanggupan = $dKesanggupan['infaq_kesanggupan'];
                                // Total setor infaq
                                $qSetorInfaq = $koneksi->query("SELECT SUM(infaq_pembayaran) as total FROM tb_setor_infaq WHERE nis='$nis'");
                                $dSetorInfaq = $qSetorInfaq->fetch_assoc();
                                $total_infaq = $dSetorInfaq['total'] ? $dSetorInfaq['total'] : 0;
                                $kurang_infaq = $infaq_kesanggupan - $total_infaq;
                                if ($kurang_infaq < 0) $kurang_infaq = 0;
                                $tampil_infaq = rupiah($kurang_infaq);
                            } else {
                                $tampil_infaq = "<span style='color:red'>Belum mengisi kesanggupan</span>";
                            }
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($nis); ?></td>
                            <td><?php echo htmlspecialchars($nama_siswa); ?></td>
                            <td><?php echo htmlspecialchars($jenis_kelas); ?></td>
                            <td align="right"><?php echo rupiah($kurang_spp); ?></td>
                            <td align="right"><?php echo rupiah($kurang_kegiatan); ?></td>
                            <td align="right"><?php echo $tampil_infaq; ?></td>
                            <td align="center">
                                <a href="?page=render_pembayaran_siswa&nis=<?php echo urlencode($nis); ?>&nama=<?php echo urlencode($nama_siswa); ?>" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>