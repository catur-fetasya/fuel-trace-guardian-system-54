<?php

    if(isset($_GET['kode'])){
        $sql_cek = "SELECT * FROM tb_kelas WHERE id_kelas='".$_GET['kode']."'";
        $query_cek = mysqli_query($koneksi, $sql_cek);
        $data_cek = mysqli_fetch_array($query_cek,MYSQLI_BOTH);
    }
?>

<section class="content-header">
	<h1>
		Master Data
		<small>Kelas</small>
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
					<h3 class="box-title">Ubah Kelas</h3>
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
						<input type='hidden' class="form-control" name="id_kelas" value="<?php echo $data_cek['id_kelas']; ?>"
						 readonly/>

						<div class="form-group">
							<label>Kelas</label>
							<input class="form-control" name="kelas" value="<?php echo $data_cek['kelas']; ?>" required/>
						</div>
						<div class="form-group">
							<label>Jenis Kelas</label>
							<select name="jenis_kelas" class="form-control" required>
								<option value="">-- Pilih Jenis Kelas --</option>
								<option value="REGULER" <?php if($data_cek['jenis_kelas']=='REGULER'){echo 'selected';} ?>>REGULER</option>
								<option value="PLUS" <?php if($data_cek['jenis_kelas']=='PLUS'){echo 'selected';} ?>>PLUS</option>
							</select>
						</div>

					</div>
					<!-- /.box-body -->

					<div class="box-footer">
						<input type="submit" name="Ubah" value="Ubah" class="btn btn-success">
						<a href="?page=MyApp/data_kelas" class="btn btn-warning">Batal</a>
					</div>
				</form>
			</div>
			<!-- /.box -->
</section>

<?php

if (isset ($_POST['Ubah'])){
    //mulai proses ubah
    $sql_ubah = "UPDATE tb_kelas SET
        kelas='".$_POST['kelas']."',
        jenis_kelas='".$_POST['jenis_kelas']."'
        WHERE id_kelas='".$_POST['id_kelas']."'";
    $query_ubah = mysqli_query($koneksi, $sql_ubah);

    if ($query_ubah) {
        echo "<script>
        Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_kelas';
            }
        })</script>";
        }else{
        echo "<script>
        Swal.fire({title: 'Ubah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_kelas';
            }
        })</script>";
    }
}

