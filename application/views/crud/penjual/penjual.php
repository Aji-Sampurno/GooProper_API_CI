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
					<th>Nama Penjual</th>
					<th>Nama Toko</th>
					<th>Alamat</th>
					<th>No Telepon</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
                <?php $no = 1;
					foreach($penjual as $baris){
				?>
				<tr>
					<td width='20px'><?php echo $no++ ?></td>
					<td><?php echo $baris -> nama; ?></td>
					<td><?php echo $baris -> namatoko; ?></td>
					<td><?php echo $baris -> alamat; ?></td>
					<td><?php echo $baris -> telp; ?></td>
					<td>
						<a href="<?php echo base_url('Ikanku/edit_penjual/'.$baris->idproduk) ?>">Blokir Penjual</a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
    </div>
</div>
