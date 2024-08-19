<div class="container-fluid">
    <?php foreach($listing as $baris):?>
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ul class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
                <li data-target="#myCarousel" data-slide-to="3"></li>
                <li data-target="#myCarousel" data-slide-to="4"></li>
                <li data-target="#myCarousel" data-slide-to="5"></li>
                <li data-target="#myCarousel" data-slide-to="6"></li>
                <li data-target="#myCarousel" data-slide-to="7"></li>
            </ul>
            
            <!-- Slides -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row gx-12 gx-lg-12 row-cols-12 row-cols-md-12 row-cols-xl-12 text-center">
                        <div class="col">
                            <img src="<?php echo $baris->Img1 ?>" alt="..." data-toggle="modal" data-target="#Img1Modal" style="object-fit: cover; cursor: pointer; max-width: auto; height: 100%;">
                            <div class="modal fade" id="Img1Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                          <img src="<?php echo $baris->Img1 ?>" alt="..." style="width: 100%;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row gx-12 gx-lg-12 row-cols-12 row-cols-md-12 row-cols-xl-12 text-center">
                        <div class="col">
                            <img src="<?php echo $baris->Img2 ?>" alt="..." data-toggle="modal" data-target="#Img2Modal" style="object-fit: cover; cursor: pointer; max-width: auto; height: 100%;">
                            <div class="modal fade" id="Img2Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                          <img src="<?php echo $baris->Img2 ?>" alt="..." style="width: 100%;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row gx-12 gx-lg-12 row-cols-12 row-cols-md-12 row-cols-xl-12 text-center">
                        <div class="col">
                            <img src="<?php echo $baris->Img3 ?>" alt="..." data-toggle="modal" data-target="#Img3Modal" style="object-fit: cover; cursor: pointer; max-width: auto; height: 100%;">
                            <div class="modal fade" id="Img3Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                          <img src="<?php echo $baris->Img3 ?>" alt="..." style="width: 100%;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row gx-12 gx-lg-12 row-cols-12 row-cols-md-12 row-cols-xl-12 text-center">
                        <div class="col">
                            <img src="<?php echo $baris->Img3 ?>" alt="..." data-toggle="modal" data-target="#Img3Modal" style="object-fit: cover; cursor: pointer; max-width: auto; height: 100%;">
                            <div class="modal fade" id="Img3Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                          <img src="<?php echo $baris->Img3 ?>" alt="..." style="width: 100%;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row gx-12 gx-lg-12 row-cols-12 row-cols-md-12 row-cols-xl-12 text-center">
                        <div class="col">
                            <img src="<?php echo $baris->Img3 ?>" alt="..." data-toggle="modal" data-target="#Img3Modal" style="object-fit: cover; cursor: pointer; max-width: auto; height: 100%;">
                            <div class="modal fade" id="Img3Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                          <img src="<?php echo $baris->Img3 ?>" alt="..." style="width: 100%;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row gx-12 gx-lg-12 row-cols-12 row-cols-md-12 row-cols-xl-12 text-center">
                        <div class="col">
                            <img src="<?php echo $baris->Img3 ?>" alt="..." data-toggle="modal" data-target="#Img3Modal" style="object-fit: cover; cursor: pointer; max-width: auto; height: 100%;">
                            <div class="modal fade" id="Img3Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                          <img src="<?php echo $baris->Img3 ?>" alt="..." style="width: 100%;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row gx-12 gx-lg-12 row-cols-12 row-cols-md-12 row-cols-xl-12 text-center">
                        <div class="col">
                            <img src="<?php echo $baris->Img3 ?>" alt="..." data-toggle="modal" data-target="#Img3Modal" style="object-fit: cover; cursor: pointer; max-width: auto; height: 100%;">
                            <div class="modal fade" id="Img3Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                          <img src="<?php echo $baris->Img3 ?>" alt="..." style="width: 100%;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row gx-12 gx-lg-12 row-cols-12 row-cols-md-12 row-cols-xl-12 text-center">
                        <div class="col">
                            <img src="<?php echo $baris->Img3 ?>" alt="..." data-toggle="modal" data-target="#Img3Modal" style="object-fit: cover; cursor: pointer; max-width: auto; height: 100%;">
                            <div class="modal fade" id="Img3Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                          <img src="<?php echo $baris->Img3 ?>" alt="..." style="width: 100%;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Controls -->
            <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <div class="row">
            <div class="col">
                <h5 class="card-title mt-5">
                    <strong>
                        <?php echo $baris -> NamaListing ?>
                    </strong>
                </h5>
                <h5 class="card-title">
                    <strong>
                        Rp. <?php echo number_format( $baris -> Harga); ?>
                    </strong>
                </h5>
                <h5 class="card-title">
                    <?php echo ( $baris -> Alamat); ?>
                </h5>
                <hr>
                <h5 class="card-title">
                    Detail Properti
                </h5>
                <table>
                    <tr>
                        <td>
                            Tipe Hunian
                        </td>
                        <td>
                             : <?php echo $baris -> JenisProperti ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Status Hunian
                        </td>
                        <td>
                             : <?php echo $baris -> Kondisi ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Luas Bangunan
                        </td>
                        <td>
                             : <?php echo $baris -> Wide ?> m2
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Luas Tanah
                        </td>
                        <td>
                             : <?php echo $baris -> Land ?> m2
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Kamar Tidur
                        </td>
                        <td>
                             : <?php echo $baris -> Bed ?> + <?php echo $baris -> BedArt ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Kamar Mandi
                        </td>
                        <td>
                             : <?php echo $baris -> Bath ?> + <?php echo $baris -> BathArt ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Lantai
                        </td>
                        <td>
                             : <?php echo $baris -> Level ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Garasi
                        </td>
                        <td>
                             : <?php echo $baris -> Garage ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Carpot
                        </td>
                        <td>
                             : <?php echo $baris -> Carpot ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Listrik
                        </td>
                        <td>
                             : <?php echo $baris -> Listrik ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Sumber Air
                        </td>
                        <td>
                             : <?php echo $baris -> SumberAir ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Deskripsi
                        </td>
                        <td>
                             : <?php echo $baris -> Deskripsi ?>
                        </td>
                    </tr>
                </table>
                <hr>
                <h5 class="card-title">
                    Kontak
                </h5>
                <div class="card">
                    <div class="card-body">
                        <div class="row gx-12 gx-lg-12 row-cols-12 row-cols-md-12 row-cols-xl-12 text-center">
                            <div class="col">
                                <img src="<?php echo $baris->Photo ?>" alt="..." data-toggle="modal" data-target="#PhotoModal" style="object-fit: cover; cursor: pointer; max-width: auto; height: 100%;">
                                <div class="modal fade" id="PhotoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                              <img src="<?php echo $baris->Photo ?>" alt="..." style="width: 100%;" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row gx-12 gx-lg-12 row-cols-12 row-cols-md-12 row-cols-xl-12 text-center">
                            <div class="col">
                                <a href="https://api.whatsapp.com/send?phone=62<?php echo $baris->NoTelp ?>"class="btn btn-sm btn-success">WhatsApp</a>
                                <a href="<?php echo $baris->Instagram?>"class="btn btn-sm btn-secondary">Instagram</a>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row gx-12 gx-lg-12 row-cols-12 row-cols-md-12 row-cols-xl-12 text-center mb-5">
                    <div class="col">
                        <a href="<?php echo base_url('GooProper/followup/'.$baris->IdListing)?>"class="btn btn-sm btn-primary">Follow Up</a>
                        <a href="<?php echo base_url('GooProper/')?>"class="btn btn-sm btn-danger">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
<script>
    function tampilkanGambarPenuh(imgSrc) {
        var gambar = new Image();
        gambar.src = imgSrc;
        gambar.onload = function() {
            var lebarGambar = this.width;
            var tinggiGambar = this.height;
            var popUp = window.open('', 'PopUpGambar', 'width=' + lebarGambar + ',height=' + tinggiGambar);
            popUp.document.write('<img src="' + imgSrc + '" alt="Gambar Penuh" style="width:100%;height:100%;" />');
        };
    }
</script>