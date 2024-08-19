<div class="container-fluid">
    <div class="col mb-3">
        <div class="card" id="map" style="width: 100%; height: 400px;"></div>
    </div>
    <div class="col mb-3">
        <div class="row">
            <div class="col-6">
                Listing Sold
            </div>
            <div class="col-6 text-right">
                <a href="<?php echo base_url('GooProper/listingnew/')?>"class="btn btn-sm btn-primary">See All</a>
            </div>
        </div>
    </div>
    <div class="col-md-12 d-none d-md-block">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php $chunked_listing = array_chunk($listingsold, 4); ?>
                <?php foreach($chunked_listing as $index => $chunk): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="row">
                            <?php foreach($chunk as $baris): ?>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-12 text-center">
                                    <div class="card mb-5">
                                        <img src="<?php echo $baris->Img1 ?>" class="card-img-top" alt="..." style="height: 16rem; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $baris->NamaListing ?></h5>
                                            <small><?php echo $baris->Deskripsi ?></small><br>
                                            <span class="badge badge-pill badge-success">Rp. <?php echo number_format($baris->Harga); ?></span><br><br>
                                            <a href="<?php echo base_url('GooProper/detail/' . $baris->IdListing) ?>" class="btn btn-sm btn-success">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div class="col-12 d-block d-md-none text-center">
        <div id="listingCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php $first = true; foreach($listingsold as $baris): ?>
                    <div class="carousel-item <?php if($first) echo 'active'; ?>">
                        <div class="card">
                            <img src="<?php echo $baris->Img1 ?>" class="card-img-top" alt="..." style="height: 16rem; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $baris->NamaListing ?></h5>
                                <small><?php echo $baris->Deskripsi ?></small><br>
                                <span class="badge badge-pill badge-success">Rp. <?php echo number_format($baris->Harga); ?></span><br><br>
                                <a href="<?php echo base_url('GooProper/detail/'.$baris->IdListing) ?>" class="btn btn-sm btn-success">Detail</a>
                            </div>
                        </div>
                    </div>
                <?php $first = false; endforeach; ?>
            </div>
            <a class="carousel-control-prev" href="#listingCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#listingCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div class="col mb-3 mt-3">
        <div class="row">
            <div class="col-6">
                Listing Terpopuler
            </div>
            <div class="col-6 text-right">
                <a href="<?php echo base_url('GooProper/listingpopuler/')?>"class="btn btn-sm btn-primary">See All</a>
            </div>
        </div>
    </div>
    <div class="col-md-12 d-none d-md-block">
        <div id="populerCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php $chunked_listing = array_chunk($listingpopuler, 4); ?>
                <?php foreach($chunked_listing as $index => $chunk): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="row">
                            <?php foreach($chunk as $baris): ?>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-12 text-center">
                                    <div class="card mb-5">
                                        <img src="<?php echo $baris->Img1 ?>" class="card-img-top" alt="..." style="height: 16rem; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $baris->NamaListing ?></h5>
                                            <small><?php echo $baris->Deskripsi ?></small><br>
                                            <span class="badge badge-pill badge-success">Rp. <?php echo number_format($baris->Harga); ?></span><br><br>
                                            <a href="<?php echo base_url('GooProper/tambahfollowup/' . $baris->IdListing) ?>" class="btn btn-sm btn-primary">Follow Up</a>
                                            <a href="<?php echo base_url('GooProper/detail/' . $baris->IdListing) ?>" class="btn btn-sm btn-success">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            <a class="carousel-control-prev" href="#populerCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#populerCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div class="col-12 d-block d-md-none text-center">
        <div id="listingpopulerCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php $first = true; foreach($listingpopuler as $baris): ?>
                    <div class="carousel-item <?php if($first) echo 'active'; ?>">
                        <div class="card">
                            <img src="<?php echo $baris->Img1 ?>" class="card-img-top" alt="..." style="height: 16rem; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $baris->NamaListing ?></h5>
                                <small><?php echo $baris->Deskripsi ?></small><br>
                                <span class="badge badge-pill badge-success">Rp. <?php echo number_format($baris->Harga); ?></span><br><br>
                                <a href="<?php echo base_url('GooProper/tambahfollowup/'.$baris->IdListing) ?>" class="btn btn-sm btn-primary">Follow Up</a>
                                <a href="<?php echo base_url('GooProper/detail/'.$baris->IdListing) ?>" class="btn btn-sm btn-success">Detail</a>
                            </div>
                        </div>
                    </div>
                <?php $first = false; endforeach; ?>
            </div>
            <a class="carousel-control-prev" href="#listingpopulerCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#listingpopulerCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div class="col mb-3 mt-3">
        <div class="row">
            <div class="col-6">
                Listing Terbaru
            </div>
            <div class="col-6 text-right">
                <a href="<?php echo base_url('GooProper/listingnew/')?>"class="btn btn-sm btn-primary">See All</a>
            </div>
        </div>
    </div>
    <div class="col-md-12 d-none d-md-block">
        <div id="newCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php $chunked_listing = array_chunk($listing, 4); ?>
                <?php foreach($chunked_listing as $index => $chunk): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="row">
                            <?php foreach($chunk as $baris): ?>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-12 text-center">
                                    <div class="card mb-5">
                                        <img src="<?php echo $baris->Img1 ?>" class="card-img-top" alt="..." style="height: 16rem; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $baris->NamaListing ?></h5>
                                            <small><?php echo $baris->Deskripsi ?></small><br>
                                            <span class="badge badge-pill badge-success">Rp. <?php echo number_format($baris->Harga); ?></span><br><br>
                                            <a href="<?php echo base_url('GooProper/tambahfollowup/' . $baris->IdListing) ?>" class="btn btn-sm btn-primary">Follow Up</a>
                                            <a href="<?php echo base_url('GooProper/detail/' . $baris->IdListing) ?>" class="btn btn-sm btn-success">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            <a class="carousel-control-prev" href="#newCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#newCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div class="col-12 d-block d-md-none text-center">
        <div id="listingnewCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php $first = true; foreach($listing as $baris): ?>
                    <div class="carousel-item <?php if($first) echo 'active'; ?>">
                        <div class="card">
                            <img src="<?php echo $baris->Img1 ?>" class="card-img-top" alt="..." style="height: 16rem; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $baris->NamaListing ?></h5>
                                <small><?php echo $baris->Deskripsi ?></small><br>
                                <span class="badge badge-pill badge-success">Rp. <?php echo number_format($baris->Harga); ?></span><br><br>
                                <a href="<?php echo base_url('GooProper/tambahfollowup/'.$baris->IdListing) ?>" class="btn btn-sm btn-primary">Follow Up</a>
                                <a href="<?php echo base_url('GooProper/detail/'.$baris->IdListing) ?>" class="btn btn-sm btn-success">Detail</a>
                            </div>
                        </div>
                    </div>
                <?php $first = false; endforeach; ?>
            </div>
            <a class="carousel-control-prev" href="#listingnewCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#listingnewCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div class="col mt-3"></div>
</div>
<script>
    function initMap() {
      var map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: -6.2088, lng: 106.8456 },
        zoom: 12
      });
      
      var marker = new google.maps.Marker({
        position: { lat: -6.2088, lng: 106.8456 },
        map: map,
        title: "Lokasi 1"
      });
      
      var infowindow = new google.maps.InfoWindow({
        content: "Nama Lokasi: Lokasi 1"
      });
      
      marker.addListener("click", function() {
        infowindow.open(map, marker);
      });
    }
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAVZitLJe0lrPxyqQl0hKw-ngRLZMtVgJc&callback=initMap" async defer></script>