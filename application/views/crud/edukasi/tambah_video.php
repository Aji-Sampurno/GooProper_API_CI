<?php
$getId = $this->session->userdata('session_id');
$getUser = $this->session->userdata('session_user');
?>
<div class="row">
	<div class="col-lg-12"> 
	<div class="p-5"> 
	<div class="text-center"> 
	<h1 class="h4 text-gray-900 mb-4"><?php echo $title; ?></h1></div> 
	<?php echo form_open_multipart ('ikanku/tambah_video');?>
		<div class="form-group">
			<label>Judul</label>
			<input type="text" class="form-control" id="judul" name="judul" placeholder="Judul" required=""> 
		</div>
		<div class="form-group">
			<label>Sumber</label>
			<input type="text" class="form-control" id="sumber" name="sumber" placeholder="Sumber" required=""> 
		</div>
		<div class="form-group"> 
			<label>Link</label>
			<textarea id="link" name="link" class="form-control" placeholder="Isi Link Video" rows="4" required=""></textarea> 
		</div> 
		<button type="submit" class="btn btn-success btn-icon-split">
			<span class="text">Tambah</span>
		</button>
	</form><hr> 
	<div class="text-center"> 
	<a class="small" href="<?php echo base_url('ikanku/video')?>">Kembali</a>
</div></div></div></div>