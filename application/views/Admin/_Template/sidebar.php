<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url('GooProper/dashboard');?>">
        <div class="sidebar-brand-icon">
            <img src="<?php echo base_url('assets/img/LOGO.png'); ?>" alt="Ikon"  style="width: 50px; height: auto;" />
        </div>
        <div class="sidebar-brand-text mx-3">Goo Proper</sup></div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url('GooProper/dashboard');?>">
            <i class="fas fa-fw fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Menu</div>
    <?php if($status=="1"){
        echo'
        <li class="nav-item">
            <a class="nav-link" href='.base_url('GooProper/followup').'>
                <i class="fas fa-fw fa-book"></i>
                <span>Follow Up</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href='.base_url('GooProper/pelamar').'>
                <i class="fas fa-fw fa-book"></i>
                <span>Pelamar</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href='.base_url('GooProper/agen').'>
                <i class="fas fa-fw fa-book"></i>
                <span>Agen</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href='.base_url('Listing').' data-toggle="collapse" data-target="#collapseEdukasi" aria-expanded="true" aria-controls="collapseEdukasi">
                <i class="fas fa-fw fa-book"></i>
                <span>Listing</span>
            </a>
            <div id="collapseEdukasi" class="collapse" aria-labelledby="headingEdukasi" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href='.base_url('GooProper/listing').'>Listing</a>
                    <a class="collapse-item" href='.base_url('GooProper/pralisting').'>Listing Masuk</a>
                    <a class="collapse-item" href='.base_url('GooProper/tambahlisting').'>Tambah Listing</a>
                </div>
            </div>
        </li>';
    } else if ($status=="2"){
        echo'
        <li class="nav-item">
            <a class="nav-link" href='.base_url('GooProper/followup').'>
                <i class="fas fa-fw fa-book"></i>
                <span>Follow Up</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href='.base_url('GooProper/pelamar').'>
                <i class="fas fa-fw fa-book"></i>
                <span>Pelamar</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href='.base_url('GooProper/agen').'>
                <i class="fas fa-fw fa-book"></i>
                <span>Agen</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href='.base_url('Listing').' data-toggle="collapse" data-target="#collapseEdukasi" aria-expanded="true" aria-controls="collapseEdukasi">
                <i class="fas fa-fw fa-book"></i>
                <span>Listing</span>
            </a>
            <div id="collapseEdukasi" class="collapse" aria-labelledby="headingEdukasi" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href='.base_url('GooProper/listing').'>Listing</a>
                    <a class="collapse-item" href='.base_url('GooProper/pralisting').'>Listing Masuk</a>
                    <a class="collapse-item" href='.base_url('GooProper/tambahlisting').'>Tambah Listing</a>
                </div>
            </div>
        </li>';
    } else if ($status=="3"){
        echo'
        <li class="nav-item">
            <a class="nav-link" href='.base_url('GooProper/followup').'>
                <i class="fas fa-fw fa-book"></i>
                <span>Follow Up</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href='.base_url('Listing').' data-toggle="collapse" data-target="#collapseEdukasi" aria-expanded="true" aria-controls="collapseEdukasi">
                <i class="fas fa-fw fa-book"></i>
                <span>Listing</span>
            </a>
            <div id="collapseEdukasi" class="collapse" aria-labelledby="headingEdukasi" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href='.base_url('GooProper/listingku').'>ListingKu</a>
                    <a class="collapse-item" href='.base_url('GooProper/tambahlisting').'>Tambah Listing</a>
                </div>
            </div>
        </li>';
    } else {
        echo'
        <li class="nav-item">
            <a class="nav-link" href='.base_url('GooProper/agen').'>
                <i class="fas fa-fw fa-book"></i>
                <span>Agen</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href='.base_url('GooProper/listing').'>
                <i class="fas fa-fw fa-book"></i>
                <span>Listing</span>
            </a>
        </li>';
    }?>
    <hr class="sidebar-divider">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>