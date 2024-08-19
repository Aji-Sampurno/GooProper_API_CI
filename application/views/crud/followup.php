<div class="container-fluid">
    <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 text-center">
        <?php if ($status == "3"): ?>
            <?php foreach($followup as $baris):?>
                <div class="col mb-5">
                    <div class="card h-100">
                        <!--<img src="<?php echo $baris->Img1 ?>" class="card-img-top" alt="..."  style="height: 16rem; object-fit: cover;">-->
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $baris -> NamaListing ?></h5>
                            <small><?php echo $baris -> Deskripsi ?></small><br>
                            <span class="badge badge-pill badge-success"><?php echo $baris -> Tanggal ?></span><br><br>
                            <a href="<?php echo base_url('GooProper/addfollowup/'.$baris->IdListing)?>"class="btn btn-sm btn-primary">Follow Up</a>
                            <a href="<?php echo base_url('GooProper/detail/'.$baris->IdListing)?>"class="btn btn-sm btn-success">Detail</a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        <?php else: ?>
            <?php foreach($followupadmin as $baris):?>
                <div class="col mb-5">
                    <div class="card h-100">
                        <!--<img src="<?php echo $baris->Img1 ?>" class="card-img-top" alt="..."  style="height: 16rem; object-fit: cover;">-->
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $baris -> NamaListing ?></h5>
                            <small><?php echo $baris -> NamaBuyer ?></small><br>
                            <span class="badge badge-pill badge-success"><?php echo $baris -> Tanggal ?></span><br><br>
                            <a href="<?php echo base_url('GooProper/addfollowup/'.$baris->IdListing)?>"class="btn btn-sm btn-primary">Follow Up</a>
                            <a href="<?php echo base_url('GooProper/detail/'.$baris->IdListing)?>"class="btn btn-sm btn-success">Detail</a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        <?php endif ?>
        
    </div>
</div>
