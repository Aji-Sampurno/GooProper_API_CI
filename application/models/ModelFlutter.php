<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelFlutter extends CI_Model
{
    // CRUD ============================================================================================================================================================================================
    
    public function Input_Data($data,$table){
		$this->db->insert($table,$data);
        return $this->db->affected_rows() > 0;
	}
	
	public function Update_Data($where,$data,$table){
		$this->db->where($where);
		$this->db->update($table,$data);
        return $this->db->affected_rows() > 0;
	}
    
    // Authentication ==================================================================================================================================================================================
        
    public function Login_Admin($username, $password) {
        $hashed_password = md5($password);
        
        $this->db->where('Username', $username);
        $this->db->where('Password', $hashed_password);
        $query = $this->db->get('admin');
        
        if ($query->num_rows() == 1) {
            return $query->row_array();
        } else {
            return false;
        }
    }
    
    public function Login_Agen($username, $password) {
        $hashed_password = md5($password);
        
        $this->db->where('Username', $username);
        $this->db->where('Password', $hashed_password);
        $query = $this->db->get('agen');
        
        if ($query->num_rows() == 1) {
            return $query->row_array();
        } else {
            return false;
        }
    }
    
    public function Login_Customer($username, $password) {
        $hashed_password = md5($password);
        
        $this->db->where('Username', $username);
        $this->db->where('Password', $hashed_password);
        $query = $this->db->get('customer');
        
        if ($query->num_rows() == 1) {
            return $query->row_array();
        } else {
            return false;
        }
    }
    
    public function Check_Login($idAgen) {

        $this->db->where('IdAgen', $idAgen);
        $query = $this->db->get('agen');

        if ($query->num_rows() == 1) {
            return $query->row_array();
        } else {
            return false;
        }
    }
    
    // Data ============================================================================================================================================================================================
    
    public function Get_Provinsi() {
        $query = $this->db->query(" SELECT 
                                    	*
                                    FROM 
                                    	provinsi; ");
        return $query->result_array();
    }
    
    public function Get_Daerah($id) {
        $query = $this->db->query(" SELECT 
                                    	*
                                    FROM 
                                    	daerah
                                    WHERE
                                        IdProvinsi = $id; ");
        return $query->result_array();
    }
    
    public function Get_Wilayah($id) {
        $query = $this->db->query(" SELECT 
                                    	*
                                    FROM 
                                    	wilayah
                                    WHERE
                                        IdDaerah = $id; ");
        return $query->result_array();
    }
    
    public function Get_Jenis_Properti() {
        $query = $this->db->query(" SELECT 
                                    	*
                                    FROM 
                                    	jenisproperty; ");
        return $query->result_array();
    }
    
    public function Get_Agen_List() {
        $query = $this->db->query(" SELECT
                                        agen.IdAgen,
                                        agen.NamaTemp
                                    FROM
                                        agen
                                    WHERE
                                        agen.IsAkses = 1 AND
                                        agen.Approve = 1; ");
        return $query->result_array();
    }
    
    public function Get_Agen($limit, $offset, $search = '') {
        $searchCondition = '';
        if (!empty($search)) {
            $keywords = explode(' ', $search);
            foreach ($keywords as $keyword) {
                $searchCondition .= " AND agen.NamaTemp LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
            }
        }
    
        $query = $this->db->query("
            SELECT
                agen.IdAgen,
                agen.NamaTemp,
                agen.NoTelpTemp,
                agen.Email,
                agen.KotaAgen,
                agen.Photo,
                COUNT(listing.IdListing) AS listing,
                COUNT(CASE WHEN listing.Kondisi = 'Jual' OR listing.Kondisi = 'Jual/Sewa' THEN 1 END) AS jual,
                COUNT(CASE WHEN listing.Kondisi = 'Sewa' THEN 1 END) AS sewa,
                COUNT(CASE WHEN listing.Sold = 1 OR listing.SoldAgen = 1 THEN 1 END) AS terjual,
                COUNT(CASE WHEN listing.Rented = 1 OR listing.RentedAgen = 1 THEN 1 END) AS tersewa
            FROM
                agen
                LEFT JOIN listing ON agen.IdAgen = listing.IdAgen
                AND listing.IsDouble = 0
                AND listing.IsDelete = 0
            WHERE
                agen.IsAkses = 1
                AND agen.Approve = 1
                AND agen.IsAktif = 1
                $searchCondition
            GROUP BY
                agen.IdAgen,
                agen.NamaTemp,
                agen.NoTelpTemp,
                agen.Email,
                agen.KotaAgen,
                agen.Photo
            LIMIT ? OFFSET ?
        ", array($limit, $offset));
    
        return $query->result_array();
    }
    
    public function Get_Detail_Agen($id) {
        $query = $this->db->query(" SELECT 
                                        agen.*,
                                        COUNT(listing.IdListing) AS listing,
                                        COUNT(CASE WHEN listing.Kondisi = 'Jual' OR listing.Kondisi = 'Jual/Sewa' THEN 1 END) AS jual,
                                        COUNT(CASE WHEN listing.Kondisi = 'Sewa' THEN 1 END) AS sewa,
                                        COUNT(CASE WHEN listing.Sold = 1 OR listing.SoldAgen = 1 THEN 1 END) AS terjual,
                                        COUNT(CASE WHEN listing.Rented = 1 OR listing.RentedAgen = 1 THEN 1 END) AS tersewa
                                    FROM 
                                        agen
                                        LEFT JOIN listing ON agen.IdAgen = listing.IdAgen 
                                        AND listing.IsDouble = 0 
                                        AND listing.IsDelete = 0
                                    WHERE
                                        agen.IsAkses = 1 
                                        AND agen.Approve = 1 
                                        AND agen.IdAgen = $id
                                    GROUP BY 
                                        agen.IdAgen;");
        return $query->result_array();
    }
    
    public function Get_Pelamar($limit, $offset) {
        $query = $this->db->query(" SELECT
                                        agen.IdAgen,
                                        agen.Nama,
                                        agen.NoTelp,
                                        agen.Email,
                                        agen.Pendidikan,
                                        agen.AlamatDomisili,
                                        agen.Photo
                                    FROM
                                        agen
                                    WHERE
                                        agen.IsAkses = 1 AND
                                        agen.Approve = 0 AND
                                        agen.Reject = 0
                                    LIMIT $limit OFFSET $offset; ");
        return $query->result_array();
    }
    
    public function Get_Detail_Pelamar($id) {
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        agen 
                                    WHERE 
                                        IdAgen = $id; ");
        return $query->result_array();
    }
    
    // Count ---------------------------------------------------------------
    
    public function Count_Pelamar(){
        $query = $this->db->query(" SELECT 
                                    	COUNT(*) AS Total
                                    FROM 
                                        agen 
                                    WHERE 
                                        agen.IsAkses = 1 AND
                                        agen.Approve = 0 AND
                                        agen.Reject = 0;");
        return $query->result_array();
    }
    
    // Closing =========================================================================================================================================================================================
    
    public function Get_Closing_Agen($id, $limit, $offset, $search = '') {
        $searchCondition = '';
        if (!empty($search)) {
            $keywords = explode(' ', $search);
            foreach ($keywords as $keyword) {
                $searchCondition .= " AND CONCAT(closing.NamaPemilik, ' ', closing.NamaBuyer, ' ', closing.JenisProperti, ' ', closing.AlamatProperti) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
            }
        }
        
        $query = $this->db->query(" SELECT 
                                        closing.*,
                                        agen.IdAgen AS IdAgenAgen,
                                        agen.NamaTemp,
                                        agen.NoTelp,
                                        agen.Instagram
                                    FROM 
                                        closing
                                        LEFT JOIN agen ON closing.IdAgen = agen.IdAgen
                                    WHERE 
                                        closing.IdAgen = $id
                                        $searchCondition
                                    ORDER BY 
                                        closing.TglMaksPelunasan ASC
                                    LIMIT $limit OFFSET $offset; ");
        return $query->result_array();
    }
    
    public function Get_Closing($limit, $offset, $search = '') {
        $searchCondition = '';
        if (!empty($search)) {
            $keywords = explode(' ', $search);
            foreach ($keywords as $keyword) {
                $searchCondition .= " WHERE CONCAT(closing.NamaPemilik, ' ', closing.NamaBuyer, ' ', closing.JenisProperti, ' ', closing.AlamatProperti) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
            }
        }
        
        $query = $this->db->query(" SELECT 
                                        closing.*,
                                        agen.IdAgen AS IdAgenAgen,
                                        agen.NamaTemp,
                                        agen.NoTelp,
                                        agen.Instagram
                                    FROM 
                                        closing
                                        LEFT JOIN agen ON closing.IdAgen = agen.IdAgen
                                        $searchCondition
                                    ORDER BY 
                                        closing.TglMaksPelunasan ASC
                                    LIMIT $limit OFFSET $offset; ");
        return $query->result_array();
    }
    
    public function Get_Detail_Closing($id) {
        $query = $this->db->query(" SELECT 
                                        closing.*,
                                        agen.IdAgen AS IdAgenAgen,
                                        agen.NamaTemp,
                                        agen.NoTelp,
                                        agen.Instagram
                                    FROM 
                                        closing
                                        LEFT JOIN agen ON closing.IdAgen = agen.IdAgen
                                    WHERE 
                                        closing.IdClosing = $id; ");
        return $query->result_array();
    }
    
    public function Get_Report_Closing($limit, $offset, $search = '') {
        $searchCondition = '';
        if (!empty($search)) {
            $keywords = explode(' ', $search);
            foreach ($keywords as $keyword) {
                $searchCondition .= " AND CONCAT(listing.NamaListing, ' ', listing.MetaNamaListing, ' ', listing.Alamat, ' ', listing.Location, ' ', listing.Wilayah, ' ', listing.Daerah) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
            }
        }
        
        $query = $this->db->query(" SELECT
                                        reportsold.IdReportSold,
                                        reportsold.IdListing As IdListingReport,
                                        reportsold.Report,
                                        reportsold.IsRead,
                                        listing.IdListing,
                                        listing.NamaListing,
                                        listing.Kondisi,
                                        listing.Harga,
                                        listing.HargaSewa,
                                        listing.Wide,
                                        listing.Land,
                                        listing.Priority,
                                        listing.NoArsip,
                                        listing.Img1
                                    FROM
                                        reportsold
                                        LEFT JOIN listing ON reportsold.IdListing = listing.IdListing
                                    WHERE
                                        IsRead = 0
                                        $searchCondition
                                    ORDER BY 
                                        IdListing DESC
                                    LIMIT $limit OFFSET $offset; ");
        return $query->result_array();
    }
    
    // Report Buyer ====================================================================================================================================================================================
    
    public function Get_Report_Buyer_Agen($id, $limit, $offset, $search = ''){
        $searchCondition = '';
        if (!empty($search)) {
            $keywords = explode(' ', $search);
            foreach ($keywords as $keyword) {
                $searchCondition .= " AND reportbuyer.NamaBuyer LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
            }
        }
            
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        reportbuyer 
                                    WHERE 
                                        IdAgen = $id   
                                        $searchCondition
                                    ORDER BY 
                                        TglReport ASC
                                    LIMIT $limit OFFSET $offset; ");
        return $query->result();
    }
    
    public function Get_Report_Buyer_Agen_Ready($id, $limit, $offset){
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        reportbuyer 
                                    WHERE 
                                        IdAgen = $id
                                        AND DATEDIFF(NOW(), TglReport)  < 20
                                        AND IsClose = 0
                                    ORDER BY 
                                        TglReport ASC
                                    LIMIT $limit OFFSET $offset; ");
        return $query->result();
    }
    
    public function Get_Report_Buyer_Agen_To_Expired($id, $limit, $offset){
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        reportbuyer 
                                    WHERE 
                                        IdAgen = $id
                                        AND DATEDIFF(NOW(), TglReport)  BETWEEN 20 AND 30
                                        AND IsClose = 0
                                    ORDER BY 
                                        TglReport ASC
                                    LIMIT $limit OFFSET $offset; ");
        return $query->result();
    }
    
    public function Get_Report_Buyer_Agen_Expired($id, $limit, $offset){
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        reportbuyer 
                                    WHERE 
                                        IdAgen = $id
                                        AND DATEDIFF(NOW(), TglReport) > 30
                                        AND IsClose = 0
                                    ORDER BY 
                                        TglReport ASC
                                    LIMIT $limit OFFSET $offset; ");
        return $query->result();
    }
    
    public function Get_Report_Buyer($limit, $offset, $search = ''){
        $searchCondition = '';
        if (!empty($search)) {
            $keywords = explode(' ', $search);
            foreach ($keywords as $keyword) {
                $searchCondition .= " WHERE reportbuyer.NamaBuyer LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
            }
        }
            
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        reportbuyer
                                        $searchCondition
                                    ORDER BY 
                                        TglReport ASC
                                    LIMIT $limit OFFSET $offset; ");
        return $query->result();
    }
    
    public function Get_Report_Buyer_Ready($limit, $offset){
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        reportbuyer 
                                    WHERE 
                                        DATEDIFF(NOW(), TglReport)  < 20
                                        AND IsClose = 0
                                    ORDER BY 
                                        TglReport ASC
                                    LIMIT $limit OFFSET $offset; ");
        return $query->result();
    }
    
    public function Get_Report_Buyer_Expired($limit, $offset){
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        reportbuyer 
                                    WHERE 
                                        DATEDIFF(NOW(), TglReport) > 30
                                        AND IsClose = 0
                                    ORDER BY 
                                        TglReport ASC
                                    LIMIT $limit OFFSET $offset; ");
        return $query->result();
    }
    
    public function Get_Report_Buyer_To_Expired($limit, $offset){
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        reportbuyer 
                                    WHERE 
                                        DATEDIFF(NOW(), TglReport)  BETWEEN 20 AND 30
                                        AND IsClose = 0
                                    ORDER BY
                                        DATEDIFF(NOW(), TglReport) DESC
                                    LIMIT $limit OFFSET $offset; ");
        return $query->result();
    }
    
    public function Get_Detail_Report_Buyer($id){
        $query = $this->db->query(" SELECT 
                                        reportbuyer.*,
                                        agen.IdAgen AS IdAgenAgen,
                                        agen.NamaTemp,
                                        agen.NoTelp,
                                        agen.Instagram
                                    FROM 
                                        reportbuyer
                                        LEFT JOIN agen ON reportbuyer.IdAgen = agen.IdAgen
                                    WHERE 
                                        reportbuyer.IdReportBuyer = $id; ");
        return $query->result();
    }
    
    // Tampungan =======================================================================================================================================================================================
    
        // Get -----------------------------------------------------------------
        
        public function Get_List_Tampungan($id){
            $query = $this->db->query(" SELECT 
                                            * 
                                        FROM 
                                            sharelokasi 
                                        WHERE 
                                            IdAgen = $id AND IsListing = 0; ");
            return $query->result_array();
        }
    
    // Pralisting ======================================================================================================================================================================================
    
        // Count ---------------------------------------------------------------
        
        public function Count_Pralisting_Admin(){
            $query = $this->db->query(" SELECT 
                                        	COUNT(*) AS Total
                                        FROM 
                                            pralisting 
                                        WHERE 
                                            IsRejected = 0 AND
                                            IsDelete = 0 AND
                                            IsAdmin = 0;");
            return $query->result_array();
        }
        
        public function Count_Pralisting_Manager(){
            $query = $this->db->query(" SELECT 
                                        	COUNT(*) AS Total
                                        FROM 
                                            pralisting 
                                        WHERE 
                                            IsRejected = 0 AND
                                            IsDelete = 0 AND
                                            IsAdmin = 1 AND
                                            IsManager = 0;");
            return $query->result_array();
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_List_PraListing_Agen($id, $limit, $offset){
            $query = $this->db->query(" SELECT 
                                        	IdPraListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                        	pralisting
                                        WHERE
                                            IdAgen = $id AND
                                        	(IsAdmin = 0 OR
                                            IsManager = 0) AND 
                                            IsRejected = 0 AND
                                            IsDelete = 0
                                        ORDER BY 
                                            Priority ASC, 
                                            IdPraListing DESC
                                        LIMIT $limit OFFSET $offset ");
            return $query->result_array();
        }
        
        public function Get_List_PraListing_Rejected_Agen($id, $limit, $offset){
            $query = $this->db->query(" SELECT 
                                        	IdPraListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                        	pralisting
                                        WHERE
                                            IdAgen = $id AND
                                            IsRejected = 1 AND
                                            IsDelete = 0
                                        ORDER BY 
                                            Priority ASC, 
                                            IdPraListing DESC
                                        LIMIT $limit OFFSET $offset ");
            return $query->result_array();
        }
        
        public function Get_List_PraListing_Officer(){
            $query = $this->db->query(" SELECT 
                                        	pralisting.IdPraListing,
                                            pralisting.NamaListing,
                                            pralisting.Harga,
                                            pralisting.HargaSewa,
                                            pralisting.Bed,
                                            pralisting.Bath,
                                            pralisting.Level,
                                            pralisting.Garage,
                                            pralisting.Img1
                                        FROM 
                                        	pralisting
                                        WHERE
                                        	IsCekLokasi = 0 AND
                                            IsRejected = 0 AND
                                            IsDelete = 0 AND
                                            IsAdmin = 0 AND
                                            IsManager = 0 ORDER BY 
                                            pralisting.Priority ASC, 
                                            pralisting.IdPraListing ASC; ");
            return $query->result_array();
        }
        
        public function Get_List_PraListing_Admin($limit, $offset){
            $query = $this->db->query(" SELECT 
                                        	IdPraListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                        	pralisting
                                        WHERE
                                            IsRejected = 0 AND
                                            IsDelete = 0 AND
                                            IsAdmin = 0 ORDER BY 
                                            Priority ASC, 
                                            IdPraListing ASC
                                        LIMIT $limit OFFSET $offset; ");
            return $query->result_array();
        }
        
        public function Get_List_PraListing_Manager($limit, $offset){
            $query = $this->db->query(" SELECT
                                        	IdPraListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                        	pralisting
                                        WHERE
                                            IsRejected = 0 AND
                                            IsDelete = 0 AND
                                            IsAdmin = 1 AND
                                            IsManager = 0 ORDER BY 
                                            Priority ASC, 
                                            IdPraListing ASC
                                        LIMIT $limit OFFSET $offset; ");
            return $query->result_array();
        }
        
        public function Get_List_PraListing_Rejected(){
            $query = $this->db->query(" SELECT 
                                        	pralisting.IdPraListing,
                                            pralisting.NamaListing,
                                            pralisting.Harga,
                                            pralisting.HargaSewa,
                                            pralisting.Bed,
                                            pralisting.Bath,
                                            pralisting.Level,
                                            pralisting.Garage,
                                            pralisting.Img1
                                        FROM 
                                        	pralisting
                                        WHERE
                                        	IsRejected = 1 AND
                                            IsDelete = 0 ORDER BY 
                                            pralisting.Priority ASC, 
                                            pralisting.IdPraListing ASC; ");
            return $query->result_array();
        }
        
        public function Get_Detail_PraListing($id){
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
                                        	pralisting.IdPralisting = $id; ");
            return $query->result_array();
        }
        
        public function Get_Spec_PraListing($id){
            $query = $this->db->query(" SELECT 
                                        	pralisting.IdPraListing,
                                        	pralisting.Size,
                                        	pralisting.Fee,
                                            pralisting.TglInput,
                                            pralisting.JenisProperti,
                                            pralisting.Kondisi,
                                            pralisting.SHM,
                                            pralisting.HGB,
                                            pralisting.HSHP,
                                            pralisting.PPJB,
                                            pralisting.Stratatitle,
                                            pralisting.AJB,
                                            pralisting.PetokD,
                                            pralisting.Wide,
                                            pralisting.Land,
                                            pralisting.Dimensi,
                                            pralisting.Hadap,
                                            pralisting.Bed,
                                            pralisting.BedArt,
                                            pralisting.Bath,
                                            pralisting.BathArt,
                                            pralisting.Level,
                                            pralisting.Listrik,
                                            pralisting.SumberAir,
                                            pralisting.Prabot,
                                            vendor.IdVendor,
                                            vendor.NamaLengkap AS NamaVendor,
                                            vendor.NoTelp AS NoTelpVendor
                                        FROM 
                                        	pralisting
                                        	LEFT JOIN vendor USING(IdVendor)
                                        WHERE
                                        	pralisting.IdPraListing = $id");
            return $query->result_array();
        }
        
        public function Get_Agen_PraListing($id){
            $query = $this->db->query(" SELECT 
                                            pralisting.IdPraListing,
                                            pralisting.IdAgen,
                                            pralisting.IdAgenCo,
                                            agen1.IdAgen AS IdAgenAgen,
                                            agen1.NamaTemp AS NamaTemp,
                                            agen1.NoTelp AS NoTelp,
                                            agen1.Instagram AS Instagram,
                                            agen2.IdAgen AS IdAgenCo,
                                            agen2.NamaTemp AS NamaTempCo,
                                            agen2.NoTelp AS NoTelpCo,
                                            agen2.Instagram AS InstagramCo
                                        FROM 
                                            pralisting
                                            LEFT JOIN agen AS agen1 ON pralisting.IdAgen = agen1.IdAgen
                                            LEFT JOIN agen AS agen2 ON pralisting.IdAgenCo = agen2.IdAgen
                                        WHERE
                                            pralisting.IdPraListing = $id");
            return $query->result_array();
        }
        
        public function Get_Meta_PraListing($id){
            $query = $this->db->query(" SELECT 
                                        	IdPraListing,
                                            NamaListing,
                                            Alamat,
                                            Kondisi,
                                            Harga,
                                            HargaSewa
                                        FROM 
                                        	pralisting
                                        WHERE
                                        	pralisting.IdPraListing = $id");
            return $query->result_array();
        }
        
        public function Get_Image_PraListing($id){
            $query = $this->db->query(" SELECT 
                                            pralisting.IdPraListing,
                                            pralisting.Img1,
                                            pralisting.Img2,
                                            pralisting.Img3,
                                            pralisting.Img4,
                                            pralisting.Img5,
                                            pralisting.Img6,
                                            pralisting.Img7,
                                            pralisting.Img8,
                                            pralisting.Img9,
                                            pralisting.Img10,
                                            pralisting.Img11,
                                            pralisting.Img12,
                                            template.IdTemplate,
                                            template.IdListing,
                                            template.Template,
                                            template.TemplateBlank
                                        FROM
                                            pralisting
                                            LEFT JOIN template ON pralisting.IdPraListing = template.IdListing
                                        WHERE
                                        	pralisting.IdPraListing = $id");
            return $query->result_array();
        }
        
        public function Get_Lampiran_PraListing($id){
            $query = $this->db->query(" SELECT 
                                        	pralisting.*,
                                            penilaian.*,
                                            vendor.IdVendor,
                                            vendor.NamaLengkap AS NamaVendor,
                                            vendor.NoTelp AS NoTelpVendor,
                                            agen1.IdAgen AS IdAgenAgen,
                                            agen1.NamaTemp AS NamaTemp,
                                            agen1.NoTelp AS NoTelp,
                                            agen1.Instagram AS Instagram,
                                            agen2.IdAgen AS IdAgenCo,
                                            agen2.NamaTemp AS NamaTempCo,
                                            agen2.NoTelp AS NoTelpCo,
                                            agen2.Instagram AS InstagramCo,
                                            template.IdTemplate,
                                            template.IdListing,
                                            template.Template,
                                            template.TemplateBlank
                                        FROM 
                                            `pralisting`
                                            LEFT JOIN penilaian ON pralisting.IdPraListing = penilaian.IdPraListing
                                        	LEFT JOIN vendor USING(IdVendor)
                                            LEFT JOIN agen AS agen1 ON pralisting.IdAgen = agen1.IdAgen
                                            LEFT JOIN agen AS agen2 ON pralisting.IdAgenCo = agen2.IdAgen
                                            LEFT JOIN template ON pralisting.IdPraListing = template.IdListing
                                        WHERE
                                            pralisting.IdPraListing = $id;");
            return $query->result_array();
        }
        
        // Count ---------------------------------------------------------------
        
        public function Count_Pralisting_Rejected($id){
            $query = $this->db->query(" SELECT 
                                        	COUNT(*) AS Total
                                        FROM 
                                        	pralisting
                                        WHERE
                                            IdAgen = $id AND
                                            IsRejected = 1 AND
                                            IsDelete = 0");
            return $query->result_array();
        }
        
        // Approval ------------------------------------------------------------
        
    // Listing =========================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Listing($id){
            $query = $this->db->query("INSERT INTO `listing` (
                                      `IdAgen`,`IdAgenCo`,`IdInput`,`IdVendor`,`NoArsip`,`NamaListing`,`MetaNamaListing`,`Alamat`,`AlamatTemplate`,`Latitude`,`Longitude`,`Location`,`Wilayah`,`Daerah`,`Provinsi`,`Selfie`,`Wide`,`Land`,`Dimensi`,`Listrik`,`Level`,`Bed`,`Bath`,`BedArt`,`BathArt`,`Garage`,`Carpot`,`Hadap`,`SHM`,`HGB`,`HSHP`,`PPJB`,`Stratatitle`,`AJB`,`PetokD`,`Pjp`,`ImgSHM`,`ImgHGB`,`ImgHSHP`,`ImgPPJB`,`ImgStratatitle`,`ImgAJB`,`ImgPetokD`,`ImgPjp`,`ImgPjp1`,`NoCertificate`,`Pbb`,`JenisProperti`,`JenisCertificate`,`SumberAir`,`Kondisi`,`RuangTamu`,`RuangMakan`,`Dapur`,`Jemuran`,`Masjid`,`Taman`,`Playground`,`Cctv`,`OneGateSystem`,`Deskripsi`,`MetaDeskripsi`,`Prabot`,`KetPrabot`,`Priority`,`Ttd`,`Banner`,`Size`,`Harga`,`HargaSewa`,`RangeHarga`,`TglInput`,`TglUpdate`,`Img1`,`Img2`,`Img3`,`Img4`,`Img5`,`Img6`,`Img7`,`Img8`,`Img9`,`Img10`,`Img11`,`Img12`,`Video`,`LinkFacebook`,`LinkTiktok`,`LinkInstagram`,`LinkYoutube`,`IsAdmin`,`IsManager`,`IsRejected`,`Sold`,`Rented`,`SoldAgen`,`RentedAgen`,`View`,`Marketable`,`StatusHarga`,`IsSelfie`,`IsLokasi`,`Surveyor`,`Fee`,`NoKtp`,`ImgKtp`,`TipeHarga`,`Pending`,`IsCekLokasi`,`IsDouble`,`IsDelete`,`Akun1`,`Akun2`
                                    ) 
                                    SELECT 
                                      `IdAgen`,`IdAgenCo`,`IdInput`,`IdVendor`,`NoArsip`,`NamaListing`,`MetaNamaListing`,`Alamat`,`AlamatTemplate`,`Latitude`,`Longitude`,`Location`,`Wilayah`,`Daerah`,`Provinsi`,`Selfie`,`Wide`,`Land`,`Dimensi`,`Listrik`,`Level`,`Bed`,`Bath`,`BedArt`,`BathArt`,`Garage`,`Carpot`,`Hadap`,`SHM`,`HGB`,`HSHP`,`PPJB`,`Stratatitle`,`AJB`,`PetokD`,`Pjp`,`ImgSHM`,`ImgHGB`,`ImgHSHP`,`ImgPPJB`,`ImgStratatitle`,`ImgAJB`,`ImgPetokD`,`ImgPjp`,`ImgPjp1`,`NoCertificate`,`Pbb`,`JenisProperti`,`JenisCertificate`,`SumberAir`,`Kondisi`,`RuangTamu`,`RuangMakan`,`Dapur`,`Jemuran`,`Masjid`,`Taman`,`Playground`,`Cctv`,`OneGateSystem`,`Deskripsi`,`MetaDeskripsi`,`Prabot`,`KetPrabot`,`Priority`,`Ttd`,`Banner`,`Size`,`Harga`,`HargaSewa`,`RangeHarga`,`TglInput`,`TglUpdate`,`Img1`,`Img2`,`Img3`,`Img4`,`Img5`,`Img6`,`Img7`,`Img8`,`Img9`,`Img10`,`Img11`,`Img12`,`Video`,`LinkFacebook`,`LinkTiktok`,`LinkInstagram`,`LinkYoutube`,`IsAdmin`,`IsManager`,`IsRejected`,`Sold`,`Rented`,`SoldAgen`,`RentedAgen`,`View`,`Marketable`,`StatusHarga`,`IsSelfie`,`IsLokasi`,`Surveyor`,`Fee`,`NoKtp`,`ImgKtp`,`TipeHarga`,`Pending`,`IsCekLokasi`,`IsDouble`,`IsDelete`,`Akun1`,`Akun2`
                                    FROM `pralisting` 
                                    WHERE `IdPraListing` = $id;
                                    ");
            return $this->db->insert_id();
    	}
        
        // Get -----------------------------------------------------------------
        
        public function Get_List_Listing_Agen($limit, $offset, $id, $search = ''){
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= " AND listing.MetaNamaListing LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
                }
            }
            
            $query = $this->db->query(" SELECT 
                                        	IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Wilayah,
                                            Daerah,
                                            Provinsi,
                                            Img1
                                        FROM 
                                        	listing
                                        WHERE
                                            IdAgen = $id AND
                                        	IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0        
                                            $searchCondition
                                        ORDER BY 
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset ");
            return $query->result_array();
        }
        
        public function Get_List_Listing_Terbaru_Pagination($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Wilayah,
                                            Daerah,
                                            Provinsi,
                                            Img1
                                        FROM 
                                            listing
                                        WHERE
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Listing_Terbaru_Jual($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Wilayah,
                                            Daerah,
                                            Provinsi,
                                            Img1
                                        FROM 
                                            listing
                                        WHERE
                                            Kondisi = 'Jual' AND
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Listing_Terbaru_Sewa($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Wilayah,
                                            Daerah,
                                            Provinsi,
                                            Img1
                                        FROM 
                                            listing
                                        WHERE
                                            Kondisi = 'Sewa' AND
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Listing_Terbaru_JualSewa($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Wilayah,
                                            Daerah,
                                            Provinsi,
                                            Img1
                                        FROM 
                                            listing
                                        WHERE
                                            Kondisi = 'Jual/Sewa' AND
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Listing_Rumah($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                            listing
                                        WHERE
                                            JenisProperti = 'Rumah' AND
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Listing_Ruko($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                            listing
                                        WHERE
                                            JenisProperti = 'Ruko' AND
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Listing_Tanah($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                            listing
                                        WHERE
                                            JenisProperti = 'Tanah' AND
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Listing_Gudang($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                            listing
                                        WHERE
                                            JenisProperti = 'Gudang' AND
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Listing_RuangUsaha($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                            listing
                                        WHERE
                                            JenisProperti = 'Ruang Usaha' AND
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Listing_Villa($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                            listing
                                        WHERE
                                            JenisProperti = 'Villa' AND
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Listing_Apartemen($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                            listing
                                        WHERE
                                            JenisProperti = 'Apartemen' AND
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Listing_Pabrik($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                            listing
                                        WHERE
                                            JenisProperti = 'Pabrik' AND
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Listing_Kantor($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                            listing
                                        WHERE
                                            JenisProperti = 'Kantor' AND
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Listing_Hotel($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                            listing
                                        WHERE
                                            JenisProperti = 'Hotel' AND
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Listing_Rukost($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                            listing
                                        WHERE
                                            JenisProperti = 'Rukost' AND
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Listing_Sold($limit, $offset){
            $query = $this->db->query(" SELECT 
                                        	IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                        	listing
                                        WHERE
                                        	Sold = 1 OR 
                                        	SoldAgen = 1 OR 
                                        	Rented = 1 OR 
                                        	RentedAgen = 1 ORDER BY
                                            IdListing ASC
                                        LIMIT $limit OFFSET $offset; ");
            return $query->result_array();
        }
        
        public function Get_List_Listing_Exclusive($limit, $offset){
            $query = $this->db->query(" SELECT 
                                        	IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                        	listing
                                        WHERE
                                            Priority = 'exclusive' AND
                                        	IsDouble = 0 AND 
                                        	IsDelete = 0 AND 
                                        	Sold = 0 AND 
                                        	SoldAgen = 0 AND 
                                        	Rented = 0 AND 
                                        	RentedAgen = 0 AND
                                            Pending = 0 ORDER BY
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset; ");
            return $query->result_array();
        }
        
        public function Get_List_Listing_Filter($filters) {
            $this->db->select('*');
            $this->db->from('listing');
    
            if (!empty($filters['Kondisi'])) {
                $this->db->where('Kondisi', $filters['Kondisi']);
            }
            if (!empty($filters['JenisProperti'])) {
                $this->db->where('JenisProperti', $filters['JenisProperti']);
            }
            if (!empty($filters['Alamat'])) {
                $this->db->like('Alamat', $filters['Alamat']);
            }
            if (!empty($filters['Wilayah'])) {
                $this->db->like('Wilayah', $filters['Wilayah']);
            }
            if (!empty($filters['Daerah'])) {
                $this->db->where('Daerah', $filters['Daerah']);
            }
            if (!empty($filters['Wide'])) {
                $this->db->where('Wide', $filters['Wide']);
            }
            if (!empty($filters['Land'])) {
                $this->db->where('Land', $filters['Land']);
            }
            if (!empty($filters['Bed'])) {
                $this->db->where('Bed', $filters['Bed']);
            }
            if (!empty($filters['Bath'])) {
                $this->db->where('Bath', $filters['Bath']);
            }
            if (!empty($filters['Kondisi'])) {
                if ($filters['Kondisi'] == 'Jual') {
                    if (!empty($filters['HargaMin']) && !empty($filters['HargaMax'])) {
                        $this->db->where('harga_jual >=', $filters['HargaMin']);
                        $this->db->where('harga_jual <=', $filters['HargaMax']);
                    } else if (!empty($filters['HargaMin'])) {
                        $this->db->where('harga_jual >=', $filters['HargaMin']);
                    } else if (!empty($filters['HargaMax'])) {
                        $this->db->where('harga_jual <=', $filters['HargaMax']);
                    }
                } else if ($filters['Kondisi'] == 'Sewa') {
                    if (!empty($filters['HargaMin']) && !empty($filters['HargaMax'])) {
                        $this->db->where('harga_sewa >=', $filters['HargaMin']);
                        $this->db->where('harga_sewa <=', $filters['HargaMax']);
                    } else if (!empty($filters['HargaMin'])) {
                        $this->db->where('harga_sewa >=', $filters['HargaMin']);
                    } else if (!empty($filters['HargaMax'])) {
                        $this->db->where('harga_sewa <=', $filters['HargaMax']);
                    }
                }
            }
    
            $query = $this->db->get();
            return $query->result();
        }
        
        public function Get_List_Listing_Pencarian($limit, $offset, $search = '', $priority = '', $sold = null, $rented = null, $soldagen = null, $rentedagen = null, $status = '', $jenis = '', $kota = '', $wilayah = '', $prabot = '', $bed = null, $bath = null, $hargaMin = '', $hargaMax ='', $landMin = null, $landMax = null, $wideMin = null, $wideMax = null) {
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= " AND CONCAT(listing.NamaListing, ' ', listing.MetaNamaListing, ' ', listing.Alamat, ' ', listing.Location, ' ', listing.Wilayah, ' ', listing.Daerah) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
                }
            }
            
            if (!empty($priority)) {
                $searchCondition .= " AND listing.Priority = " . $this->db->escape($priority) . " ";
            }
            
            if (!empty($status)) {
                $searchCondition .= " AND listing.Kondisi = " . $this->db->escape($status) . " ";
            }
            
            if (!empty($jenis)) {
                $searchCondition .= " AND listing.JenisProperti = " . $this->db->escape($jenis) . " ";
            }
            
            if (!empty($kota)) {
                $searchCondition .= " AND listing.Daerah = " . $this->db->escape($kota) . " ";
            }
            
            if (!empty($wilayah)) {
                $searchCondition .= " AND listing.Wilayah = " . $this->db->escape($wilayah) . " ";
            }
            
            if (!empty($prabot)) {
                $searchCondition .= " AND listing.Prabot = " . $this->db->escape($prabot) . " ";
            }
            
            if (!empty($bed)) {
                $searchCondition .= " AND listing.Bed = " . $this->db->escape($bed) . " ";
            }
            
            if (!empty($bath)) {
                $searchCondition .= " AND listing.Bath = " . $this->db->escape($bath) . " ";
            }
        
            if ($status === 'Jual') {
                if (!empty($hargaMin) && !empty($hargaMax)) {
                    $searchCondition .= " AND CAST(listing.Harga AS UNSIGNED) BETWEEN " . $this->db->escape($hargaMin) . " AND " . $this->db->escape($hargaMax) . " ";
                }
            } elseif ($status === 'Sewa') {
                if (!empty($hargaMin) && !empty($hargaMax)) {
                    $searchCondition .= " AND CAST(listing.HargaSewa AS UNSIGNED) BETWEEN " . $this->db->escape($hargaMin) . " AND " . $this->db->escape($hargaMax) . " ";
                }
            } elseif ($status === 'Jual/Sewa') {
                if (!empty($hargaMin) && !empty($hargaMax)) {
                    $searchCondition .= " AND (CAST(listing.Harga AS UNSIGNED) BETWEEN " . $this->db->escape($hargaMin) . " AND " . $this->db->escape($hargaMax) . " OR CAST(listing.HargaSewa AS UNSIGNED) BETWEEN " . $this->db->escape($hargaMin) . " AND " . $this->db->escape($hargaMax) . ") ";
                }
            } elseif (empty($status)) {
                if (!empty($hargaMin) && !empty($hargaMax)) {
                    $searchCondition .= " AND (CAST(listing.Harga AS UNSIGNED) BETWEEN " . $this->db->escape($hargaMin) . " AND " . $this->db->escape($hargaMax) . " OR CAST(listing.HargaSewa AS UNSIGNED) BETWEEN " . $this->db->escape($hargaMin) . " AND " . $this->db->escape($hargaMax) . ") ";
                }
            }
        
            if (!empty($landMin) && !empty($landMax)) {
                $searchCondition .= " AND CAST(REPLACE(listing.Land, ' m²', '') AS UNSIGNED) BETWEEN " . $this->db->escape($landMin) . " AND " . $this->db->escape($landMax) . " ";
            }
        
            if (!empty($wideMin) && !empty($wideMax)) {
                $searchCondition .= " AND CAST(REPLACE(listing.Wide, ' m²', '') AS UNSIGNED) BETWEEN " . $this->db->escape($wideMin) . " AND " . $this->db->escape($wideMax) . " ";
            }
            
            $query = $this->db->query("
                SELECT 
                    IdListing,
                    NamaListing,
                    MetaNamaListing,
                    Kondisi,
                    Harga,
                    HargaSewa,
                    Wide,
                    Land,
                    Priority,
                    NoArsip,
                    Img1
                FROM 
                    listing
                WHERE
                    IsDouble = 0 AND 
                    IsDelete = 0 AND 
                    Sold = 0 AND 
                    SoldAgen = 0 AND 
                    Rented = 0 AND 
                    RentedAgen = 0 AND
                    Pending = 0 
                    $searchCondition
                ORDER BY
                    IdListing DESC
                LIMIT ? OFFSET ?
            ", array($limit, $offset));
        
            return $query->result_array();
        }
        
        public function Get_List_Listing_Sold_Pencarian($limit, $offset, $search = '', $priority = '', $sold = null, $rented = null, $soldagen = null, $rentedagen = null, $status = '', $jenis = '', $kota = '', $wilayah = '', $prabot = '', $bed = null, $bath = null, $hargaMin = '', $hargaMax ='', $landMin = null, $landMax = null, $wideMin = null, $wideMax = null) {
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= " AND CONCAT(listing.NamaListing, ' ', listing.MetaNamaListing, ' ', listing.Alamat, ' ', listing.Location, ' ', listing.Wilayah, ' ', listing.Daerah) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
                }
            }
            
            if (!empty($priority)) {
                $searchCondition .= " AND listing.Priority = " . $this->db->escape($priority) . " ";
            }
            
            if (!empty($status)) {
                $searchCondition .= " AND listing.Kondisi = " . $this->db->escape($status) . " ";
            }
            
            if (!empty($jenis)) {
                $searchCondition .= " AND listing.JenisProperti = " . $this->db->escape($jenis) . " ";
            }
            
            if (!empty($kota)) {
                $searchCondition .= " AND listing.Daerah = " . $this->db->escape($kota) . " ";
            }
            
            if (!empty($wilayah)) {
                $searchCondition .= " AND listing.Wilayah = " . $this->db->escape($wilayah) . " ";
            }
            
            if (!empty($prabot)) {
                $searchCondition .= " AND listing.Prabot = " . $this->db->escape($prabot) . " ";
            }
            
            if (!empty($bed)) {
                $searchCondition .= " AND listing.Bed = " . $this->db->escape($bed) . " ";
            }
            
            if (!empty($bath)) {
                $searchCondition .= " AND listing.Bath = " . $this->db->escape($bath) . " ";
            }
        
            if ($status === 'Jual') {
                if (!empty($hargaMin) && !empty($hargaMax)) {
                    $searchCondition .= " AND CAST(listing.Harga AS UNSIGNED) BETWEEN " . $this->db->escape($hargaMin) . " AND " . $this->db->escape($hargaMax) . " ";
                }
            } elseif ($status === 'Sewa') {
                if (!empty($hargaMin) && !empty($hargaMax)) {
                    $searchCondition .= " AND CAST(listing.HargaSewa AS UNSIGNED) BETWEEN " . $this->db->escape($hargaMin) . " AND " . $this->db->escape($hargaMax) . " ";
                }
            } elseif ($status === 'Jual/Sewa') {
                if (!empty($hargaMin) && !empty($hargaMax)) {
                    $searchCondition .= " AND (CAST(listing.Harga AS UNSIGNED) BETWEEN " . $this->db->escape($hargaMin) . " AND " . $this->db->escape($hargaMax) . " OR CAST(listing.HargaSewa AS UNSIGNED) BETWEEN " . $this->db->escape($hargaMin) . " AND " . $this->db->escape($hargaMax) . ") ";
                }
            } elseif (empty($status)) {
                if (!empty($hargaMin) && !empty($hargaMax)) {
                    $searchCondition .= " AND (CAST(listing.Harga AS UNSIGNED) BETWEEN " . $this->db->escape($hargaMin) . " AND " . $this->db->escape($hargaMax) . " OR CAST(listing.HargaSewa AS UNSIGNED) BETWEEN " . $this->db->escape($hargaMin) . " AND " . $this->db->escape($hargaMax) . ") ";
                }
            }
        
            if (!empty($landMin) && !empty($landMax)) {
                $searchCondition .= " AND CAST(REPLACE(listing.Land, ' m²', '') AS UNSIGNED) BETWEEN " . $this->db->escape($landMin) . " AND " . $this->db->escape($landMax) . " ";
            }
        
            if (!empty($wideMin) && !empty($wideMax)) {
                $searchCondition .= " AND CAST(REPLACE(listing.Wide, ' m²', '') AS UNSIGNED) BETWEEN " . $this->db->escape($wideMin) . " AND " . $this->db->escape($wideMax) . " ";
            }
            
            $query = $this->db->query("
                SELECT 
                    IdListing,
                    NamaListing,
                    MetaNamaListing,
                    Kondisi,
                    Harga,
                    HargaSewa,
                    Wide,
                    Land,
                    Priority,
                    NoArsip,
                    Img1
                FROM 
                    listing
                WHERE
                    IsDouble = 0 AND IsDelete = 0 AND (Sold = 1 OR SoldAgen = 1 OR Rented = 1 OR RentedAgen = 1) AND Pending = 0 
                    $searchCondition
                ORDER BY
                    IdListing DESC
                LIMIT ? OFFSET ?
            ", array($limit, $offset));
        
            return $query->result_array();
        }
        
        public function Get_List_Listing_Pending($limit, $offset){
            
            $query = $this->db->query(" SELECT 
                                        	IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                        	listing
                                        WHERE
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 1
                                        ORDER BY 
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset ");
            return $query->result_array();
        }
        
        public function Get_List_Susulan($id, $limit, $offset){
            
            $query = $this->db->query(" SELECT 
                                        	*
                                        FROM 
                                        	susulan
                                        WHERE
                                            IdListing= $id
                                        LIMIT $limit OFFSET $offset ");
            return $query->result_array();
        }
        
        public function Get_Spec_Listing($id){
            $query = $this->db->query(" SELECT 
                                        	listing.IdListing,
                                        	listing.Size,
                                        	listing.Fee,
                                            listing.TglInput,
                                            listing.JenisProperti,
                                            listing.Kondisi,
                                            listing.SHM,
                                            listing.HGB,
                                            listing.HSHP,
                                            listing.PPJB,
                                            listing.Stratatitle,
                                            listing.AJB,
                                            listing.PetokD,
                                            listing.Wide,
                                            listing.Land,
                                            listing.Dimensi,
                                            listing.Hadap,
                                            listing.Bed,
                                            listing.BedArt,
                                            listing.Bath,
                                            listing.BathArt,
                                            listing.Level,
                                            listing.Listrik,
                                            listing.SumberAir,
                                            listing.Prabot,
                                            vendor.IdVendor,
                                            vendor.NamaLengkap AS NamaVendor,
                                            vendor.NoTelp AS NoTelpVendor
                                        FROM 
                                        	listing
                                        	LEFT JOIN vendor USING(IdVendor)
                                        WHERE
                                        	listing.IdListing = $id");
            return $query->result_array();
        }
        
        public function Get_Agen_Listing($id){
            $query = $this->db->query(" SELECT 
                                            listing.IdListing,
                                            listing.IdAgen,
                                            listing.IdAgenCo,
                                            agen1.IdAgen AS IdAgenAgen,
                                            agen1.NamaTemp AS NamaTemp,
                                            agen1.NoTelp AS NoTelp,
                                            agen1.Instagram AS Instagram,
                                            agen2.IdAgen AS IdAgenCo,
                                            agen2.NamaTemp AS NamaTempCo,
                                            agen2.NoTelp AS NoTelpCo,
                                            agen2.Instagram AS InstagramCo
                                        FROM 
                                            listing
                                            LEFT JOIN agen AS agen1 ON listing.IdAgen = agen1.IdAgen
                                            LEFT JOIN agen AS agen2 ON listing.IdAgenCo = agen2.IdAgen
                                        WHERE
                                            listing.IdListing = $id");
            return $query->result_array();
        }
        
        public function Get_Meta_Listing($id){
            $query = $this->db->query(" SELECT 
                                        	IdListing,
                                            NamaListing,
                                            Alamat,
                                            Kondisi,
                                            Harga,
                                            HargaSewa
                                        FROM 
                                        	listing
                                        WHERE
                                        	listing.IdListing = $id");
            return $query->result_array();
        }
        
        public function Get_Image_Listing($id){
            $query = $this->db->query(" SELECT 
                                            listing.IdListing,
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
                                            listing.Img12,
                                            template.IdTemplate,
                                            template.IdListing,
                                            template.Template,
                                            template.TemplateBlank
                                        FROM
                                            listing
                                            LEFT JOIN template ON listing.IdListing = template.IdListing
                                        WHERE
                                        	listing.IdListing = $id");
            return $query->result_array();
        }
        
        public function Get_Lampiran_Listing($id){
            $query = $this->db->query(" SELECT 
                                            listing.*,
                                            penilaian.IdPenilaian,
                                            penilaian.IdPralisting,
                                            penilaian.IdListing AS IdListingPenilaian,
                                            penilaian.AksesJalanAgen,
                                            penilaian.AksesJalanOfficer,
                                            penilaian.AksesJalanManager,
                                            penilaian.KondisiAgen,
                                            penilaian.KondisiOfficer,
                                            penilaian.KondisiManager,
                                            penilaian.AreaSekitarAgen,
                                            penilaian.AreaSekitarOfficer,
                                            penilaian.AreaSekitarManager,
                                            penilaian.HargaAgen,
                                            penilaian.HargaOfficer,
                                            penilaian.HargaManager,
                                            reportvendor.IdReport,
                                            reportvendor.IdListing AS IdListingReportVendor,
                                            reportvendor.Repost,
                                            reportvendor.Catatan,
                                            vendor.IdVendor,
                                            vendor.NamaLengkap AS NamaVendor,
                                            vendor.NoTelp AS NoTelpVendor,
                                            agen1.IdAgen AS IdAgenAgen,
                                            agen1.NamaTemp AS NamaTemp,
                                            agen1.NoTelp AS NoTelp,
                                            agen1.Instagram AS Instagram,
                                            agen2.IdAgen AS IdAgenCo,
                                            agen2.NamaTemp AS NamaTempCo,
                                            agen2.NoTelp AS NoTelpCo,
                                            agen2.Instagram AS InstagramCo,
                                            template.IdTemplate,
                                            template.IdListing AS IdListingTemplate,
                                            template.Template,
                                            template.TemplateBlank
                                        FROM 
                                            `listing`
                                            LEFT JOIN penilaian ON listing.IdListing = penilaian.IdListing
                                            LEFT JOIN reportvendor ON listing.IdListing = reportvendor.IdListing
                                        	LEFT JOIN vendor USING(IdVendor)
                                            LEFT JOIN agen AS agen1 ON listing.IdAgen = agen1.IdAgen
                                            LEFT JOIN agen AS agen2 ON listing.IdAgenCo = agen2.IdAgen
                                            LEFT JOIN template ON listing.IdListing = template.IdListing
                                        WHERE
                                            listing.IdListing = $id;");
            return $query->result_array();
        }
        
        // Report Vendor -------------------------------------------------------
        
        public function Get_Report_Vendor($limit, $offset){
            
            $query = $this->db->query(" SELECT 
                                        	IdListing,
                                            NamaListing,
                                            Kondisi,
                                            Harga,
                                            HargaSewa,
                                            Wide,
                                            Land,
                                            Priority,
                                            NoArsip,
                                            Img1
                                        FROM 
                                        	listing
                                        WHERE
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 0 AND
                                            DAY(TglInput) = DAY(CURDATE())
                                        ORDER BY 
                                            IdListing DESC
                                        LIMIT $limit OFFSET $offset ");
            return $query->result_array();
        }
        
        public function Get_Data_Report_Vendor($id){
            
            $query = $this->db->query(" SELECT 
                                            *
                                        FROM 
                                        	reportvendor
                                        WHERE
                                            IdListing = $id");
            return $query->result_array();
        }
        
        // Count ---------------------------------------------------------------
        
        public function Count_Listing_Pending(){
            $query = $this->db->query(" SELECT 
                                        	COUNT(*) AS Total
                                        FROM 
                                        	listing
                                        WHERE
                                            IsDouble = 0 AND 
                                            IsDelete = 0 AND 
                                            Sold = 0 AND 
                                            SoldAgen = 0 AND 
                                            Rented = 0 AND 
                                            RentedAgen = 0 AND
                                            Pending = 1;");
            return $query->result_array();
        }
        
    // Primary =========================================================================================================================================================================================
    
        // Get -----------------------------------------------------------------
        
        public function Get_List_Listing_Primary($limit, $offset, $search = ''){
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= "WHERE CONCAT(Nama, ' ',JenisProperty, ' ',Developer, ' ',Kota, ' ',Area) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
                }
            }
            
            $query = $this->db->query(" SELECT 
                                        	IdNew,
                                            Nama,
                                            Kota,
                                            Img
                                        FROM 
                                        	listingnew
                                        $searchCondition 
                                        LIMIT $limit OFFSET $offset;");
            return $query->result_array();
        }
        
        public function Get_Detail_Primary($id){
            $query = $this->db->query(" SELECT 
                                        	*
                                        FROM 
                                        	listingnew
                                        WHERE
                                            IdNew = $id; ");
            return $query->result_array();
        }
        
        public function Get_Image_Primary($id){
            $query = $this->db->query(" SELECT 
                                        	IdNew,
                                        	Img
                                        FROM 
                                        	listingnew
                                        WHERE
                                            IdNew = $id; ");
            return $query->result_array();
        }
        
        public function Get_List_Tipe_Listing_Primary($id){
            $query = $this->db->query(" SELECT 
                                        	*
                                        FROM 
                                        	tipenewlisting
                                        WHERE
                                            IdNew = $id; ");
            return $query->result_array();
        }
        
    // Info =========================================================================================================================================================================================
    
        // Get -----------------------------------------------------------------
        
        public function Get_List_Info($limit, $offset, $search = '') {
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= "AND CONCAT(JenisProperty, ' ', StatusProperty, ' ', Lokasi, ' ', Alamat, ' ', Keterangan) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
                }
            }
            
            $query = $this->db->query(" 
                                        SELECT
                                            IdInfo,
                                            JenisProperty,
                                            StatusProperty,
                                            Lokasi,
                                            ImgProperty
                                        FROM 
                                            infoproperty
                                        WHERE
                                            IsHide = 0
                                            $searchCondition
                                        ORDER BY
                                        	IdInfo DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Info_Agen($id, $limit, $offset) {
            $query = $this->db->query(" SELECT 
                                        	IdInfo,
                                            JenisProperty,
                                            StatusProperty,
                                            Lokasi,
                                            ImgProperty
                                        FROM 
                                        	infoproperty
                                        WHERE 
                                            IdAgen = $id
                                        ORDER BY
                                        	IdInfo DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Info_Jual($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                        	IdInfo,
                                            JenisProperty,
                                            StatusProperty, 
                                            Lokasi,
                                            ImgProperty
                                        FROM 
                                        	infoproperty
                                        WHERE 
                                        	StatusProperty = 'Jual'
                                        ORDER BY
                                        	IdInfo DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Info_Sewa($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                        	IdInfo,
                                            JenisProperty,
                                            StatusProperty, 
                                            Lokasi,
                                            ImgProperty
                                        FROM 
                                        	infoproperty
                                        WHERE 
                                        	StatusProperty = 'Sewa'
                                        ORDER BY
                                        	IdInfo DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_List_Info_JualSewa($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                        	IdInfo,
                                            JenisProperty,
                                            StatusProperty, 
                                            Lokasi,
                                            ImgProperty
                                        FROM 
                                        	infoproperty
                                        WHERE 
                                        	StatusProperty = 'Jual/Sewa'
                                        ORDER BY
                                        	IdInfo DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
        public function Get_Image_Info($id){
            $query = $this->db->query(" SELECT 
                                            infoproperty.IdInfo,
                                            infoproperty.ImgProperty
                                        FROM
                                            infoproperty
                                        WHERE
                                        	infoproperty.IdInfo = $id");
            return $query->result_array();
        }
        
        public function Get_Lampiran_Info($id){
            $query = $this->db->query(" SELECT 
                                        	infoproperty.*,
                                            agen.IdAgen AS IdAgenAgen,
                                            agen.NamaTemp AS NamaTemp,
                                            agen.NoTelp AS NoTelpAgen,
                                            agen.Instagram AS Instagram
                                        FROM 
                                            infoproperty
                                            LEFT JOIN agen ON infoproperty.IdAgen = agen.IdAgen
                                        WHERE
                                            infoproperty.IdInfo = $id;");
            return $query->result_array();
        }
        
    // Count ===================================================================
    
    public function Count_Report_Vendor(){
        $query = $this->db->query(" SELECT 
                                    	COUNT(*) AS Total
                                    FROM 
                                    	listing
                                    WHERE
                                        IsDouble = 0 AND 
                                        IsDelete = 0 AND 
                                        Sold = 0 AND 
                                        SoldAgen = 0 AND 
                                        Rented = 0 AND 
                                        RentedAgen = 0 AND
                                        Pending = 0 AND
                                        DAY(TglInput) = DAY(CURDATE());");
        return $query->result_array();
    }
    
    public function Count_Report_Closing(){
        $query = $this->db->query(" SELECT 
                                    	COUNT(*) AS Total
                                    FROM 
                                    	reportsold
                                    WHERE
                                        IsRead = 0;");
        return $query->result_array();
    }
        
    
}
