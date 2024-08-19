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
					<th>Judul</th>
					<th>Isi/Link</th>
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
				</tr>
				<?php } ?>
			</tbody>
		</table>
    </div>
</div>
