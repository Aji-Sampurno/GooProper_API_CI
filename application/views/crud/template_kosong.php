<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width = device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?php echo $title; ?></title>
        <link rel="icon" type="image/x-icon" href="<?= base_url(); ?>/assets/img/LOGO.png" />
        <!-- Custom fonts for this template-->
        <link href="<?= base_url(); ?>/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            .html2canvas-container {
                width: 3000px !important;
                height: 3000px !important;
            }
        
        </style>
    </head>
    <body id="page-top">
        <div id="wrapper">
            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    <div id="source-in" style="width: 1200px; height: 1200px; border: 1px solid; font-family: Calibri;">
                        <?php foreach($listing as $baris):?>
                        <table style="width: 100%; height: 100%; border: 1px solid;">
                            <tbody>
                                <tr style="height: 100%;">
                                    <td colspan="6" style="background-image: url('<?php echo $baris->Img1 ?>'); background-repeat: no-repeat; background-size: cover; background-position: center center; position: relative;">
                                        <div style="position: absolute; width: 200px; top: 300px; left: 50px; border-radius: 0px;">
                                            <?php 
                                                $img = $baris->Img2;
                                                if (trim($img) !== '0') {
                                                    echo '<img src="' . $baris->Img2 . '" style="width: 150px; height: 150px; border: 5px solid rgb(255,255,255); border-radius: 0%;">';
                                                }
                                            ?>
                                        </div>
                                        <div style="position: absolute; width: 200px; top: 480px; left: 50px; border-radius: 0px;">
                                            <?php 
                                                $img = $baris->Img3;
                                                if (trim($img) !== '0') {
                                                    echo '<img src="' . $baris->Img3 . '" style="width: 150px; height: 150px; border: 5px solid rgb(255,255,255); border-radius: 0%;">';
                                                }
                                            ?>
                                        </div>
                                        <div style="position: absolute; width: 200px; top: 660px; left: 50px; border-radius: 0px;">
                                            <?php 
                                                $img = $baris->Img4;
                                                if (trim($img) !== '0') {
                                                    echo '<img src="' . $baris->Img4 . '" style="width: 150px; height: 150px; border: 5px solid rgb(255,255,255); border-radius: 0%;">';
                                                }
                                            ?>
                                        </div>
                                        <div style="position: absolute; top: 20px; left: 40px; border-radius: 0px;">
                                            <img src="<?php echo base_url('assets/img/AREBI LOGO.png'); ?>" alt="Ikon"  style="width: 80px; height: auto;" />
                                        </div>
                                        <div style="position: absolute; top: 20px; right: 40px; border-radius: 0px;">
                                            <img src="<?php echo base_url('assets/img/LOGO_GP_EXCLUSIVE.png'); ?>" alt="Ikon"  style="width: auto; height: 80px;" />
                                        </div>
                                        <div style="background: rgb(85,3,118); border: 1px solid black; position: absolute; height: 200px; width: 100%; bottom: 0px; left: 0px; box-sizing: border-box; border: 5px solid rgb(255,242,0);">
                                            <div style="background: rgb(85,3,118,0); position: absolute; height: 200px; width: 740px; bottom: 0px; left: 430px; border-radius: 0px; display: flex; justify-content: space-between; box-sizing: border-box; padding: 10px;">
                                                <div style="width: 100%; text-align: left; margin-right: 5px">
                                                    <?php $kondisi = $baris->Kondisi;
                                                        if ($kondisi == 'Jual') {
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 350%; font-weight: 1000; margin: 0px;">JUAL ' . strtoupper($baris->JenisProperti) . '</h1>';
                                                        } elseif ($kondisi == 'Sewa') {
                                                            echo '
                                                                <h1 style="color: rgb(255,255,255); font-size: 350%; font-weight: 1000; margin: 0px;">SEWA ' . strtoupper($baris->JenisProperti) . '</h1>';
                                                        } elseif ($kondisi == 'Jual/Sewa') {
                                                            echo '
                                                                <h1 style="color: rgb(255,255,255); font-size: 350%; font-weight: 1000; margin: 0px;">JUAL/SEWA ' . strtoupper($baris->JenisProperti) . '</h1>';
                                                        } else {
                                                        }
                                                    ?>
                                                    <h1 style=" color: rgb(255,255,255); font-size: 170%; margin: 0px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo strtoupper($baris -> AlamatTemplate) ?></h1>
                                                    <h1 style=" color: rgb(51,3,118); font-size: 170%; margin: 5px;">
                                                        <?php $kondisi = $baris->Kondisi;
                                                            if ($kondisi == 'Jual') {
                                                                $harga = $baris->Harga;
                                                                $jenisproperti = $baris->JenisProperti;
                                                                $tipeharga = $baris->TipeHarga;
                                                                function formatHargaBlank($harga) {
                                                                    if ($harga >= 100000000000000) {
                                                                        $nilai = $harga / 1000000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' T';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' T';
                                                                        }
                                                                    } elseif ($harga >= 10000000000000) {
                                                                        $nilai = $harga / 1000000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' T';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' T';
                                                                        }
                                                                    } elseif ($harga >= 1000000000000) {
                                                                        $nilai = $harga / 1000000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' T';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' T';
                                                                        }
                                                                    } elseif ($harga >= 100000000000) {
                                                                        $nilai = $harga / 1000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' M';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' M';
                                                                        }
                                                                    } elseif ($harga >= 10000000000) {
                                                                        $nilai = $harga / 1000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' M';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' M';
                                                                        }
                                                                    } elseif ($harga >= 1000000000) {
                                                                        $nilai = $harga / 1000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' M';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' M';
                                                                        }
                                                                    } elseif ($harga >= 100000000) {
                                                                        $nilai = $harga / 1000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Jt';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Jt';
                                                                        }
                                                                    } elseif ($harga >= 10000000) {
                                                                        $nilai = $harga / 1000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Jt';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Jt';
                                                                        }
                                                                    } elseif ($harga >= 1000000) {
                                                                        $nilai = $harga / 1000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Jt';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Jt';
                                                                        }
                                                                    } elseif ($harga >= 100000) {
                                                                        $nilai = $harga / 1000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Ribu';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                        }
                                                                    } elseif ($harga >= 10000) {
                                                                        $nilai = $harga / 1000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Ribu';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                        }
                                                                    } elseif ($harga >= 1000) {
                                                                        $nilai = $harga / 1000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Ribu';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                        }
                                                                    } else {
                                                                        return number_format($harga);
                                                                    }
                                                                }    
                                                                if ($tipeharga == 'Permeter' || $jenisproperti == 'Tanah') {
                                                                    echo '
                                                                        <div style="display: inline-block; background: rgb(255,242,0); border: 2px solid rgb(51,3,118);">
                                                                            <h1 style="color: rgb(51,3,118); font-size: 150%; margin: 5px;">Rp. ' . formatHargaBlank($harga) . ' / m2</h1>
                                                                        </div>';
                                                                } else {
                                                                    echo '
                                                                        <div style="display: inline-block; background: rgb(255,242,0); border: 2px solid rgb(51,3,118);">
                                                                            <h1 style="color: rgb(51,3,118); font-size: 150%; margin: 5px;">Rp. ' . formatHargaBlank($harga) . '</h1>
                                                                        </div>';
                                                                }
                                                            } elseif ($kondisi == 'Sewa') {
                                                                $hargasewa = $baris->HargaSewa;
                                                                $jenisproperti = $baris->JenisProperti;
                                                                $tipeharga = $baris->TipeHarga;
                                                                function formatHargaSewaBlank($hargasewa) {
                                                                    if ($hargasewa >= 100000000000000) {
                                                                        $nilai = $hargasewa / 1000000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' T';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' T';
                                                                        }
                                                                    } elseif ($hargasewa >= 10000000000000) {
                                                                        $nilai = $hargasewa / 1000000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' T';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' T';
                                                                        }
                                                                    } elseif ($hargasewa >= 1000000000000) {
                                                                        $nilai = $hargasewa / 1000000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' T';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' T';
                                                                        }
                                                                    } elseif ($hargasewa >= 100000000000) {
                                                                        $nilai = $hargasewa / 1000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' M';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' M';
                                                                        }
                                                                    } elseif ($hargasewa >= 10000000000) {
                                                                        $nilai = $hargasewa / 1000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' M';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' M';
                                                                        }
                                                                    } elseif ($hargasewa >= 1000000000) {
                                                                        $nilai = $hargasewa / 1000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' M';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' M';
                                                                        }
                                                                    } elseif ($hargasewa >= 100000000) {
                                                                        $nilai = $hargasewa / 1000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Jt';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Jt';
                                                                        }
                                                                    } elseif ($hargasewa >= 10000000) {
                                                                        $nilai = $hargasewa / 1000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Jt';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Jt';
                                                                        }
                                                                    } elseif ($hargasewa >= 1000000) {
                                                                        $nilai = $hargasewa / 1000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Jt';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Jt';
                                                                        }
                                                                    } elseif ($hargasewa >= 100000) {
                                                                        $nilai = $hargasewa / 1000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Ribu';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                        }
                                                                    } elseif ($hargasewa >= 10000) {
                                                                        $nilai = $hargasewa / 1000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Ribu';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                        }
                                                                    } elseif ($hargasewa >= 1000) {
                                                                        $nilai = $hargasewa / 1000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Ribu';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                        }
                                                                    } else {
                                                                        return number_format($hargasewa);
                                                                    }
                                                                }    
                                                                echo '
                                                                    <div style="display: inline-block; background: rgb(255,242,0); border: 2px solid rgb(51,3,118);">
                                                                        <h1 style="color: rgb(51,3,118); font-size: 200%; margin: 5px;">Rp. '. formatHargaSewaBlank($hargasewa) . '</h1>
                                                                    </div>';
                                                            } elseif ($kondisi == 'Jual/Sewa') {
                                                                $tipeharga = $baris->TipeHarga;
                                                                $jenisproperti = $baris->JenisProperti;
                                                                $harga = $baris->Harga;
                                                                function formatHargaBlank($harga) {
                                                                    if ($harga >= 100000000000000) {
                                                                        $nilai = $harga / 1000000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' T';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' T';
                                                                        }
                                                                    } elseif ($harga >= 10000000000000) {
                                                                        $nilai = $harga / 1000000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' T';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' T';
                                                                        }
                                                                    } elseif ($harga >= 1000000000000) {
                                                                        $nilai = $harga / 1000000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' T';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' T';
                                                                        }
                                                                    } elseif ($harga >= 100000000000) {
                                                                        $nilai = $harga / 1000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' M';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' M';
                                                                        }
                                                                    } elseif ($harga >= 10000000000) {
                                                                        $nilai = $harga / 1000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' M';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' M';
                                                                        }
                                                                    } elseif ($harga >= 1000000000) {
                                                                        $nilai = $harga / 1000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' M';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' M';
                                                                        }
                                                                    } elseif ($harga >= 100000000) {
                                                                        $nilai = $harga / 1000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Jt';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Jt';
                                                                        }
                                                                    } elseif ($harga >= 10000000) {
                                                                        $nilai = $harga / 1000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Jt';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Jt';
                                                                        }
                                                                    } elseif ($harga >= 1000000) {
                                                                        $nilai = $harga / 1000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Jt';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Jt';
                                                                        }
                                                                    } elseif ($harga >= 100000) {
                                                                        $nilai = $harga / 1000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Ribu';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                        }
                                                                    } elseif ($harga >= 10000) {
                                                                        $nilai = $harga / 1000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Ribu';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                        }
                                                                    } elseif ($harga >= 1000) {
                                                                        $nilai = $harga / 1000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Ribu';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                        }
                                                                    } else {
                                                                        return number_format($harga);
                                                                    }
                                                                }
                                                                $hargasewa = $baris->HargaSewa;
                                                                function formatHargaSewaBlank($hargasewa) {
                                                                    if ($hargasewa >= 100000000000000) {
                                                                        $nilai = $hargasewa / 1000000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' T';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' T';
                                                                        }
                                                                    } elseif ($hargasewa >= 10000000000000) {
                                                                        $nilai = $hargasewa / 1000000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' T';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' T';
                                                                        }
                                                                    } elseif ($hargasewa >= 1000000000000) {
                                                                        $nilai = $hargasewa / 1000000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' T';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' T';
                                                                        }
                                                                    } elseif ($hargasewa >= 100000000000) {
                                                                        $nilai = $hargasewa / 1000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' M';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' M';
                                                                        }
                                                                    } elseif ($hargasewa >= 10000000000) {
                                                                        $nilai = $hargasewa / 1000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' M';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' M';
                                                                        }
                                                                    } elseif ($hargasewa >= 1000000000) {
                                                                        $nilai = $hargasewa / 1000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' M';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' M';
                                                                        }
                                                                    } elseif ($hargasewa >= 100000000) {
                                                                        $nilai = $hargasewa / 1000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Jt';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Jt';
                                                                        }
                                                                    } elseif ($hargasewa >= 10000000) {
                                                                        $nilai = $hargasewa / 1000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Jt';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Jt';
                                                                        }
                                                                    } elseif ($hargasewa >= 1000000) {
                                                                        $nilai = $hargasewa / 1000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Jt';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Jt';
                                                                        }
                                                                    } elseif ($hargasewa >= 100000) {
                                                                        $nilai = $hargasewa / 1000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Ribu';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                        }
                                                                    } elseif ($hargasewa >= 10000) {
                                                                        $nilai = $hargasewa / 1000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Ribu';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                        }
                                                                    } elseif ($hargasewa >= 1000) {
                                                                        $nilai = $hargasewa / 1000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Ribu';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                        }
                                                                    } else {
                                                                        return number_format($hargasewa);
                                                                    }
                                                                }   
                                                                if ($tipeharga == 'Permeter' || $jenisproperti == 'Tanah') {
                                                                    echo '
                                                                        <div style="display: inline-block; background: rgb(255,242,0); border: 2px solid rgb(51,3,118);">
                                                                            <h1 style="color: rgb(51,3,118); font-size: 200%; margin: 5px;">Rp. ' . formatHargaBlank($harga) .' / m2 / Rp. '. formatHargaSewaBlank($hargasewa) . '</h1>
                                                                        </div>';
                                                                } else {
                                                                    echo '
                                                                        <div style="display: inline-block; background: rgb(255,242,0); border: 2px solid rgb(51,3,118);">
                                                                            <h1 style="color: rgb(51,3,118); font-size: 200%; margin: 5px;">Rp. ' . formatHargaBlank($harga) .' / Rp. '. formatHargaSewaBlank($hargasewa) . '</h1>
                                                                        </div>';
                                                                }
                                                            } else {
                                                                echo '<h1 style="color: rgb(51,3,118); font-size: 200%; margin: 5px;">' . strtoupper($baris->Harga) .' '. strtoupper($baris->HargaSewa) . '</h1>';
                                                            }
                                                        ?>
                                                    </h1>
                                                </div>
                                                <div style="background: rgb(85,3,118,0); position: absolute; height: 200px; width: 35%; bottom: 0px; left: 45%; border-radius: 0px; display: flex; align-items: flex-end; justify-content: space-between; box-sizing: border-box; padding: 10px;">
                                                    <div style="width: 100%;">
                                                        <?php 
                                                            $rh = $baris->RangeHarga;
                                                            $jp = $baris->JenisProperti;
                                                            if (trim($jp) == 'Rukost') {
                                                                function formatRangeHarga($rh) {
                                                                    if ($rh >= 100000000000000) {
                                                                        $nilai = $rh / 1000000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' T';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' T';
                                                                        }
                                                                    } elseif ($rh >= 10000000000000) {
                                                                        $nilai = $rh / 1000000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' T';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' T';
                                                                        }
                                                                    } elseif ($rh >= 1000000000000) {
                                                                        $nilai = $rh / 1000000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' T';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' T';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' T';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' T';
                                                                        }
                                                                    } elseif ($rh >= 100000000000) {
                                                                        $nilai = $rh / 1000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' M';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' M';
                                                                        }
                                                                    } elseif ($rh >= 10000000000) {
                                                                        $nilai = $rh / 1000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' M';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' M';
                                                                        }
                                                                    } elseif ($rh >= 1000000000) {
                                                                        $nilai = $rh / 1000000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' M';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' M';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' M';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' M';
                                                                        }
                                                                    } elseif ($rh >= 100000000) {
                                                                        $nilai = $rh / 1000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Jt';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Jt';
                                                                        }
                                                                    } elseif ($rh >= 10000000) {
                                                                        $nilai = $rh / 1000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Jt';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Jt';
                                                                        }
                                                                    } elseif ($rh >= 1000000) {
                                                                        $nilai = $rh / 1000000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Jt';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Jt';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Jt';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Jt';
                                                                        }
                                                                    } elseif ($rh >= 100000) {
                                                                        $nilai = $rh / 1000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Ribu';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                        }
                                                                    } elseif ($rh >= 10000) {
                                                                        $nilai = $rh / 1000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Ribu';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                        }
                                                                    } elseif ($rh >= 1000) {
                                                                        $nilai = $rh / 1000;
                                                                        $string_nilai = strval($nilai);
                                                                        $parts = explode('.', $string_nilai);
                                                                        if (isset($parts[1])) {
                                                                            $panjang_desimal = strlen($parts[1]);
                                                                            if ($panjang_desimal == 0) {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 3) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 2) {
                                                                                return number_format($nilai, 2, ',', '') . ' Ribu';
                                                                            } elseif ($panjang_desimal == 1) {
                                                                                return number_format($nilai, 1, ',', '') . ' Ribu';
                                                                            } else {
                                                                                return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                            }
                                                                        } else {
                                                                            return number_format($nilai, 0, ',', '') . ' Ribu';
                                                                        }
                                                                    } else {
                                                                        return number_format($rh);
                                                                    }
                                                                }   
                                                                echo '<div style="display: inline-block; background: rgb(255,242,0); border: 2px solid rgb(255,0,0); text-align: center;">
                                                                            <h1 style="color: rgb(51,3,118); font-size: 130%; margin: 5px;">Harga Sewa Perbulan</h1>
                                                                            <h1 style="color: rgb(51,3,118); font-size: 130%; margin: 5px;">Rp. ' . formatRangeHarga($rh) . '</h1>
                                                                        </div>';
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="position: absolute; top: 30px; right: 30px;">
                                                <img src="<?php echo base_url('assets/img/LOGO_EXCLUSIVE.png'); ?>" alt="Ikon"  style="width: auto; height: 70px;" />
                                            </div>
                                            <div style="background: rgb(85,3,118,0); position: absolute; height: 100px; width: 10%; bottom: 0px; right: 0px;border-radius: 0px; display: flex; align-items: center; justify-content: center; justify-content: space-between; box-sizing: border-box; padding: 10px;">
                                                <div style="width: 100%;">
                                                    <?php 
                                                        $agen = $baris->IdAgen;
                                                        $agenco = $baris->IdAgenCo;
                                                        if (trim($agenco) == '0') {
                                                            echo '<h1 style="color: rgb(255,242,0, 0.5); font-size: 150%; margin: 0px; line-height: 1;">'. $baris -> KodeAgen .'</h1>';
                                                        } elseif (trim($agenco) == trim($agen))  {
                                                            echo '<h1 style="color: rgb(255,242,0, 0.5); font-size: 150%; margin: 0px; line-height: 1;">'. $baris -> KodeAgen .'</h1>';
                                                        } else {
                                                            echo '<h1 style="color: rgb(255,242,0, 0.5); font-size: 150%; margin: 0px; line-height: 1;">'. $baris -> KodeAgen .' - '. $baris -> KodeAgenCo .'</h1>';
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="position: absolute; bottom: 30px; left: 110px;">
                                            <img src="<?php echo base_url('assets/img/LOGO_SOSMED_EXCLUSIVE.png'); ?>" alt="Ikon"  style="width: auto; height: 30px;" />
                                        </div>
                                        <div style="background: rgb(85,3,118); position: absolute; height: 260px; width: 30%; bottom: 90px; left: 50px; box-sizing: border-box; border: 5px solid rgb(255,242,0); border-radius: 10%;">
                                            <div style="background: rgb(85,3,118,0); position: absolute; height: 260px; width: 50%; left: 0px; display: flex; justify-content: center; align-items: center; padding: 10px;">
                                                <div style="width: 100%;">
                                                    <?php 
                                                        $lt = $baris->Wide;
                                                        if (trim($lt) !== '') {
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">LT : ' . $baris->Wide . '</h1>';
                                                        }
                                                    ?>
                                                    <?php 
                                                        $lb = $baris->Land;
                                                        if (trim($lb) !== '') {
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">LB : ' . $baris->Land . '</h1>';
                                                        }
                                                    ?>
                                                    <?php 
                                                        $dimensi = $baris->Dimensi;
                                                        if (trim($dimensi) !== '') {
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">Dim : ' . $baris->Dimensi . '</h1>';
                                                        }
                                                    ?>
                                                    <?php 
                                                        $lantai = $baris->Level;
                                                        if (trim($lantai) !== '') {
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">' . $baris->Level . ' Lantai</h1>';
                                                        }
                                                    ?>
                                                    <?php 
                                                        $shm = $baris->SHM;
                                                        $hgb = $baris->HGB;
                                                        $hshp = $baris->HSHP;
                                                        $ppjb = $baris->PPJB;
                                                        $stra = $baris->Stratatitle;
                                                        $ajb = $baris->AJB;
                                                        $petokd = $baris->PetokD;
                                                        if ($shm == '1') {
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">SHM</h1>';
                                                        } elseif ($hgb == '1') { 
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">HGB</h1>';
                                                        } elseif ($hshp == '1') { 
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">HSHP</h1>';
                                                        } elseif ($ppjb == '1') { 
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">PPJB</h1>';
                                                        } elseif ($stra == '1') { 
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">Stratatitle</h1>';
                                                        } elseif ($ajb == '1') { 
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">AJB</h1>';
                                                        } elseif ($petokd == '1') { 
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">Petok D</h1>';
                                                        } else {
                                                        }
                                                    ?>
                                                    <?php 
                                                        $listrik = $baris->Listrik;
                                                        if (trim($listrik) !== '') {
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">' . $baris->Listrik . ' W</h1>';
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <div style="background: rgb(85,3,118,0); position: absolute; height: 260px; width: 50%; right: 0px; display: flex; justify-content: center; align-items: center; text-align: right; padding: 10px;">
                                                <div style="width: 100%;">
                                                    <?php 
                                                        $bed = $baris->Bed;
                                                        $bedart = $baris->BedArt;
                                                        if (trim($bed) !== '0') {
                                                            if (trim($bedart) !== '0') {
                                                                echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">KT : ' . $baris->Bed . ' + ' . $baris->BedArt . '</h1>';
                                                            } else {
                                                                echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">KT : ' . $baris->Bed . '</h1>';
                                                            }
                                                        }
                                                    ?>
                                                    <?php 
                                                        $bath = $baris->Bath;
                                                        $bathart = $baris->BathArt;
                                                        if (trim($bath) !== '0') {
                                                            if (trim($bathart) !== '0') {
                                                                echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">KM : ' . $baris->Bath . ' + ' . $baris->BathArt . '</h1>';
                                                            } else {
                                                                echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">KM : ' . $baris->Bath . '</h1>';
                                                            }
                                                        }
                                                    ?>
                                                    <?php 
                                                        $garasi = $baris->Garage;
                                                        if (trim($garasi) !== '0') {
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">' . $baris->Garage . ' Garasi</h1>';
                                                        }
                                                    ?>
                                                    <?php 
                                                        $carpot = $baris->Carpot;
                                                        if (trim($carpot) !== '0') {
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">' . $baris->Carpot . ' Carpot</h1>';
                                                        }
                                                    ?>
                                                    <?php 
                                                        $hadap = $baris->Hadap;
                                                        if (trim($hadap) !== '') {
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">' . $baris->Hadap . '</h1>';
                                                        }
                                                    ?>
                                                    <?php 
                                                        $air = $baris->SumberAir;
                                                        if (trim($air) !== '') {
                                                            echo '<h1 style="color: rgb(255,255,255); font-size: 150%; margin: 5px; line-height: 1;">' . $baris->SumberAir . '</h1>';
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background: rgb(255,242,0); position: absolute; height: 50px; width: 300px; bottom: 325px; left: 80px; box-sizing: border-box; border: 5px solid rgb(255,242,0); border-radius: 10%; display: flex; justify-content: center; align-items: center;">
                                            <h1 style="color: rgb(51,3,118); font-size: 200%; margin: 5px;">SPESIFIKASI</h1>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php endforeach ?>
                    </div>
                    <div id="resultContainer" style="width: 300px;height: 300px;object-fit:cover"></div>
                </div>
            </div>
        </div>
        
        <!-- Bootstrap core JavaScript-->
        <script src="<?= base_url(); ?>/assets/vendor/jquery/jquery.min.js"></script>
        <script src="<?= base_url(); ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="<?= base_url(); ?>/assets/vendor/jquery-easing/jquery.easing.min.js"></script>
        <!-- Custom scripts for all pages-->
        <script src="<?= base_url(); ?>/assets/js/sb-admin-2.min.js"></script>
        <!-- Page level plugins -->
        <script src="<?= base_url(); ?>/assets/vendor/chart.js/Chart.min.js"></script>
        <!-- Page level custom scripts -->
        <script src="<?= base_url(); ?>/assets/js/demo/chart-area-demo.js"></script>
        <!--<script src="<?= base_url(); ?>/assets/js/demo/chart-bar-demo.js"></script>-->
        <script src="<?= base_url(); ?>/assets/js/demo/chart-pie-demo.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                function downloadURI(uri, name) {
                    var link = document.createElement("a");
                    
                    link.download = name;
                    link.href = uri;
                    document.body.appendChild(link);
                    link.click();
                    
                    $("#source-in").css("display", "none");
                    $("#resultContainer").attr('src', uri);
                    
                }
                //return;
                html2canvas($("#resultContainer")[0], {
                    allowTaint: false,
                    useCORS: true
                }).then(function(canvas) {
                    var myImage = canvas.toDataURL();
                    //downloadURI(myImage, "e-brochure-O036173.png");
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function downloadURI(uri, name) {
                    var link = document.createElement("a");
                    
                    link.download = name;
                    link.href = uri;
                    document.body.appendChild(link);
                    link.click();
                    
                    $("#source-in").css("display", "none");
                    
                }
                //return;
                html2canvas($("#source-in")[0], {
                    allowTaint: false,
                    useCORS: true
                }).then(function(canvas) {
                    document.getElementById('resultContainer').appendChild(canvas);
                    var myImage = canvas.toDataURL();
                    downloadURI(myImage, "Template_Blank.png");
                });
            });
        </script>
    </body>
</html>
