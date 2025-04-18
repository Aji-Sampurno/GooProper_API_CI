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
        
        public function Login_Kode($username, $kode) {
            $this->db->where('Username', $username);
            $this->db->where('KodeVerifikasi', $kode);
            $query = $this->db->get('agen');
            
            if ($query->num_rows() == 1) {
                return $query->row_array();
            } else {
                return false;
            }
        }
        
        public function Login_Admin_Email($email) {
            $this->db->where('Email', $email);
            $query = $this->db->get('admin');
            
            if ($query->num_rows() == 1) {
                return $query->row_array();
            } else {
                return false;
            }
        }
        
        public function Login_Agen_Email($email) {
            $this->db->where('Email', $email);
            $this->db->where('IsAkses', '1');
            $this->db->where('Approve', '1');
            $this->db->where('IsAktif', '1');
            $query = $this->db->get('agen');
            
            if ($query->num_rows() == 1) {
                return $query->row_array();
            } else {
                return false;
            }
        }
        
        public function Login_Customer_Email($email) {
            $this->db->where('Email', $email);
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
        
        public function Check_Email($email) {
            $query = $this->db->query(" SELECT 
                                        	COUNT(*) AS Total
                                        FROM 
                                            agen
                                        WHERE 
                                            Email = '$email';");
            return $query->result_array();
        }
    
    // Data ============================================================================================================================================================================================
    
        public function Check_Token($token) {
            $this->db->where('Token', $token);
            $query = $this->db->get('device');
            return $query->row_array();
        }
        
        public function Check_Token_Agen($token) {
            $this->db->where('Token', $token);
            $query = $this->db->get('deviceagen');
            return $query->row_array();
        }
        
        public function Check_Token_Customer($token) {
            $this->db->where('Token', $token);
            $query = $this->db->get('devicecustomer');
            return $query->row_array();
        }
        
        public function Get_Device($status) {
            $query = $this->db->query(" SELECT 
                                            *
                                        FROM 
                                            device
                                        WHERE
                                            Status = $status; ");
            return $query->result_array();
        }
        
        public function Get_Device_Agen($id) {
            $query = $this->db->query(" SELECT 
                                            *
                                        FROM 
                                            deviceagen
                                        WHERE
                                            IdAgen = $id; ");
            return $query->result_array();
        }
        
        public function Get_Device_Customer() {
            $query = $this->db->query(" SELECT 
                                            *
                                        FROM 
                                            devicecustomer; ");
            return $query->result_array();
        }
        
        public function Get_Device_All($limit, $offset) {
            $query = $this->db->query("
                SELECT * FROM devicecustomer
                UNION ALL
                SELECT * FROM deviceagen
                LIMIT $offset, $limit
            ");
        
            return $query->result_array();
        }
        
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
        
        public function Get_Referral($id) {
            $query = $this->db->query("
                SELECT
                    agen.Referral
                FROM
                    agen
                WHERE
                    agen.IdAgen = $id");
        
            return $query->result_array();
        }
        
        public function Get_Aga($limit, $offset, $referrer, $search = '') {
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
                    AND agen.Referrer = '$referrer'
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
        
        public function Get_Agen_Kode_List($limit, $offset, $search = '') {
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
                    agen.NoTelp,
                    agen.Photo,
                    agen.Username,
                    agen.KodeVerifikasi
                FROM
                    agen
                WHERE
                    agen.IsAkses = 1
                    AND agen.Approve = 1
                    AND agen.IsAktif = 1
                    $searchCondition
                LIMIT ? OFFSET ?", array($limit, $offset)
                );
                
            return $query->result_array();
        }
        
        public function Get_Detail_Agen($id) {
            $query = $this->db->query(" SELECT 
                                            agen.*,
                                            karyawan.NoKaryawan,
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
                                            LEFT JOIN karyawan ON agen.IdAgen = karyawan.IdAgen
                                        WHERE
                                            agen.IsAkses = 1 
                                            AND agen.Approve = 1 
                                            AND agen.IdAgen = $id
                                        GROUP BY 
                                            agen.IdAgen, karyawan.NoKaryawan;");
            return $query->result_array();
        }
        
        public function Get_Pelamar($limit, $offset) {
            $this->db->query("
                DELETE FROM agen  
                WHERE Approve = 0
                AND IsProses = 0
                AND Reject = 0 
                AND DATEDIFF(NOW(), CreatedAt) > 90;
            ");
            
            $query = $this->db->query(" SELECT
                                            agen.IdAgen,
                                            agen.Nama,
                                            agen.NoTelp,
                                            agen.Email,
                                            agen.Pendidikan,
                                            agen.AlamatDomisili,
                                            agen.Photo,
                                            agen.IsProses
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
        
        public function Get_Detail_Pertanyaan($id) {
            $query = $this->db->query(" SELECT 
                                            * 
                                        FROM 
                                            psikotes 
                                        WHERE 
                                            IdAgen = $id; ");
            return $query->result_array();
        }
        
        public function Get_Kota_Agen() {
            $query = $this->db->query(" SELECT 
                                            *
                                        FROM 
                                            kotaagen; ");
            return $query->result_array();
        }
        
        public function Get_Include() {
            $query = $this->db->query(" SELECT 
                                            *
                                        FROM 
                                            include; ");
            return $query->result_array();
        }
    
    // Event =============================================================================================================================================================================================
    
        public function Get_Event($tgl, $hasYear = true) {
            if ($hasYear) {
                $query = $this->db->query("SELECT * FROM event WHERE TglEvent = '$tgl';");
            } else {
                $query = $this->db->query("
                    SELECT * FROM event WHERE DATE_FORMAT(TglEvent, '%m-%d') = '$tgl';
                ");
            }
        
            return $query ? $query->result_array() : [];
        }
        
        public function Get_Event_All($limit, $offset) {
            $query = $this->db->query(" SELECT 
                                            *
                                        FROM 
                                            event
                                        WHERE
                                            TipeEvent != 'Ultah' AND
                                            TipeEvent != 'Closing'
                                        LIMIT $limit OFFSET $offset; ");
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
                                            listing.Img1,
                                            listing.TipeHarga,
                                            listing.IsSingleOpen
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
                    $searchCondition .= " AND NamaBuyer LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
                }
            }
                
            $query = $this->db->query(" SELECT 
                                            *
                                        FROM 
                                            reportbuyer
                                        WHERE 
                                            IdAgen = $id
                                            $searchCondition
                                        GROUP BY
                                            IdReportBuyer
                                        ORDER BY
                                            CASE KategoriProspek
                                                WHEN 'Hot Prospek' THEN 1
                                                WHEN 'Medium Prospek' THEN 2
                                                WHEN 'Low Prospek' THEN 3
                                                ELSE 4
                                            END,
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
                                            AND DATEDIFF(NOW(), TglReport)  < 14
                                            AND IsClose = 0
                                        GROUP BY
                                            IdReportBuyer
                                        ORDER BY 
                                            CASE KategoriProspek
                                                WHEN 'Hot Prospek' THEN 1
                                                WHEN 'Medium Prospek' THEN 2
                                                WHEN 'Low Prospek' THEN 3
                                                ELSE 4
                                            END,
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
                                            AND DATEDIFF(NOW(), TglReport)  BETWEEN 7 AND 14
                                            AND IsClose = 0
                                        GROUP BY
                                            IdReportBuyer
                                        ORDER BY 
                                            CASE KategoriProspek
                                                WHEN 'Hot Prospek' THEN 1
                                                WHEN 'Medium Prospek' THEN 2
                                                WHEN 'Low Prospek' THEN 3
                                                ELSE 4
                                            END,
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
                                            AND DATEDIFF(NOW(), TglReport) > 14
                                            AND IsClose = 0
                                        GROUP BY
                                            IdReportBuyer
                                        ORDER BY
                                            CASE KategoriProspek
                                                WHEN 'Hot Prospek' THEN 1
                                                WHEN 'Medium Prospek' THEN 2
                                                WHEN 'Low Prospek' THEN 3
                                                ELSE 4
                                            END, 
                                            TglReport ASC
                                        LIMIT $limit OFFSET $offset; ");
            return $query->result();
        }
        
        public function Get_Report_Buyer($limit, $offset, $search = ''){
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= " WHERE NamaBuyer LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
                }
            }
                
            $query = $this->db->query(" SELECT 
                                            *
                                        FROM 
                                            reportbuyer
                                            $searchCondition
                                        GROUP BY
                                            IdReportBuyer
                                        ORDER BY 
                                            CASE KategoriProspek
                                                WHEN 'Hot Prospek' THEN 1
                                                WHEN 'Medium Prospek' THEN 2
                                                WHEN 'Low Prospek' THEN 3
                                                ELSE 4
                                            END,
                                            TglReport DESC
                                        LIMIT $limit OFFSET $offset; ");
            return $query->result();
        }
        
        public function Get_Report_Buyer_Ready($limit, $offset){
            $query = $this->db->query(" SELECT 
                                            *
                                        FROM 
                                            reportbuyer
                                        WHERE 
                                            DATEDIFF(NOW(), TglReport)  < 14
                                            AND IsClose = 0
                                        GROUP BY
                                            IdReportBuyer
                                        ORDER BY 
                                            CASE KategoriProspek
                                                WHEN 'Hot Prospek' THEN 1
                                                WHEN 'Medium Prospek' THEN 2
                                                WHEN 'Low Prospek' THEN 3
                                                ELSE 4
                                            END,
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
                                            DATEDIFF(NOW(), TglReport) > 14
                                            AND IsClose = 0
                                        GROUP BY
                                            CASE KategoriProspek
                                                WHEN 'Hot Prospek' THEN 1
                                                WHEN 'Medium Prospek' THEN 2
                                                WHEN 'Low Prospek' THEN 3
                                                ELSE 4
                                            END,
                                            IdReportBuyer
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
                                            DATEDIFF(NOW(), TglReport)  BETWEEN 7 AND 14
                                            AND IsClose = 0
                                        GROUP BY
                                            IdReportBuyer
                                        ORDER BY
                                            CASE KategoriProspek
                                                WHEN 'Hot Prospek' THEN 1
                                                WHEN 'Medium Prospek' THEN 2
                                                WHEN 'Low Prospek' THEN 3
                                                ELSE 4
                                            END,
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
    
    // Report Listing ==================================================================================================================================================================================
    
        public function Get_Report_Listing_Buyer_Agen($idAgen, $limit, $offset, $search = ''){
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= " AND CONCAT(reportlisting.NamaBuyer, ' ', listing.NamaListing, ' ', listing.AlamatTemplate) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
                }
            }
            
            $query = $this->db->query(" SELECT
                                            reportlisting.*,
                                            listing.NamaListing,
                                            listing.AlamatTemplate,
                                            listing.Img1
                                        FROM
                                            reportlisting
                                            LEFT JOIN listing ON reportlisting.IdListing = listing.IdListing
                                        WHERE
                                            (IdAgenListing = $idAgen OR IdAgenCoListing = $idAgen OR IdAgenBuyer = $idAgen)
                                            $searchCondition
                                        ORDER BY 
                                            TglUpdateReport DESC
                                        LIMIT $limit OFFSET $offset; ");
            return $query->result();
        }
        
        public function Get_Detail_Report_Listing_Buyer($id){
            $query = $this->db->query(" SELECT 
                                            reportlisting.*,
                                            listing.*,
                                            agen.IdAgen AS IdAgenAgen,
                                            agen.NamaTemp,
                                            agen.NoTelp,
                                            agen.Instagram
                                        FROM 
                                            reportlisting
                                            LEFT JOIN listing ON reportlisting.IdListing = listing.IdListing
                                            LEFT JOIN agen ON reportlisting.IdAgenBuyer = agen.IdAgen
                                        WHERE 
                                            IdReportListing = $id; ");
            return $query->result();
        }
    
    // Tampungan =======================================================================================================================================================================================
    
        // Get -----------------------------------------------------------------
        
        public function Get_List_Tampungan($id, $limit, $offset){
            $query = $this->db->query(" SELECT 
                                            * 
                                        FROM 
                                            sharelokasi 
                                        WHERE 
                                            IdAgen = $id AND IsListing = 0
                                        LIMIT $limit OFFSET $offset; ");
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
        
        public function Get_List_PraListing_Agen($id, $limit, $offset, $search = ''){
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= " AND CONCAT(pralisting.NamaListing, ' ', pralisting.MetaNamaListing, ' ', pralisting.Alamat, ' ', pralisting.Location, ' ', pralisting.Wilayah, ' ', pralisting.Daerah) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
                }
            }
            
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen
                                        FROM 
                                        	pralisting
                                        WHERE
                                            IdAgen = $id AND
                                        	(IsAdmin = 0 OR
                                            IsManager = 0) AND 
                                            IsRejected = 0 AND
                                            IsDelete = 0
                                            $searchCondition
                                        ORDER BY 
                                            Priority ASC, 
                                            IdPraListing DESC
                                        LIMIT $limit OFFSET $offset ");
            return $query->result_array();
        }
        
        public function Get_List_PraListing_Rejected_Agen($id, $limit, $offset, $search = ''){
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= " AND CONCAT(pralisting.NamaListing, ' ', pralisting.MetaNamaListing, ' ', pralisting.Alamat, ' ', pralisting.Location, ' ', pralisting.Wilayah, ' ', pralisting.Daerah) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
                }
            }
            
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen
                                        FROM 
                                        	pralisting
                                        WHERE
                                            IdAgen = $id AND
                                            IsRejected = 1 AND
                                            IsDelete = 0
                                            $searchCondition
                                        ORDER BY 
                                            Priority ASC, 
                                            IdPraListing DESC
                                        LIMIT $limit OFFSET $offset ");
            return $query->result_array();
        }
        
        public function Get_List_PraListing_Admin($limit, $offset, $search = ''){
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= " AND CONCAT(pralisting.NamaListing, ' ', pralisting.MetaNamaListing, ' ', pralisting.Alamat, ' ', pralisting.Location, ' ', pralisting.Wilayah, ' ', pralisting.Daerah) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
                }
            }
            
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen
                                        FROM 
                                        	pralisting
                                        WHERE
                                            IsRejected = 0 AND
                                            IsDelete = 0 AND
                                            IsAdmin = 0
                                            $searchCondition ORDER BY 
                                            Priority ASC, 
                                            IdPraListing ASC
                                        LIMIT $limit OFFSET $offset; ");
            return $query->result_array();
        }
        
        public function Get_List_PraListing_Manager($limit, $offset, $search = ''){
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= " AND CONCAT(pralisting.NamaListing, ' ', pralisting.MetaNamaListing, ' ', pralisting.Alamat, ' ', pralisting.Location, ' ', pralisting.Wilayah, ' ', pralisting.Daerah) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
                }
            }
            
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen
                                        FROM 
                                        	pralisting
                                        WHERE
                                            IsRejected = 0 AND
                                            IsDelete = 0 AND
                                            IsAdmin = 1 AND
                                            IsManager = 0
                                            $searchCondition ORDER BY 
                                            Priority ASC, 
                                            IdPraListing ASC
                                        LIMIT $limit OFFSET $offset; ");
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
                                            penilaian.IdPenilaian,
                                            penilaian.IdPralisting AS IdPraListingPenilaian,
                                            penilaian.IdListing,
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
                                            vendor.IdVendor,
                                            vendor.NamaLengkap AS NamaVendor,
                                            vendor.NoTelp AS NoTelpVendor,
                                            agen1.IdAgen AS IdAgenAgen,
                                            agen1.NamaTemp AS NamaTemp,
                                            agen1.NoTelp AS NoTelp,
                                            agen1.Email AS Email,
                                            agen1.Instagram AS Instagram,
                                            agen2.IdAgen AS IdAgenCo,
                                            agen2.NamaTemp AS NamaTempCo,
                                            agen2.NoTelp AS NoTelpCo,
                                            agen2.Email AS EmailCo,
                                            agen2.Instagram AS InstagramCo,
                                            reportvendor.IdReport,
                                            reportvendor.IdPraListing AS IdPraListingReportVendor,
                                            reportvendor.Repost,
                                            reportvendor.Catatan,
                                            template.IdTemplate,
                                            template.IdListing AS IdListingTemplate,
                                            template.Template,
                                            template.TemplateBlank
                                        FROM 
                                            `pralisting`
                                            LEFT JOIN penilaian ON pralisting.IdPraListing = penilaian.IdPraListing
                                            LEFT JOIN reportvendor ON pralisting.IdPraListing = reportvendor.IdPraListing
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
        
        public function Add_Listing($id) {
            $cek = $this->db->get_where('pralisting', ['IdPraListing' => $id])->row();
        
            if (!$cek) {
                return null;
            }
        
            $sql = "INSERT INTO `listing` (
                        `IdAgen`,`IdAgenCo`,`IdInput`,`IdVendor`,`NoArsip`,`NamaListing`,`MetaNamaListing`,
                        `Alamat`,`AlamatTemplate`,`Latitude`,`Longitude`,`Location`,`Wilayah`,`Daerah`,
                        `Provinsi`,`Selfie`,`Wide`,`Land`,`Dimensi`,`Listrik`,`Level`,`Bed`,`Bath`,
                        `BedArt`,`BathArt`,`Garage`,`Carpot`,`Hadap`,`RowJalan`,`SHM`,`HGB`,`HSHP`,`PPJB`,
                        `Stratatitle`,`AJB`,`PetokD`,`Pjp`,`ImgSHM`,`ImgHGB`,`ImgHSHP`,`ImgPPJB`,`ImgStratatitle`,
                        `ImgAJB`,`ImgPetokD`,`ImgPjp`,`ImgPjp1`,`NoCertificate`,`Pbb`,`JenisProperti`,
                        `JenisCertificate`,`SumberAir`,`Kondisi`,`RuangTamu`,`RuangMakan`,`Dapur`,`Jemuran`,
                        `Masjid`,`Taman`,`Playground`,`Cctv`,`OneGateSystem`,`Deskripsi`,`MetaDeskripsi`,
                        `Prabot`,`KetPrabot`,`Priority`,`Ttd`,`Banner`,`Size`,`Harga`,`HargaSewa`,`RangeHarga`,
                        `Include`,`TglInput`,`TglUpdate`,`Img1`,`Img2`,`Img3`,`Img4`,`Img5`,`Img6`,`Img7`,
                        `Img8`,`Img9`,`Img10`,`Img11`,`Img12`,`Video`,`LinkFacebook`,`LinkTiktok`,`LinkInstagram`,
                        `LinkYoutube`,`IsAdmin`,`IsManager`,`IsRejected`,`Sold`,`Rented`,`SoldAgen`,`RentedAgen`,
                        `View`,`Marketable`,`StatusHarga`,`IsSelfie`,`IsLokasi`,`Surveyor`,`Fee`,`NoKtp`,
                        `ImgKtp`,`TipeHarga`,`Pending`,`IsCekLokasi`,`IsDouble`,`IsDelete`,`Akun1`,`Akun2`,
                        `InUse`,`Area`,`IsSingleOpen`
                    ) 
                    SELECT 
                        `IdAgen`,`IdAgenCo`,`IdInput`,`IdVendor`,`NoArsip`,`NamaListing`,`MetaNamaListing`,
                        `Alamat`,`AlamatTemplate`,`Latitude`,`Longitude`,`Location`,`Wilayah`,`Daerah`,
                        `Provinsi`,`Selfie`,`Wide`,`Land`,`Dimensi`,`Listrik`,`Level`,`Bed`,`Bath`,
                        `BedArt`,`BathArt`,`Garage`,`Carpot`,`Hadap`,`RowJalan`,`SHM`,`HGB`,`HSHP`,`PPJB`,
                        `Stratatitle`,`AJB`,`PetokD`,`Pjp`,`ImgSHM`,`ImgHGB`,`ImgHSHP`,`ImgPPJB`,`ImgStratatitle`,
                        `ImgAJB`,`ImgPetokD`,`ImgPjp`,`ImgPjp1`,`NoCertificate`,`Pbb`,`JenisProperti`,
                        `JenisCertificate`,`SumberAir`,`Kondisi`,`RuangTamu`,`RuangMakan`,`Dapur`,`Jemuran`,
                        `Masjid`,`Taman`,`Playground`,`Cctv`,`OneGateSystem`,`Deskripsi`,`MetaDeskripsi`,
                        `Prabot`,`KetPrabot`,`Priority`,`Ttd`,`Banner`,`Size`,`Harga`,`HargaSewa`,`RangeHarga`,
                        `Include`,`TglInput`,`TglUpdate`,`Img1`,`Img2`,`Img3`,`Img4`,`Img5`,`Img6`,`Img7`,
                        `Img8`,`Img9`,`Img10`,`Img11`,`Img12`,`Video`,`LinkFacebook`,`LinkTiktok`,`LinkInstagram`,
                        `LinkYoutube`,`IsAdmin`,`IsManager`,`IsRejected`,`Sold`,`Rented`,`SoldAgen`,`RentedAgen`,
                        `View`,`Marketable`,`StatusHarga`,`IsSelfie`,`IsLokasi`,`Surveyor`,`Fee`,`NoKtp`,
                        `ImgKtp`,`TipeHarga`,`Pending`,`IsCekLokasi`,`IsDouble`,`IsDelete`,`Akun1`,`Akun2`,
                        `InUse`,`Area`,`IsSingleOpen`
                    FROM `pralisting` 
                    WHERE `IdPraListing` = ?";
        
            $this->db->query($sql, [$id]);
        
            if ($this->db->affected_rows() > 0) {
                return $this->db->insert_id();
            } else {
                return null;
            }
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen
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
                    Img1,
                    TipeHarga,
                    IsSingleOpen
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
                    Img1,
                    TipeHarga,
                    IsSingleOpen
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen
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
        
        public function Get_List_Listing_Selection($limit, $offset, $search = '') {
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= " AND CONCAT(listing.NamaListing, ' ', listing.MetaNamaListing, ' ', listing.Alamat, ' ', listing.Location, ' ', listing.Wilayah, ' ', listing.Daerah) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
                }
            }
            
            $query = $this->db->query("
                SELECT 
                    *
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
        
        public function Get_List_Listing_Pasang_Banner($limit, $offset, $search = '') {
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= " AND CONCAT(NamaListing, ' ', MetaNamaListing, ' ', Alamat, ' ', Location, ' ', Wilayah, ' ', Daerah) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen,
                                            NoUrut,
                                            TiketBanner
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
                                            Banner = 'Ya' AND 
                                            IsPasangBanner = 0
                                            $searchCondition
                                        ORDER BY
                                            NoUrut DESC
                                        LIMIT $limit OFFSET $offset ");
            return $query->result_array();
        }
        
        public function Get_List_Listing_Pasang_Banner_Selesai($limit, $offset, $search = '') {
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= " AND CONCAT(NamaListing, ' ', MetaNamaListing, ' ', Alamat, ' ', Location, ' ', Wilayah, ' ', Daerah) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen,
                                            NoUrut,
                                            TiketBanner
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
                                            Banner = 'Ya' AND 
                                            IsPasangBanner = 1
                                            $searchCondition
                                        ORDER BY 
                                            TglPesanBanner DESC
                                        LIMIT $limit OFFSET $offset ");
            return $query->result_array();
        }
        
        public function Get_List_Listing_Pasang_Banner_Agen($id, $limit, $offset, $search = '') {
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= " AND CONCAT(NamaListing, ' ', MetaNamaListing, ' ', Alamat, ' ', Location, ' ', Wilayah, ' ', Daerah) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen,
                                            NoUrut,
                                            TiketBanner
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
                                            Banner = 'Ya' AND 
                                            IsPasangBanner = 0 AND 
                                            (IdAgen = $id OR IdAgenCo = $id)
                                            $searchCondition
                                        ORDER BY
                                            TglPesanBanner DESC
                                        LIMIT $limit OFFSET $offset ");
            return $query->result_array();
        }
        
        public function Get_List_Listing_Pasang_Banner_Agen_Selesai($id, $limit, $offset, $search = '') {
            $searchCondition = '';
            if (!empty($search)) {
                $keywords = explode(' ', $search);
                foreach ($keywords as $keyword) {
                    $searchCondition .= " AND CONCAT(NamaListing, ' ', MetaNamaListing, ' ', Alamat, ' ', Location, ' ', Wilayah, ' ', Daerah) LIKE '%" . $this->db->escape_like_str($keyword) . "%' ";
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen,
                                            NoUrut,
                                            TiketBanner
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
                                            Banner = 'Ya' AND 
                                            IsPasangBanner = 1 AND 
                                            (IdAgen = $id OR IdAgenCo = $id)
                                            $searchCondition
                                        ORDER BY
                                            TglPesanBanner DESC
                                        LIMIT $limit OFFSET $offset ");
            return $query->result_array();
        }
        
        public function Get_Bukti_Pasang_Banner($id){
            $query = $this->db->query(" SELECT 
                                        	*
                                        FROM 
                                        	pasangbanner
                                        WHERE
                                            IdListing = $id
                                        ORDER BY IdPasangBanner DESC
                                        LIMIT 1; ");
            return $query->result_array();
        }
        
        public function Get_Keterangan_Pasang_Ulang_Banner($id){
            $query = $this->db->query(" SELECT 
                                        	*
                                        FROM 
                                        	pasangulang
                                        WHERE
                                            IdListing = $id
                                        ORDER BY IdListing DESC
                                        LIMIT 1; ");
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
                                            listing.Video,
                                            listing.LinkYoutube,
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
                                            agen1.Email AS Email,
                                            agen1.Instagram AS Instagram,
                                            agen2.IdAgen AS IdAgenCo,
                                            agen2.NamaTemp AS NamaTempCo,
                                            agen2.NoTelp AS NoTelpCo,
                                            agen2.Email AS EmailCo,
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
        
        public function Cek_Double_Template_Listing($id){
            $query = $this->db->query(" SELECT 
                                            COUNT(*) AS Jumlah
                                        FROM 
                                            template
                                        WHERE 
                                            IdListing = $id;");
            return $query->result_array();
        }
        
        public function Get_Double_Template_Listing($id){
            $query = $this->db->query(" SELECT 
                                            *
                                        FROM 
                                            template
                                        WHERE 
                                            IdListing = $id;");
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
                                            Img1,
                                            TipeHarga,
                                            IsSingleOpen
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
        
        public function Get_Brosur_Primary($id){
            $query = $this->db->query(" SELECT 
                                        	Brosur
                                        FROM 
                                        	brosurprimary
                                        WHERE
                                            IdListing = $id
                                        ORDER BY IdBrosur DESC
                                        LIMIT 1; ");
            return $query->result_array();
        }
        
        public function Get_Siteplan_Primary($id){
            $query = $this->db->query(" SELECT 
                                        	Siteplan
                                        FROM 
                                        	siteplanprimary
                                        WHERE
                                            IdListing = $id
                                        ORDER BY IdSiteplan DESC
                                        LIMIT 1; ");
            return $query->result_array();
        }
        
        public function Get_Pricelist_Primary($id){
            $query = $this->db->query(" SELECT 
                                        	Pricelist
                                        FROM 
                                        	pricelistprimary
                                        WHERE
                                            IdListing = $id
                                        ORDER BY IdPricelist DESC
                                        LIMIT 1; ");
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
        
    // SOP ==========================================================================================================================================================================================
    
        // Get -----------------------------------------------------------------
        
        public function Get_Sop($limit, $offset) {
            $query = $this->db->query(" 
                                        SELECT
                                            *
                                        FROM 
                                            sop
                                        ORDER BY
                                        	IdSop DESC
                                        LIMIT $limit OFFSET $offset;");
            
            return $query->result_array();
        }
        
    // Count ========================================================================================================================================================================================
    
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
        
        public function Count_Pasang_Banner(){
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
                                            Banner = 'Ya' AND 
                                            IsPasangBanner = 0;");
            return $query->result_array();
        }
        
        public function Count_Pasang_Banner_Agen($id){
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
                                            Banner = 'Ya' AND 
                                            IsPasangBanner = 0 AND 
                                            (IdAgen = $id OR IdAgenCo = $id);");
            return $query->result_array();
        }
        
        public function Count_Pasang_Banner_Hari_Ini(){
            $query = $this->db->query(" SELECT 
                                            COUNT(*) AS Total
                                        FROM 
                                            pasangbanner
                                        WHERE
                                            DATE(UpdatedAt) = CURDATE();");
            return $query->result_array();
        }
        
        public function Count_Report_Buyer(){
            $query = $this->db->query(" SELECT 
                                            COUNT(*) AS Total
                                        FROM 
                                            reportbuyer
                                        WHERE
                                            IsRead = 0 AND 
                                            IsClose = 0;");
            return $query->result_array();
        }
        
        public function Count_Report_Buyer_Agen($id){
            $query = $this->db->query(" SELECT 
                                            COUNT(*) AS Total
                                        FROM 
                                            reportbuyer
                                        WHERE
                                            IsRead = 0 AND 
                                            IsClose = 0 AND 
                                            IdAgen = $id;");
            return $query->result_array();
        }
}