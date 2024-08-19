<?php
    class M_GooProper extends CI_Model{
    	
    	function input_data($data,$table){
    		$this->db->insert($table,$data);
    	}
    	
    	function login($username,$password){
    	    $hashed_password = md5($password);
            $query = $this->db->get_where('admin', array('Username' => $username, 'Password' => $hashed_password));
    
            if ($query->num_rows() == 1) {
                $user = $query->row();
                $user_id = $user->IdAdmin;
                $username = $user->Username;
                $status = $user->Status;
                return compact('user_id', 'status', 'username');
            }
    
            $query = $this->db->get_where('agen', array('Username' => $username, 'Password' => $hashed_password));
    
            if ($query->num_rows() == 1) {
                $user = $query->row();
                $user_id = $user->IdAgen;
                $username = $user->Username;
                $status = $user->Status;
                return compact('user_id', 'status', 'username');
            }
    
            $query = $this->db->get_where('customer', array('Username' => $username, 'Password' => $hashed_password));
    
            if ($query->num_rows() == 1) {
                $user = $query->row();
                $user_id = $user->IdCustomer;
                $username = $user->Username;
                $status = $user->Status;
                return compact('user_id', 'status', 'username');
            }
    
            return null;
    	}
    
    	function getNewListing(){
    	    $this->db->select('listing.*, agen.*');
            $this->db->from('listing');
            $this->db->join('agen', 'listing.IdAgen = agen.IdAgen', 'inner');
            return $this->db->get()->result();
    	}
    	
    	function getListingKu(){
    	    $this->db->where('listing.IdAgen',$this->session->userdata('user_id'));
    	    return $this -> db -> get('listing') -> result();
    	}
    	
    	function getListing(){
    	    $this->db->select('listing.*, agen.*');
            $this->db->from('listing');
            $this->db->join('agen', 'listing.IdAgen = agen.IdAgen', 'inner');
            return $this->db->get()->result();
    	}
    	
    	function getListingSold(){
    	    $this->db->select('listing.*, agen.*');
            $this->db->from('listing');
    	    $this->db->where('listing.Sold',1);
            $this->db->join('agen', 'listing.IdAgen = agen.IdAgen', 'inner');
            return $this->db->get()->result();
    	}
    	
    	function getListingPopuler(){
    	    $this->db->select('listing.*, agen.*');
            $this->db->from('listing');
    	    $this->db->where('listing.Priority',"exclusive");
            $this->db->join('agen', 'listing.IdAgen = agen.IdAgen', 'inner');
            return $this->db->get()->result();
    	}
    	
    	function getPraListing(){
    	    $this->db->select('listing.*, agen.*');
            $this->db->from('listing');
            $this->db->join('agen', 'listing.IdAgen = agen.IdAgen', 'inner');
    	    return $this -> db -> get() -> result();
    	}
    	
    	function getFollowUp(){
    	    $this->db->select('flowup.*, listing.*');
            $this->db->from('flowup');
            $this->db->where('flowup.IdInput', $this->session->userdata('user_id'));
            $this->db->join('listing', 'flowup.IdListing = listing.IdListing', 'inner');
            return $this->db->get()->result();
    	}
    	
    	function getFollowUpAdmin(){
    	    $this->db->select('flowup.*, listing.*');
            $this->db->from('flowup');
            $this->db->where('flowup.IdInput', "0");
            $this->db->join('listing', 'flowup.IdListing = listing.IdListing', 'inner');
            return $this->db->get()->result();
    	}
    	
    	public function getAgen(){
    	    $this->db->where('agen.IsAkses',1);
    	    $this->db->where('agen.Approve',1);
    	    return $this -> db -> get('agen') -> result();
        }
    	
    	public function getPelamar(){
    	    $this->db->where('agen.IsAkses',1);
    	    $this->db->where('agen.Approve',0);
    	    return $this -> db -> get('agen') -> result();
        }
        
    	function find($id){
    		$result = $this->db->where('id_barang',$id)
    						   ->limit(1)
    						   ->get('barang');
    		if($result->num_rows()>0){
    			return $result->row();
    		}else{
    			return array();
    		}
    	}
    
    	function DetailListing($idListing){
    	    $this->db->select('listing.*, agen.*');
            $this->db->from('listing');
            $this->db->where('listing.IdListing', $idListing);
            $this->db->join('agen', 'listing.IdAgen = agen.IdAgen', 'inner');
    		$result = $this->db->get();
    		if($result->num_rows()>0){
    			return $result->result();
    		}else {
    			return false;
    		}
    	}
    	
    	function DetailPraListing($idListing){
    	    $this->db->select('pralisting.*, agen.IdAgen, agen.Nama, agen.NoTelpTemp, agen.Photo, agen.NamaTemp, agen.KodeAgen, agenco.Photo AS PhotoCo, agenco.NamaTemp AS NamaTempCo, agenco.KodeAgen AS KodeAgenCo, agenco.NoTelpTemp AS NoTelpTempCo, vendor.IdVendor, vendor.NamaLengkap AS NamaVendor, vendor.NoTelp AS NoTelpVendor');
            $this->db->from('pralisting');
            $this->db->where('pralisting.IdPraListing', $idListing);
            $this->db->join('agen', 'pralisting.IdAgen = agen.IdAgen', 'left');
            $this->db->join('agen AS agenco', 'pralisting.IdAgenCo = agenco.IdAgen', 'left');
            $this->db->join('vendor', 'pralisting.IdVendor = vendor.IdVendor', 'left');
    		$result = $this->db->get();
    		if($result->num_rows()>0){
    			return $result->result();
    		}else {
    			return false;
    		}
    	}
    	
    	function DetailListingTemplate($idListing){
    	    $this->db->select('listing.*, agen.IdAgen, agen.Nama, agen.NoTelpTemp, agen.Photo, agen.NamaTemp, agen.KodeAgen, agenco.Photo AS PhotoCo, agenco.NamaTemp AS NamaTempCo, agenco.KodeAgen AS KodeAgenCo, agenco.NoTelpTemp AS NoTelpTempCo, vendor.IdVendor, vendor.NamaLengkap AS NamaVendor, vendor.NoTelp AS NoTelpVendor');
            $this->db->from('listing');
            $this->db->where('listing.IdListing', $idListing);
            $this->db->join('agen', 'listing.IdAgen = agen.IdAgen', 'left');
            $this->db->join('agen AS agenco', 'listing.IdAgenCo = agenco.IdAgen', 'left');
            $this->db->join('vendor', 'listing.IdVendor = vendor.IdVendor', 'left');
    		$result = $this->db->get();
    		if($result->num_rows()>0){
    			return $result->result();
    		}else {
    			return false;
    		}
    	}
    
    	function barang_keranjang(){
    		$this->db->where('keranjang.idpelanggan',$this->session->userdata('session_id'));
    		return $this -> db -> get('keranjang') -> result();
    	}
    
    	function edit_data($where, $table){
    		return $this -> db -> get_where($table,$where);
    	}
    
    	function update_data($where,$data,$table){
    		$this->db->where($where);
    		$this->db->update($table,$data);
    	}
    
    	function hapus_data($where,$table){
    		$this->db->where($where);
    		$this->db->delete($table);
    	}
    }
?>
