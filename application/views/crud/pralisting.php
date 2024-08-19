<div class="container-fluid">
    <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 text-center">
        <?php foreach($listing as $baris):?>
            <div class="col mb-5">
                <div class="card h-100">
                    <img src="<?php echo $baris->Img1 ?>" class="card-img-top" alt="..."  style="height: 16rem; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $baris -> NamaListing ?></h5>
                        <small><?php echo $baris -> Deskripsi ?></small><br>
                        <span class="badge badge-pill badge-success">Rp. <?php echo number_format( $baris -> Harga); ?></span><br><br>
                        <a href="<?php echo base_url('GooProper/addlisting/'.$baris->IdPraListing)?>"class="btn btn-sm btn-primary">Approve</a>
                        <a href="<?php echo base_url('GooProper/detailpralisting/'.$baris->IdPraListing)?>"class="btn btn-sm btn-success">Detail</a>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
