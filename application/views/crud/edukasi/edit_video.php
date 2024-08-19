<div class="row">
	<div class="col-lg-12">
		<div class="p-5">
			<div class="text-center">
				<h1 class="h4 text-gray-900 mb-4"><?php echo $title; ?></h1>
			</div>
			<?php foreach($edukasi as $baris){ ?>
			<form class="user" action="<?php echo base_url('ikanku/update_video');?>"method="post">
				<div class="form-group">
					<input type="hidden" name="idedukasi" value="<?php echo $baris->idedukasi; ?>">
					<label>Judul</label>
					<input type="text" class="form-control form-control" id="judul" name="judul" placeholder="Judul" value="<?php echo $baris->judul; ?>" required="">
				</div>
				<div class="form-group">
					<label>Sumber</label>
					<input type="text" class="form-control form-control" id="sumber" name="sumber" placeholder="Sumber" value="<?php echo $baris->sumber; ?>" required="">
				</div>
				<div class="form-group">
					<label>Link</label>
					<input type="textarea" class="form-control form-control" id="link" name="link" placeholder="Link" value="<?php echo $baris->link; ?>" required="">
				</div>
				<button type="submit" class="btn btn-success btn-icon-split">
					<span class="text">Simpan</span>
				</button>
			</form><?php } ?><hr>
			<div class="text-center"><a class="small" href="<?php echo base_url('ikanku/video')?>">Kembali</a>
			</div>
		</div>
	</div>
</div>
