<div class="row">
	<div class="col-lg-12"> 
    	<div class="p-0"> 
        	<?php echo form_open_multipart ('GooProper/addfollowup');?>
        	    <div class="form-group">
        			<label>Nama Lengkap</label>
        			<input type="text" class="form-control" id="namalengkap" name="namalengkap" placeholder="Nama Lengkap" required=""> 
        		</div>
        		<div class="form-group">
        		    <label>Nomor Hp</label>
        		    <input type="number" class="form-control" id="nohp" name="nohp" placeholder="08XXXXXXX" required=""> 
        		</div>
        		<div class="form-group">
        			<label>Sumber Follow Up</label>
        			<input type="text" class="form-control" id="sumber" name="sumber" placeholder="Sumber Follow Up" required=""> 
        		</div>
        		<div class="form-group">
        			<label>Tanggal Follow Up</label>
        			<input type="date" class="form-control" id="tglfollowup" name="tglfollowup" placeholder="Tanggal Follow Up" required=""> 
        		</div>
        		<div class="form-group">
        			<label>Jam Follow Up</label>
        			<input type="time" class="form-control" id="jamfollowup" name="jamfollowup" placeholder="Jam Follow Up" required=""> 
        		</div>
        		<div class="from-group">
        		    <label>Status Follow Up</label>
        		</div>
        		<div class="row">
                    <div class="col-xl-4 col-md-4 col-sm-4 col-4 mb-2">
                        <input type="checkbox" name="chat" id="chat" value="1" <?php echo set_checkbox('chat', '1'); ?> />
                        <label for="chat">Chat</label>
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-4 col-4 mb-2">
                        <input type="checkbox" name="survei" id="survei" value="1" <?php echo set_checkbox('survei', '1'); ?> />
                        <label for="survei">Survei</label>
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-4 col-4 mb-2">
                        <input type="checkbox" name="tawar" id="tawar" value="1" <?php echo set_checkbox('tawar', '1'); ?> />
                        <label for="tawar">Tawar</label>
                    </div>
                </div>
        		<div class="row">
                    <div class="col-xl-8 col-md-8 col-sm-8 col-8 mb-2">
                        <input type="checkbox" name="lokasi" id="lokasi" value="1" <?php echo set_checkbox('lokasi', '1'); ?> />
                        <label for="lokasi">Cari Lokasi Lain</label>
                    </div>
                    <div class="col-xl-4 col-md-4 col-sm-4 col-4 mb-2">
                        <input type="checkbox" name="deal" id="deal" value="1" <?php echo set_checkbox('deal', '1'); ?> />
                        <label for="deal">Deal</label>
                    </div>
                </div>
        		<div class="form-group"> 
        			<label>Selfie</label>
        			<input type="file" name="selfie" id="selfie" size="20" required="" />
        		</div>
        		<div class="form-group" id="selfiePreview" style="display: none;">
                    <img id="preview" src="#" alt="Preview Selfie" style="max-width: 200px; max-height: 200px;" />
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function () {
                        $("#selfie").change(function () {
                            readURL1(this);
                        });
                        function readURL1(input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                
                                reader.onload = function (e) {
                                    $("#selfiePreview").show();
                                    $("#preview").attr("src", e.target.result);
                                }
                                
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
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