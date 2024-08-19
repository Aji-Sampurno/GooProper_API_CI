<?php
$getId = $this->session->userdata('session_id');
$getUser = $this->session->userdata('session_user');
$getGrup = $this->session->userdata('session_grup');
?>
<div class="container-fluid">
    <div class="card">
    <div class="card-header">
        <?php echo $title; ?>
    </div>
    <div class="card-body">
        <?php foreach($barang as $brg):?>
        <div class="row">
            <div class="col-md-4">
                <img src="<?php echo base_url().'/gambar/'.$brg->gambar ?>" class="card-img-top" alt="...">
            </div>
            <div class="col-md-8">
                <table class="table">
                    <tr>
                        <td>Nama Barang</td>
                        <td>
                            <strong>
                                <?php echo $brg -> nama_barang ?>
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td>Kategori Barang</td>
                        <td>
                            <strong>
                                <?php echo $brg -> kategori ?>
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td>Deskripsi Barang</td>
                        <td>
                            <strong>
                                <?php echo $brg -> deskripsi ?>
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td>Stok Barang</td>
                        <td>
                            <strong>
                                <?php echo $brg -> stok ?>
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td>Harga Barang</td>
                        <td>
                            <strong>
                                Rp. <?php echo number_format( $brg -> harga,0,',','.'); ?>
                            </strong>
                        </td>
                    </tr>
                </table>
                <form class="user" action="<?php echo base_url('Ikanku/keranjang/'.$brg->id_barang)?>" method="post"> 
                        <input type="number" id="jumlah" name="jumlah" value="1">
                        <input type="hidden" name="idpelanggan" value="<?php echo $getId; ?>"> 
					<input type="submit" name="btn_log" value="Tambah Keranjang" class="btn btn-sm btn-primary">
                    <a href="<?php echo base_url('Ikanku/detail/'.$brg->id_barang)?>"class="btn btn-sm btn-success">Detail</a>
                    <a href="<?php echo base_url('Ikanku')?>"class="btn btn-sm btn-danger">Kembali</a>
                </form><hr>
            </div>
        </div>
        <?php endforeach ?>
    </div>
    </div>
</div>