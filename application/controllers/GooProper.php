<?php
    defined('BASEPATH')OR exit('No direct script access allowed');
	class GooProper extends CI_Controller{
		function __construct(){
			parent::__construct();
			$this -> load -> model('M_GooProper');
			$this -> load -> helper('url');
			$this -> load -> library('form_validation');
			$this -> load -> library('encryption');
		}
		
		public function index(){
		    if ($this->session->userdata('user_id')) {
                redirect('GooProper/dashboard');
            } else {
			    redirect('GooProper/login');
            }
		}

        public function login(){
            if ($this->session->userdata('user_id')) {
                redirect('GooProper/dashboard');
            } else {
			    $data['title'] = 'Login - Goo Proper';
    			$this->load->view('Admin/_Template/head', $data, TRUE);
    			$this->load->view('crud/login');
            }
		}
		
		public function cek_log(){
			$username = $this->input->post('username');
            $password = $this->input->post('password');
        
            $user_data = $this->M_GooProper->login($username, $password);
        
            if ($user_data) {
                $this->session->set_userdata($user_data);
        
                if ($user_data['status'] == "1") {
                    redirect('GooProper/dashboard');
                } elseif ($user_data['status'] == "2") {
                    redirect('GooProper/dashboard');
                } elseif ($user_data['status'] == "3") {
                    redirect('GooProper/dashboard');
                } elseif ($user_data['status'] == "4") {
                    redirect('GooProper/dashboard');
                }
            } else {
                redirect('GooProper/login');
            }
		}
		
		public function logout(){
            $this->session->sess_destroy();
			redirect('GooProper/login');
        }
		
		public function dashboard(){
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->getNewListing();
			$data['listingsold'] = $this->M_GooProper->getListingSold();
			$data['listingpopuler'] = $this->M_GooProper->getListingPopuler();
			$data['title'] = 'Dashborad';
			$encrypted_data = $this->encryption->encrypt($data);
			
			$this->load->view('Admin/_Template/head', $encrypted_data, TRUE);
			$this->template->views('crud/dashboard',$data);
		}
		
		public function followup(){
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['followup'] = $this->M_GooProper->getFollowUp();
			$data['followupadmin'] = $this->M_GooProper->getFollowUpAdmin();
			$data['title'] = 'Follow Up';
			
			$this->load->view('Admin/_Template/head', $data, TRUE);
			$this->template->views('crud/followup',$data);
		}
		
		public function tambahfollowup($idListing){
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->DetailListing($idListing);
			$data['title'] = 'Follow Up';
			
			$this->load->view('Admin/_Template/head', $data, TRUE);
			$this->template->views('crud/tambahfollowup',$data);
		}
		
		public function tambahlisting(){
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['title'] = 'Tambah Listing';
			
			$this->load->view('Admin/_Template/head', $data, TRUE);
			$this->template->views('crud/tambah_listing',$data);
		}
		
		public function listingku(){
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->getListingKu();
			$data['title'] = 'ListingKu';
			
			$this->load->view('Admin/_Template/head', $data, TRUE);
			$this->template->views('crud/listingku',$data);
		}
		
		public function listingsold(){
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->getListingSold();
			$data['title'] = 'Listing Sold';
			
			$this->load->view('Admin/_Template/head', $data, TRUE);
			$this->template->views('crud/listingsold',$data);
		}
		
		public function listingpopuler(){
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->getListingPopuler();
			$data['title'] = 'Listing Populer';
			
			$this->load->view('Admin/_Template/head', $data, TRUE);
			$this->template->views('crud/listingpopuler',$data);
		}
		
		public function listingnew(){
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->getNewListing();
			$data['title'] = 'Listing';
			
			$this->load->view('Admin/_Template/head', $data, TRUE);
			$this->template->views('crud/listingnew',$data);
		}
		
		public function pralisting(){
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->getPraListing();
			$data['title'] = 'Pra Listing';
			
			$this->load->view('Admin/_Template/head', $data, TRUE);
			$this->template->views('crud/pralisting',$data);
		}
		
		public function listing(){
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->getListing();
			$data['title'] = 'Listing';
			
			$this->load->view('Admin/_Template/head', $data, TRUE);
			$this->template->views('crud/listing',$data);
		}
		
		public function agen(){
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['agen'] = $this->M_GooProper->getAgen();
			$data['title'] = 'Agen';
			
			$this->load->view('Admin/_Template/head', $data, TRUE);
			$this->template->views('crud/agen',$data);
		}
		
		public function pelamar(){
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['agen'] = $this->M_GooProper->getPelamar();
			$data['title'] = 'Pelamar';
			
			$this->load->view('Admin/_Template/head', $data, TRUE);
			$this->template->views('crud/pelamar',$data);
		}
		
		public function detail($idListing){
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->DetailListing($idListing);
			$data['title'] = 'Detail Listing';
			
			$this->load->view('Admin/_Template/head', $data, TRUE);
			$this->template->views('crud/detail_listing',$data);
		}
		
		public function addpralisting(){
		}
		
		public function kirimPesan() {
            $nomorTelepon = '628113338838';
    
            $tautanWhatsapp = 'https://wa.me/' . $nomorTelepon;
    
            redirect($tautanWhatsapp);
        }
		
		public function Template($idListing) {
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->DetailPraListing($idListing);
			$data['title'] = 'Template';
			
    		$this->load->view('crud/lihat_template',$data);
        }
		
		public function TemplateBlank($idListing) {
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->DetailPraListing($idListing);
			$data['title'] = 'Template';
			
    		$this->load->view('crud/lihat_template_kosong',$data);
        }
		
		public function TemplateExclusive($idListing) {
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->DetailPraListing($idListing);
			$data['title'] = 'Template';
			
    		$this->load->view('crud/template',$data);
        }
		
		public function TemplateBlankExclusive($idListing) {
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->DetailPraListing($idListing);
			$data['title'] = 'Template';
			
    		$this->load->view('crud/template_kosong',$data);
        }
		
		public function NewTemplate($idListing) {
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->DetailListingTemplate($idListing);
			$data['title'] = 'Template';
			
    		$this->load->view('crud/lihat_template',$data);
        }
		
		public function NewTemplateBlank($idListing) {
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->DetailListingTemplate($idListing);
			$data['title'] = 'Template';
			
    		$this->load->view('crud/lihat_template_kosong',$data);
        }
		
		public function NewTemplateExclusive($idListing) {
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->DetailListingTemplate($idListing);
			$data['title'] = 'Template';
			
    		$this->load->view('crud/template',$data);
        }
		
		public function NewTemplateBlankExclusive($idListing) {
            $user_id = $this->session->userdata('user_id');
            $username = $this->session->userdata('username');
            $status = $this->session->userdata('status');
            $data['user_id'] = $user_id;
            $data['username'] = $username;
            $data['status'] = $status;
			$data['listing'] = $this->M_GooProper->DetailListingTemplate($idListing);
			$data['title'] = 'Template';
			
    		$this->load->view('crud/template_kosong',$data);
        }
        
        
		
		public function Privacy() {
			$data['title'] = 'Ketentuan';
			
    		$this->load->view('crud/privacy');
        }

		
	}
	
?>