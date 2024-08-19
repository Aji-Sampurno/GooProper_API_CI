<div class="card shadow mb-4">
	<div class="card-header py-3 py-3 d-flex flex-row align-items-center justify-content-between">
		<h6 class="m-0 fint-weight-bold text-primary"><?php echo $title; ?></h6>
	</div>
	<div class="card-body"><div class="table-responsive">
		<?php echo $this->session->flashdata('pesan');?>
		<table class="table table-bordered" id="dataTabe" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama Produk</th>
					<th>Deskripsi</th>
					<th>Gambar</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
                <?php $no = 1;
					foreach($produk as $baris){
				?>
				<tr>
					<td width='20px'><?php echo $no++ ?></td>
					<td><?php echo $baris -> namaproduk; ?></td>
					<td><?php echo $baris -> deskripsi; ?></td>
					<td><img src="<?php echo $baris->gambarproduk; ?>" width="50"></td>
					<td>
						<a href="<?php echo base_url('Ikanku/hapus_produk/'.$baris->idproduk) ?>">Hapus Produk</a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
    </div>
</div>
