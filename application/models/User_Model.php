<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_Model extends CI_Model
{
    // CRUD ==========================================================================================================================================================================================
    
    public function input_data($data,$table){
		$this->db->insert($table,$data);
	}

    public function edit_data($where, $table){
		return $this -> db -> get_where($table,$where);
	}

    public function update_data($where,$data,$table){
		$this->db->where($where);
		$this->db->update($table,$data);
	}
	
	public function hapus_data($where,$table){
		$this->db->where($where);
		$this->db->delete($table);
		return $result;
	}
	
    // Akun ==========================================================================================================================================================================================
    
    public function login($username, $password){
        $sql = "SELECT * FROM admin WHERE Username = ?";
        $query = $this->db->query($sql, array($username));
        $row = $query->row_array();
        if (isset($row)) 
        {
            $new_password=md5($password);
            $hashedPassword = $row['Password'];
            if($new_password==$hashedPassword)
            {
                return $row;
            } else {
                return false;
            }
        }
	}

    public function logincustomer($username, $password){
        $sql = "SELECT * FROM customer WHERE Username = ?";
        $query = $this->db->query($sql, array($username));
        $row = $query->row_array();
        if (isset($row)) 
        {
            $new_password=md5($password);
            $hashedPassword = $row['Password'];
            if($new_password==$hashedPassword)
            {
                return $row;
            } else {
                return false;
            }
        }
	}

    public function loginagen($username, $password){
        $sql = "SELECT * FROM agen WHERE Username = ?";
        $query = $this->db->query($sql, array($username));
        $row = $query->row_array();
        if (isset($row)) 
        {
            $new_password=md5($password);
            $hashedPassword = $row['Password'];
            if($new_password==$hashedPassword)
            {
                return $row;
            } else {
                return false;
            }
        }
	}
	
	// Admin =========================================================================================================================================================================================
	
    public function countPelamar(){
        $query = $this->db->query(" SELECT 
                                    	COUNT(*) AS Total
                                    FROM 
                                        agen
                                    WHERE 
                                        Approve = 0 AND Reject = 0");
        return $query->result_array();
        
    }
    
    // Jenis Property ================================================================================================================================================================================
    
    public function getJenisProperty() {
        $query = $this->db->query(" SELECT 
                                        jenisproperty.* 
                                    FROM 
                                        jenisproperty");
        return $query->result_array();
    }
    
    // Info ==========================================================================================================================================================================================
    
    public function getInfo(){
        $query = $this->db->query(" SELECT 
                                        infoproperty.* 
                                    FROM 
                                        infoproperty 
                                    WHERE 
                                        infoproperty.IsListing = 0 AND infoproperty.IsHide = 0
                                    ORDER BY 
                                        infoproperty.IdInfo DESC");
        return $query->result_array();
    }
    
    public function getInfoAgen($id){
        $query = $this->db->query(" SELECT 
                                        infoproperty.* 
                                    FROM 
                                        infoproperty 
                                    WHERE 
                                        infoproperty.IdAgen = $id 
                                    ORDER BY 
                                        infoproperty.IdInfo DESC");
        return $query->result_array();
    }
    
    public function getAgenInfo($id){
        $query = $this->db->query(" SELECT 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram 
                                    FROM 
                                        agen 
                                    WHERE 
                                        agen.IdAgen = $id");
        return $query->result_array();
    }
    
    public function getLaporanInfo(){
        $query = $this->db->query("SELECT 
                                    	infoproperty.*, 
                                        agen.IdAgen, 
                                        agen.Nama,  
                                        agen.Instagram
                                    FROM 
                                    	infoproperty 
                                    LEFT JOIN 
                                    	agen ON infoproperty.IdAgen = agen.IdAgen
                                    WHERE 
                                    	infoproperty.TglInput BETWEEN '2024-10-01' AND '2024-10-31'");
        return $query->result_array();
    }
    
    // Share Lokasi ==================================================================================================================================================================================
    
    public function getShareLokasi($id){
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        sharelokasi 
                                    WHERE 
                                        IdAgen = $id AND IsListing = 0");
        return $query->result_array();
    }
    
    // Pra Listing ===================================================================================================================================================================================
    
    public function getDataPraListingForAdmin(){
        $query = $this->db->query(" SELECT 
                                        pralisting.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                        pralisting LEFT JOIN agen USING(IdAgen) LEFT JOIN vendor USING(IdVendor) 
                                    WHERE 
                                        IsAdmin = 0 AND IsManager = 0 AND IsRejected = 0 AND IsCekLokasi = 1 ORDER BY pralisting.Priority ASC, pralisting.IdPraListing ASC");
        return $query->result_array();
    }
    
    public function getDataPraListingForManager(){
        $query = $this->db->query(" SELECT 
                                        pralisting.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                        pralisting LEFT JOIN agen USING(IdAgen) LEFT JOIN vendor USING(IdVendor) 
                                    WHERE 
                                        IsAdmin = 1 AND IsManager = 0 AND IsRejected = 0 AND IsCekLokasi = 1 ORDER BY pralisting.Priority ASC, pralisting.IdPraListing ASC");
        return $query->result_array();
    }
    
    //--------------------------------------------------------------------------
    
    public function getPraListingAdmin(){
        $query = $this->db->query(" SELECT 
                                        pralisting.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                        pralisting LEFT JOIN agen USING(IdAgen) LEFT JOIN vendor USING(IdVendor) 
                                    WHERE 
                                        IsAdmin = 0 AND IsManager = 1 AND IsRejected = 0 ORDER BY pralisting.Priority ASC, pralisting.IdPraListing ASC");
        return $query->result_array();
    }
    
    public function getPraListingAdminBaru(){
        $query = $this->db->query(" SELECT 
                                        pralisting.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                        pralisting LEFT JOIN agen USING(IdAgen) LEFT JOIN vendor USING(IdVendor) 
                                    WHERE 
                                        IsAdmin = 0 AND IsManager = 1 AND IsRejected = 0 AND IsCekLokasi = 1 ORDER BY pralisting.Priority ASC, pralisting.IdPraListing ASC");
        return $query->result_array();
    }
    
    public function getPraListingManager(){
        $query = $this->db->query(" SELECT 
                                        pralisting.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                        pralisting LEFT JOIN agen USING(IdAgen) LEFT JOIN vendor USING(IdVendor) 
                                    WHERE 
                                        IsAdmin = 0 AND IsManager = 0 AND IsRejected = 0 ORDER BY pralisting.Priority ASC, pralisting.IdPraListing ASC");
        return $query->result_array();
    }
    
    public function getPraListingManagerBaru(){
        $query = $this->db->query(" SELECT 
                                        pralisting.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                        pralisting LEFT JOIN agen USING(IdAgen) LEFT JOIN vendor USING(IdVendor) 
                                    WHERE 
                                        IsAdmin = 0 AND IsManager = 0 AND IsRejected = 0 AND IsCekLokasi = 1 ORDER BY pralisting.Priority ASC, pralisting.IdPraListing ASC");
        return $query->result_array();
    }
    
    public function getPraListingAgen($id){
        $query = $this->db->query(" SELECT 
                                        pralisting.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                        pralisting LEFT JOIN agen USING(IdAgen) LEFT JOIN vendor USING(IdVendor)
                                    WHERE 
                                        pralisting.IdAgen = $id AND (IsAdmin = 0 OR IsManager = 0) AND IsRejected = 0 ORDER BY pralisting.Priority ASC, pralisting.IdPraListing DESC");
        return $query->result_array();
    }
    
    public function getPraListingTerdekat(){
        $query = $this->db->query(" SELECT 
                                        pralisting.* 
                                    FROM 
                                        pralisting 
                                    WHERE 
                                        IsAdmin = 0 AND IsManager = 0 AND IsRejected = 0 AND IsCekLokasi = 0 ORDER BY pralisting.Priority ASC, pralisting.IdPraListing ASC");
        return $query->result_array();
    }
    
    public function getPraListingSurvey($id){
        $query = $this->db->query(" SELECT 
                                    	pralisting.*,
                                        agen.NamaTemp AS NamaAgen1,
                                        agen.NoTelp AS Telp1,
                                        agenco.NamaTemp AS NamaAgen2,
                                        agenco.NoTelp AS Telp2, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor
                                    FROM 
                                    	pralisting
                                        INNER JOIN agen ON pralisting.IdAgen = agen.IdAgen
                                        INNER JOIN agen AS agenco ON pralisting.IdAgen = agenco.IdAgen
                                        LEFT JOIN vendor USING(IdVendor)
                                    WHERE
                                    	pralisting.IdPraListing = $id ");
        return $query->result_array();
    }
    
    public function getSurveyorPraListing($id){
        $query = $this->db->query(" SELECT 
                                    	pralisting.Surveyor,
                                        pralisting.IdPraListing,
                                        reportkinerja.Keterangan,
                                        agen.NamaTemp
                                    FROM
                                    	pralisting
                                        INNER JOIN reportkinerja ON pralisting.IdPraListing = reportkinerja.IdListing
                                        INNER JOIN agen ON pralisting.Surveyor = agen.IdAgen
                                    WHERE 
                                    	pralisting.IdPraListing = $id ");
        return $query->result_array();
    }
    
    public function getPasangBanner(){
        $query = $this->db->query(" SELECT 
                                        IdListing,
                                        NamaListing,
                                        Alamat,
                                        Priority,
                                        Kondisi,
                                        Size,
                                        Img1
                                    FROM 
                                        `listing` 
                                    WHERE 
                                        Banner = 'Ya' AND IsPasangBanner = '0' ");
        return $query->result_array();
    }
    
    public function countPralistingTerdekat(){
        $query = $this->db->query(" SELECT 
                                    	COUNT(*) AS Total
                                    FROM 
                                        pralisting 
                                    WHERE 
                                        IsAdmin = 0 AND IsManager = 0 AND IsRejected = 0 AND IsCekLokasi = 0;");
        return $query->result_array();
        
    }
    
    public function countPralistingAgen($id){
        $query = $this->db->query(" SELECT 
                                    	COUNT(*) AS Total
                                    FROM 
                                        pralisting 
                                    WHERE 
                                        (IsAdmin = 0 OR IsManager = 0) AND IsRejected = 0 AND IdAgen = $id");
        return $query->result_array();
        
    }
    
    public function countPralistingAgenRejected($id){
        $query = $this->db->query(" SELECT 
                                    	COUNT(*) AS Total
                                    FROM 
                                        pralisting 
                                    WHERE 
                                        (IsAdmin = 0 OR IsManager = 0) AND IsRejected = 1 AND IdAgen = $id");
        return $query->result_array();
        
    }
    
    public function countPralistingRejected(){
        $query = $this->db->query(" SELECT 
                                    	COUNT(*) AS Total
                                    FROM 
                                        pralisting 
                                    WHERE 
                                        (IsAdmin = 0 OR IsManager = 0) AND IsRejected = 1");
        return $query->result_array();
        
    }
    
    public function countPralistingAdmin(){
        $query = $this->db->query(" SELECT 
                                    	COUNT(*) AS Total
                                    FROM 
                                        pralisting 
                                    WHERE 
                                        IsAdmin = 0 AND IsManager = 1 AND IsRejected = 0 AND IsCekLokasi = 1;");
        return $query->result_array();
        
    }
    
    public function countPralistingManager(){
        $query = $this->db->query(" SELECT 
                                    	COUNT(*) AS Total
                                    FROM 
                                        pralisting 
                                    WHERE 
                                        IsAdmin = 0 AND IsManager = 0 AND IsRejected = 0 AND IsCekLokasi = 1;");
        return $query->result_array();
        
    }
    
    public function countPasangBanner(){
        $query = $this->db->query(" SELECT 
                                        COUNT(*) AS Total 
                                    FROM 
                                        `listing` 
                                    WHERE 
                                        Banner = 'Ya' AND IsPasangBanner = '0' ");
        return $query->result_array();
        
    }
    
    // User ==========================================================================================================================================================================================
    
	public function getFavorite($id){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor,
                                        template.IdTemplate,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                        favorite 
                                        JOIN listing ON favorite.IdListing = listing.IdListing 
                                        JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor) 
                                        LEFT JOIN template ON listing.IdListing = template.IdListing 
                                    WHERE 
                                        favorite.IdCustomer = $id");
        return $query->result_array();
    }
    
    public function getFavoriteAgen($id){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor,
                                        template.IdTemplate,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                        favorite 
                                        JOIN listing ON favorite.IdListing = listing.IdListing 
                                        JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor) 
                                        LEFT JOIN template ON listing.IdListing = template.IdListing 
                                    WHERE 
                                        favorite.IdAgen = $id");
        return $query->result_array();
    }
    
    public function getSeen($id){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor,
                                        template.IdTemplate,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                        view 
                                        JOIN listing ON view.IdListing = listing.IdListing 
                                        JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor) 
                                        LEFT JOIN template ON listing.IdListing = template.IdListing 
                                    WHERE 
                                        view.IdCustomer = $id");
        return $query->result_array();
    }
    
    public function getSeenAgen($id){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor,
                                        template.IdTemplate,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                        view 
                                        JOIN listing ON view.IdListing = listing.IdListing 
                                        JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor) 
                                        LEFT JOIN template ON listing.IdListing = template.IdListing
                                    WHERE 
                                        view.IdAgen = $id");
        return $query->result_array();
    }
	
    // Agen ==========================================================================================================================================================================================
    
    public function getLastSeenAgenAktif(){
        $query = $this->db->query(" SELECT 
                                        agen.*, 
                                        karyawan.NoKaryawan 
                                    FROM 
                                        agen 
                                        INNER JOIN karyawan ON agen.IdAgen = karyawan.IdAgen 
                                    WHERE 
                                        LastSeen >= CURDATE() - INTERVAL 3 MONTH");
        return $query->result_array();
    }
    
    public function getLastSeenAgenTidakAktif(){
        $query = $this->db->query(" SELECT 
                                        agen.*, 
                                        karyawan.NoKaryawan 
                                    FROM 
                                        agen 
                                        INNER JOIN karyawan ON agen.IdAgen = karyawan.IdAgen 
                                    WHERE 
                                        LastSeen < CURDATE() - INTERVAL 3 MONTH");
        return $query->result_array();
    }
    	
	public function countSewa($idagen){
	    $query = $this->db->query(" SELECT 
	                                    COUNT(*) AS sewa 
	                                FROM 
	                                    listing 
	                                WHERE 
	                                    Kondisi = 'Sewa' AND IdAgen= $idagen");
	    return $query->result_array();
	}
    	
	public function countJual($idagen){
	    $query = $this->db->query(" SELECT 
	                                    COUNT(*) AS jual 
	                                FROM 
	                                    listing 
	                                WHERE 
	                                    Kondisi = 'Jual' AND IdAgen= $idagen");
	    return $query->result_array();
	}
    	
	public function countListing($idagen){
	    $query = $this->db->query(" SELECT 
	                                    COUNT(*) AS listing 
	                                FROM 
	                                    listing 
	                                WHERE 
	                                    IdAgen= $idagen");
	    return $query->result_array();
	}
    
    public function getAktif($id){
        $query = $this->db->query(" SELECT 
                                        agen.* 
                                    FROM 
                                        agen 
                                    WHERE 
                                        IdAgen = $id ");
        return $query->result_array();
    }
    
    public function getOfficer($id){
        $query = $this->db->query(" SELECT 
                                        karyawan.Officer
                                    FROM 
                                        karyawan
                                    WHERE 
                                        IdAgen = $id ");
        return $query->result_array();
    }
    
    public function getAgenDeep($id){
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        `agen` 
                                    WHERE 
                                        IdAgen = $id");
        return $query->result_array();
    }
    
    public function getWilayah($id){
        $query = $this->db->query(" SELECT 
                                        *
                                    FROM 
                                        wilayah
                                    WHERE
                                        IdDaerah = $id");
        return $query->result_array();
    }
    
    public function getDaerah(){
        $query = $this->db->query(" SELECT 
                                        *
                                    FROM 
                                        daerah");
        return $query->result_array();
    }
    
    public function getAgen(){
        $query = $this->db->query(" SELECT 
                                        `IdAgen`,
                                        `Nama` 
                                    FROM 
                                        `agen` 
                                    WHERE 
                                        `IsAkses` = 1 AND `Approve` = 1");
        return $query->result_array();
    }
    
    public function getUltahAgen(){
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        agen 
                                    WHERE 
                                        MONTH(TglLahir) = MONTH(CURRENT_DATE()) AND DAY(TglLahir) = DAY(CURRENT_DATE())");
        return $query->result_array();
    }
    
    public function getDaftarAgen(){
        $query = $this->db->query(" SELECT 
                                        agen.*, 
                                        karyawan.NoKaryawan 
                                    FROM 
                                        agen 
                                        INNER JOIN karyawan ON agen.IdAgen = karyawan.IdAgen 
                                    WHERE 
                                        agen.IsAkses = 1 AND agen.Approve = 1");
        return $query->result_array();
    }
    
    public function getPelamarAgen(){
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        `agen` 
                                    WHERE 
                                        `IsAkses` = 1 AND `Approve` = 0 AND `Reject` = 0");
        return $query->result_array();
    }
    
    public function getPelamarMitra(){
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        `agen` 
                                    WHERE 
                                        `IsAkses` = 2 AND `Approve` = 0 AND `Reject` = 0");
        return $query->result_array();
    }
    
    public function getPelamarKantorLain(){
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        `agen` 
                                    WHERE 
                                        `IsAkses` = 3 AND `Approve` = 0 AND `Reject` = 0");
        return $query->result_array();
    }
    
    public function getAgenNew(){
        $query = $this->db->query(" SELECT
                                        * 
                                    FROM 
                                        `agen` ");
        return $query->result_array();
    }
    
    // Device ========================================================================================================================================================================================
	
	public function device($token) {
        $this->db->where('Token', $token);
        $query = $this->db->get('device');
        
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    public function getDevice(){
        $query = $this->db->query(" SELECT 
                                        Token 
                                    FROM 
                                        `device` ");
        return $query->result_array();
    }
	
	public function deviceagen($token) {
        $this->db->where('Token', $token);
        $query = $this->db->get('deviceagen');
        
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    public function getDeviceAgen(){
        $query = $this->db->query(" SELECT 
                                        Token 
                                    FROM 
                                        `deviceagen` ");
        return $query->result_array();
    }
    
    public function getDeviceByAgen($idagen){
        $query = $this->db->query(" SELECT 
                                        Token 
                                    FROM 
                                        `deviceagen` 
                                    WHERE 
                                        IdAgen = $idagen ");
        return $query->result_array();
    }
	
	public function devicecustomer($token) {
        $this->db->where('Token', $token);
        $query = $this->db->get('devicecustomer');
        
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
	
    // PraListing ====================================================================================================================================================================================
    
    public function updateIsManager($id) {
        $data = array('IsManager' => 1);
        $this->db->where('IdPraListing', $id);
        $this->db->update('pralisting', $data);
    }
    
    // Listing =======================================================================================================================================================================================
    public function addDataListing($id){
	    $query = $this->db->query("INSERT INTO `listing` (
                                  `IdAgen`,`IdAgenCo`,`IdInput`,`IdVendor`,`NoArsip`,`NamaListing`,`MetaNamaListing`,`Alamat`,`AlamatTemplate`,`Latitude`,`Longitude`,`Location`,`Wilayah`,`Selfie`,`Wide`,`Land`,`Dimensi`,`Listrik`,`Level`,`Bed`,`Bath`,`BedArt`,`BathArt`,`Garage`,`Carpot`,`Hadap`,`SHM`,`HGB`,`HSHP`,`PPJB`,`Stratatitle`,`AJB`,`PetokD`,`Pjp`,`ImgSHM`,`ImgHGB`,`ImgHSHP`,`ImgPPJB`,`ImgStratatitle`,`ImgAJB`,`ImgPetokD`,`ImgPjp`,`ImgPjp1`,`NoCertificate`,`Pbb`,`JenisProperti`,`JenisCertificate`,`SumberAir`,`Kondisi`,`RuangTamu`,`RuangMakan`,`Dapur`,`Jemuran`,`Masjid`,`Taman`,`Playground`,`Cctv`,`OneGateSystem`,`Deskripsi`,`MetaDeskripsi`,`Prabot`,`KetPrabot`,`Priority`,`Ttd`,`Banner`,`Size`,`Harga`,`HargaSewa`,`RangeHarga`,`TglInput`,`TglUpdate`,`Img1`,`Img2`,`Img3`,`Img4`,`Img5`,`Img6`,`Img7`,`Img8`,`Img9`,`Img10`,`Img11`,`Img12`,`Video`,`LinkFacebook`,`LinkTiktok`,`LinkInstagram`,`LinkYoutube`,`IsAdmin`,`IsManager`,`IsRejected`,`Sold`,`Rented`,`SoldAgen`,`RentedAgen`,`View`,`Marketable`,`StatusHarga`,`IsSelfie`,`IsLokasi`,`Surveyor`,`Fee`,`NoKtp`,`ImgKtp`,`TipeHarga`,`Pending`,`IsCekLokasi`,`IsDouble`,`IsDelete`
                                ) 
                                SELECT 
                                  `IdAgen`,`IdAgenCo`,`IdInput`,`IdVendor`,`NoArsip`,`NamaListing`,`MetaNamaListing`,`Alamat`,`AlamatTemplate`,`Latitude`,`Longitude`,`Location`,`Wilayah`,`Selfie`,`Wide`,`Land`,`Dimensi`,`Listrik`,`Level`,`Bed`,`Bath`,`BedArt`,`BathArt`,`Garage`,`Carpot`,`Hadap`,`SHM`,`HGB`,`HSHP`,`PPJB`,`Stratatitle`,`AJB`,`PetokD`,`Pjp`,`ImgSHM`,`ImgHGB`,`ImgHSHP`,`ImgPPJB`,`ImgStratatitle`,`ImgAJB`,`ImgPetokD`,`ImgPjp`,`ImgPjp1`,`NoCertificate`,`Pbb`,`JenisProperti`,`JenisCertificate`,`SumberAir`,`Kondisi`,`RuangTamu`,`RuangMakan`,`Dapur`,`Jemuran`,`Masjid`,`Taman`,`Playground`,`Cctv`,`OneGateSystem`,`Deskripsi`,`MetaDeskripsi`,`Prabot`,`KetPrabot`,`Priority`,`Ttd`,`Banner`,`Size`,`Harga`,`HargaSewa`,`RangeHarga`,`TglInput`,`TglUpdate`,`Img1`,`Img2`,`Img3`,`Img4`,`Img5`,`Img6`,`Img7`,`Img8`,`Img9`,`Img10`,`Img11`,`Img12`,`Video`,`LinkFacebook`,`LinkTiktok`,`LinkInstagram`,`LinkYoutube`,`IsAdmin`,`IsManager`,`IsRejected`,`Sold`,`Rented`,`SoldAgen`,`RentedAgen`,`View`,`Marketable`,`StatusHarga`,`IsSelfie`,`IsLokasi`,`Surveyor`,`Fee`,`NoKtp`,`ImgKtp`,`TipeHarga`,`Pending`,`IsCekLokasi`,`IsDouble`,`IsDelete`
                                FROM `pralisting` 
                                WHERE `IdPraListing` = $id;
                                ");
        return $this->db->insert_id();
	}
    
    public function addListing($id){
	    $query = $this->db->query("INSERT INTO `listing` (
                                  `IdAgen`, `IdAgenCo`, `IdInput`, `IdVendor`, `NoArsip`, `NamaListing`, `MetaNamaListing`, `Alamat`, `AlamatTemplate`, `Latitude`, `Longitude`, `Location`, `Wilayah`, `Selfie`, `Wide`, `Land`, `Dimensi`, `Listrik`, `Level`, `Bed`, `Bath`, `BedArt`, `BathArt`, `Garage`, `Carpot`, `Hadap`, `SHM`, `HGB`, `HSHP`, `PPJB`, `Stratatitle`, `AJB`, `PetokD`, `Pjp`, `ImgSHM`, `ImgHGB`, `ImgHSHP`, `ImgPPJB`, `ImgStratatitle`, `ImgAJB`, `ImgPetokD`, `ImgPjp`, `ImgPjp1`, `NoCertificate`, `Pbb`, `JenisProperti`, `JenisCertificate`, `SumberAir`, `Kondisi`, `Deskripsi`, `Prabot`, `KetPrabot`, `Priority`, `Ttd`, `Banner`, `Size`, `Harga`, `HargaSewa`, `RangeHarga`, `TglInput`, `Img1`, `Img2`, `Img3`, `Img4`, `Img5`, `Img6`, `Img7`, `Img8`, `Img9`, `Img10`, `Img11`, `Img12`, `Video`, `LinkFacebook`, `LinkTiktok`, `LinkInstagram`, `LinkYoutube`, `IsAdmin`, `IsManager`, `IsRejected`, `Sold`, `Rented`, `SoldAgen`, `RentedAgen`, `View`, `Marketable`, `StatusHarga`, `IsSelfie`, `IsLokasi`, `Surveyor`, `Fee`, `NoKtp`, `ImgKtp`, `TipeHarga`, `Pending`, `IsCekLokasi`, `IsDouble`, `IsDelete`
                                ) 
                                SELECT 
                                  `IdAgen`, `IdAgenCo`, `IdInput`, `IdVendor`, `NoArsip`, `NamaListing`, `MetaNamaListing`, `Alamat`, `AlamatTemplate`, `Latitude`, `Longitude`, `Location`, `Wilayah`, `Selfie`, `Wide`, `Land`, `Dimensi`, `Listrik`, `Level`, `Bed`, `Bath`, `BedArt`, `BathArt`, `Garage`, `Carpot`, `Hadap`, `SHM`, `HGB`, `HSHP`, `PPJB`, `Stratatitle`, `AJB`, `PetokD`, `Pjp`, `ImgSHM`, `ImgHGB`, `ImgHSHP`, `ImgPPJB`, `ImgStratatitle`, `ImgAJB`, `ImgPetokD`, `ImgPjp`, `ImgPjp1`, `NoCertificate`, `Pbb`, `JenisProperti`, `JenisCertificate`, `SumberAir`, `Kondisi`, `Deskripsi`, `Prabot`, `KetPrabot`, `Priority`, `Ttd`, `Banner`, `Size`, `Harga`, `HargaSewa`, `RangeHarga`, `TglInput`, `Img1`, `Img2`, `Img3`, `Img4`, `Img5`, `Img6`, `Img7`, `Img8`, `Img9`, `Img10`, `Img11`, `Img12`, `Video`, `LinkFacebook`, `LinkTiktok`, `LinkInstagram`, `LinkYoutube`, `IsAdmin`, `IsManager`, `IsRejected`, `Sold`, `Rented`, `SoldAgen`, `RentedAgen`, `View`, `Marketable`, `StatusHarga`, `IsSelfie`, `IsLokasi`, `Surveyor`, `Fee`, `NoKtp`, `ImgKtp`, `TipeHarga`, `Pending`, `IsCekLokasi`, `IsDouble`, `IsDelete`
                                FROM `pralisting` 
                                WHERE `IdPraListing` = $id;
                                ");
        return $this->db->insert_id();
	}
	
	public function addListingfinal($id){
	    $query = $this->db->query("INSERT INTO `listing` (
                                  `IdAgen`, `IdAgenCo`, `IdInput`, `IdVendor`, `NoArsip`, `NamaListing`, `MetaNamaListing`, `Alamat`, `AlamatTemplate`, `Latitude`, `Longitude`, `Location`, `Wilayah`, `Selfie`, `Wide`, `Land`, `Dimensi`, `Listrik`, `Level`, `Bed`, `Bath`, `BedArt`, `BathArt`, `Garage`, `Carpot`, `Hadap`, `SHM`, `HGB`, `HSHP`, `PPJB`, `Stratatitle`, `AJB`, `PetokD`, `Pjp`, `ImgSHM`, `ImgHGB`, `ImgHSHP`, `ImgPPJB`, `ImgStratatitle`, `ImgAJB`, `ImgPetokD`, `ImgPjp`, `ImgPjp1`, `NoCertificate`, `Pbb`, `JenisProperti`, `JenisCertificate`, `SumberAir`, `Kondisi`, `Deskripsi`, `Prabot`, `KetPrabot`, `Priority`, `Ttd`, `Banner`, `Size`, `Harga`, `HargaSewa`, `RangeHarga`, `TglInput`, `Img1`, `Img2`, `Img3`, `Img4`, `Img5`, `Img6`, `Img7`, `Img8`, `Img9`, `Img10`, `Img11`, `Img12`, `Video`, `LinkFacebook`, `LinkTiktok`, `LinkInstagram`, `LinkYoutube`, `IsAdmin`, `IsManager`, `IsRejected`, `Sold`, `Rented`, `SoldAgen`, `RentedAgen`, `View`, `Marketable`, `StatusHarga`, `IsSelfie`, `IsLokasi`, `Surveyor`, `Fee`, `NoKtp`, `ImgKtp`, `TipeHarga`, `Pending`, `IsCekLokasi`, `IsDouble`, `IsDelete`
                                ) 
                                SELECT 
                                  `IdAgen`, `IdAgenCo`, `IdInput`, `IdVendor`, `NoArsip`, `NamaListing`, `MetaNamaListing`, `Alamat`, `AlamatTemplate`, `Latitude`, `Longitude`, `Location`, `Wilayah`, `Selfie`, `Wide`, `Land`, `Dimensi`, `Listrik`, `Level`, `Bed`, `Bath`, `BedArt`, `BathArt`, `Garage`, `Carpot`, `Hadap`, `SHM`, `HGB`, `HSHP`, `PPJB`, `Stratatitle`, `AJB`, `PetokD`, `Pjp`, `ImgSHM`, `ImgHGB`, `ImgHSHP`, `ImgPPJB`, `ImgStratatitle`, `ImgAJB`, `ImgPetokD`, `ImgPjp`, `ImgPjp1`, `NoCertificate`, `Pbb`, `JenisProperti`, `JenisCertificate`, `SumberAir`, `Kondisi`, `Deskripsi`, `Prabot`, `KetPrabot`, `Priority`, `Ttd`, `Banner`, `Size`, `Harga`, `HargaSewa`, `RangeHarga`, `TglInput`, `Img1`, `Img2`, `Img3`, `Img4`, `Img5`, `Img6`, `Img7`, `Img8`, `Img9`, `Img10`, `Img11`, `Img12`, `Video`, `LinkFacebook`, `LinkTiktok`, `LinkInstagram`, `LinkYoutube`, `IsAdmin`, `IsManager`, `IsRejected`, `Sold`, `Rented`, `SoldAgen`, `RentedAgen`, `View`, `Marketable`, `StatusHarga`, `IsSelfie`, `IsLokasi`, `Surveyor`, `Fee`, `NoKtp`, `ImgKtp`, `TipeHarga`, `Pending`, `IsCekLokasi`, `IsDouble`, `IsDelete`
                                FROM `pralisting` 
                                WHERE `IdPraListing` = $id;
                                ");
        return $this->db->insert_id();
	}
	
	 public function getDataListingBaru($page, $pageSize){
	     
	     $offset = ($page - 1) * $pageSize;
	     
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor,
                                        template.IdTemplate,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                        listing 
                                        INNER JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor) 
                                        LEFT JOIN template ON listing.IdListing = template.IdListing 
                                    WHERE 
                                        listing.Sold = 0 AND listing.Rented = 0 AND listing.SoldAgen = 0 AND listing.RentedAgen = 0 AND listing.Pending = 0 AND listing.IsDouble = 0 AND listing.IsDelete = 0
                                    ORDER BY 
                                        listing.IdListing DESC
                                    LIMIT $pageSize OFFSET $offset;");
        return $query->result_array();
    }
	
    public function getListing(){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                        listing INNER JOIN agen ON listing.IdAgen = agen.IdAgen LEFT JOIN vendor USING(IdVendor) 
                                    WHERE 
                                        listing.Sold = 0 AND listing.Rented = 0 AND listing.SoldAgen = 0 AND listing.RentedAgen = 0 AND listing.Pending = 0 AND listing.IsDouble = 0 AND listing.IsDelete = 0
                                    ORDER BY 
                                        listing.IdListing DESC");
        return $query->result_array();
    }
    
    public function getListingSold(){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                        listing INNER JOIN agen ON listing.IdAgen = agen.IdAgen LEFT JOIN vendor USING(IdVendor) 
                                    WHERE 
                                        listing.Sold = 1 OR listing.Rented = 1 OR listing.SoldAgen = 1 OR listing.RentedAgen = 1 AND listing.IsDouble = 0 AND listing.IsDelete = 0
                                    ORDER BY 
                                        listing.IdListing DESC");
        return $query->result_array();
    }
    
    public function getListingTerbaru(){
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        listing 
                                    ORDER BY 
                                        `listing`.`TglInput` DESC
                                    WHERE
                                        listing.IsDouble = 0 AND listing.IsDelete = 0");
        return $query->result_array();
    }
    
    public function getListingHot(){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                        listing INNER JOIN agen ON listing.IdAgen = agen.IdAgen LEFT JOIN vendor USING(IdVendor) 
                                    WHERE 
                                        listing.Sold = 0 AND listing.Rented = 0 AND listing.SoldAgen = 0 AND listing.RentedAgen = 0 AND listing.Priority = 'exclusive' AND listing.Pending = 0 AND listing.IsDouble = 0 AND listing.IsDelete = 0
                                    ORDER BY 
                                        listing.IdListing DESC");
        return $query->result_array();
    }
	
    public function getListingFinal(){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor,
                                        template.IdTemplate,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                        listing 
                                        INNER JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor) 
                                        LEFT JOIN template ON listing.IdListing = template.IdListing 
                                    WHERE 
                                        listing.Sold = 0 AND listing.Rented = 0 AND listing.SoldAgen = 0 AND listing.RentedAgen = 0 AND listing.Pending = 0 AND listing.IsDouble = 0 AND listing.IsDelete = 0
                                    ORDER BY 
                                        listing.IdListing DESC;");
        return $query->result_array();
    }
    
    public function getListingSoldFinal(){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor,
                                        template.IdTemplate,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                        listing 
                                        INNER JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor)
                                        LEFT JOIN template ON listing.IdListing = template.IdListing 
                                    WHERE 
                                        listing.Sold = 1 OR listing.Rented = 1 OR listing.SoldAgen = 1 OR listing.RentedAgen = 1 AND listing.Pending = 0 AND listing.IsDouble = 0 AND listing.IsDelete = 0
                                    ORDER BY 
                                        listing.IdListing DESC");
        return $query->result_array();
    }
    
    public function getListingTerbaruFinal(){
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        listing 
                                    ORDER BY 
                                        `listing`.`TglInput` DESC
                                    WHERE
                                        listing.IsDouble = 0 AND listing.IsDelete = 0");
        return $query->result_array();
    }
    
    public function getListingHotFinal(){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor,
                                        template.IdTemplate,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                        listing 
                                        INNER JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor)
                                        LEFT JOIN template ON listing.IdListing = template.IdListing 
                                    WHERE 
                                        listing.Sold = 0 AND listing.Rented = 0 AND listing.SoldAgen = 0 AND listing.RentedAgen = 0 AND listing.Priority = 'exclusive' AND listing.Pending = 0 AND listing.IsDouble = 0 AND listing.IsDelete = 0
                                    ORDER BY 
                                        listing.IdListing DESC");
        return $query->result_array();
    }
    
    public function getListingAgenFinal($id){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor,
                                        template.IdTemplate,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                        listing 
                                        INNER JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor) 
                                        LEFT JOIN template ON listing.IdListing = template.IdListing 
                                    WHERE 
                                        listing.Sold = 0 AND listing.Rented = 0 AND listing.SoldAgen = 0 AND listing.RentedAgen = 0 AND listing.IdAgen = $id AND listing.Pending = 0 AND listing.IsDouble = 0 AND listing.IsDelete = 0
                                    ORDER BY 
                                        listing.IdListing DESC");
        return $query->result_array();
    }
    
    public function getListingDeepFinal($id){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor,
                                        template.IdTemplate,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                        listing 
                                        INNER JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor) 
                                        LEFT JOIN template ON listing.IdListing = template.IdListing 
                                    WHERE 
                                        listing.IdListing = $id");
        return $query->result_array();
    }
    
    public function getListingPending(){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor,
                                        template.IdTemplate,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                        listing 
                                        INNER JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor) 
                                        LEFT JOIN template ON listing.IdListing = template.IdListing 
                                    WHERE 
                                        listing.Pending = 1 AND listing.IsDouble = 0 AND listing.IsDelete = 0");
        return $query->result_array();
    }
    
    public function getListingAgen($id){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                        listing 
                                        INNER JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor) 
                                    WHERE 
                                        listing.Sold = 0 AND listing.Rented = 0 AND listing.IdAgen = $id AND listing.IsDouble = 0 AND listing.IsDelete = 0
                                    ORDER BY 
                                        listing.IdListing DESC");
        return $query->result_array();
    }
    
    public function getListingDeep($id){
        $query = $this->db->query("SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                        listing 
                                        INNER JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor) 
                                    WHERE 
                                        listing.IdListing = $id");
        return $query->result_array();
    }
	
    public function getListingSekitar($wilayah, $jenis, $kondisi){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor,
                                        template.IdTemplate,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                        listing 
                                        INNER JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor)
                                        LEFT JOIN template ON listing.IdListing = template.IdListing 
                                    WHERE 
                                        listing.Sold = 0 AND listing.Rented = 0 AND listing.SoldAgen = 0 AND listing.RentedAgen = 0 AND listing.Pending = 0 AND listing.IsDouble = 0 AND listing.IsDelete = 0 AND listing.Wilayah Like '%$wilayah%' AND listing.JenisProperti = '$jenis' AND listing.Kondisi = '$kondisi'
                                    ORDER BY 
                                        listing.IdListing DESC");
        return $query->result_array();
    }
	
    public function getListingTerkait($jenis, $kondisi){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor,
                                        template.IdTemplate,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                        listing 
                                        INNER JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor)
                                        LEFT JOIN template ON listing.IdListing = template.IdListing 
                                    WHERE 
                                        listing.Sold = 0 AND listing.Rented = 0 AND listing.SoldAgen = 0 AND listing.RentedAgen = 0 AND listing.Pending = 0 AND listing.IsDouble = 0 AND listing.IsDelete = 0 AND listing.JenisProperti = '$jenis' AND listing.Kondisi = '$kondisi'
                                    ORDER BY 
                                        listing.IdListing DESC");
        return $query->result_array();
    }
    
    public function getListingPDF($id){
        $query = $this->db->query(" SELECT
                                    	listing.*,
                                        vendor.*,
                                        template.*,
                                        agen.Nama AS NamaAgen,
                                        agenco.Nama As NamaAgenCo
                                    FROM
                                    	listing
                                        LEFT JOIN vendor USING(IdVendor)
                                        LEFT JOIN template USING(IdListing)
                                        LEFT JOIN agen USING(IdAgen)
                                        LEFT JOIN agen AS agenco ON listing.IdAgenCo = agenco.IdAgen
                                    WHERE
                                    	listing.IdListing = $id;");
        return $query->result_array();
    }
    
    public function getCoListing($id){
        $query = $this->db->query(" SELECT 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram 
                                    FROM 
                                        agen 
                                    WHERE 
                                        agen.IdAgen = $id");
        return $query->result_array();
    }
    
    public function getPrimary(){
        $query = $this->db->query(" SELECT 
                                        listingprimary.* 
                                    FROM 
                                        listingprimary 
                                    ORDER BY 
                                        listingprimary.IdListingPrimary DESC");
        return $query->result_array();
    }
    
    public function getTipePrimary($id){
        $query = $this->db->query(" SELECT 
                                        tipeprimary.* 
                                    FROM 
                                        tipeprimary 
                                    WHERE 
                                        IdListingPrimary = $id");
        return $query->result_array();
    }
    
    public function countLike($idlisting){
	    $query = $this->db->query(" SELECT 
	                                    COUNT(*) AS fav 
	                                FROM 
	                                    favorite 
	                                WHERE 
	                                    IdListing= $idlisting");
	    return $query->result_array();
	}
    
    public function getSusulan($id){
        $query = $this->db->query(" SELECT 
                                    	susulan.Keterangan,
                                        susulan.PoinTambahan,
                                        susulan.PoinBerkurang,
                                        susulan.TglInput,
                                        listing.TglInput AS tgl
                                    FROM 
                                    	susulan
                                    JOIN
                                    	listing ON susulan.IdListing = listing.IdListing
                                    WHERE 
                                    	MONTH(susulan.TglInput) <> MONTH(listing.TglInput) AND susulan.IdListing = $id");
        return $query->result_array();
    }
    
    public function getIsCekLokasi($id){
        $query = $this->db->query(" SELECT 
                                        listing.IsCekLokasi
                                    FROM 
                                        listing
                                    WHERE 
                                        IdListing = $id ");
        return $query->result_array();
    }
    
    public function getNoArsip($id){
        $query = $this->db->query(" SELECT 
                                        listing.NoArsip
                                    FROM 
                                        listing
                                    WHERE 
                                        IdListing = $id ");
        return $query->result_array();
    }
    
    public function getTemplateDouble($id){
        $query = $this->db->query(" SELECT 
                                        COUNT(IdTemplate) AS total 
                                    FROM 
                                        template 
                                    WHERE 
                                        IdListing = $id ");
        return $query->result_array();
    }
    
    public function countListingPending(){
        $query = $this->db->query(" SELECT 
                                    	COUNT(*) AS Total
                                    FROM 
                                        listing 
                                    WHERE 
                                        listing.Pending = 1 AND listing.IsDouble = 0 AND listing.IsDelete = 0");
        return $query->result_array();
        
    }
    
    // Rejected ======================================================================================================================================================================================
    
    public function getRejectedAgen($id){
        $query = $this->db->query(" SELECT 
                                        pralisting.*, 
                                        keteranganreject.Keterangan, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                        pralisting LEFT JOIN keteranganreject USING(IdPraListing) LEFT JOIN agen USING(IdAgen) LEFT JOIN vendor USING(IdVendor) 
                                    WHERE 
                                        pralisting.IdAgen = $id  AND IsRejected = 1 ORDER BY pralisting.Priority ASC, pralisting.IdPraListing DESC");
        return $query->result_array();
    }
    
    public function getPraListingRejected(){
        $query = $this->db->query(" SELECT 
                                        pralisting.*, 
                                        keteranganreject.Keterangan, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                        pralisting LEFT JOIN keteranganreject USING(IdPraListing) LEFT JOIN agen USING(IdAgen) LEFT JOIN vendor USING(IdVendor) 
                                    WHERE 
                                        pralisting.IsRejected = 1 ORDER BY pralisting.Priority ASC, pralisting.IdPraListing DESC");
        return $query->result_array();
    }
    
    public function getKeteranganRejected($id){
        $query = $this->db->query(" SELECT 
                                        Keterangan 
                                    FROM 
                                        keteranganreject 
                                    WHERE 
                                        IdPraListing = $id");
        return $query->result_array();
    }
    
    // Follow Up =====================================================================================================================================================================================
    
    public function getFlowUpAgen(){
        $query = $this->db->query(" SELECT 
                                        flowup.*, 
                                        listing.NamaListing, 
                                        listing.Alamat, 
                                        listing.Latitude, 
                                        listing.Longitude, 
                                        listing.Harga, 
                                        listing.Img1, 
                                        listing.Img2, 
                                        listing.Img3, 
                                        listing.Img4, 
                                        listing.Img5, 
                                        listing.Img6, 
                                        listing.Img7, 
                                        listing.Img8, 
                                        listing.Img9, 
                                        listing.Img10, 
                                        listing.Img11, 
                                        listing.Img12
                                    FROM 
                                        flowup INNER JOIN listing ON flowup.IdListing = listing.IdListing
                                    WHERE 
                                        IsClose = 0");
        return $query->result_array();
    }
    
    public function getFlowUp($id){
        $query = $this->db->query(" SELECT 
                                        flowup.*, 
                                        listing.NamaListing, 
                                        listing.Alamat, 
                                        listing.Latitude, 
                                        listing.Longitude, 
                                        listing.Harga, 
                                        listing.Img1, 
                                        listing.Img2, 
                                        listing.Img3, 
                                        listing.Img4, 
                                        listing.Img5, 
                                        listing.Img6, 
                                        listing.Img7, 
                                        listing.Img8, 
                                        listing.Img9, 
                                        listing.Img10, 
                                        listing.Img11, 
                                        listing.Img12 
                                    FROM 
                                        flowup INNER JOIN listing ON flowup.IdListing = listing.IdListing 
                                    WHERE 
                                        flowup.IsClose = 0 AND flowup.IdInput = $id ORDER BY listing.IdListing DESC");
        return $query->result_array();
    }
    
    public function getUpdateFlowUp($id){
        $query = $this->db->query(" SELECT 
                                        updatefollowup.* 
                                    FROM 
                                        updatefollowup 
                                    WHERE 
                                        IdFlowup = $id");
        return $query->result_array();
    }
    
    public function getUpdateFlowUpPrimary($id){
        $query = $this->db->query(" SELECT 
                                        updatefollowupprimary.*
                                    FROM 
                                        updatefollowupprimary
                                    WHERE 
                                        IdFlowup = $id");
        return $query->result_array();
    }
    
    public function getFlowUpPrimaryAgen(){
        $query = $this->db->query(" SELECT 
                                        flowupprimary.*, 
                                        listingprimary.JudulListingPrimary, 
                                        listingprimary.AlamatListingPrimary, 
                                        listingprimary.HargaListingPrimary 
                                    FROM 
                                        flowupprimary INNER JOIN listingprimary ON flowupprimary.IdListingPrimary = listingprimary.IdListingPrimary");
        return $query->result_array();
    }
    
    public function getFlowUpPrimary($id){
        $query = $this->db->query(" SELECT 
                                        flowupprimary.*, 
                                        listingprimary.JudulListingPrimary, 
                                        listingprimary.AlamatListingPrimary, 
                                        listingprimary.HargaListingPrimary 
                                    FROM 
                                        flowupprimary INNER JOIN listingprimary ON flowupprimary.IdListingPrimary = listingprimary.IdListingPrimary 
                                    WHERE 
                                        flowupprimary.IdInput = $id ORDER BY listingprimary.IdListingPrimary DESC");
        return $query->result_array();
    }
    
    public function getFlowUpInfoAgen(){
        $query = $this->db->query(" SELECT 
                                        flowupinfo.*, 
                                        infoproperty.*
                                    FROM 
                                        flowupinfo INNER JOIN infoproperty ON flowupinfo.IdAgen = infoproperty.IdAgen");
        return $query->result_array();
    }
    
    public function getFlowUpInfo($id){
        $query = $this->db->query(" SELECT 
                                        flowupinfo.*, 
                                        infoproperty.*
                                    FROM 
                                        flowupinfo INNER JOIN infoproperty ON flowupinfo.IdAgen = infoproperty.IdAgen
                                    WHERE 
                                        flowupinfo.IdAgen = $id");
        return $query->result_array();
    }
    
    public function getHistoryFollowUp(){
        $query = $this->db->query(" SELECT 
                                        flowup.*, 
                                        listing.NamaListing, 
                                        listing.Alamat, 
                                        listing.Latitude, 
                                        listing.Longitude, 
                                        listing.Harga, 
                                        listing.Img1, 
                                        listing.Img2, 
                                        listing.Img3, 
                                        listing.Img4, 
                                        listing.Img5, 
                                        listing.Img6, 
                                        listing.Img7, 
                                        listing.Img8, 
                                        listing.Img9, 
                                        listing.Img10, 
                                        listing.Img11, 
                                        listing.Img12 
                                    FROM 
                                        flowup INNER JOIN listing ON flowup.IdListing = listing.IdListing 
                                    WHERE 
                                        flowup.IsClose = 1 ORDER BY flowup.IdFlowup DESC");
        return $query->result_array();
    }
    
    public function getImg(){
        $query = $this->db->query(" SELECT 
                                        Img1,
                                        Img2,
                                        Img3,
                                        Img4,
                                        Img5,
                                        Img6,
                                        Img7,
                                        Img8, 
                                        Img9, 
                                        Img10, 
                                        Img11, 
                                        Img12
                                    FROM 
                                        `pralisting` 
                                    WHERE 
                                        IdPraListing > 700");
        return $query->result_array();
    }
    
    // Share Lokasi Selfie ===========================================================================================================================================================================
    
    public function getReportAgen(){
        $query = $this->db->query(" SELECT 
                                        `Nama`,
                                        `NoKaryawan` 
                                    FROM 
                                        karyawan 
                                    ORDER BY CAST(SUBSTRING_INDEX(NoKaryawan, '-', -1) AS SIGNED)");
        return $query->result_array();
    }
    
    // LAPORAN =======================================================================================================================================================================================
    
    public function getLaporanListing(){
        $query = $this->db->query("SELECT 
                                    	listing.*, 
                                        agenon.IdAgen, 
                                        agenon.Nama, 
                                    	agenon.NoTelp, 
                                        agenon.Instagram, 
                                        agenco.Nama AS NamaCo, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                    	vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                    	listing 
                                    LEFT JOIN 
                                    	agen AS agenon ON listing.IdAgen = agenon.IdAgen 
                                    LEFT JOIN 
                                    	agen AS agenco ON listing.IdAgenCo = agenco.IdAgen 
                                    LEFT JOIN 
                                    	vendor USING(IdVendor) 
                                    WHERE 
                                    	listing.TglInput BETWEEN '2024-10-01' AND '2024-10-31' AND listing.IsDouble = 0 AND listing.IsDelete = 0");
        return $query->result_array();
    }
    
    public function getLaporanListingFinal(){
        $query = $this->db->query(" SELECT 
                                        listing.*, 
                                        agen.IdAgen, 
                                        agen.Nama, 
                                        agen.NoTelp, 
                                        agen.Instagram, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                        vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                        listing 
                                        INNER JOIN agen ON listing.IdAgen = agen.IdAgen 
                                        LEFT JOIN vendor USING(IdVendor) 
                                    WHERE 
                                        listing.TglInput BETWEEN DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY) AND CURRENT_DATE() AND listing.IsDouble = 0 AND listing.IsDelete = 0");
        return $query->result_array();
    }
    
    public function getLaporanPraListing(){
        $query = $this->db->query("SELECT 
                                    	pralisting.*, 
                                        agenon.IdAgen, 
                                        agenon.Nama, 
                                    	agenon.NoTelp, 
                                        agenon.Instagram, 
                                        agenco.Nama AS NamaCo, 
                                        vendor.IdVendor, 
                                        vendor.NamaLengkap AS NamaVendor, 
                                    	vendor.NoTelp AS NoTelpVendor 
                                    FROM 
                                    	pralisting 
                                    LEFT JOIN 
                                    	agen AS agenon ON pralisting.IdAgen = agenon.IdAgen 
                                    LEFT JOIN 
                                    	agen AS agenco ON pralisting.IdAgenCo = agenco.IdAgen 
                                    LEFT JOIN 
                                    	vendor USING(IdVendor) 
                                    WHERE 
                                    	pralisting.TglInput BETWEEN '2024-10-01' AND '2024-10-31' AND pralisting.IsCekLokasi = 0");
        return $query->result_array();
    }
    
    public function getLaporanSusulan(){
        $query = $this->db->query(" SELECT 
                                    	susulan.Keterangan, 
                                        susulan.PoinTambahan, 
                                        susulan.PoinBerkurang, 
                                        susulan.TglInput, 
                                        listing.TglInput AS tgl,
                                        listing.Alamat,
                                        agen.KodeAgen
                                    FROM 
                                    	susulan 
                                    JOIN 
                                    	listing ON susulan.IdListing = listing.IdListing 
                                    JOIN
                                        agen ON listing.IdAgen = agen.IdAgen
                                    WHERE 
                                    	MONTH(susulan.TglInput) <> MONTH(listing.TglInput) AND susulan.TglInput BETWEEN '2024-10-01' AND '2024-10-31'");
        return $query->result_array();
    }
    	
	public function countListingReady(){
	    $query = $this->db->query(" SELECT 
	                                    COUNT(*) AS sum 
	                                FROM 
	                                    listing 
	                                WHERE 
	                                    (Sold = 0 AND SoldAgen = 0 AND Rented = 0 AND RentedAgen = 0) AND listing.IsDouble = 0 AND listing.IsDelete = 0");
	    return $query->result_array();
	}
    	
	public function countListingSolded(){
	    $query = $this->db->query(" SELECT 
	                                    COUNT(*) AS sum 
	                                FROM 
	                                    listing 
	                                WHERE 
	                                    (Sold = 1 OR SoldAgen = 1) AND listing.IsDouble = 0 AND listing.IsDelete = 0");
	    return $query->result_array();
	}
    	
	public function countListingRented(){
	    $query = $this->db->query(" SELECT 
	                                    COUNT(*) AS sum 
	                                FROM 
	                                    listing
	                                WHERE 
	                                    (Rented = 1 OR RentedAgen = 1) AND listing.IsDouble = 0 AND listing.IsDelete = 0");
	    return $query->result_array();
	}
    	
	public function countListingSold(){
	    $query = $this->db->query(" SELECT 
	                                    COUNT(*) AS sum 
	                                FROM 
	                                    listing 
	                                WHERE 
	                                    Kondisi = 'Jual' AND listing.IsDouble = 0 AND listing.IsDelete = 0");
	    return $query->result_array();
	}
    	
	public function countListingRent(){
	    $query = $this->db->query(" SELECT 
	                                    COUNT(*) AS sum 
	                                FROM 
	                                    listing 
	                                WHERE 
	                                    Kondisi = 'Sewa' AND listing.IsDouble = 0 AND listing.IsDelete = 0");
	    return $query->result_array();
	}
    	
	public function countListingSoldRent(){
	    $query = $this->db->query(" SELECT 
	                                    COUNT(*) AS sum 
	                                FROM 
	                                    listing 
	                                WHERE 
	                                    Kondisi = 'Jual/Sewa' AND listing.IsDouble = 0 AND listing.IsDelete = 0");
	    return $query->result_array();
	}
    	
	public function countListingAll(){
	    $query = $this->db->query(" SELECT 
	                                    COUNT(*) AS sum 
	                                FROM 
	                                    listing
	                                WHERE
	                                    listing.IsDouble = 0 AND listing.IsDelete = 0");
	    return $query->result_array();
	}
    	
	public function countListingTahun(){
	    $query = $this->db->query(" SELECT 
	                                    COUNT(*) AS sum 
	                                FROM 
	                                    listing 
	                                WHERE 
	                                    YEAR(listing.TglInput) = YEAR(CURDATE()) AND listing.IsDouble = 0 AND listing.IsDelete = 0");
	    return $query->result_array();
	}
    	
	public function countListingBulan(){
	    $query = $this->db->query(" SELECT 
	                                    COUNT(*) AS sum 
	                                FROM 
	                                    listing 
	                                WHERE 
	                                    YEAR(listing.TglInput) = YEAR(CURDATE()) AND MONTH(listing.TglInput) = MONTH(CURDATE()) AND listing.IsDouble = 0 AND listing.IsDelete = 0");
	    return $query->result_array();
	}
    
    // TBO ===========================================================================================================================================================================================
    
    public function getCountListingAgenBulanLalu($id){
        $query = $this->db->query("SELECT 
                                    	COUNT(*) AS TotalPoin
                                    FROM 
                                    	listing
                                    WHERE 
                                    	(IdAgen = $id OR IdAgenCo = $id) AND MONTH(TglInput) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND listing.IsDouble = 0 AND listing.IsDelete = 0");
        return $query->result_array();
    }
    
    public function getCountInfoAgenBulanLalu($id){
        $query = $this->db->query("SELECT 
                                    	COUNT(*) AS TotalPoin
                                    FROM 
                                    	infoproperty
                                    WHERE 
                                    	IdAgen = $id AND MONTH(TglInput) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))");
        return $query->result_array();
    }
    
    public function getCountOpenAgenBulanLalu($id){
        $query = $this->db->query("SELECT 
                                    	COUNT(*) AS TotalPoin
                                    FROM 
                                    	listing
                                    WHERE 
                                    	(IdAgen = $id OR IdAgenCo = $id) AND Priority = 'open' AND MONTH(TglInput) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND listing.IsDouble = 0 AND listing.IsDelete = 0");
        return $query->result_array();
    }
    
    public function getCountExclusiveAgenBulanLalu($id){
        $query = $this->db->query("SELECT 
                                    	COUNT(*) AS TotalPoin
                                    FROM 
                                    	listing
                                    WHERE 
                                    	(IdAgen = $id OR IdAgenCo = $id) AND Priority = 'exclusive' AND MONTH(TglInput) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND listing.IsDouble = 0 AND listing.IsDelete = 0");
        return $query->result_array();
    }
    
    public function getCountBannerAgenBulanLalu($id){
        $query = $this->db->query("SELECT 
                                    	COUNT(*) AS TotalPoin
                                    FROM 
                                    	listing
                                    WHERE 
                                    	(IdAgen = $id OR IdAgenCo = $id) AND Banner = 'Ya' AND MONTH(TglInput) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND listing.IsDouble = 0 AND listing.IsDelete = 0");
        return $query->result_array();
    }
    
    public function getCountListingAgenBulanIni($id){
        $query = $this->db->query("SELECT 
                                    	COUNT(*) AS TotalPoin
                                    FROM 
                                    	listing
                                    WHERE 
                                    	(IdAgen = $id OR IdAgenCo = $id) AND MONTH(TglInput) = MONTH(CURRENT_DATE()) AND listing.IsDouble = 0 AND listing.IsDelete = 0");
        return $query->result_array();
    }
    
    public function getCountInfoAgenBulanIni($id){
        $query = $this->db->query("SELECT 
                                    	COUNT(*) AS TotalPoin
                                    FROM 
                                    	infoproperty
                                    WHERE 
                                    	IdAgen = $id AND MONTH(TglInput) =  MONTH(CURRENT_DATE())");
        return $query->result_array();
    }
    
    public function getCountOpenAgenBulanIni($id){
        $query = $this->db->query("SELECT 
                                    	COUNT(*) AS TotalPoin
                                    FROM 
                                    	listing
                                    WHERE 
                                    	(IdAgen = $id OR IdAgenCo = $id) AND Priority = 'open' AND MONTH(TglInput) =  MONTH(CURRENT_DATE()) AND listing.IsDouble = 0 AND listing.IsDelete = 0");
        return $query->result_array();
    }
    
    public function getCountExclusiveAgenBulanIni($id){
        $query = $this->db->query("SELECT 
                                    	COUNT(*) AS TotalPoin
                                    FROM 
                                    	listing
                                    WHERE 
                                    	(IdAgen = $id OR IdAgenCo = $id) AND Priority = 'exclusive' AND MONTH(TglInput) =  MONTH(CURRENT_DATE()) AND listing.IsDouble = 0 AND listing.IsDelete = 0");
        return $query->result_array();
    }
    
    public function getCountBannerAgenBulanIni($id){
        $query = $this->db->query("SELECT 
                                    	COUNT(*) AS TotalPoin
                                    FROM 
                                    	listing
                                    WHERE 
                                    	(IdAgen = $id OR IdAgenCo = $id) AND Banner = 'Ya' AND MONTH(TglInput) =  MONTH(CURRENT_DATE()) AND listing.IsDouble = 0 AND listing.IsDelete = 0");
        return $query->result_array();
    }
    
    public function getSumPoinBulanLalu($id){
        $query = $this->db->query(" SELECT SUM(total_poin) AS TotalPoin
                                    FROM (
                                        SELECT
                                            IdListing,
                                            IdAgen,
                                            IdAgenCo,
                                            Priority,
                                            Pjp,
                                            Banner,
                                            IsLokasi,
                                            IsSelfie,
                                            StatusHarga,
                                            Marketable,
                                            poin1,
                                            poin2,
                                            poin3,
                                            poin1 + poin2 + poin3 AS total_poin
                                        FROM (
                                            SELECT
                                                IdListing,
                                                IdAgen,
                                                IdAgenCo,
                                                Priority,
                                                Pjp,
                                                Banner,
                                                IsLokasi,
                                                IsSelfie,
                                                StatusHarga,
                                                Marketable,
                                                CASE
                                                    WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Ya' THEN 50
                                                    WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Tidak' THEN 40
                                                    WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Ya' THEN 30
                                                    WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Tidak' THEN 20
                                                    WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Ya' THEN 10
                                                    WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Tidak' THEN 10
                                                    ELSE 0
                                                END AS poin1,
                                                CASE
                                                    WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 THEN 50
                                                    WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 THEN 40
                                                    WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 THEN 30
                                                    WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 THEN 20
                                                    WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 THEN 30
                                                    WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 THEN 10
                                                    ELSE 0
                                                END AS poin2,
                                                CASE
                                                    WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 20
                                                    WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 20
                                                    WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 10
                                                    WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 20
                                                    WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 10
                                                    WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 10
                                                    ELSE 0
                                                END AS poin3
                                            FROM
                                                listing
                                            WHERE
                                                (IdAgen = $id OR IdAgenCo = $id) AND MONTH(TglInput) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                                        ) AS subquery
                                    ) AS total_poin_sum");
        return $query->result_array();
    }
    
    public function getSumPoinBulanIni($id){
        $query = $this->db->query(" SELECT SUM(total_poin) AS TotalPoin
                                    FROM (
                                        SELECT
                                            IdListing,
                                            IdAgen,
                                            IdAgenCo,
                                            Priority,
                                            Pjp,
                                            Banner,
                                            IsLokasi,
                                            IsSelfie,
                                            StatusHarga,
                                            Marketable,
                                            poin1,
                                            poin2,
                                            poin3,
                                            poin1 + poin2 + poin3 AS total_poin
                                        FROM (
                                            SELECT
                                                IdListing,
                                                IdAgen,
                                                IdAgenCo,
                                                Priority,
                                                Pjp,
                                                Banner,
                                                IsLokasi,
                                                IsSelfie,
                                                StatusHarga,
                                                Marketable,
                                                CASE
                                                    WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Ya' THEN 50
                                                    WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Tidak' THEN 40
                                                    WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Ya' THEN 30
                                                    WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Tidak' THEN 20
                                                    WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Ya' THEN 10
                                                    WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Tidak' THEN 10
                                                    ELSE 0
                                                END AS poin1,
                                                CASE
                                                    WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 THEN 50
                                                    WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 THEN 40
                                                    WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 THEN 30
                                                    WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 THEN 20
                                                    WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 THEN 30
                                                    WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 THEN 10
                                                    ELSE 0
                                                END AS poin2,
                                                CASE
                                                    WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 20
                                                    WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 20
                                                    WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 10
                                                    WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 20
                                                    WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 10
                                                    WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 10
                                                    ELSE 0
                                                END AS poin3
                                            FROM
                                                listing
                                            WHERE
                                                (IdAgen = $id OR IdAgenCo = $id) AND MONTH(TglInput) = MONTH(CURRENT_DATE())
                                        ) AS subquery
                                    ) AS total_poin_sum");
        return $query->result_array();
    }
    
    public function getSumPoinInfoBulanLalu($id){
        $query = $this->db->query(" SELECT SUM(total_poin) AS TotalPoin
                                    FROM (
                                        SELECT
                                            IdAgen,
                                        	ImgSelfie,
                                        	Latitude,
                                        	Longitude,
                                        	IsSpek,
                                            poin1,
                                            poin2,
                                            poin1 + poin2 AS total_poin
                                        FROM (
                                            SELECT
                                                IdAgen,
                                                ImgSelfie,
                                                Latitude,
                                                Longitude,
                                                IsSpek,
                                                CASE
                                                    WHEN IsSpek = '1' THEN 10
                                                    ELSE 0
                                                END AS poin1,
                                                CASE
                                                    WHEN ImgSelfie <> '0' AND Latitude <> '0' AND Longitude <> '0' THEN 10
                                                    ELSE 0
                                                END AS poin2
                                            FROM
                                                infoproperty
                                            WHERE
                                                IdAgen = $id AND MONTH(TglInput) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                                        ) AS subquery
                                    ) AS total_poin_sum");
        return $query->result_array();
    }
    
    public function getSumPoinInfoBulanIni($id){
        $query = $this->db->query(" SELECT SUM(total_poin) AS TotalPoin
                                    FROM (
                                        SELECT
                                            IdAgen,
                                        	ImgSelfie,
                                        	Latitude,
                                        	Longitude,
                                        	IsSpek,
                                            poin1,
                                            poin2,
                                            poin1 + poin2 AS total_poin
                                        FROM (
                                            SELECT
                                                IdAgen,
                                                ImgSelfie,
                                                Latitude,
                                                Longitude,
                                                IsSpek,
                                                CASE
                                                    WHEN IsSpek = '1' THEN 10
                                                    ELSE 0
                                                END AS poin1,
                                                CASE
                                                    WHEN ImgSelfie <> '0' AND Latitude <> '0' AND Longitude <> '0' THEN 10
                                                    ELSE 0
                                                END AS poin2
                                            FROM
                                                infoproperty
                                            WHERE
                                                IdAgen = $id AND MONTH(TglInput) = MONTH(CURRENT_DATE())
                                        ) AS subquery
                                    ) AS total_poin_sum");
        return $query->result_array();
    }
    
    public function getSumTotalPoinBulanLalu($id){
        $query = $this->db->query(" SELECT SUM(TotalPoin) AS TotalPoin 
                                    FROM (
                                        SELECT SUM(total_poin) AS TotalPoin
                                        FROM (
                                            SELECT
                                                IdAgen,
                                                ImgSelfie,
                                                Latitude,
                                                Longitude,
                                                IsSpek,
                                                poin1,
                                                poin2,
                                                poin1 + poin2 AS total_poin
                                            FROM (
                                                SELECT
                                                    IdAgen,
                                                    ImgSelfie,
                                                    Latitude,
                                                    Longitude,
                                                    IsSpek,
                                                    CASE
                                                        WHEN IsSpek = '1' THEN 10
                                                        ELSE 0
                                                    END AS poin1,
                                                    CASE
                                                        WHEN ImgSelfie <> '0' AND Latitude <> '0' AND Longitude <> '0' THEN 10
                                                        ELSE 0
                                                    END AS poin2
                                                FROM
                                                    infoproperty
                                                WHERE
                                                    IdAgen = $id AND MONTH(TglInput) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                                            ) AS subquery1
                                        ) AS total_poin_sum1
                                
                                        UNION ALL
                                    
                                        SELECT SUM(total_poin) AS TotalPoin
                                        FROM (
                                            SELECT
                                                IdListing,
                                                IdAgen,
                                                IdAgenCo,
                                                Priority,
                                                Pjp,
                                                Banner,
                                                IsLokasi,
                                                IsSelfie,
                                                StatusHarga,
                                                Marketable,
                                                poin1,
                                                poin2,
                                                poin3,
                                                poin1 + poin2 + poin3 AS total_poin
                                            FROM (
                                                SELECT
                                                    IdListing,
                                                    IdAgen,
                                                    IdAgenCo,
                                                    Priority,
                                                    Pjp,
                                                    Banner,
                                                    IsLokasi,
                                                    IsSelfie,
                                                    StatusHarga,
                                                    Marketable,
                                                    CASE
                                                        WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Ya' THEN 50
                                                        WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Tidak' THEN 40
                                                        WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Ya' THEN 30
                                                        WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Tidak' THEN 20
                                                        WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Ya' THEN 10
                                                        WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Tidak' THEN 10
                                                        ELSE 0
                                                    END AS poin1,
                                                    CASE
                                                        WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 THEN 50
                                                        WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 THEN 40
                                                        WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 THEN 30
                                                        WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 THEN 20
                                                        WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 THEN 30
                                                        WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 THEN 10
                                                        ELSE 0
                                                    END AS poin2,
                                                    CASE
                                                        WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 20
                                                        WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 20
                                                        WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 10
                                                        WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 20
                                                        WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 10
                                                        WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 10
                                                        ELSE 0
                                                    END AS poin3
                                                FROM
                                                    listing
                                                WHERE
                                                    (IdAgen = $id OR IdAgenCo = $id) AND MONTH(TglInput) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                                            ) AS subquery2
                                        ) AS total_poin_sum2
                                    ) AS total_poin_combined");
        return $query->result_array();
    }
    
    public function getSumTotalPoinBulanIni($id){
        $query = $this->db->query(" SELECT SUM(TotalPoin) AS TotalPoin 
                                    FROM (
                                        SELECT SUM(total_poin) AS TotalPoin
                                        FROM (
                                            SELECT
                                                IdAgen,
                                                ImgSelfie,
                                                Latitude,
                                                Longitude,
                                                IsSpek,
                                                poin1,
                                                poin2,
                                                poin1 + poin2 AS total_poin
                                            FROM (
                                                SELECT
                                                    IdAgen,
                                                    ImgSelfie,
                                                    Latitude,
                                                    Longitude,
                                                    IsSpek,
                                                    CASE
                                                        WHEN IsSpek = '1' THEN 10
                                                        ELSE 0
                                                    END AS poin1,
                                                    CASE
                                                        WHEN ImgSelfie <> '0' AND Latitude <> '0' AND Longitude <> '0' THEN 10
                                                        ELSE 0
                                                    END AS poin2
                                                FROM
                                                    infoproperty
                                                WHERE
                                                    IdAgen = $id AND MONTH(TglInput) = MONTH(CURRENT_DATE())
                                            ) AS subquery1
                                        ) AS total_poin_sum1
                                
                                        UNION ALL
                                    
                                        SELECT SUM(total_poin) AS TotalPoin
                                        FROM (
                                            SELECT
                                                IdListing,
                                                IdAgen,
                                                IdAgenCo,
                                                Priority,
                                                Pjp,
                                                Banner,
                                                IsLokasi,
                                                IsSelfie,
                                                StatusHarga,
                                                Marketable,
                                                poin1,
                                                poin2,
                                                poin3,
                                                poin1 + poin2 + poin3 AS total_poin
                                            FROM (
                                                SELECT
                                                    IdListing,
                                                    IdAgen,
                                                    IdAgenCo,
                                                    Priority,
                                                    Pjp,
                                                    Banner,
                                                    IsLokasi,
                                                    IsSelfie,
                                                    StatusHarga,
                                                    Marketable,
                                                    CASE
                                                        WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Ya' THEN 50
                                                        WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Tidak' THEN 40
                                                        WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Ya' THEN 30
                                                        WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Tidak' THEN 20
                                                        WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Ya' THEN 10
                                                        WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Tidak' THEN 10
                                                        ELSE 0
                                                    END AS poin1,
                                                    CASE
                                                        WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 THEN 50
                                                        WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 THEN 40
                                                        WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 THEN 30
                                                        WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 THEN 20
                                                        WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 THEN 30
                                                        WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 THEN 10
                                                        ELSE 0
                                                    END AS poin2,
                                                    CASE
                                                        WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 20
                                                        WHEN Priority = 'exclusive' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 20
                                                        WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 10
                                                        WHEN Priority = 'open' AND Pjp <> '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 20
                                                        WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Ya' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 10
                                                        WHEN Priority = 'open' AND Pjp = '' AND Banner = 'Tidak' AND IsSelfie = 1 AND IsLokasi = 1 AND Marketable = 1 AND StatusHarga = 1 THEN 10
                                                        ELSE 0
                                                    END AS poin3
                                                FROM
                                                    listing
                                                WHERE
                                                    (IdAgen = $id OR IdAgenCo = $id) AND MONTH(TglInput) = MONTH(CURRENT_DATE())
                                            ) AS subquery2
                                        ) AS total_poin_sum2
                                    ) AS total_poin_combined");
        return $query->result_array();
    }
    
    public function getReportKinerjaOfficer($id){
        $query = $this->db->query(" SELECT
                                        reportkinerja.Keterangan,
                                        listing.NamaListing,
                                        listing.Alamat,
                                        listing.Location,
                                        listing.Wilayah,
                                        listing.JenisProperti,
                                        listing.Kondisi,
                                        listing.Priority,
                                        listing.Img1,
                                        listing.Img2,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                    	reportkinerja
                                    	LEFT JOIN listing USING(IdListing)
                                    	LEFT JOIN template USING(IdListing)
                                    WHERE 
                                        DATE(Tanggal) = CURDATE() AND reportkinerja.IdAgen = $id");
        return $query->result_array();
    }
    
    public function getUraianKerjaOfficer($id){
        $query = $this->db->query(" SELECT
                                        reportkinerja.Keterangan,
                                        listing.NamaListing,
                                        listing.Alamat,
                                        listing.Location,
                                        listing.Wilayah,
                                        listing.JenisProperti,
                                        listing.Kondisi,
                                        listing.Priority,
                                        listing.Img1,
                                        listing.Img2,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                    	reportkinerja
                                    	LEFT JOIN listing USING(IdListing)
                                    	LEFT JOIN template USING(IdListing)
                                    WHERE 
                                        DATE(Tanggal) = CURDATE() AND reportkinerja.Kinerja ='Kerja Harian' AND reportkinerja.IdAgen = $id");
        return $query->result_array();
    }
    
    public function getCallOfficerOfficer($id){
        $query = $this->db->query(" SELECT
                                        reportkinerja.Keterangan,
                                        listing.NamaListing,
                                        listing.Alamat,
                                        listing.Location,
                                        listing.Wilayah,
                                        listing.JenisProperti,
                                        listing.Kondisi,
                                        listing.Priority,
                                        listing.Img1,
                                        listing.Img2,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                    	reportkinerja
                                    	LEFT JOIN listing USING(IdListing)
                                    	LEFT JOIN template USING(IdListing)
                                    WHERE 
                                        DATE(Tanggal) = CURDATE() AND reportkinerja.Kinerja ='Call' AND reportkinerja.IdAgen = $id");
        return $query->result_array();
    }
    
    public function getFollowUpInfoOfficer($id){
        $query = $this->db->query(" SELECT
                                        reportkinerja.Keterangan,
                                        listing.NamaListing,
                                        listing.Alamat,
                                        listing.Location,
                                        listing.Wilayah,
                                        listing.JenisProperti,
                                        listing.Kondisi,
                                        listing.Priority,
                                        listing.Img1,
                                        listing.Img2,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                    	reportkinerja
                                    	LEFT JOIN listing USING(IdListing)
                                    	LEFT JOIN template USING(IdListing)
                                    WHERE 
                                        DATE(Tanggal) = CURDATE() AND reportkinerja.Kinerja ='Follow Up Info' AND reportkinerja.IdAgen = $id");
        return $query->result_array();
    }
    
    public function getFollowUpVendorOfficer($id){
        $query = $this->db->query(" SELECT
                                        reportkinerja.Keterangan,
                                        listing.NamaListing,
                                        listing.Alamat,
                                        listing.Location,
                                        listing.Wilayah,
                                        listing.JenisProperti,
                                        listing.Kondisi,
                                        listing.Priority,
                                        listing.Img1,
                                        listing.Img2,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                    	reportkinerja
                                    	LEFT JOIN listing USING(IdListing)
                                    	LEFT JOIN template USING(IdListing)
                                    WHERE 
                                        DATE(Tanggal) = CURDATE() AND reportkinerja.Kinerja ='Follow Up Vendor' AND reportkinerja.IdAgen = $id");
        return $query->result_array();
    }
    
    public function getFollowUpBuyerOfficer($id){
        $query = $this->db->query(" SELECT
                                        reportkinerja.Keterangan,
                                        listing.NamaListing,
                                        listing.Alamat,
                                        listing.Location,
                                        listing.Wilayah,
                                        listing.JenisProperti,
                                        listing.Kondisi,
                                        listing.Priority,
                                        listing.Img1,
                                        listing.Img2,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                    	reportkinerja
                                    	LEFT JOIN listing USING(IdListing)
                                    	LEFT JOIN template USING(IdListing)
                                    WHERE 
                                        DATE(Tanggal) = CURDATE() AND reportkinerja.Kinerja ='Follow Up Buyer' AND reportkinerja.IdAgen = $id");
        return $query->result_array();
    }
    
    public function getTinjauLokasiOfficer($id){
        $query = $this->db->query(" SELECT
                                        reportkinerja.Keterangan,
                                        reportkinerja.Tanggal,
                                        listing.NamaListing,
                                        listing.Alamat,
                                        listing.Location,
                                        listing.Wilayah,
                                        listing.JenisProperti,
                                        listing.Kondisi,
                                        listing.Priority,
                                        listing.Img1,
                                        listing.Img2,
                                        template.Template,
                                        template.TemplateBlank
                                    FROM 
                                    	reportkinerja
                                    	LEFT JOIN listing USING(IdListing)
                                    	LEFT JOIN template USING(IdListing)
                                    WHERE 
                                        DATE(Tanggal) = CURDATE() AND reportkinerja.Kinerja ='Recheck' AND reportkinerja.IdAgen = $id");
        return $query->result_array();
    }
}
