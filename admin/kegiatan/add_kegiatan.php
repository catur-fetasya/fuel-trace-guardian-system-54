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
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">Tambah KEGIATAN</h3>
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
							<label>Jenis KEGIATAN</label>
							<input type="text" name="jenis_kegiatan" id="jenis_kegiatan" class="form-control" placeholder="Jenis KEGIATAN" required>
						</div>
						<div class="form-group">
							<label>Tahun KEGIATAN</label>
							<input type="number" name="tahun_kegiatan" id="tahun_kegiatan" class="form-control" placeholder="Tahun KEGIATAN" min="2000" max="2099" required>
						</div>
						<div class="form-group">
							<label>Besaran (Rupiah)</label>
							<input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Contoh: 75000" min="0" required>
						</div>
					</div>
					<!-- /.box-body -->

					<div class="box-footer">
						<input type="submit" name="Simpan" value="Simpan" class="btn btn-info">
						<a href="?page=MyApp/data_kegiatan" class="btn btn-warning">Batal</a>
					</div>
				</form>
			</div>
			<!-- /.box -->
</section>

<?php

    if (isset ($_POST['Simpan'])){
        $sql_simpan = "INSERT INTO tb_kegiatan (jenis_kegiatan, tahun_kegiatan, jumlah) VALUES ('".$_POST['jenis_kegiatan']."', '".$_POST['tahun_kegiatan']."', '".$_POST['jumlah']."')";
        $query_simpan = mysqli_query($koneksi, $sql_simpan);
        mysqli_close($koneksi);

    if ($query_simpan){
      echo "<script>
      Swal.fire({title: 'Tambah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
      }).then((result) => {
          if (result.value) {
              window.location = 'index.php?page=MyApp/data_kegiatan';
          }
      })</script>";
      }else{
      echo "<script>
      Swal.fire({title: 'Tambah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
      }).then((result) => {
          if (result.value) {
              window.location = 'index.php?page=MyApp/add_kegiatan';
          }
      })</script>";
    }
  }

