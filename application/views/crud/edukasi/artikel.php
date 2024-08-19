<div class="card shadow mb-4">
	<div class="card-header py-3 py-3 d-flex flex-row align-items-center justify-content-between">
		<h6 class="m-0 fint-weight-bold text-primary"><?php echo $title; ?></h6>
        <a href="<?php echo base_url('ikanku/tambahedukasi')?>" class="btn btn-success btn-icon-split">
			<span class="text">Tambah Artikel</span>
		</a>
	</div>
	<div class="card-body"><div class="table-responsive">
		<?php echo $this->session->flashdata('pesan');?>
		<table class="table table-bordered" id="dataTabe" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th>No</th>
					<th>Judul</th>
					<th>Isi</th>
					<th>Gambar</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
                <?php $no = 1;
					foreach($edukasi as $baris){
				?>
				<tr>
					<td width='20px'><?php echo $no++ ?></td>
					<td><?php echo $baris -> judul; ?></td>
					<td><?php echo $baris -> isi; ?></td>
					<td><img src="<?php echo $baris->gambar; ?>" width="50"></td>
					<td>
					    <a href="<?php echo base_url('Ikanku/edit_edukasi/'.$baris->idedukasi) ?>">Edit</a>
					    <a href="<?php echo base_url('Ikanku/hapus_edukasi/'.$baris->idedukasi) ?>">Hapus</a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
    </div>
</div>
