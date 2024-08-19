<div class="container-fluid">
    <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 text-center">
        <?php foreach($agen as $baris):?>
            <div class="col mb-5">
                <div class="card h-100">
                    <img src="<?php echo $baris->Photo ?>" class="card-img-top" alt="..."  style="height: 16rem; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $baris -> Nama ?></h5>
                        <small><?php echo $baris -> Username ?></small><br>
                        <a href="<?php echo base_url('GooProper//'.$baris->IdAgen)?>"class="btn btn-sm btn-primary">Follow</a>
                        <a href="<?php echo base_url('GooProper/detailagen/'.$baris->IdAgen)?>"class="btn btn-sm btn-success">Detail</a>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>