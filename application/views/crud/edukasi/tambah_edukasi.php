<?php
$getId = $this->session->userdata('session_id');
$getUser = $this->session->userdata('session_user');
?>
<div class="row">
	<div class="col-lg-12"> 
	<div class="p-5"> 
	<div class="text-center"> 
	<h1 class="h4 text-gray-900 mb-4"><?php echo $title; ?></h1></div> 
	<?php echo form_open_multipart ('ikanku/tambah_edukasi');?>
		<div class="form-group">
			<label>Judul</label>
			<input type="text" class="form-control" id="judul" name="judul" placeholder="Judul" required=""> 
		</div>
		<div class="form-group">
			<label>Sumber</label>
			<input type="text" class="form-control" id="sumber" name="sumber" placeholder="Sumber" required=""> 
		</div>
		<div class="form-group">
			<label>Kategori</label>
			<select id="kategori" class="form-control" name="kategori" require>
				<option value="0">Pilih Kategori</option>
				<option value="1">Budidaya</option>
				<option value="2">Penyakit</option>
                <option value="3">Olahan</option>
			</select>
		</div>
		<div class="form-group"> 
			<label>Isi</label>
			<textarea id="isi" name="isi"class="form-control" placeholder="Isi Edukasi" rows="4" required=""></textarea> 
		</div> 
		<div class="form-group"> 
			<label>Gambar Edukasi</label>
			<input type="file" name="filegambar" id="filegambar" size="20" required="" />
		</div> 
		<button type="submit" class="btn btn-success btn-icon-split">
			<span class="text">Tambah</span>
		</button>
	</form><hr> 
	<div class="text-center"> 
	<a class="small" href="<?php echo base_url('ikanku/artikel')?>">Kembali</a>
</div></div></div></div>