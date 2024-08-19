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
    
    // Data ============================================================================================================================================================================================
    
    public function Get_Wilayah($id) {
        $query = $this->db->query(" SELECT 
                                    	*
                                    FROM 
                                    	wilayah
                                    WHERE
                                        IdDaerah = $id; ");
        return $query->result_array();
    }
    
    public function Get_Daerah() {
        $query = $this->db->query(" SELECT 
                                    	*
                                    FROM 
                                    	daerah; ");
        return $query->result_array();
    }
    
    public function Get_Jenis_Properti() {
        $query = $this->db->query(" SELECT 
                                    	*
                                    FROM 
                                    	jenisproperty; ");
        return $query->result_array();
    }
    
    public function Get_Agen() {
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        agen 
                                    WHERE 
                                        IsAkses = 1 AND Approve = 1; ");
        return $query->result_array();
    }
    
    public function Get_Detail_Agen($id) {
        $query = $this->db->query(" SELECT 
                                        * 
                                    FROM 
                                        agen 
                                    WHERE 
                                        IdAgen = $id; ");
        return $query->result_array();
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
                                            IsRejected = 0
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
                                        	(IsAdmin = 0 OR
                                            IsManager = 0) AND 
                                            IsRejected = 1
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
                                            IsAdmin = 0 AND
                                            IsManager = 0 ORDER BY 
                                            pralisting.Priority ASC, 
                                            pralisting.IdPraListing ASC; ");
            return $query->result_array();
        }
        
        public function Get_List_PraListing_Admin(){
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
                                        	IsCekLokasi = 1 AND
                                            IsRejected = 0 AND
                                            IsAdmin = 0 AND
                                            IsManager = 0 ORDER BY 
                                            pralisting.Priority ASC, 
                                            pralisting.IdPraListing ASC; ");
            return $query->result_array();
        }
        
        public function Get_List_PraListing_Manager(){
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
                                        	IsCekLokasi = 1 AND
                                            IsRejected = 0 AND
                                            IsAdmin = 1 AND
                                            IsManager = 0 ORDER BY 
                                            pralisting.Priority ASC, 
                                            pralisting.IdPraListing ASC; ");
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
                                        	IsRejected = 1 ORDER BY 
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
        
        // Approval ------------------------------------------------------------
        
    // Listing =========================================================================================================================================================================================
    
        // Get -----------------------------------------------------------------
        
        public function Get_List_Listing_Terbaru() {
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
                                            Pending = 0 
                                        ORDER BY
                                            IdListing DESC
                                        LIMIT 50;");
            
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
        
        public function Get_List_Listing_Pencarian($limit, $offset, $keywords) {
            $this->db->select('*');
            $this->db->from('listing');
        
            foreach ($keywords as $keyword) {
                $this->db->like('NamaListing', $keyword);
            }
        
            $this->db->limit($limit, $offset);
            $query = $this->db->get();
            return $query->result();
        }
        
    // Primary =========================================================================================================================================================================================
    
        // Get -----------------------------------------------------------------
        
        public function Get_List_Listing_Primary($limit, $offset){
            $query = $this->db->query(" SELECT 
                                        	IdNew,
                                            Nama,
                                            Kota,
                                            Img
                                        FROM 
                                        	listingnew
                                        LIMIT $limit OFFSET $offset;");
            return $query->result_array();
        }
        
        public function Get_List_Tipe_Listing_Primary($id){
            $query = $this->db->query(" SELECT 
                                        	IdListing,
                                            NamaListing,
                                            Kota
                                        FROM 
                                        	tipenewlisting
                                        WHERE
                                            IdNew = $id;
                                        	ORDER BY 
                                            IdNew ASC; ");
            return $query->result_array();
        }
        
    // Listing =========================================================================================================================================================================================
    
        // Get -----------------------------------------------------------------
        
        public function Get_List_Info_Jual($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                        	IdInfo,
                                            JenisProperty,
                                            StatusProperty, 
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
                                            ImgProperty
                                        FROM 
                                        	infoproperty
                                        WHERE 
                                        	StatusProperty = 'Jual?Sewa'
                                        ORDER BY
                                        	IdInfo DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
    
}