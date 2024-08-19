<div class="row">
	<div class="col-lg-12"> 
    	<div class="p-0"> 
        	<?php echo form_open_multipart ('GooProper/addpralisting');?>
        	    <div class="form-group">
        	        <label>Info Dasar</label>
        	    </div>
        		<div class="form-group">
        			<label>Nama Vendor</label>
        			<input type="text" class="form-control" id="namavendor" name="namavendor" placeholder="Nama Vendor" required=""> 
        		</div>
        		<div class="form-group">
        		    <label>Nomor Hp</label>
        		    <input type="number" class="form-control" id="nohp" name="nohp" placeholder="08XXXXXXX" required=""> 
        		</div>
        		<div class="form-group">
        			<label>NIK</label>
        			<input type="text" class="form-control" id="nik" name="nik" placeholder="NIK" required=""> 
        		</div>
        		<div class="form-group">
        			<label>Alamat</label>
        			<input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat Vendor" required=""> 
        		</div>
        		<div class="form-group">
        			<label>Tanggal Lahir</label>
        			<input type="date" class="form-control" id="tgllahir" name="tgllahir" placeholder="Tanggal Lahir Vendor" required=""> 
        		</div>
        		<div class="row">
                    <div class="col-xl-6 col-md-6 col-sm-6 col-6 mb-2">
                        <div class="form-group">
                			<label>No Rekening</label>
                			<input type="text" class="form-control" id="norek" name="norek" placeholder="No Rekening Vendor" required=""> 
                		</div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-6 col-6 mb-2">
                        <div class="form-group">
                			<label>Bank</label>
                			<input type="text" class="form-control" id="bank" name="bank" placeholder="Bank" required=""> 
                		</div>
                    </div>
                </div>
        		<div class="form-group">
        			<label>Atas Nama</label>
        			<input type="text" class="form-control" id="atasnama" name="atasnama" placeholder="Atas Nama" required=""> 
        		</div>
        		<div class="form-group">
        			<label>Spesifikasi Properti</label>
        		</div>
        		<div class="form-group">
        			<label>Jenis Properti</label>
        			<select class="form-control" id="jenis" name="jenis" placeholder="Jenis Properti" required="">
        			    <option value="">Jenis Properti</option>
        			    <option value="Rumah">Rumah</option> 
        			    <option value="Ruko">Ruko</option> 
        			    <option value="Tanah">Tanah</option> 
        			    <option value="Gudang">Gudang</option> 
        			    <option value="Ruang Usaha">Ruang Usaha</option> 
        			    <option value="Villa">Villa</option> 
        			    <option value="Apartemen">Apartemen</option> 
        			    <option value="Pabrik">Pabrik</option> 
        			    <option value="Kantor">Kantor</option> 
        			    <option value="Hotel">Hotel</option> 
        			    <option value="Kondohotel">Kondoholet</option>  
        			</select>
        		</div>
        		<div class="form-group">
        			<label>Nama Properti</label>
        			<input type="text" class="form-control" id="namaproperti" name="namaproperti" placeholder="Nama Properti" required=""> 
        		</div>
        		<div class="form-group">
        			<label>Alamat Properti</label>
        			<input type="text" class="form-control" id="alamatproperti" name="alamatproperti" placeholder="Alamat Properti" required=""> 
        		</div>
        		<div class="form-group">
        			<label>Hadap Bangunan</label>
        			<input type="text" class="form-control" id="hadap" name="hadap" placeholder="Hadap Bangunan"> 
        		</div>
        		<div class="form-group">
        			<label>Luas Bangunan</label>
        			<input type="number" class="form-control" id="luasbangunan" name="luasbangunan" placeholder="Luas Bangunan"> 
        		</div>
        		<div class="form-group">
        			<label>Luas Tanah</label>
        			<input type="number" class="form-control" id="luastanah" name="luastanah" placeholder="Luas Tanah" required=""> 
        		</div>
        		<div class="form-group">
        			<label>Jumlah Lantai</label>
        			<input type="number" class="form-control" id="lantai" name="lantai" placeholder="Jumlah Lantai"> 
        		</div>
        		<div class="form-group">
        		    <label>Detail Ruangan</label> 
        		</div>
        		<div class="row">
                    <div class="col-xl-6 col-md-6 col-sm-6 col-6 mb-2">
                        <div class="form-group">
                            <label>Kamar Tidur</label>
                            <input type="number" class="form-control" id="bed" name="bed" placeholder="Kamar Tidur"> 
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-6 col-6 mb-2">
                        <div class="form-group">
                            <label>Kamar Mandi</label>
                            <input type="number" class="form-control" id="bath" name="bath" placeholder="Kamar Mandi"> 
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-md-6 col-sm-6 col-6 mb-2">
                        <div class="form-group">
                            <label>Kamar Tidur Pembantu</label>
                            <input type="number" class="form-control" id="bedart" name="bedart" placeholder="Kamar Tidur Pembantu"> 
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-6 col-6 mb-2">
                        <div class="form-group">
                            <label>Kamar Mandi Pembantu</label>
                            <input type="number" class="form-control" id="bathart" name="bathart" placeholder="Kamar Mandi Pembantu"> 
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-md-6 col-sm-6 col-6 mb-2">
                        <div class="form-group">
                            <label>Garasi</label>
                            <input type="number" class="form-control" id="garasi" name="garasi" placeholder="Garasi"> 
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-6 col-6 mb-2">
                        <div class="form-group">
                            <label>Carpot</label>
                            <input type="number" class="form-control" id="carpot" name="carpot" placeholder="Carpot"> 
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-md-6 col-sm-6 col-6 mb-2">
                        <div class="form-group">
                            <label>Daya Listrik</label>
                            <input type="number" class="form-control" id="listrik" name="listrik" placeholder="Daya Listrik"> 
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-sm-6 col-6 mb-2">
                        <div class="form-group">
                            <label>Sumber Air</label>
                            <select class="form-control" id="sumberair" name="sumberair">
                                <option vale="">Sumber Air</option>
                                <option value="PAM atau PDAM">PAM atau PDAM</option>
                                <option value="Sumur Pompa">Sumur Pompa</option>
                                <option value="Sumur Bor">Sumur Bor</option>
                                <option value="Sumur Resapan">Sumur Resapan</option>
                                <option value="Sumur Galian">Sumur Galian</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Perabot</label>
                    <select id="perabot" class="form-control" name="perabot">
                        <option value="">Perabot</option>
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Keterangan Perabot</label>
                    <input type="text" class="form-control" id="ketperabot" name="ketperabot" placeholder="Keterangan Perabot"> 
                </div>
                <div class="form-group">
                    <label>Banner</label>
                    <select id="banner" class="form-control" name="banner" required>
                        <option value="">Banner</option>
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Ukuran Banner</label>
                    <select class="form-control" id="size" name="size">
                        <option value="">Ukuran Banner</option>
                        <option value="80 X 90">80 X 90</option>
                        <option value="100 X 125">100 X 125</option>
                        <option value="180 X 80">180 X 80</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Ukuran Lainnya</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <!-- Konten modal di sini, misalnya input tambahan -->
                                <div class="form-group">
                                    <label>Ukuran Lainnya</label>
                                    <input type="text" id="inputTambahan" class="form-control" name="input_tambahan">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                <script>
                    $(document).ready(function () {
                        var nilaiInputTambahan = "";
                        $("#size").change(function () {
                            var selectedValue = $(this).val();
                            if (selectedValue === "Lainnya") {
                                $("#myModal").modal('show');
                            } else {
                                $("#myModal").modal('hide');
                            }
                        });
                        $("#myModal").on('hidden.bs.modal', function () {
                            nilaiInputTambahan = $("#inputTambahan").val();
                            $("#size").val(nilaiInputTambahan);
                        });
                    });
                </script>
        		<div class="form-group">
        			<label>Status</label>
        			<select id="status" class="form-control" name="status" required>
        				<option value="">Status</option>
        				<option value="Jual">Jual</option>
        				<option value="Sewa">Sewa</option>
        			</select>
        		</div>
        		<div class="form-group">
        			<label>Harga</label>
        			<input type="number" class="form-control" id="harga" name="harga" placeholder="Harga" required=""> 
        		</div>
        		<div class="form-group">
        			<label>Keterangan</label>
        			<textarea type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" rows="4"></textarea> 
        		</div>
        		<div class="form-group"> 
        			<label>Gambar Properti</label>
        			<input type="file" name="filegambar" id="filegambar" size="20" required="" />
        		</div>
        		<div class="form-group" id="gambarPreview1" style="display: none;">
                    <img id="preview1" src="#" alt="Preview Gambar" style="max-width: 200px; max-height: 200px;" />
                </div>
        		<div id="gambar2" style="display: none;" class="form-group">
                    <label>Gambar Properti</label>
                    <input type="file" id="filegambar1" name="filegambar1" size="20">
                </div>
        		<div class="form-group" id="gambarPreview2" style="display: none;">
                    <img id="preview2" src="#" alt="Preview Gambar" style="max-width: 200px; max-height: 200px;" />
                </div>
        		<div id="gambar3" style="display: none;" class="form-group">
                    <label>Gambar Properti</label>
                    <input type="file" id="filegambar2" name="filegambar2" size="20">
                </div>
        		<div class="form-group" id="gambarPreview3" style="display: none;">
                    <img id="preview3" src="#" alt="Preview Gambar" style="max-width: 200px; max-height: 200px;" />
                </div>
        		<div id="gambar4" style="display: none;" class="form-group">
                    <label>Gambar Properti</label>
                    <input type="file" id="filegambar3" name="filegambar3" size="20">
                </div>
        		<div class="form-group" id="gambarPreview4" style="display: none;">
                    <img id="preview4" src="#" alt="Preview Gambar" style="max-width: 200px; max-height: 200px;" />
                </div>
        		<div id="gambar5" style="display: none;" class="form-group">
                    <label>Gambar Properti</label>
                    <input type="file" id="filegambar4" name="filegambar4" size="20">
                </div>
        		<div class="form-group" id="gambarPreview5" style="display: none;">
                    <img id="preview5" src="#" alt="Preview Gambar" style="max-width: 200px; max-height: 200px;" />
                </div>
        		<div id="gambar6" style="display: none;" class="form-group">
                    <label>Gambar Properti</label>
                    <input type="file" id="filegambar5" name="filegambar5" size="20">
                </div>
        		<div class="form-group" id="gambarPreview6" style="display: none;">
                    <img id="preview6" src="#" alt="Preview Gambar" style="max-width: 200px; max-height: 200px;" />
                </div>
        		<div id="gambar7" style="display: none;" class="form-group">
                    <label>Gambar Properti</label>
                    <input type="file" id="filegambar6" name="filegambar6" size="20">
                </div>
        		<div class="form-group" id="gambarPreview7" style="display: none;">
                    <img id="preview7" src="#" alt="Preview Gambar" style="max-width: 200px; max-height: 200px;" />
                </div>
        		<div id="gambar8" style="display: none;" class="form-group">
                    <label>Gambar Properti</label>
                    <input type="file" id="filegambar7" name="filegambar7" size="20">
                </div>
        		<div class="form-group" id="gambarPreview8" style="display: none;">
                    <img id="preview8" src="#" alt="Preview Gambar" style="max-width: 200px; max-height: 200px;" />
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function () {
                        $("#filegambar").change(function () {
                            readURL1(this);
                        });
                        function readURL1(input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                
                                reader.onload = function (e) {
                                    $("#gambarPreview1").show();
                                    $("#preview1").attr("src", e.target.result);
                                }
                                
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
                        $("#filegambar1").change(function () {
                            readURL2(this);
                        });
                        function readURL2(input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                
                                reader.onload = function (e) {
                                    $("#gambarPreview2").show();
                                    $("#preview2").attr("src", e.target.result);
                                }
                                
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
                        $("#filegambar2").change(function () {
                            readURL3(this);
                        });
                        function readURL3(input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                
                                reader.onload = function (e) {
                                    $("#gambarPreview3").show();
                                    $("#preview3").attr("src", e.target.result);
                                }
                                
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
                        $("#filegambar3").change(function () {
                            readURL4(this);
                        });
                        function readURL4(input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                
                                reader.onload = function (e) {
                                    $("#gambarPreview4").show();
                                    $("#preview4").attr("src", e.target.result);
                                }
                                
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
                        $("#filegambar4").change(function () {
                            readURL5(this);
                        });
                        function readURL5(input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                
                                reader.onload = function (e) {
                                    $("#gambarPreview5").show();
                                    $("#preview5").attr("src", e.target.result);
                                }
                                
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
                        $("#filegambar5").change(function () {
                            readURL6(this);
                        });
                        function readURL6(input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                
                                reader.onload = function (e) {
                                    $("#gambarPreview6").show();
                                    $("#preview6").attr("src", e.target.result);
                                }
                                
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
                        $("#filegambar6").change(function () {
                            readURL7(this);
                        });
                        function readURL7(input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                
                                reader.onload = function (e) {
                                    $("#gambarPreview7").show();
                                    $("#preview7").attr("src", e.target.result);
                                }
                                
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
                        $("#filegambar7").change(function () {
                            readURL8(this);
                        });
                        function readURL8(input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                
                                reader.onload = function (e) {
                                    $("#gambarPreview8").show();
                                    $("#preview8").attr("src", e.target.result);
                                }
                                
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
                        
                        $("#filegambar").on("input", function () {
                            var inputValue = $(this).val();
                            if (inputValue.trim() !== "") {
                                $("#gambar2").show();
                            } else {
                                $("#gambar2").hide();
                            }
                        });
                        $("#filegambar1").on("input", function () {
                            var inputValue = $(this).val();
                            if (inputValue.trim() !== "") {
                                $("#gambar3").show();
                            } else {
                                $("#gambar3").hide();
                            }
                        });
                        $("#filegambar2").on("input", function () {
                            var inputValue = $(this).val();
                            if (inputValue.trim() !== "") {
                                $("#gambar4").show();
                            } else {
                                $("#gambar4").hide();
                            }
                        });
                        $("#filegambar3").on("input", function () {
                            var inputValue = $(this).val();
                            if (inputValue.trim() !== "") {
                                $("#gambar5").show();
                            } else {
                                $("#gambar5").hide();
                            }
                        });
                        $("#filegambar4").on("input", function () {
                            var inputValue = $(this).val();
                            if (inputValue.trim() !== "") {
                                $("#gambar6").show();
                            } else {
                                $("#gambar6").hide();
                            }
                        });
                        $("#filegambar5").on("input", function () {
                            var inputValue = $(this).val();
                            if (inputValue.trim() !== "") {
                                $("#gambar7").show();
                            } else {
                                $("#gambar7").hide();
                            }
                        });
                        $("#filegambar6").on("input", function () {
                            var inputValue = $(this).val();
                            if (inputValue.trim() !== "") {
                                $("#gambar8").show();
                            } else {
                                $("#gambar8").hide();
                            }
                        });
                    });
                </script>
        		<button type="submit" class="btn btn-success btn-icon-split">
        			<span class="text">Submit</span>
        		</button>
        	</form><hr> 
        	<div class="text-center"> 
        	    <a class="small" href="<?php echo base_url('GooProper/dashboard')?>">Kembali</a>
            </div>
        </div>
    </div>
</div>