<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Info Infaq Siswa
		<small>Pencarian</small>
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
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Cari Siswa</h3>
				</div>
				<!-- form start -->
				<form action="" method="post" enctype="multipart/form-data" id="formCariSiswa">
					<div class="box-body">
						<div class="form-group">
							<label>Siswa</label>
							<select name="nis" id="nis" class="form-control select2" style="width: 100%;" required onchange="document.getElementById('formCariSiswa').submit();">
								<option value="">-- Pilih --</option>
								<?php
                                $query = "SELECT * FROM tb_siswa WHERE status='Aktif' ORDER BY nama_siswa ASC";
                                $hasil = mysqli_query($koneksi, $query);
                                while ($row = mysqli_fetch_array($hasil)) {
                                ?>
								<option value="<?php echo $row['nis'] ?>" <?php if(isset($_POST['nis']) && $_POST['nis']==$row['nis']) echo "selected"; ?>>
									<?php echo $row['nis'] ?> - <?php echo $row['nama_siswa'] ?>
								</option>
								<?php } ?>
							</select>
						</div>
					</div>
				</form>
			</div>
			<!-- /.box -->

			<?php if(isset($_POST['nis']) && $_POST['nis']!="") {
                $nis = $_POST['nis'];

                // Ambil data siswa
                $sql_siswa = $koneksi->query("SELECT * FROM tb_siswa WHERE nis='$nis'");
                $siswa = $sql_siswa->fetch_assoc();

                // Ambil kesanggupan infaq
                $sql_kesanggupan = $koneksi->query("SELECT infaq_kesanggupan FROM tb_infaq_kesanggupan WHERE nis='$nis' ORDER BY tgl DESC, id_infaq_kesanggupan DESC LIMIT 1");
                $kesanggupan = $sql_kesanggupan->fetch_assoc();

                // Ambil total setor infaq
                $sql_setor = $koneksi->query("SELECT SUM(infaq_pembayaran) as total_setor FROM tb_setor_infaq WHERE nis='$nis'");
                $data_setor = $sql_setor->fetch_assoc();
                $total_setor = $data_setor['total_setor'] ? $data_setor['total_setor'] : 0;
            ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Informasi Infaq Siswa</h3>
                </div>
                <div class="box-body">
                    <p><b>NIS:</b> <?php echo $siswa['nis']; ?></p>
                    <p><b>Nama:</b> <?php echo $siswa['nama_siswa']; ?></p>
                    <hr>
                    <!-- A. Pengisian Kesanggupan Infaq -->
                    <p><b>A. Pengisian Kesanggupan Infaq:</b><br>
                        <?php
                        if($kesanggupan) {
                            echo rupiah($kesanggupan['infaq_kesanggupan']);
                            $nilai_kesanggupan = $kesanggupan['infaq_kesanggupan'];
                        } else {
                            echo '<span class="label label-warning">Belum mengisi kesanggupan infaq</span>';
                            $nilai_kesanggupan = 0;
                        }
                        ?>
                    </p>
                    <!-- B. Nilai Infaq Saat Ini -->
                    <p><b>B. Nilai Infaq Saat Ini:</b><br>
                        <?php echo rupiah($total_setor); ?>
                    </p>
                    <!-- C. Kekurangan Infaq -->
                    <p><b>C. Kekurangan Infaq:</b><br>
                        <?php
                        if($nilai_kesanggupan > 0 && $total_setor < $nilai_kesanggupan) {
                            echo rupiah($nilai_kesanggupan - $total_setor);
                        } else {
                            echo "-";
                        }
                        ?>
                    </p>
                    <!-- D. Kelebihan Infaq -->
                    <p><b>D. Kelebihan Infaq:</b><br>
                        <?php
                        if($nilai_kesanggupan > 0 && $total_setor > $nilai_kesanggupan) {
                            echo rupiah($total_setor - $nilai_kesanggupan);
                        } else {
                            echo "-";
                        }
                        ?>
                    </p>
                    <?php if($nilai_kesanggupan > 0) { ?>
                        <a href="index.php?page=data_info_infaq&nis=<?php echo $siswa['nis']; ?>" class="btn btn-primary">
                            Lihat Detail Pembayaran
                        </a>
                        <!-- <a href="petugas/info_infaq/print_infaq.php?nis=<?php echo $siswa['nis']; ?>" target="_blank" class="btn btn-success">
                            <i class="fa fa-print"></i> Cetak
                        </a> -->
                    <?php } else { ?>
                        <button class="btn btn-primary" disabled>
                            Lihat Detail Pembayaran
                        </button>
                        <!-- <button class="btn btn-success" disabled>
                            <i class="fa fa-print"></i> Cetak
                        </button> -->
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>