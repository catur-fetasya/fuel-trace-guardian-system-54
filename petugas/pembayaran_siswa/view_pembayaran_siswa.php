<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Status Pembayaran Siswa
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
				<form action="?page=data_pembayaran_siswa" method="post" enctype="multipart/form-data">
					<div class="box-body">

						<div class="form-group">
							<label>Siswa</label>
							<select name="nis" id="nis" class="form-control select2" style="width: 100%;">
								<option selected="selected">-- Pilih --</option>
								<?php
                        // ambil data dari database
                        $query = "select * from tb_siswa where status='Aktif'";
                        $hasil = mysqli_query($koneksi, $query);
                        while ($row = mysqli_fetch_array($hasil)) {
                        ?>
								<option value="<?php echo $row['nis'] ?>">
									<?php echo $row['nis'] ?>
									-
									<?php echo $row['nama_siswa'] ?>
								</option>
								<?php
                        }
                        ?>
							</select>
						</div>

						<div class="form-group">
							<label>Total Setoran SPP</label>
							<input type="text" id="total_spp" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Kekurangan Pembayaran SPP</label>
							<input type="text" id="kurang_spp" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Total Setoran Kegiatan</label>
							<input type="text" id="total_kegiatan" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Kekurangan Pembayaran Kegiatan</label>
							<input type="text" id="kurang_kegiatan" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Jenis Kelas</label>
							<input type="text" id="jenis_kelas" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Keterangan SPP</label>
							<input type="text" id="ket_spp" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Keterangan Kegiatan</label>
							<input type="text" id="ket_kegiatan" class="form-control" readonly>
						</div>

					</div>
					<!-- /.box-body -->

					<div class="box-footer">
    <input type="submit" id="btnLihat" name="Lihat" value="Lihat Rangkuman" class="btn btn-primary" disabled>
</div>
				</form>
			</div>
			<!-- /.box -->
</section>

<script src="././bootstrap/lookup.js"></script>
<script>
	$(document).ready(function() {
        // Tombol disabled saat awal
        $('#btnLihat').prop('disabled', true);

        $('#nis').change(function() {
            var nis = $(this).val();
            if(nis === "-- Pilih --" || nis === "") {
                // Jika belum pilih siswa, disable tombol dan kosongkan field
                $('#btnLihat').prop('disabled', true);
                $('.form-control').not('#nis').val('');
                return;
            }
            $.ajax({
                url: "plugins/proses-pembayaran-siswa.php",
                method: "POST",
                dataType: "json",
                data: { nis: nis },
                success: function(data) {
                    if(data.notif_spp || data.notif_kegiatan) {
                        if(data.notif_spp) alert(data.notif_spp);
                        if(data.notif_kegiatan) alert(data.notif_kegiatan);

                        $('#jenis_kelas').val('');
                        $('#total_spp').val('');
                        $('#kurang_spp').val('');
                        $('#total_kegiatan').val('');
                        $('#kurang_kegiatan').val('');
                        $('#ket_spp').val('');
                        $('#ket_kegiatan').val('');
                        $('#btnLihat').prop('disabled', true); // Disable tombol jika gagal
                        return;
                    }

                    $('#jenis_kelas').val(data.jenis_kelas);
                    $('#total_spp').val(data.total_spp);
                    $('#kurang_spp').val(data.kurang_spp);
                    $('#total_kegiatan').val(data.total_kegiatan);
                    $('#kurang_kegiatan').val(data.kurang_kegiatan);

                    if(data.selisih_spp > 0) {
                        $('#ket_spp').val('Kelebihan pembayaran SPP: Rp ' + Math.abs(data.selisih_spp).toLocaleString('id-ID'));
                    } else if(data.selisih_spp < 0) {
                        $('#ket_spp').val('Kekurangan pembayaran SPP: ' + data.kurang_spp);
                    } else {
                        $('#ket_spp').val('Pembayaran SPP sudah lunas');
                    }

                    if(data.selisih_kegiatan > 0) {
                        $('#ket_kegiatan').val('Kelebihan pembayaran Kegiatan: Rp ' + Math.abs(data.selisih_kegiatan).toLocaleString('id-ID'));
                    } else if(data.selisih_kegiatan < 0) {
                        $('#ket_kegiatan').val('Kekurangan pembayaran Kegiatan: ' + data.kurang_kegiatan);
                    } else {
                        $('#ket_kegiatan').val('Pembayaran Kegiatan sudah lunas');
                    }

                    $('#btnLihat').prop('disabled', false); // Enable tombol jika sukses
                },
                error: function() {
                    $('#btnLihat').prop('disabled', true); // Disable tombol jika AJAX error
                }
            });
        });
    });
</script>