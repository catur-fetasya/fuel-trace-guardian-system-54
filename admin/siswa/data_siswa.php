<section class="content-header">
	<h1>
		Master Data
		<small>Siswa</small>
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
<!-- Main content -->
<section class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<a href="?page=MyApp/add_siswa" title="Tambah Data" class="btn btn-primary">
				<i class="glyphicon glyphicon-plus"></i> Tambah Data</a>
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
		<div class="box-body">
			<div class="table-responsive">
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>NIS</th>
							<th>Nama</th>
							<th>JK</th>
							<th>Kelas</th>
							<th>Status</th>
                            <th>Th Masuk</th>
                            <th>Status Kelas</th>
                            <th>Aksi</th>
						</tr>
					</thead>
					<tbody>

						<?php
                  $no = 1;
                  include "inc/kelas_helper.php";
                  $sql = $koneksi->query("SELECT s.nis, s.nama_siswa, s.jekel, s.status, s.th_masuk, s.naik_kelas, k.kelas 
                  from tb_siswa s inner join tb_kelas k on s.id_kelas=k.id_kelas 
                  order by kelas asc, nis asc");
                  while ($data= $sql->fetch_assoc()) {
                ?>

						<tr>
							<td>
								<?php echo $no++; ?>
							</td>
							<td>
								<?php echo $data['nis']; ?>
							</td>
							<td>
								<?php echo $data['nama_siswa']; ?>
							</td>
							<td>
								<?php echo $data['jekel']; ?>
							</td>
							<td>
								<?php echo $data['kelas']; ?>
							</td>

							<?php $warna = $data['status']  ?>
							<td>
								<?php if ($warna == 'Aktif') { ?>
								<span class="label label-primary">Aktif</span>
								<?php } elseif ($warna == 'Lulus') { ?>
								<span class="label label-success">Lulus</span>
								<?php } elseif ($warna == 'Pindah') { ?>
								<span class="label label-danger">Pindah</span>
							</td>
							<?php } ?>

                            <td>
                                <?php echo $data['th_masuk']; ?>
                            </td>
                            
                            <td>
                                <span class="label <?php echo $data['naik_kelas'] == 0 ? 'label-warning' : 'label-success'; ?>">
                                    <?php echo getKeteranganKelas($data['naik_kelas']); ?>
                                </span>
                            </td>

                            <td>
                                <a href="?page=MyApp/edit_siswa&kode=<?php echo $data['nis']; ?>" title="Ubah"
                                 class="btn btn-success btn-sm">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <a href="?page=MyApp/naik_kelas&nis=<?php echo $data['nis']; ?>" title="Naik Kelas"
                                 class="btn btn-warning btn-sm">
                                    <i class="glyphicon glyphicon-arrow-up"></i>
                                </a>
                                <a href="?page=MyApp/del_siswa&kode=<?php echo $data['nis']; ?>" onclick="return confirm('Yakin Hapus Data Ini ?')"
                                 title="Hapus" class="btn btn-danger btn-sm">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </td>
						</tr>
						<?php
                  }
                ?>
					</tbody>

				</table>
			</div>
		</div>
	</div>
</section>