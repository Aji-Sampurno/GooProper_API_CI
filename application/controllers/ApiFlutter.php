<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiFlutter extends CI_Controller
{
    protected $validApiKey;
    
    public function __construct(){
        parent::__construct();
        $this->load->model('ModelFlutter');
        $this->encryption_key = 'gpp090922';
        $this->load->helper('encryption');
        $this->load->helper('url');
		$this->load->library('form_validation');
        $this->validApiKey = 'hjhlasewguwIBDOq98w9q2';
    }
    
    // Authentication ======================================================================================================================================
    
        public function Login() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            if (!isset($input['Username']) || !isset($input['Password'])) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Username dan Password']));
                return;
            }
        
            $userAdmin = $this->ModelFlutter->Login_Admin($input['Username'], $input['Password']);
        
            if ($userAdmin) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user' => $userAdmin]));
            } else {
                $userAgen = $this->ModelFlutter->Login_Agen($input['Username'], $input['Password']);
                
                if ($userAgen) {
                    $idAgen = $userAgen['IdAgen'];
                    
                    $idAgenTidakUpdate = ['66', '123'];
                    
                    if (!in_array($idAgen, $idAgenTidakUpdate)) {
                        $kodeRandom = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
                        $kodeBaru = 'agen' . $kodeRandom;
                        
                        $data = [
                            'Password' => md5($kodeBaru),
                            'KodeVerifikasi' => $kodeRandom,
                        ];
                
                        $where = array('IdAgen' => $idAgen,);
                        $this->ModelFlutter->Update_Data($where,$data,'agen');
                    }
                    
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'user' => $userAgen]));
                } else {
                    $userCustomer = $this->ModelFlutter->Login_Customer($input['Username'], $input['Password']);
                    
                    if ($userCustomer) {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'user' => $userCustomer]));
                    } else {
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(401)
                            ->set_output(json_encode(['status' => 'fail', 'message' => 'Username atau Password Salah']));
                    }
                }
            }
        }
        
        public function Check_Login() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            if (!isset($input['IdAgen'])) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Pengguna Tidak Ditemukan']));
                return;
            }
        
            $idAgen = $input['IdAgen'];
        
            if (empty($idAgen)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Pengguna Tidak Ditemukan']));
                return;
            }
        
            $userAdmin = $this->ModelFlutter->Check_Login($idAgen);
        
            if ($userAdmin) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user' => $userAdmin]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(401)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Pengguna Tidak Ditemukan']));
            }
        }
        
        public function Login_Kode() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            if (!isset($input['Username']) || !isset($input['Kode'])) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Username dan KodeVerifikasi']));
                return;
            }
            
            $userAgen = $this->ModelFlutter->Login_Kode($input['Username'], $input['Kode']);
            
            if ($userAgen) {
                $idAgen = $userAgen['IdAgen'];
                
                $idAgenTidakUpdate = ['66', '123'];
                
                if (!in_array($idAgen, $idAgenTidakUpdate)) {
                    $kodeRandom = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
                    $kodeBaru = 'agen' . $kodeRandom;
                    
                    $data = [
                        'Password' => md5($kodeBaru),
                        'KodeVerifikasi' => $kodeRandom,
                    ];
                    
                    $where = array('IdAgen' => $idAgen,);
                    $this->ModelFlutter->Update_Data($where,$data,'agen');
                }
                
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user' => $userAgen]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(401)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Username atau Kode Salah']));
            }
        }
    
    // Customer ============================================================================================================================================
    
        public function Registrasi_Customer() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $username = $input['Username'];
            $password = $input['Password'];
            $namalengkap = $input['NamaLengkap'];
            $notelp = $input['NoTelp'];
            $email = $input['Email'];

            if(empty($username) || empty($password) || empty($namalengkap)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Data']));
                return;
            }

            $hashed_password = md5($password);

            $data = [
                'Username' => $username,
                'NamaLengkap' => $namalengkap,
                'NoTelp' => $notelp,
                'Email' => $email,
                'Password' => $hashed_password,
            ];

            $insert_id = $this->ModelFlutter->Input_Data($data, 'customer');

            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Registrasi Gagal']));
            }
        }
    
    // Agen ================================================================================================================================================
    
        // Psikotes ------------------------------------------------------------
        
        public function Add_Psikotes(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = array(
				'Pertanyaan1' => $input['Pertanyaan1'],
				'Pertanyaan2' => $input['Pertanyaan2'],
				'Pertanyaan3' => $input['Pertanyaan3'],
				'Pertanyaan4' => $input['Pertanyaan4'],
				'Pertanyaan5' => $input['Pertanyaan5'],
				'Pertanyaan6' => $input['Pertanyaan6'],
				'Pertanyaan7' => $input['Pertanyaan7'],
				'Pertanyaan8' => $input['Pertanyaan8'],
				'Pertanyaan9' => $input['Pertanyaan9'],
				'Pertanyaan10' => $input['Pertanyaan10'],
				'Pertanyaan11' => $input['Pertanyaan11'],
				'Pertanyaan12' => $input['Pertanyaan12'],
				'Pertanyaan13' => $input['Pertanyaan13'],
			);
			$this->db->insert('psikotes',$data);
			$idpsikotes = $this->db->insert_id();
    
            if($idpsikotes) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'IdPsikotes' => $idpsikotes]));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Psikotes Gagal Submit']));
            }
        }
        
        // Add -----------------------------------------------------------------
        
        public function Registrasi_Agen() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = [
                'Username' => $input['Username'],
                'Password' => md5($input['Password']),
                'Nama' => $input['Nama'],
                'NoTelp' => $input['NoTelp'],
                'Email' => $input['Email'],
                'TglLahir' => $input['TglLahir'],
                'KotaKelahiran' => $input['KotaKelahiran'],
                'Pendidikan' => $input['Pendidikan'],
                'NamaSekolah' => $input['NamaSekolah'],
                'MasaKerja' => $input['MasaKerja'],
                'Jabatan' => $input['Jabatan'],
                'Konfirmasi' => $input['Konfirmasi'],
                'AlamatDomisili' => $input['AlamatDomisili'],
                'Facebook' => $input['Facebook'],
                'Instagram' => $input['Instagram'],
                'Npwp' => $input['Npwp'],
                'NoKtp' => $input['NoKtp'],
                'ImgKtp' => $input['ImgKtp'],
                'Cv' => $input['Cv'],
                'Ijazah' => $input['Ijazah'],
                'Photo' => $input['Photo'],
                'Status' => 3,
                'IsAkses' => 1,
            ];
            
            $this->db->insert('agen', $data);
			$insert_id = $this->db->insert_id();
            
            if($insert_id) {
                $add = [
                    'IdAgen'=> $insert_id,
                ];
                $where = array('IdPsikotes'=> $input['IdPsikotes'],);
                $add_id = $this->ModelFlutter->Update_Data($where,$add,'psikotes');
                
                if($add_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'user_id' => $add_id]));
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Registrasi Gagal']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Registrasi Gagal']));
            }
        }
        
        // Update --------------------------------------------------------------
        
        public function Update_Agen() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdAgen = $input['IdUser'];
            $NamaTemp = $input['NamaTemp'];
            $Username = $input['Username'];
            $Email = $input['Email'];
            $NoTelp = $input['WhatsApp'];
            $Instagram = $input['Instagram'];
            $Facebook = $input['Facebook'];
            $Photo = $input['Profile'];
            
            $data = [
                'NamaTemp' => $NamaTemp,
                'Username' => $Username,
                'Email' => $Email,
                'NoTelp' => $NoTelp,
                'Instagram' => $Instagram,
                'Facebook' => $Facebook,
                'Photo' => $Photo,
            ];
            
            $where = array('IdAgen' => $IdAgen,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'agen');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update Profil']));
            }
        }
        
        public function Update_Profile_Agen() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = [
                'NoTelp' => $input['NoTelp'],
                'NoTelpTemp'=> $input['NoTelpTemp'],
                'NamaTemp'=> $input['NamaTemp'],
                'KodeAgen'=> $input['KodeAgen'],
                'KotaAgen'=> $input['KotaAgen'],
                'AgenListing'=> $input['AgenListing'],
            ];
            
            $where = array('IdAgen' => $input['IdAgen'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'agen');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update Profil']));
            }
        }
        
        public function Update_Photo_Agen() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = [
                'Photo' => $input['Photo'],
            ];
            
            $where = array('IdAgen' => $input['IdAgen'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'agen');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update Profil']));
            }
        }
    
        public function Approve_Agen(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdAgen = $input['IdAgen'];
            $Nama = $input['Nama'];
            
            $this->db->trans_start();
            
            $data = [
                'Approve'=> 1,
                'IsAktif'=> 1,
                'IsLogin'=> 1,
                'NoTelpTemp'=> $input['NoTelpTemp'],
                'NamaTemp'=> $input['NamaTemp'],
                'KodeAgen'=> $input['KodeAgen'],
                'KotaAgen'=> $input['KotaAgen'],
                'AgenListing'=> $input['AgenListing'],
            ];
            $where = array('IdAgen'=> $IdAgen,);
            $edit_agen = $this->ModelFlutter->Update_Data($where,$data,'agen');
            
            if($edit_agen) {
                $currentDate = date('Y-m-d');
                list($year, $month, $day) = explode('-', $currentDate);
                $year = substr($year, -2);
                $dateFormatted = $day . $month . $year;
                
                $add = array(
                    'IdAgen' => $IdAgen,
                    'Nama' => $Nama,
                    'Posisi' => "Agen",
                    'Kode' => 600,
                    'TglMasuk' => $dateFormatted,
                    );
                $insert_karyawan = $this->db->insert('karyawan',$add);
                $nourut = $this->db->insert_id();
                
                if($insert_karyawan) {
                    $urut = array('NoKaryawan'=> "600-".$dateFormatted."-".$nourut,	);
                    $where = array('IdKaryawan' => $nourut,);
                    $edit_nourut = $this->ModelFlutter->Update_Data($where,$urut,'karyawan');
                    
                    if($edit_nourut) {
                        $agenkode = "agen600";
                        
                        $pas = array('Password'=> md5($agenkode.$nourut),);
                        $where = array('IdAgen' => $IdAgen,);
                        $edit_pass = $this->ModelFlutter->Update_Data($where,$pas,'agen');
                        
                        if($edit_pass) {
                            $this->db->trans_commit();
                                $this->output
                                    ->set_content_type('application/json')
                                    ->set_status_header(200)
                                    ->set_output(json_encode(['status' => 'success', 'Approve Agen Berhasil']));
                        } else {
                            $this->db->trans_rollback();
                            $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(500)
                                ->set_output(json_encode(['status' => 'fail', 'message' => 'Approve Agen Gagal, Password Gagal Diupdate']));
                        }
                    } else {
                        $this->db->trans_rollback();
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(500)
                            ->set_output(json_encode(['status' => 'fail', 'message' => 'Approve Agen Gagal, No Karyawan Gagal Diupdate']));
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Approve Agen Gagal, Karyawan Gagal di Tambah']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Approve Agen Gagal, Status Gagal Diupdate']));
            }
        }
        
        public function Reject_Agen(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdAgen = $input['IdAgen'];
            
            $this->db->trans_start();
            
            $data = [
                'Reject' => 1,
            ];
            $where = array('IdAgen'=> $IdAgen,);
            $edit_agen = $this->ModelFlutter->Update_Data($where,$data,'agen');
            
            if($edit_agen) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Reject Agen Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Approve Agen Gagal, Status Gagal Diupdate']));
            }
        }
        
        // Get -----------------------------------------------------------------
    
        public function Get_Agen(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            
            $data = $this->ModelFlutter->Get_Agen($limit, $offset, $search);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Agen_List(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = $this->ModelFlutter->Get_Agen_List();
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Agen_Kode_List(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            
            $data = $this->ModelFlutter->Get_Agen_Kode_List($limit, $offset, $search);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Detail_Agen(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Detail_Agen($id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Pelamar(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_Pelamar($limit, $offset);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
            
        public function Get_Detail_Pelamar(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Detail_Pelamar($id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
            
        public function Get_Detail_Pertanyaan(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Detail_Pertanyaan($id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
    
    // Data ================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Device() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdAdmin = $input['IdAdmin'];
            $Status = $input['Status'];
            $Token = $input['Token'];
            
            $existingToken = $this->ModelFlutter->Check_Token($Token);
            
            if ($existingToken) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(409)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Token Sudah Ada']));
                return;
            }
            
            $data = [
                'IdAdmin' => $IdAdmin,
                'Status' => $Status,
                'Token' => $Token,
            ];
            
            $insert_id = $this->ModelFlutter->Input_Data($data, 'device');
            
            if ($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'message' => 'Add Device Berhasil']));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Add Device Gagal']));
            }
        }
        
        public function Add_Device_Agen() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Status = $input['Status'];
            $Token = $input['Token'];
            
            $existingToken = $this->ModelFlutter->Check_Token_Agen($Token);
            
            if ($existingToken) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(409)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Token Sudah Ada']));
                return;
            }
            
            $data = [
                'IdAgen' => $input['IdAgen'],
                'Status' => $Status,
                'Token' => $Token,
            ];
            
            $insert_id = $this->ModelFlutter->Input_Data($data, 'deviceagen');
            
            if ($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'message' => 'Add Device Berhasil']));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Add Device Gagal']));
            }
        }
        
        public function Add_Device_Customer() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Status = $input['Status'];
            $Token = $input['Token'];
            
            $existingToken = $this->ModelFlutter->Check_Token_Customer($Token);
            
            if ($existingToken) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(409)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Token Sudah Ada']));
                return;
            }
            
            $data = [
                'IdCustomer' => $input['IdCustomer'],
                'Status' => $Status,
                'Token' => $Token,
            ];
            
            $insert_id = $this->ModelFlutter->Input_Data($data, 'devicecustomer');
            
            if ($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'message' => 'Add Device Berhasil']));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Add Device Gagal']));
            }
        }
        
        // Delete --------------------------------------------------------------
        
        public function Delete_Device() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
        
            $this->db->trans_start();
            
            $this->db->where('Token', $input['Token']);
            $deleteSuccessful = $this->db->delete('device');
        
            if ($deleteSuccessful) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'message' => 'Device berhasil dihapus']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal menghapus device']));
            }
        }
        
        public function Delete_Device_Agen() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
        
            $this->db->trans_start();
            
            $this->db->where('Token', $input['Token']);
            $deleteSuccessful = $this->db->delete('deviceagen');
        
            if ($deleteSuccessful) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'message' => 'Device berhasil dihapus']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal menghapus device']));
            }
        }
        
        public function Delete_Device_Customer() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
        
            $this->db->trans_start();
            
            $this->db->where('Token', $input['Token']);
            $deleteSuccessful = $this->db->delete('devicecustomer');
        
            if ($deleteSuccessful) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'message' => 'Device berhasil dihapus']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal menghapus device']));
            }
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_Device(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $status = filter_var($_GET['status'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $data = $this->ModelFlutter->Get_Device($status);
            
            $tokens = [];
            
            if (!empty($data)) {
                foreach ($data as $item) {
                    if (isset($item['Token'])) {
                        $tokens[] = $item['Token'];
                    }
                }
            }
            
            $unique_tokens = array_unique($tokens);
            
            $tokens_string = implode(',', $unique_tokens);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['tokens' => $tokens_string]));
        }
        
        public function Get_Device_Agen(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $data = $this->ModelFlutter->Get_Device_Agen($id);
            
            $tokens = [];
            
            if (!empty($data)) {
                foreach ($data as $item) {
                    if (isset($item['Token'])) {
                        $tokens[] = $item['Token'];
                    }
                }
            }
            
            $unique_tokens = array_unique($tokens);
            
            $tokens_string = implode(',', $unique_tokens);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['tokens' => $tokens_string]));
        }
        
        public function Get_Device_All(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
        
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
        
            $limit = $this->input->get('limit', TRUE) ?? 10; // Default 10
            $offset = $this->input->get('offset', TRUE) ?? 0; // Default 0
        
            if (!is_numeric($limit) || !is_numeric($offset)) {
                $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Invalid limit or offset']));
                return;
            }
        
            $data = $this->ModelFlutter->Get_Device_All((int)$limit, (int)$offset);
        
            $tokens = array_column($data, 'Token'); // Ambil kolom 'Token'
            $unique_tokens = array_unique($tokens);
        
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'tokens' => implode(',', $unique_tokens),
                    'count' => count($unique_tokens),
                ]));
        }
        
        public function Get_Jenis_Properti(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = $this->ModelFlutter->Get_Jenis_Properti();
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Wilayah(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Wilayah($id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Daerah(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Daerah($id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Provinsi(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = $this->ModelFlutter->Get_Provinsi();
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Kota_Agen(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = $this->ModelFlutter->Get_Kota_Agen();
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
    
    // Event ===============================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Event(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = array(
				'JudulEvent' => $input['JudulEvent'],
				'TipeEvent' => $input['TipeEvent'],
				'NamaEvent' => $input['NamaEvent'],
				'TglEvent' => $input['TglEvent'],
				'IsiEvent' => $input['IsiEvent'],
				'DeskripsiEvent' => $input['DeskripsiEvent'],
				'GambarEvent' => $input['GambarEvent'],
				'IdListing' => $input['IdListing'],
			);
			$this->db->insert('event',$data);
			$idpsikotes = $this->db->insert_id();
    
            if($idpsikotes) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'message' => 'Berhasil Tambah Event']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Tambah Event']));
            }
        }
        
        // Update --------------------------------------------------------------
        
        public function Update_Event(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = array(
				'JudulEvent' => $input['JudulEvent'],
				'TipeEvent' => $input['TipeEvent'],
				'NamaEvent' => $input['NamaEvent'],
				'TglEvent' => $input['TglEvent'],
				'IsiEvent' => $input['IsiEvent'],
				'DeskripsiEvent' => $input['DeskripsiEvent'],
				'GambarEvent' => $input['GambarEvent'],
				'IdListing' => $input['IdListing'],
			);
			$where = array('IdEvent'=> $input['IdEvent'],);
            $update = $this->ModelFlutter->Update_Data($where,$data,'event');
            
            if($update) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'message' => 'Berhasil Tambah Event']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Tambah Event']));
            }
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_Event() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
        
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
        
            $tgl = filter_var($this->input->get('tanggal'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
            $hasYear = preg_match('/^\d{4}-/', $tgl);
            $data = $this->ModelFlutter->Get_Event($tgl, $hasYear);
		
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Event_All(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = $this->ModelFlutter->Get_Event_All();
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
    
    // Closing =============================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Closing() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            if (!isset($input['IdAgen'])) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Data tidak lengkap']));
                return;
            }
            
            $data = [
                'IdAgen' => $input['IdAgen'],
                'JenisTransaksi' => $input['JenisTransaksi'],
                'NamaPemilik' => $input['NamaPemilik'],
                'NoPemilik' => $input['NoPemilik'],
                'AlamatPemilik' => $input['AlamatPemilik'],
                'NamaBuyer' => $input['NamaBuyer'],
                'NoBuyer' => $input['NoBuyer'],
                'AlamatBuyer' => $input['AlamatBuyer'],
                'JenisProperti' => $input['JenisProperti'],
                'AlamatProperti' => $input['AlamatProperti'],
                'Legalitas' => $input['Legalitas'],
                'NoLegalitas' => $input['NoLegalitas'],
                'HargaJual' => $input['HargaJual'],
                'HargaSewa' => $input['HargaSewa'],
                'DP' => $input['DP'],
                'PeriodeSewa' => $input['PeriodeSewa'],
                'TanggalMasukSewa' => $input['TanggalMasukSewa'],
                'Deposit' => $input['Deposit'],
                'MetodePembayaran' => $input['MetodePembayaran'],
                'GracePeriode' => $input['GracePeriode'],
                'TglMaksDP' => $input['TglMaksDP'],
                'TglMaksPelunasan' => $input['TglMaksPelunasan'],
                'Note' => $input['Note'],
                'KtpPemilik' => $input['KtpPemilik'],
                'KtpPenyewa' => $input['KtpPenyewa']
            ];
            
            $insert_id = $this->ModelFlutter->Input_Data($data, 'closing');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Closing Gagal']));
            }
        }
        
        // Update --------------------------------------------------------------
        
        public function Update_Read(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $sql = "UPDATE reportsold SET IsRead = '1' WHERE IdListing = ?";
            $this->db->query($sql, array($input['IdListing']));
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_Closing_Agen() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            $data = $this->ModelFlutter->Get_Closing_Agen($id, $limit, $offset, $search);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Closing() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            $data = $this->ModelFlutter->Get_Closing($limit, $offset, $search);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Detail_Closing() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Detail_Closing($id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Report_Closing() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            $data = $this->ModelFlutter->Get_Report_Closing($limit, $offset, $search);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
    // Report Buyer ========================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Report_Buyer() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            if (!isset($input['IdAgen'], $input['NamaBuyer'], $input['NoTelp'], $input['JenisProperti'], $input['AlamatProperti'], $input['SumberBuyer'], $input['StatusFollowUp'])) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Data tidak lengkap']));
                return;
            }
            
            $data = [
                'IdAgen' => $input['IdAgen'],
                'NamaBuyer' => $input['NamaBuyer'],
                'TelpBuyer' => $input['NoTelp'],
                'JenisProperti' => $input['JenisProperti'],
                'JenisTransaksi' => $input['JenisTransaksi'],
                'CaraBayar' => $input['CaraBayar'],
                'Budget' => $input['Budget'],
                'AlamatProperti' => $input['AlamatProperti'],
                'SumberInformasi' => $input['SumberBuyer'],
                'StatusFollowUp' => $input['StatusFollowUp'],
                'KeteranganFollowUp' => $input['KeteranganFollowUp'],
                'Selfie' => $input['Selfie']
            ];
            
            $insert_id = $this->ModelFlutter->Input_Data($data, 'reportbuyer');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Report Buyer Gagal']));
            }
        }
        
        // Update --------------------------------------------------------------
        
        public function Update_Report_Buyer() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            if (!isset($input['StatusFollowUp'])) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Data tidak lengkap']));
                return;
            }
            
            date_default_timezone_set('Asia/Jakarta');
            
            $data = [
                'NamaBuyer' => $input['NamaBuyer'],
                'TelpBuyer' => $input['NoTelp'],
                'StatusFollowUp' => $input['StatusFollowUp'],
                'KeteranganFollowUp' => $input['KeteranganFollowUp'],
                'Selfie' => $input['Selfie'],
                'TglReport' => date('Y-m-d H:i:s'),
                'IsRead' => 0
            ];
            
            $where = array('IdReportBuyer'=> $input['IdReport'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'reportbuyer');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Report Buyer Gagal']));
            }
        }
        
        public function Read_Report_Buyer() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = [
                'IsRead' => 1
            ];
            
            $where = array('IdReportBuyer'=> $input['IdReport'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'reportbuyer');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Read Report Buyer Gagal']));
            }
        }
        
        public function Close_Report_Buyer() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = [
                'IsClose' => 1
            ];
            
            $where = array('IdReportBuyer'=> $input['IdReport'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'reportbuyer');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Close Report Buyer Gagal']));
            }
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_Report_Buyer_Agen() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            $status_data = $this->ModelFlutter->Get_Report_Buyer_Agen($id, $limit, $offset, $search);
            
            $current_date = new DateTime();
            $response = [];
        
            foreach ($status_data as $data) {
                $updated_at = new DateTime($data->TglReport);
                $one_month_after_update = clone $updated_at;
                $one_month_after_update->modify('+1 month');
        
                $interval = $current_date->diff($one_month_after_update);
        
                if ($interval->invert == 0) {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => $interval->days,
                        'message' => "Akan jatuh tempo dalam " . $interval->days . " hari."
                    ];
                } else {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => 0,
                        'message' => "Sudah melewati batas 1 bulan."
                    ];
                }
            }
        
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
        
        public function Get_Report_Buyer_Agen_Ready() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $status_data = $this->ModelFlutter->Get_Report_Buyer_Agen_Ready($id, $limit, $offset);
            
            $current_date = new DateTime();
            $response = [];
        
            foreach ($status_data as $data) {
                $updated_at = new DateTime($data->TglReport);
                $one_month_after_update = clone $updated_at;
                $one_month_after_update->modify('+1 month');
        
                $interval = $current_date->diff($one_month_after_update);
        
                if ($interval->invert == 0) {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => $interval->days,
                        'message' => "Akan jatuh tempo dalam " . $interval->days . " hari."
                    ];
                } else {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => 0,
                        'message' => "Sudah melewati batas 1 bulan."
                    ];
                }
            }
        
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
        
        public function Get_Report_Buyer_Agen_To_Expired() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $status_data = $this->ModelFlutter->Get_Report_Buyer_Agen_To_Expired($id, $limit, $offset);
            
            $current_date = new DateTime();
            $response = [];
        
            foreach ($status_data as $data) {
                $updated_at = new DateTime($data->TglReport);
                $one_month_after_update = clone $updated_at;
                $one_month_after_update->modify('+1 month');
        
                $interval = $current_date->diff($one_month_after_update);
        
                if ($interval->invert == 0) {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => $interval->days,
                        'message' => "Akan jatuh tempo dalam " . $interval->days . " hari."
                    ];
                } else {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => 0,
                        'message' => "Sudah melewati batas 1 bulan."
                    ];
                }
            }
        
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
        
        public function Get_Report_Buyer_Agen_Expired() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $status_data = $this->ModelFlutter->Get_Report_Buyer_Agen_Expired($id, $limit, $offset);
            
            $current_date = new DateTime();
            $response = [];
        
            foreach ($status_data as $data) {
                $updated_at = new DateTime($data->TglReport);
                $one_month_after_update = clone $updated_at;
                $one_month_after_update->modify('+1 month');
        
                $interval = $current_date->diff($one_month_after_update);
        
                if ($interval->invert == 0) {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => $interval->days,
                        'message' => "Akan jatuh tempo dalam " . $interval->days . " hari."
                    ];
                } else {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => 0,
                        'message' => "Sudah melewati batas 1 bulan."
                    ];
                }
            }
        
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
        
        public function Get_Report_Buyer() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            $status_data = $this->ModelFlutter->Get_Report_Buyer($limit, $offset, $search);
            
            $current_date = new DateTime();
            $response = [];
        
            foreach ($status_data as $data) {
                $updated_at = new DateTime($data->TglReport);
                $one_month_after_update = clone $updated_at;
                $one_month_after_update->modify('+1 month');
        
                $interval = $current_date->diff($one_month_after_update);
        
                if ($interval->invert == 0) {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => $interval->days,
                        'message' => "Akan jatuh tempo dalam " . $interval->days . " hari."
                    ];
                } else {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => 0,
                        'message' => "Sudah melewati batas 1 bulan."
                    ];
                }
            }
        
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
        
        public function Get_Report_Buyer_Ready() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $status_data = $this->ModelFlutter->Get_Report_Buyer_Ready($limit, $offset);
            
            $current_date = new DateTime();
            $response = [];
        
            foreach ($status_data as $data) {
                $updated_at = new DateTime($data->TglReport);
                $one_month_after_update = clone $updated_at;
                $one_month_after_update->modify('+1 month');
        
                $interval = $current_date->diff($one_month_after_update);
        
                if ($interval->invert == 0) {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => $interval->days,
                        'message' => "Akan jatuh tempo dalam " . $interval->days . " hari."
                    ];
                } else {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => 0,
                        'message' => "Sudah melewati batas 1 bulan."
                    ];
                }
            }
        
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
        
        public function Get_Report_Buyer_To_Expired() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $status_data = $this->ModelFlutter->Get_Report_Buyer_To_Expired($limit, $offset);
            
            $current_date = new DateTime();
            $response = [];
        
            foreach ($status_data as $data) {
                $updated_at = new DateTime($data->TglReport);
                $one_month_after_update = clone $updated_at;
                $one_month_after_update->modify('+1 month');
        
                $interval = $current_date->diff($one_month_after_update);
        
                if ($interval->invert == 0) {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => $interval->days,
                        'message' => "Akan jatuh tempo dalam " . $interval->days . " hari."
                    ];
                } else {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => 0,
                        'message' => "Sudah melewati batas 1 bulan."
                    ];
                }
            }
        
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
        
        public function Get_Report_Buyer_Expired() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $status_data = $this->ModelFlutter->Get_Report_Buyer_Expired($limit, $offset);
            
            $current_date = new DateTime();
            $response = [];
        
            foreach ($status_data as $data) {
                $updated_at = new DateTime($data->TglReport);
                $one_month_after_update = clone $updated_at;
                $one_month_after_update->modify('+1 month');
        
                $interval = $current_date->diff($one_month_after_update);
        
                if ($interval->invert == 0) {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => $interval->days,
                        'message' => "Akan jatuh tempo dalam " . $interval->days . " hari."
                    ];
                } else {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => 0,
                        'message' => "Sudah melewati batas 1 bulan."
                    ];
                }
            }
        
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
        
        public function Get_Detail_Report_Buyer() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $status_data = $this->ModelFlutter->Get_Detail_Report_Buyer($id);
            
            $current_date = new DateTime();
            $response = [];
        
            foreach ($status_data as $data) {
                $updated_at = new DateTime($data->TglReport);
                $one_month_after_update = clone $updated_at;
                $one_month_after_update->modify('+1 month');
        
                $interval = $current_date->diff($one_month_after_update);
        
                if ($interval->invert == 0) {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaTemp' => $data->NamaTemp,
                        'NoTelp' => $data->NoTelp,
                        'Instagram' => $data->Instagram,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => $interval->days,
                        'message' => "Akan jatuh tempo dalam " . $interval->days . " hari."
                    ];
                } else {
                    $response[] = [
                        'IdReportBuyer' => $data->IdReportBuyer,
                        'IdAgen' => $data->IdAgen,
                        'NamaTemp' => $data->NamaTemp,
                        'NamaBuyer' => $data->NamaBuyer,
                        'TelpBuyer' => $data->TelpBuyer,
                        'JenisProperti' => $data->JenisProperti,
                        'JenisTransaksi' => $data->JenisTransaksi,
                        'CaraBayar' => $data->CaraBayar,
                        'Budget' => $data->Budget,
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'KeteranganFollowUp' => $data->KeteranganFollowUp,
                        'Selfie' => $data->Selfie,
                        'TglReport' => $data->TglReport,
                        'IsRead' => $data->IsRead,
                        'IsClose' => $data->IsClose,
                        'days_remaining' => 0,
                        'message' => "Sudah melewati batas 1 bulan."
                    ];
                }
            }
        
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
        
    // Report Listing ======================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Report_Listing_Buyer() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            if (!isset($input['IdListing'], $input['IdAgenListing'], $input['IdAgenCoListing'], $input['IdAgenBuyer'], $input['NamaBuyer'], $input['TelpBuyer'], $input['StatusReport'])) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Data tidak lengkap']));
                return;
            }
            
            $data = [
                'IdListing' => $input['IdListing'],
                'IdAgenListing' => $input['IdAgenListing'],
                'IdAgenCoListing' => $input['IdAgenCoListing'],
                'IdAgenBuyer' => $input['IdAgenBuyer'],
                'NamaBuyer' => $input['NamaBuyer'],
                'TelpBuyer' => $input['TelpBuyer'],
                'StatusReport' => $input['StatusReport']
            ];
            
            $insert_id = $this->ModelFlutter->Input_Data($data, 'reportlisting');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Report Listing Buyer Gagal']));
            }
        }
        
        // Update --------------------------------------------------------------
        
        public function Update_Report_Listing_Buyer() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            if (!isset($input['StatusReport'])) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Data tidak lengkap']));
                return;
            }
            
            date_default_timezone_set('Asia/Jakarta');
            
            $data = [
                'StatusReport' => $input['StatusReport']
            ];
            
            $where = array('IdReportListing'=> $input['IdReportListing'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'reportlisting');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Report Listing Buyer Gagal']));
            }
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_Report_Listing_Buyer_Agen() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $idAgen = filter_var($_GET['IdAgen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            
            $data = $this->ModelFlutter->Get_Report_Listing_Buyer_Agen($idAgen, $limit, $offset, $search);
            
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($data));
        }
        
        public function Get_Detail_Report_Listing_Buyer() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $status_data = $this->ModelFlutter->Get_Detail_Report_Listing_Buyer($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($status_data));
        }
        
    // Report Vendor =======================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Repost_Vendor() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            if (!isset($input['IdListing'], $input['Repost'])) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Data tidak lengkap']));
                return;
            }
            
            $data = [
                'IdListing' => $input['IdListing'],
                'Repost' => $input['Repost'],
            ];
            
            $insert_id = $this->ModelFlutter->Input_Data($data, 'reportvendor');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Report Gagal']));
            }
        }
        
        public function Add_Catatan_Vendor() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            if (!isset($input['IdListing'], $input['Catatan'])) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Data tidak lengkap']));
                return;
            }
            
            $data = [
                'IdListing' => $input['IdListing'],
                'Catatan' => $input['Catatan'],
            ];
            
            $insert_id = $this->ModelFlutter->Input_Data($data, 'reportvendor');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Report Gagal']));
            }
        }
        
        // Update --------------------------------------------------------------
        
        public function Update_Repost_Vendor() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            if (!isset($input['Repost'])) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Data tidak lengkap']));
                return;
            }
            
            $data = [
                'Repost' => $input['Repost'],
            ];
            
            $where = array('IdReport'=> $input['IdReport'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'reportvendor');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Report Gagal']));
            }
        }
        
        public function Update_Catatan_Vendor() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            if (!isset($input['Catatan'])) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Data tidak lengkap']));
                return;
            }
            
            $data = [
                'Catatan' => $input['Catatan'],
            ];
            
            $where = array('IdReport'=> $input['IdReport'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'reportvendor');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Report Gagal']));
            }
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_Report_Vendor() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            
            $data = $this->ModelFlutter->Get_Report_Vendor($limit, $offset);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
    // Tampungan ===========================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Tampungan() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdAgen = $input['IdAgen'];
            $Alamat = $input['Alamat'];
            $Lokasi = $input['Lokasi'];
            $Latitude = $input['Latitude'];
            $Longitude = $input['Longitude'];
            $Selfie = $input['Selfie'];
            
            $data = [
                'IdAgen' => $IdAgen,
                'Alamat' => $Alamat,
                'Lokasi' => $Lokasi,
                'Latitude' => $Latitude,
                'Longitude' => $Longitude,
                'Selfie' => $Selfie,
                'IsListing' => 0,
            ];
            
            $insert_id = $this->ModelFlutter->Input_Data($data, 'sharelokasi');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Tampungan Gagal']));
            }
        }
        
        // Delete ---------------------------------------------------------------
        
        public function Delete_Tampungan() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            if (!isset($input['IdShareLokasi'])) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'IdTampungan tidak diberikan']));
                return;
            }
        
            $IdShareLokasi = $input['IdShareLokasi'];
        
            $this->db->trans_start();
            
            $this->db->where('IdShareLokasi', $IdShareLokasi);
            $deleteSuccessful = $this->db->delete('sharelokasi');
        
            if ($deleteSuccessful) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'message' => 'Tampungan berhasil dihapus']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal menghapus Tampungan']));
            }
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_List_Tampungan(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_List_Tampungan($id, $limit, $offset);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
    // Pasang Banner =======================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Update_Pasang_Banner_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = [
                'IdListing' => $input['IdListing'],
                'Bukti' => $input['Bukti'],
                'Keterangan' => $input['Keterangan'],
            ];
            $insert_id = $this->ModelFlutter->Input_Data($data, 'pasangbanner');
            
            if($insert_id) {
                
                $dataup = [
                    'IsPasangBanner' => 1,
                ];
                $where = array('IdListing'=> $input['IdListing'],);
                $insert_up = $this->ModelFlutter->Update_Data($where,$dataup,'listing');
                
                if($insert_up) {
                    $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Pasang Banner Berhasil']));
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Pasang Banner Gagal']));
                }
                
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Pasang Banner Gagal']));
            }
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_List_Listing_Pasang_Banner() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            
            $data = $this->ModelFlutter->Get_List_Listing_Pasang_Banner($limit, $offset, $search);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Listing_Pasang_Banner_Selesai() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            
            $data = $this->ModelFlutter->Get_List_Listing_Pasang_Banner_Selesai($limit, $offset, $search);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Listing_Pasang_Banner_Agen() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            
            $data = $this->ModelFlutter->Get_List_Listing_Pasang_Banner_Agen($id, $limit, $offset, $search);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Listing_Pasang_Banner_Agen_Selesai() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            
            $data = $this->ModelFlutter->Get_List_Listing_Pasang_Banner_Agen_Selesai($id, $limit, $offset, $search);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Bukti_Pasang_Banner() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Bukti_Pasang_Banner($id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
    // Pasang Ulang ========================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Update_Pasang_Ulang_Banner_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = [
                'IdListing' => $input['IdListing'],
                'Keterangan' => $input['Keterangan'],
            ];
            $insert_id = $this->ModelFlutter->Input_Data($data, 'pasangulang');
            
            if($insert_id) {
                
                $dataup = [
                    'IsPasangBanner' => 0,
                ];
                $where = array('IdListing'=> $input['IdListing'],);
                $insert_up = $this->ModelFlutter->Update_Data($where,$dataup,'listing');
                
                if($insert_up) {
                    $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Pasang Ulang Banner Berhasil']));
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Pasang Ulang Banner Gagal']));
                }
                
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Pasang Ulang Banner Gagal']));
            }
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_Keterangan_Pasang_Ulang_Banner() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Keterangan_Pasang_Ulang_Banner($id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
    // Pralisting ==========================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $inputDate = $input['TglInput'];
            $currentDate = date('Y-m-d');
            
            $NamaLengkap = $input['NamaLengkap'];
            $NoTelp = $input['NoTelp'];
            $AlamatVendor = $input['AlamatVendor'];
            $TglLahir = $input['TglLahir'];
            $Nik = $input['Nik'];
            $NoRekening = $input['NoRekening'];
            $Bank = $input['Bank'];
            $AtasNama = $input['AtasNama'];
			
            $IdAgen = $input['IdAgen'];
            $IdAgenCo = $input['IdAgenCo'];
            $IdInput = $input['IdInput'];
            $NamaListing = $input['NamaListing'];
            $MetaNamaListing = $input['MetaNamaListing'];	
            $Alamat = $input['Alamat'];	
            $AlamatTemplate = $input['AlamatTemplate'];
            $Latitude = $input['Latitude'];
            $Longitude = $input['Longitude'];
            $Location = $input['Location'];
            $Wilayah = $input['Wilayah'];
            $Daerah = $input['Daerah'];
            $Provinsi = $input['Provinsi'];
            $Wide = $input['Wide'];	
            $Land = $input['Land'];	
            $Dimensi = $input['Dimensi'];	
            $Listrik = $input['Listrik'];	
            $Level = $input['Level'];	
            $Bed = $input['Bed'];	
            $Bath = $input['Bath'];	
            $BedArt = $input['BedArt'];	
            $BathArt = $input['BathArt'];	
            $Garage = $input['Garage'];	
            $Carpot = $input['Carpot'];
            $Hadap = $input['Hadap'];
            $SHM = $input['SHM'];
            $HGB = $input['HGB'];
            $HSHP = $input['HSHP'];
            $PPJB = $input['PPJB'];
            $Stratatitle = $input['Stratatitle'];
            $AJB = $input['AJB'];
            $PetokD = $input['PetokD'];
            $ImgSHM = $input['ImgSHM'];
            $ImgHGB = $input['ImgHGB'];
            $ImgHSHP = $input['ImgHSHP'];
            $ImgPPJB = $input['ImgPPJB'];
            $ImgStratatitle = $input['ImgStratatitle'];
            $ImgAJB = $input['ImgAJB'];
            $ImgPetokD = $input['ImgPetokD'];
            $ImgPjp = $input['ImgPjp'];
            $ImgPjp1 = $input['ImgPjp1'];
            $JenisProperti = $input['JenisProperti'];	
            $SumberAir = $input['SumberAir'];	
            $Kondisi = $input['Kondisi'];
            $RuangTamu = $input['RuangTamu'];
            $RuangMakan = $input['RuangMakan'];
            $Dapur = $input['Dapur'];
            $Jemuran = $input['Jemuran'];
            $Masjid = $input['Masjid'];
            $Taman = $input['Taman'];
            $Playground = $input['Playground'];
            $Cctv = $input['Cctv'];
            $OneGateSystem = $input['OneGateSystem'];
            $KolamRenang = $input['KolamRenang'];
            $SportSpace = $input['SportSpace'];
            $ParkingSpot = $input['ParkingSpot'];
            $Deskripsi = $input['Deskripsi'];
            $MetaDeskripsi = $input['MetaDeskripsi'];	
            $Prabot = $input['Prabot'];	
            $KetPrabot = $input['KetPrabot'];	
            $Priority = $input['Priority'];
            $Banner = $input['Banner'];
            $Size = $input['Size'];
            $TipeHarga = $input['TipeHarga'];
            $Harga = $input['Harga'];	
            $HargaSewa = $input['HargaSewa'];
            $RangeHarga = $input['RangeHarga'];	
            $Img1 = $input['Img1'];
            $Img2 = $input['Img2'];
            $Img3 = $input['Img3'];
            $Img4 = $input['Img4'];
            $Img5 = $input['Img5'];
            $Img6 = $input['Img6'];
            $Img7 = $input['Img7'];
            $Img8 = $input['Img8'];
            $Img9 = $input['Img9'];
            $Img10 = $input['Img10'];
            $Img11 = $input['Img11'];
            $Img12 = $input['Img12'];
            $Video = $input['Video'];	
            $LinkFacebook = $input['LinkFacebook'];	
            $LinkTiktok = $input['LinkTiktok'];	
            $LinkInstagram = $input['LinkInstagram'];	
            $LinkYoutube = $input['LinkYoutube'];	
            $Fee = $input['Fee'];
            $Marketable = $input['IsMarketable'];
            $StatusHarga = $input['IsHarga'];
            $IsSelfie = $input['IsSelfie'];
            $IsLokasi = $input['IsLokasi'];
            $Selfie = $input['Selfie'];
            $NoKtp = $input['NoKtp'];
            $ImgKtp = $input['ImgKtp'];
            $Area = $input['Area'];
            $InUse = $input['InUse'];
            
            $AksesJalanAgen = $input['AksesJalanAgen'];
            $KondisiAgen = $input['KondisiAgen'];
            $AreaSekitarAgen = $input['AreaSekitarAgen'];
            $HargaAgen = $input['HargaAgen'];
            
            if($inputDate == 0){
                $makeDate = $currentDate;
            } else{
                $makeDate = $inputDate;
            }
            
            $this->db->trans_start();
            
            $vendor = array(
				'NamaLengkap' => $NamaLengkap,
                'NoTelp' => $NoTelp,
                'Alamat' => $AlamatVendor,
				'TglLahir' => $TglLahir,
				'Nik' => $Nik,
				'NoRekening' => $NoRekening,
				'Bank' => $Bank,
				'AtasNama' => $AtasNama,
			);
			$this->db->insert('vendor',$vendor);
			$idvendor = $this->db->insert_id();
			
            if($idvendor) {
                $data = [
                    'IdAgen' => $IdAgen,
                    'IdAgenCo' => $IdAgenCo,
                    'IdInput' => $IdInput,
                    'IdVendor' => $idvendor,
                    'NamaListing'=> $NamaListing,
                    'MetaNamaListing'=> $MetaNamaListing,	
                    'Alamat'=> $Alamat,	
                    'AlamatTemplate'=> $AlamatTemplate,
                    'Latitude'=> $Latitude,
                    'Longitude'=> $Longitude,
                    'Location'=> $Location,
                    'Wilayah' => $Wilayah,
                    'Daerah' => $Daerah,
                    'Provinsi' => $Provinsi,
                    'Wide'=> $Wide,	
                    'Land'=> $Land,	
                    'Dimensi'=> $Dimensi,	
                    'Listrik'=> $Listrik,	
                    'Level'=> $Level,	
                    'Bed'=> $Bed,
                    'Bath'=> $Bath,	
                    'BedArt'=> $BedArt,	
                    'BathArt'=> $BathArt,	
                    'Garage'=> $Garage,	
                    'Carpot'=> $Carpot,
                    'Hadap'=> $Hadap,
                    'SHM'=> $SHM,
                    'HGB'=> $HGB,
                    'HSHP'=> $HSHP,
                    'PPJB'=> $PPJB,
                    'Stratatitle'=> $Stratatitle,
                    'AJB'=> $AJB,
                    'PetokD'=> $PetokD,
                    'ImgSHM'=> $ImgSHM,
                    'ImgHGB'=> $ImgHGB,
                    'ImgHSHP'=> $ImgHSHP,
                    'ImgPPJB'=> $ImgPPJB,
                    'ImgStratatitle'=> $ImgStratatitle,
                    'ImgAJB'=> $ImgAJB,
                    'ImgPetokD'=> $ImgPetokD,
                    'ImgPjp'=> $ImgPjp,
                    'ImgPjp1'=> $ImgPjp1,
                    'JenisProperti'=> $JenisProperti,	
                    'SumberAir'=> $SumberAir,	
                    'Kondisi'=> $Kondisi,
                    'RuangTamu'=> $RuangTamu,
                    'RuangMakan'=> $RuangMakan,
                    'Dapur'=> $Dapur,
                    'Jemuran'=> $Jemuran,
                    'Masjid'=> $Masjid,
                    'Taman'=> $Taman,
                    'Playground'=> $Playground,
                    'Cctv'=> $Cctv,
                    'OneGateSystem'=> $OneGateSystem,
                    'KolamRenang'=> $KolamRenang,
                    'SportSpace'=> $SportSpace,
                    'ParkingSpot'=> $ParkingSpot,
                    'Deskripsi'=> $Deskripsi,
                    'MetaDeskripsi'=> $MetaDeskripsi,
                    'Prabot'=> $Prabot,	
                    'KetPrabot'=> $KetPrabot,
                    'Priority'=> $Priority,
                    'Banner'=> $Banner,
                    'Size'=> $Size,
                    'TipeHarga'=> $TipeHarga,
                    'Harga'=> $Harga,
                    'HargaSewa'=> $HargaSewa,
                    'RangeHarga'=> $RangeHarga,	
                    'TglInput'=> $makeDate,
                    'Img1'=> $Img1,
                    'Img2'=> $Img2,
                    'Img3'=> $Img3,
                    'Img4'=> $Img4,
                    'Img5'=> $Img5,
                    'Img6'=> $Img6,
                    'Img7'=> $Img7,
                    'Img8'=> $Img8,
                    'Img9'=> $Img9,
                    'Img10'=> $Img10,
                    'Img11'=> $Img11,
                    'Img12'=> $Img12,
                    'Video'=> $Video,
                    'LinkFacebook'=> $LinkFacebook,	
                    'LinkTiktok'=> $LinkTiktok,	
                    'LinkInstagram'=> $LinkInstagram,	
                    'LinkYoutube'=> $LinkYoutube,
                    'Fee'=> $Fee,
                    'IsAdmin' => 0,
                    'IsManager' => 0,
                    'Marketable' => $Marketable,
                    'StatusHarga' => $StatusHarga,
                    'IsSelfie' => $IsSelfie,
                    'IsLokasi' => $IsLokasi,
                    'Selfie' => $Selfie,
                    'NoKtp' => $NoKtp,
                    'ImgKtp' => $ImgKtp,
                    'Pending' => 0,
                    'InUse' => $InUse,
                    'Area' => $Area,
                ];
                
                $this->db->insert('pralisting',$data);
                $insert_id = $this->db->insert_id();
                
                if($insert_id) {
                    $nilai = [
                        'IdPralisting' => $insert_id,
                        'AksesJalanAgen' => $AksesJalanAgen,
                        'KondisiAgen' => $KondisiAgen,
                        'AreaSekitarAgen' => $AreaSekitarAgen,
                        'HargaAgen' => $HargaAgen,
                    ];
                    $insertnilai = $this->ModelFlutter->Input_Data($nilai, 'penilaian');
                    
                    if($insertnilai) {
                        $this->db->trans_commit();
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(['status' => 'success', 'user_id' => $insertnilai]));
                    } else {
                        $this->db->trans_rollback();
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(500)
                            ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Listing Gagal']));
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Listing Gagal']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Listing Gagal']));
            }
        }
        
        public function Add_PraListing_Tampungan(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $inputDate = $input['TglInput'];
            $currentDate = date('Y-m-d');
            
            $IdShareLokasi = $input['IdShareLokasi'];
            
            $NamaLengkap = $input['NamaLengkap'];
            $NoTelp = $input['NoTelp'];
            $AlamatVendor = $input['AlamatVendor'];
            $TglLahir = $input['TglLahir'];
            $Nik = $input['Nik'];
            $NoRekening = $input['NoRekening'];
            $Bank = $input['Bank'];
            $AtasNama = $input['AtasNama'];
			
            $IdAgen = $input['IdAgen'];
            $IdAgenCo = $input['IdAgenCo'];
            $IdInput = $input['IdInput'];
            $NamaListing = $input['NamaListing'];
            $MetaNamaListing = $input['MetaNamaListing'];	
            $Alamat = $input['Alamat'];	
            $AlamatTemplate = $input['AlamatTemplate'];
            $Latitude = $input['Latitude'];
            $Longitude = $input['Longitude'];
            $Location = $input['Location'];
            $Wilayah = $input['Wilayah'];
            $Daerah = $input['Daerah'];
            $Provinsi = $input['Provinsi'];
            $Wide = $input['Wide'];	
            $Land = $input['Land'];	
            $Dimensi = $input['Dimensi'];	
            $Listrik = $input['Listrik'];	
            $Level = $input['Level'];	
            $Bed = $input['Bed'];	
            $Bath = $input['Bath'];	
            $BedArt = $input['BedArt'];	
            $BathArt = $input['BathArt'];	
            $Garage = $input['Garage'];	
            $Carpot = $input['Carpot'];
            $Hadap = $input['Hadap'];
            $SHM = $input['SHM'];
            $HGB = $input['HGB'];
            $HSHP = $input['HSHP'];
            $PPJB = $input['PPJB'];
            $Stratatitle = $input['Stratatitle'];
            $AJB = $input['AJB'];
            $PetokD = $input['PetokD'];
            $ImgSHM = $input['ImgSHM'];
            $ImgHGB = $input['ImgHGB'];
            $ImgHSHP = $input['ImgHSHP'];
            $ImgPPJB = $input['ImgPPJB'];
            $ImgStratatitle = $input['ImgStratatitle'];
            $ImgAJB = $input['ImgAJB'];
            $ImgPetokD = $input['ImgPetokD'];
            $ImgPjp = $input['ImgPjp'];
            $ImgPjp1 = $input['ImgPjp1'];
            $JenisProperti = $input['JenisProperti'];	
            $SumberAir = $input['SumberAir'];	
            $Kondisi = $input['Kondisi'];
            $RuangTamu = $input['RuangTamu'];
            $RuangMakan = $input['RuangMakan'];
            $Dapur = $input['Dapur'];
            $Jemuran = $input['Jemuran'];
            $Masjid = $input['Masjid'];
            $Taman = $input['Taman'];
            $Playground = $input['Playground'];
            $Cctv = $input['Cctv'];
            $OneGateSystem = $input['OneGateSystem'];
            $KolamRenang = $input['KolamRenang'];
            $SportSpace = $input['SportSpace'];
            $ParkingSpot = $input['ParkingSpot'];
            $Deskripsi = $input['Deskripsi'];
            $MetaDeskripsi = $input['MetaDeskripsi'];	
            $Prabot = $input['Prabot'];	
            $KetPrabot = $input['KetPrabot'];	
            $Priority = $input['Priority'];
            $Banner = $input['Banner'];
            $Size = $input['Size'];
            $TipeHarga = $input['TipeHarga'];
            $Harga = $input['Harga'];	
            $HargaSewa = $input['HargaSewa'];
            $RangeHarga = $input['RangeHarga'];	
            $Img1 = $input['Img1'];
            $Img2 = $input['Img2'];
            $Img3 = $input['Img3'];
            $Img4 = $input['Img4'];
            $Img5 = $input['Img5'];
            $Img6 = $input['Img6'];
            $Img7 = $input['Img7'];
            $Img8 = $input['Img8'];
            $Img9 = $input['Img9'];
            $Img10 = $input['Img10'];
            $Img11 = $input['Img11'];
            $Img12 = $input['Img12'];
            $Video = $input['Video'];	
            $LinkFacebook = $input['LinkFacebook'];	
            $LinkTiktok = $input['LinkTiktok'];	
            $LinkInstagram = $input['LinkInstagram'];	
            $LinkYoutube = $input['LinkYoutube'];	
            $Fee = $input['Fee'];
            $Marketable = $input['IsMarketable'];
            $StatusHarga = $input['IsHarga'];
            $IsSelfie = $input['IsSelfie'];
            $IsLokasi = $input['IsLokasi'];
            $Selfie = $input['Selfie'];
            $NoKtp = $input['NoKtp'];
            $ImgKtp = $input['ImgKtp'];
            $Area = $input['Area'];
            $InUse = $input['InUse'];
            
            $AksesJalanAgen = $input['AksesJalanAgen'];
            $KondisiAgen = $input['KondisiAgen'];
            $AreaSekitarAgen = $input['AreaSekitarAgen'];
            $HargaAgen = $input['HargaAgen'];
            
            if($inputDate == 0){
                $makeDate = $currentDate;
            } else{
                $makeDate = $inputDate;
            }
            
            $this->db->trans_start();
            
            $vendor = array(
				'NamaLengkap' => $NamaLengkap,
                'NoTelp' => $NoTelp,
                'Alamat' => $AlamatVendor,
				'TglLahir' => $TglLahir,
				'Nik' => $Nik,
				'NoRekening' => $NoRekening,
				'Bank' => $Bank,
				'AtasNama' => $AtasNama,
			);
			$this->db->insert('vendor',$vendor);
			$idvendor = $this->db->insert_id();
			
            if($idvendor) {
                $data = [
                    'IdAgen' => $IdAgen,
                    'IdAgenCo' => $IdAgenCo,
                    'IdInput' => $IdInput,
                    'IdVendor' => $idvendor,
                    'NamaListing'=> $NamaListing,
                    'MetaNamaListing'=> $MetaNamaListing,	
                    'Alamat'=> $Alamat,	
                    'AlamatTemplate'=> $AlamatTemplate,
                    'Latitude'=> $Latitude,
                    'Longitude'=> $Longitude,
                    'Location'=> $Location,
                    'Wilayah' => $Wilayah,
                    'Daerah' => $Daerah,
                    'Provinsi' => $Provinsi,
                    'Wide'=> $Wide,	
                    'Land'=> $Land,	
                    'Dimensi'=> $Dimensi,	
                    'Listrik'=> $Listrik,	
                    'Level'=> $Level,	
                    'Bed'=> $Bed,
                    'Bath'=> $Bath,	
                    'BedArt'=> $BedArt,	
                    'BathArt'=> $BathArt,	
                    'Garage'=> $Garage,	
                    'Carpot'=> $Carpot,
                    'Hadap'=> $Hadap,
                    'SHM'=> $SHM,
                    'HGB'=> $HGB,
                    'HSHP'=> $HSHP,
                    'PPJB'=> $PPJB,
                    'Stratatitle'=> $Stratatitle,
                    'AJB'=> $AJB,
                    'PetokD'=> $PetokD,
                    'ImgSHM'=> $ImgSHM,
                    'ImgHGB'=> $ImgHGB,
                    'ImgHSHP'=> $ImgHSHP,
                    'ImgPPJB'=> $ImgPPJB,
                    'ImgStratatitle'=> $ImgStratatitle,
                    'ImgAJB'=> $ImgAJB,
                    'ImgPetokD'=> $ImgPetokD,
                    'ImgPjp'=> $ImgPjp,
                    'ImgPjp1'=> $ImgPjp1,
                    'JenisProperti'=> $JenisProperti,	
                    'SumberAir'=> $SumberAir,	
                    'Kondisi'=> $Kondisi,
                    'RuangTamu'=> $RuangTamu,
                    'RuangMakan'=> $RuangMakan,
                    'Dapur'=> $Dapur,
                    'Jemuran'=> $Jemuran,
                    'Masjid'=> $Masjid,
                    'Taman'=> $Taman,
                    'Playground'=> $Playground,
                    'Cctv'=> $Cctv,
                    'OneGateSystem'=> $OneGateSystem,
                    'KolamRenang'=> $KolamRenang,
                    'SportSpace'=> $SportSpace,
                    'ParkingSpot'=> $ParkingSpot,
                    'Deskripsi'=> $Deskripsi,
                    'MetaDeskripsi'=> $MetaDeskripsi,
                    'Prabot'=> $Prabot,	
                    'KetPrabot'=> $KetPrabot,
                    'Priority'=> $Priority,
                    'Banner'=> $Banner,
                    'Size'=> $Size,
                    'TipeHarga'=> $TipeHarga,
                    'Harga'=> $Harga,
                    'HargaSewa'=> $HargaSewa,
                    'RangeHarga'=> $RangeHarga,	
                    'TglInput'=> $makeDate,
                    'Img1'=> $Img1,
                    'Img2'=> $Img2,
                    'Img3'=> $Img3,
                    'Img4'=> $Img4,
                    'Img5'=> $Img5,
                    'Img6'=> $Img6,
                    'Img7'=> $Img7,
                    'Img8'=> $Img8,
                    'Img9'=> $Img9,
                    'Img10'=> $Img10,
                    'Img11'=> $Img11,
                    'Img12'=> $Img12,
                    'Video'=> $Video,
                    'LinkFacebook'=> $LinkFacebook,	
                    'LinkTiktok'=> $LinkTiktok,	
                    'LinkInstagram'=> $LinkInstagram,
                    'LinkYoutube'=> $LinkYoutube,
                    'Fee'=> $Fee,
                    'IsAdmin' => 0,
                    'IsManager' => 0,
                    'Marketable' => $Marketable,
                    'StatusHarga' => $StatusHarga,
                    'IsSelfie' => $IsSelfie,
                    'IsLokasi' => $IsLokasi,
                    'Selfie' => $Selfie,
                    'NoKtp' => $NoKtp,
                    'ImgKtp' => $ImgKtp,
                    'Pending' => 0,
                    'InUse' => $InUse,
                    'Area' => $Area,
                ];
                
                $this->db->insert('pralisting',$data);
                $insert_id = $this->db->insert_id();
                
                if($insert_id) {
                    $nilai = [
                        'IdPralisting' => $insert_id,
                        'AksesJalanAgen' => $AksesJalanAgen,
                        'KondisiAgen' => $KondisiAgen,
                        'AreaSekitarAgen' => $AreaSekitarAgen,
                        'HargaAgen' => $HargaAgen,
                    ];
                    $insertnilai = $this->ModelFlutter->Input_Data($nilai, 'penilaian');
                    
                    if($insertnilai) {
                        $tampungan = [
                            'IsListing' => 1,
                        ];
                        $where = array('IdShareLokasi'=> $IdShareLokasi,);
                        $updatetampungan = $this->ModelFlutter->Update_Data($where,$tampungan,'sharelokasi');
                        
                        if($updatetampungan) {
                            $this->db->trans_commit();
                            $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode(['status' => 'success', 'user_id' => $updatetampungan]));
                        } else {
                            $this->db->trans_rollback();
                            $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(500)
                                ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Listing Gagal']));
                        }
                    } else {
                        $this->db->trans_rollback();
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(500)
                            ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Listing Gagal']));
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Listing Gagal']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Listing Gagal']));
            }
        }
        
        public function Add_PraListing_Info(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $inputDate = $input['TglInput'];
            $currentDate = date('Y-m-d');
            
            $IdInfo = $input['IdInfo'];
            
            $NamaLengkap = $input['NamaLengkap'];
            $NoTelp = $input['NoTelp'];
            $AlamatVendor = $input['AlamatVendor'];
            $TglLahir = $input['TglLahir'];
            $Nik = $input['Nik'];
            $NoRekening = $input['NoRekening'];
            $Bank = $input['Bank'];
            $AtasNama = $input['AtasNama'];
			
            $IdAgen = $input['IdAgen'];
            $IdAgenCo = $input['IdAgenCo'];
            $IdInput = $input['IdInput'];
            $NamaListing = $input['NamaListing'];
            $MetaNamaListing = $input['MetaNamaListing'];	
            $Alamat = $input['Alamat'];	
            $AlamatTemplate = $input['AlamatTemplate'];
            $Latitude = $input['Latitude'];
            $Longitude = $input['Longitude'];
            $Location = $input['Location'];
            $Wilayah = $input['Wilayah'];
            $Daerah = $input['Daerah'];
            $Provinsi = $input['Provinsi'];
            $Wide = $input['Wide'];	
            $Land = $input['Land'];	
            $Dimensi = $input['Dimensi'];	
            $Listrik = $input['Listrik'];	
            $Level = $input['Level'];	
            $Bed = $input['Bed'];	
            $Bath = $input['Bath'];	
            $BedArt = $input['BedArt'];	
            $BathArt = $input['BathArt'];	
            $Garage = $input['Garage'];	
            $Carpot = $input['Carpot'];
            $Hadap = $input['Hadap'];
            $SHM = $input['SHM'];
            $HGB = $input['HGB'];
            $HSHP = $input['HSHP'];
            $PPJB = $input['PPJB'];
            $Stratatitle = $input['Stratatitle'];
            $AJB = $input['AJB'];
            $PetokD = $input['PetokD'];
            $ImgSHM = $input['ImgSHM'];
            $ImgHGB = $input['ImgHGB'];
            $ImgHSHP = $input['ImgHSHP'];
            $ImgPPJB = $input['ImgPPJB'];
            $ImgStratatitle = $input['ImgStratatitle'];
            $ImgAJB = $input['ImgAJB'];
            $ImgPetokD = $input['ImgPetokD'];
            $ImgPjp = $input['ImgPjp'];
            $ImgPjp1 = $input['ImgPjp1'];
            $JenisProperti = $input['JenisProperti'];	
            $SumberAir = $input['SumberAir'];	
            $Kondisi = $input['Kondisi'];
            $RuangTamu = $input['RuangTamu'];
            $RuangMakan = $input['RuangMakan'];
            $Dapur = $input['Dapur'];
            $Jemuran = $input['Jemuran'];
            $Masjid = $input['Masjid'];
            $Taman = $input['Taman'];
            $Playground = $input['Playground'];
            $Cctv = $input['Cctv'];
            $OneGateSystem = $input['OneGateSystem'];
            $KolamRenang = $input['KolamRenang'];
            $SportSpace = $input['SportSpace'];
            $ParkingSpot = $input['ParkingSpot'];
            $Deskripsi = $input['Deskripsi'];
            $MetaDeskripsi = $input['MetaDeskripsi'];	
            $Prabot = $input['Prabot'];	
            $KetPrabot = $input['KetPrabot'];	
            $Priority = $input['Priority'];
            $Banner = $input['Banner'];
            $Size = $input['Size'];
            $TipeHarga = $input['TipeHarga'];
            $Harga = $input['Harga'];	
            $HargaSewa = $input['HargaSewa'];
            $RangeHarga = $input['RangeHarga'];	
            $Img1 = $input['Img1'];
            $Img2 = $input['Img2'];
            $Img3 = $input['Img3'];
            $Img4 = $input['Img4'];
            $Img5 = $input['Img5'];
            $Img6 = $input['Img6'];
            $Img7 = $input['Img7'];
            $Img8 = $input['Img8'];
            $Img9 = $input['Img9'];
            $Img10 = $input['Img10'];
            $Img11 = $input['Img11'];
            $Img12 = $input['Img12'];
            $Video = $input['Video'];	
            $LinkFacebook = $input['LinkFacebook'];	
            $LinkTiktok = $input['LinkTiktok'];	
            $LinkInstagram = $input['LinkInstagram'];	
            $LinkYoutube = $input['LinkYoutube'];	
            $Fee = $input['Fee'];
            $Marketable = $input['IsMarketable'];
            $StatusHarga = $input['IsHarga'];
            $IsSelfie = $input['IsSelfie'];
            $IsLokasi = $input['IsLokasi'];
            $Selfie = $input['Selfie'];
            $NoKtp = $input['NoKtp'];
            $ImgKtp = $input['ImgKtp'];
            $Area = $input['Area'];
            $InUse = $input['InUse'];
            
            $AksesJalanAgen = $input['AksesJalanAgen'];
            $KondisiAgen = $input['KondisiAgen'];
            $AreaSekitarAgen = $input['AreaSekitarAgen'];
            $HargaAgen = $input['HargaAgen'];
            
            if($inputDate == 0){
                $makeDate = $currentDate;
            } else{
                $makeDate = $inputDate;
            }
            
            $this->db->trans_start();
            
            $vendor = array(
				'NamaLengkap' => $NamaLengkap,
                'NoTelp' => $NoTelp,
                'Alamat' => $AlamatVendor,
				'TglLahir' => $TglLahir,
				'Nik' => $Nik,
				'NoRekening' => $NoRekening,
				'Bank' => $Bank,
				'AtasNama' => $AtasNama,
			);
			$this->db->insert('vendor',$vendor);
			$idvendor = $this->db->insert_id();
    
            if($idvendor) {
                $data = [
                    'IdAgen' => $IdAgen,
                    'IdAgenCo' => $IdAgenCo,
                    'IdInput' => $IdInput,
                    'IdVendor' => $idvendor,
                    'NamaListing'=> $NamaListing,
                    'MetaNamaListing'=> $MetaNamaListing,	
                    'Alamat'=> $Alamat,	
                    'AlamatTemplate'=> $AlamatTemplate,
                    'Latitude'=> $Latitude,
                    'Longitude'=> $Longitude,
                    'Location'=> $Location,
                    'Wilayah' => $Wilayah,
                    'Daerah' => $Daerah,
                    'Provinsi' => $Provinsi,
                    'Wide'=> $Wide,	
                    'Land'=> $Land,	
                    'Dimensi'=> $Dimensi,	
                    'Listrik'=> $Listrik,	
                    'Level'=> $Level,	
                    'Bed'=> $Bed,
                    'Bath'=> $Bath,	
                    'BedArt'=> $BedArt,	
                    'BathArt'=> $BathArt,	
                    'Garage'=> $Garage,	
                    'Carpot'=> $Carpot,
                    'Hadap'=> $Hadap,
                    'SHM'=> $SHM,
                    'HGB'=> $HGB,
                    'HSHP'=> $HSHP,
                    'PPJB'=> $PPJB,
                    'Stratatitle'=> $Stratatitle,
                    'AJB'=> $AJB,
                    'PetokD'=> $PetokD,
                    'ImgSHM'=> $ImgSHM,
                    'ImgHGB'=> $ImgHGB,
                    'ImgHSHP'=> $ImgHSHP,
                    'ImgPPJB'=> $ImgPPJB,
                    'ImgStratatitle'=> $ImgStratatitle,
                    'ImgAJB'=> $ImgAJB,
                    'ImgPetokD'=> $ImgPetokD,
                    'ImgPjp'=> $ImgPjp,
                    'ImgPjp1'=> $ImgPjp1,
                    'JenisProperti'=> $JenisProperti,	
                    'SumberAir'=> $SumberAir,	
                    'Kondisi'=> $Kondisi,
                    'RuangTamu'=> $RuangTamu,
                    'RuangMakan'=> $RuangMakan,
                    'Dapur'=> $Dapur,
                    'Jemuran'=> $Jemuran,
                    'Masjid'=> $Masjid,
                    'Taman'=> $Taman,
                    'Playground'=> $Playground,
                    'Cctv'=> $Cctv,
                    'OneGateSystem'=> $OneGateSystem,
                    'KolamRenang'=> $KolamRenang,
                    'SportSpace'=> $SportSpace,
                    'ParkingSpot'=> $ParkingSpot,
                    'Deskripsi'=> $Deskripsi,
                    'MetaDeskripsi'=> $MetaDeskripsi,
                    'Prabot'=> $Prabot,
                    'KetPrabot'=> $KetPrabot,
                    'Priority'=> $Priority,
                    'Banner'=> $Banner,
                    'Size'=> $Size,
                    'TipeHarga'=> $TipeHarga,
                    'Harga'=> $Harga,
                    'HargaSewa'=> $HargaSewa,
                    'RangeHarga'=> $RangeHarga,	
                    'TglInput'=> $makeDate,
                    'Img1'=> $Img1,
                    'Img2'=> $Img2,
                    'Img3'=> $Img3,
                    'Img4'=> $Img4,
                    'Img5'=> $Img5,
                    'Img6'=> $Img6,
                    'Img7'=> $Img7,
                    'Img8'=> $Img8,
                    'Img9'=> $Img9,
                    'Img10'=> $Img10,
                    'Img11'=> $Img11,
                    'Img12'=> $Img12,
                    'Video'=> $Video,	
                    'LinkFacebook'=> $LinkFacebook,
                    'LinkTiktok'=> $LinkTiktok,
                    'LinkInstagram'=> $LinkInstagram,
                    'LinkYoutube'=> $LinkYoutube,
                    'Fee'=> $Fee,
                    'IsAdmin' => 0,
                    'IsManager' => 0,
                    'Marketable' => $Marketable,
                    'StatusHarga' => $StatusHarga,
                    'IsSelfie' => $IsSelfie,
                    'IsLokasi' => $IsLokasi,
                    'Selfie' => $Selfie,
                    'NoKtp' => $NoKtp,
                    'ImgKtp' => $ImgKtp,
                    'Pending' => 0,
                    'InUse' => $InUse,
                    'Area' => $Area,
                ];
                
                $this->db->insert('pralisting',$data);
                $insert_id = $this->db->insert_id();
                
                if($insert_id) {
                    $nilai = [
                        'IdPralisting' => $insert_id,
                        'AksesJalanAgen' => $AksesJalanAgen,
                        'KondisiAgen' => $KondisiAgen,
                        'AreaSekitarAgen' => $AreaSekitarAgen,
                        'HargaAgen' => $HargaAgen,
                    ];
                    $insertnilai = $this->ModelFlutter->Input_Data($nilai, 'penilaian');
                    
                    if($insertnilai) {
                        $tampungan = [
                            'IsListing' => 1,
                        ];
                        $where = array('IdInfo'=> $IdInfo,);
                        $updatetampungan = $this->ModelFlutter->Update_Data($where,$tampungan,'infoproperty');
                        
                        if($updatetampungan) {
                            $this->db->trans_commit();
                            $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode(['status' => 'success', 'user_id' => $updatetampungan]));
                        } else {
                            $this->db->trans_rollback();
                            $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(500)
                                ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Listing Gagal']));
                        }
                    } else {
                        $this->db->trans_rollback();
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(500)
                            ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Listing Gagal']));
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Listing Gagal']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Listing Gagal']));
            }
        }
        
        public function Add_Nilai_Agen_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            $AksesJalan = $input['AksesJalanAgen'];
            $Kondisi = $input['KondisiAgen'];
            $AreaSekitar = $input['AreaSekitarAgen'];
            $Harga = $input['HargaAgen'];
            
            $this->db->trans_start();
            
            $nilai = [
                'IdPralisting' => $IdPralisting,
                'AksesJalanManager' => $AksesJalan,
                'KondisiManager' => $Kondisi,
                'AreaSekitarManager' => $AreaSekitar,
                'HargaManager' => $Harga,
            ];
            $insertnilai = $this->ModelFlutter->Input_Data($nilai, 'penilaian');
            
            if($insertnilai) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Tambah Nilai Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Nilai Gagal']));
            }
        }
        
        public function Add_Nilai_Manager_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            $IdPenilaian = $input['IdPenilaian'];
            $AksesJalan = $input['AksesJalanManager'];
            $Kondisi = $input['KondisiManager'];
            $AreaSekitar = $input['AreaSekitarManager'];
            $Harga = $input['HargaManager'];
            
            $this->db->trans_start();
            
            $nilai = [
                'AksesJalanManager' => $AksesJalan,
                'KondisiManager' => $Kondisi,
                'AreaSekitarManager' => $AreaSekitar,
                'HargaManager' => $Harga,
            ];
            $where = array('IdPenilaian'=> $IdPenilaian,);
            $insertnilai = $this->ModelFlutter->Update_Data($where,$nilai,'penilaian');
            
            if($insertnilai) {
                
                $data = [
                    'IsManager'=> 1,
                    'Marketable' => $input['Marketable'],
                    'StatusHarga' => $input['StatusHarga'],
                ];
                $where = array('IdPralisting'=> $IdPralisting,);
                $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
                
                if($insert_id) {
                    $newId = $this->ModelFlutter->Add_Listing($IdPralisting);
                    
                    if($newId) {
                        $templateId = [
                            'IdListing' => $newId,
                        ];
                        $whereId = array('IdListing' => $IdPralisting,);
                        $updateTemplate = $this->ModelFlutter->Update_Data($whereId,$templateId,'template');
                        
                        if($updateTemplate) {
                            $PenilaianId = [
                                'IdListing' => $newId,
                            ];
                            $whereId = array('IdPraListing' => $IdPralisting,);
                            $updatePenilaian = $this->ModelFlutter->Update_Data($whereId,$PenilaianId,'penilaian');
                            
                            if($updatePenilaian) {
                                $repost = [
                                    'IdListing' => $newId,
                                ];
                                
                                $whereId = array('IdPraListing' => $input['IdPraListing'],);
                                $updateRepost = $this->ModelFlutter->Update_Data($whereId,$repost,'reportvendor');
                                
                                if($updateRepost) {
                                    $this->db->trans_commit();
                                        $this->output
                                            ->set_content_type('application/json')
                                            ->set_status_header(200)
                                            ->set_output(json_encode(['status' => 'success', 'Approve Pra-Listing Berhasil']));
                                } else {
                                    $repost = [
                                        'IdPraListing' => $input['IdPraListing'],
                                        'IdListing' => $newId,
                                    ];
                                    
                                    $insert_repost = $this->ModelFlutter->Input_Data($repost, 'reportvendor');
                                    
                                    if($insert_repost) {
                                        $this->db->trans_commit();
                                        $this->output
                                            ->set_content_type('application/json')
                                            ->set_status_header(200)
                                            ->set_output(json_encode(['status' => 'success', 'Approve Pra-Listing Berhasil']));
                                    } else {
                                        $this->db->trans_rollback();
                                        $this->output
                                            ->set_content_type('application/json')
                                            ->set_status_header(500)
                                            ->set_output(json_encode(['status' => 'fail', 'message' => 'Approve Pra-Listing Gagal, Report Vendor Gagal Diupdate']));
                                    }
                                }
                            } else {
                                $this->db->trans_rollback();
                                $this->output
                                    ->set_content_type('application/json')
                                    ->set_status_header(500)
                                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Approve Pra-Listing Gagal, Penilaian Gagal Diupdate']));
                            }
                        } else {
                            $this->db->trans_rollback();
                            $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(500)
                                ->set_output(json_encode(['status' => 'fail', 'message' => 'Approve Pra-Listing Gagal, Template Gagal Diupdate']));
                        }
                    } else {
                        $this->db->trans_rollback();
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(500)
                            ->set_output(json_encode(['status' => 'fail', 'message' => 'Approve Pra-Listing Gagal, Pra-Listing Gagal di Approve']));
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Approve Pra-Listing Gagal, Status Gagal Diupdate']));
                }
                
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Nilai Gagal']));
            }
        }
        
        public function Add_Nilai_Officer_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPenilaian = $input['IdPenilaian'];
            $AksesJalan = $input['AksesJalanOfficer'];
            $Kondisi = $input['KondisiOfficer'];
            $AreaSekitar = $input['AreaSekitarOfficer'];
            $Harga = $input['HargaOfficer'];
            
            $this->db->trans_start();
            
            $nilai = [
                'AksesJalanOfficer' => $AksesJalan,
                'KondisiOfficer' => $Kondisi,
                'AreaSekitarOfficer' => $AreaSekitar,
                'HargaOfficer' => $Harga,
            ];
            $where = array('IdPenilaian'=> $IdPenilaian,);
            $insertnilai = $this->ModelFlutter->Update_Data($where,$nilai,'penilaian');
            
            if($insertnilai) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Tambah Nilai Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Nilai Gagal']));
            }
        }
        
        public function Add_Template_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdListing = $input['IdPraListing'];
            $Template = $input['Template'];
            $TemplateBlank = $input['TemplateBlank'];
            
            $this->db->trans_start();
            
            $template = [
                'IdListing' => $IdListing,
                'Template' => $Template,
                'TemplateBlank' => $TemplateBlank,
            ];
			$this->db->insert('template',$template);
			$idtemplate = $this->db->insert_id();
            
            if($idtemplate) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Tambah Template Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Template Gagal']));
            }
        }
        
        public function Add_No_Arsip_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            $NoArsip = $input['NoArsip'];
            
            $this->db->trans_start();
            
            $data = [
                'NoArsip'=> $NoArsip,
            ];
            $where = array('IdPralisting'=> $IdPralisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Tambah No Arsip Pra-Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah No Arsip Pra-Listing Gagal']));
            }
        }
        
        public function Add_No_Pjp_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            $NoPjp = $input['NoPjp'];
            
            $this->db->trans_start();
            
            $data = [
                'Pjp'=> $NoPjp,
            ];
            $where = array('IdPralisting'=> $IdPralisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Tambah No PJP Pra-Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah No PJP Pra-Listing Gagal']));
            }
        }
        
        public function Add_Daerah_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            
            $this->db->trans_start();
            
            $data = [
                'Provinsi' => $input['Provinsi'],
                'Wilayah' => $input['Wilayah'],
                'Daerah' => $input['Daerah'],
                'Area' => $input['Area'],
            ];
            $where = array('IdPralisting'=> $IdPralisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Tambah Daerah Pra-Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Daerah Pra-Listing Gagal']));
            }
        }
        
        public function Add_Rumah123_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            
            $this->db->trans_start();
            
            $data = [
                'Akun1' => $input['Akun1'],
                'Akun2' => $input['Akun2'],
            ];
            $where = array('IdPralisting'=> $IdPralisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
            
            if($insert_id) {
                
                $repost = [
                    'IdPraListing' => $input['IdPraListing'],
                    'Repost' => $input['Repost'],
                ];
                
                $insert_repost = $this->ModelFlutter->Input_Data($repost, 'reportvendor');
                
                if($insert_repost) {
                    $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Tambah Link Rumah123 Pra-Listing Berhasil']));
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Catatan Vendor Pra-Listing Gagal']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Link Rumah123 Pra-Listing Gagal']));
            }
        }
        
        public function Add_Single_Open_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $sql = "UPDATE pralisting SET IsSingleOpen = IsSingleOpen + 1 WHERE IdPralisting = ?";
            $this->db->query($sql, array($input['IdPraListing']));
            
            if ($this->db->affected_rows() > 0) {
                $this->output
                    ->set_status_header(200)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'success', 'message' => 'Tambah PraListing Double Single Berhasil']));
            } else {
                $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Tambah PraListing Double Single']));
            }
        }
        
        // Approve -------------------------------------------------------------
        
        public function Approve_Admin_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            
            $this->db->trans_start();
            
            $data = [
                'IsAdmin'=> 1,
            ];
            $where = array('IdPralisting'=> $IdPralisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Approve Pra-Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Pra-Listing Gagal, Tidak Ada Data Yang Diupdate']));
            }
        }
        
        // Reject --------------------------------------------------------------
        
        public function Reject_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            
            $this->db->trans_start();
            
            $data = [
                'IsRejected'=> 1,
            ];
            $where = array('IdPralisting'=> $IdPralisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Pra-Listing Rejected']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Reject Pra-Listing Gagal']));
            }
        }
        
        // Ajukan Ulang --------------------------------------------------------
        
        public function Ajukan_Ulang_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            
            $this->db->trans_start();
            
            $data = [
                'IsRejected'=> 0,
            ];
            $where = array('IdPralisting'=> $IdPralisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Pra-Listing di Ajukan Ulang']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Ajukan Ulang Pra-Listing Gagal']));
            }
        }
        
        // Delete --------------------------------------------------------------
        
        public function Delete_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            
            $this->db->trans_start();
            
            $data = [
                'IsDelete'=> 1,
            ];
            $where = array('IdPralisting'=> $IdPralisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Pra-Listing Deleted']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Delete Pra-Listing Gagal']));
            }
        }
        
        // Update --------------------------------------------------------------
        
        public function Update_Spec_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            $NamaListing = $input['NamaListing'];
            $MetaNamaListing = $input['MetaNamaListing'];	
            $Alamat = $input['Alamat'];	
            $AlamatTemplate = $input['AlamatTemplate'];
            $Wilayah = $input['Wilayah'];
            $Daerah = $input['Daerah'];
            $Provinsi = $input['Provinsi'];
            $Wide = $input['Wide'];	
            $Land = $input['Land'];	
            $Dimensi = $input['Dimensi'];	
            $Listrik = $input['Listrik'];	
            $Level = $input['Level'];	
            $Bed = $input['Bed'];	
            $Bath = $input['Bath'];	
            $BedArt = $input['BedArt'];	
            $BathArt = $input['BathArt'];	
            $Garage = $input['Garage'];	
            $Carpot = $input['Carpot'];
            $Hadap = $input['Hadap'];
            $SHM = $input['SHM'];
            $HGB = $input['HGB'];
            $HSHP = $input['HSHP'];
            $PPJB = $input['PPJB'];
            $Stratatitle = $input['Stratatitle'];
            $AJB = $input['AJB'];
            $PetokD = $input['PetokD'];
            $ImgSHM = $input['ImgSHM'];
            $ImgHGB = $input['ImgHGB'];
            $ImgHSHP = $input['ImgHSHP'];
            $ImgPPJB = $input['ImgPPJB'];
            $ImgStratatitle = $input['ImgStratatitle'];
            $ImgAJB = $input['ImgAJB'];
            $ImgPetokD = $input['ImgPetokD'];
            $JenisProperti = $input['JenisProperti'];	
            $SumberAir = $input['SumberAir'];	
            $Kondisi = $input['Kondisi'];
            $RuangTamu = $input['RuangTamu'];
            $RuangMakan = $input['RuangMakan'];
            $Dapur = $input['Dapur'];
            $Jemuran = $input['Jemuran'];
            $Masjid = $input['Masjid'];
            $Taman = $input['Taman'];
            $Playground = $input['Playground'];
            $Cctv = $input['Cctv'];
            $OneGateSystem = $input['OneGateSystem'];
            $KolamRenang = $input['KolamRenang'];
            $SportSpace = $input['SportSpace'];
            $ParkingSpot = $input['ParkingSpot'];
            $Deskripsi = $input['Deskripsi'];
            $MetaDeskripsi = $input['MetaDeskripsi'];	
            $Prabot = $input['Prabot'];	
            $KetPrabot = $input['KetPrabot'];	
            $Priority = $input['Priority'];
            $Banner = $input['Banner'];
            $Size = $input['Size'];
            $TipeHarga = $input['TipeHarga'];
            $Harga = $input['Harga'];	
            $HargaSewa = $input['HargaSewa'];
            $RangeHarga = $input['RangeHarga'];	
            $Fee = $input['Fee'];
            
            $this->db->trans_start();
            
            $data = [
                'NamaListing'=> $NamaListing,
                'MetaNamaListing'=> $MetaNamaListing,	
                'Alamat'=> $Alamat,	
                'AlamatTemplate'=> $AlamatTemplate,
                'Wilayah' => $Wilayah,
                'Daerah' => $Daerah,
                'Provinsi' => $Provinsi,
                'Wide'=> $Wide,	
                'Land'=> $Land,	
                'Dimensi'=> $Dimensi,	
                'Listrik'=> $Listrik,	
                'Level'=> $Level,	
                'Bed'=> $Bed,
                'Bath'=> $Bath,	
                'BedArt'=> $BedArt,	
                'BathArt'=> $BathArt,	
                'Garage'=> $Garage,	
                'Carpot'=> $Carpot,
                'Hadap'=> $Hadap,
                'SHM'=> $SHM,
                'HGB'=> $HGB,
                'HSHP'=> $HSHP,
                'PPJB'=> $PPJB,
                'Stratatitle'=> $Stratatitle,
                'AJB'=> $AJB,
                'PetokD'=> $PetokD,
                'ImgSHM'=> $ImgSHM,
                'ImgHGB'=> $ImgHGB,
                'ImgHSHP'=> $ImgHSHP,
                'ImgPPJB'=> $ImgPPJB,
                'ImgStratatitle'=> $ImgStratatitle,
                'ImgAJB'=> $ImgAJB,
                'ImgPetokD'=> $ImgPetokD,
                'JenisProperti'=> $JenisProperti,	
                'SumberAir'=> $SumberAir,	
                'Kondisi'=> $Kondisi,
                'RuangTamu'=> $RuangTamu,
                'RuangMakan'=> $RuangMakan,
                'Dapur'=> $Dapur,
                'Jemuran'=> $Jemuran,
                'Masjid'=> $Masjid,
                'Taman'=> $Taman,
                'Playground'=> $Playground,
                'Cctv'=> $Cctv,
                'OneGateSystem'=> $OneGateSystem,
                'KolamRenang'=> $KolamRenang,
                'SportSpace'=> $SportSpace,
                'ParkingSpot'=> $ParkingSpot,
                'Deskripsi'=> $Deskripsi,
                'MetaDeskripsi'=> $MetaDeskripsi,	
                'Prabot'=> $Prabot,	
                'KetPrabot'=> $KetPrabot,	
                'Priority'=> $Priority,
                'Banner'=> $Banner,
                'Size'=> $Size,
                'TipeHarga'=> $TipeHarga,
                'Harga'=> $Harga,	
                'HargaSewa'=> $HargaSewa,
                'RangeHarga'=> $RangeHarga,	
                'Fee'=> $Fee,
                'Area' => $input['Area'],
            ];
            $where = array('IdPralisting'=> $IdPralisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Update Pra-Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Pra-Listing Gagal, Tidak Ada Data Yang Diupdate']));
            }
        }
        
        public function Update_Lokasi_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            $Latitude = $input['Latitude'];
            $Longitude = $input['Longitude'];
            $Location = $input['Location'];
            $IsSelfie = $input['IsSelfie'];
            $IsLokasi = $input['IsLokasi'];
            $Selfie = $input['Selfie'];
            
            $this->db->trans_start();
            
            $data = [
                'Latitude'=> $Latitude,
                'Longitude'=> $Longitude,
                'Location'=> $Location,
                'IsSelfie' => $IsSelfie,
                'IsLokasi' => $IsLokasi,
                'Selfie' => $Selfie,
            ];
            $where = array('IdPralisting'=> $IdPralisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Update Pra-Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Pra-Listing Gagal, Tidak Ada Data Yang Diupdate']));
            }
        }
        
        public function Update_Selfie_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            $IsSelfie = $input['IsSelfie'];
            $Selfie = $input['Selfie'];
            
            $this->db->trans_start();
            
            $data = [
                'IsSelfie' => $IsSelfie,
                'Selfie' => $Selfie,
            ];
            $where = array('IdPralisting'=> $IdPralisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Update Pra-Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Pra-Listing Gagal, Tidak Ada Data Yang Diupdate']));
            }
        }
        
        public function Update_CoList_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            $IdAgenCo = $input['IdAgenCo'];
            
            $this->db->trans_start();
            
            $data = [
                'IdAgenCo' => $IdAgenCo,
            ];
            $where = array('IdPralisting'=> $IdPralisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Update Pra-Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Pra-Listing Gagal, Tidak Ada Data Yang Diupdate']));
            }
        }
        
        public function Update_Pjp_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            $ImgPjp = $input['ImgPjp'];
            $ImgPjp1 = $input['ImgPjp1'];
            
            $this->db->trans_start();
            
            $data = [
                'ImgPjp'=> $ImgPjp,
                'ImgPjp1'=> $ImgPjp1,
            ];
            $where = array('IdPralisting'=> $IdPralisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Update Pra-Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Pra-Listing Gagal, Tidak Ada Data Yang Diupdate']));
            }
        }
        
        public function Update_Gambar_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            $Img1 = $input['Img1'];
            $Img2 = $input['Img2'];
            $Img3 = $input['Img3'];
            $Img4 = $input['Img4'];
            $Img5 = $input['Img5'];
            $Img6 = $input['Img6'];
            $Img7 = $input['Img7'];
            $Img8 = $input['Img8'];
            $Img9 = $input['Img9'];
            $Img10 = $input['Img10'];
            $Img11 = $input['Img11'];
            $Img12 = $input['Img12'];
            
            $this->db->trans_start();
            
            $data = [
				'Img1'=> $Img1,
                'Img2'=> $Img2,
                'Img3'=> $Img3,
                'Img4'=> $Img4,
                'Img5'=> $Img5,
                'Img6'=> $Img6,
                'Img7'=> $Img7,
                'Img8'=> $Img8,
                'Img9'=> $Img9,
                'Img10'=> $Img10,
                'Img11'=> $Img11,
                'Img12'=> $Img12,
            ];
            $where = array('IdPralisting'=> $IdPralisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Update Pra-Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Pra-Listing Gagal, Tidak Ada Data Yang Diupdate']));
            }
        }
        
        public function Update_Data_Vendor(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdVendor = $input['IdVendor'];
            $NamaVendor = $input['NamaVendor'];
            $TelpVendor = $input['TelpVendor'];
            
            $this->db->trans_start();
            
            $data = [
                'NamaLengkap'=> $NamaVendor,
                'NoTelp'=> $TelpVendor,
            ];
            $where = array('IdVendor'=> $IdVendor,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'vendor');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Update Vendor Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Vendor Gagal, Tidak Ada Data Yang Diupdate']));
            }
        }
        
        public function Update_Rumah123_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdPralisting = $input['IdPraListing'];
            
            $this->db->trans_start();
            
            $data = [
                'Akun1' => $input['Akun1'],
                'Akun2' => $input['Akun2'],
            ];
            $where = array('IdPralisting'=> $IdPralisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pralisting');
            
            if($insert_id) {
                
                $repost = [
                    'Repost' => $input['Repost'],
                ];
            
                $where = array('IdPraListing'=> $input['IdPraListing'],);
                $insert_repost = $this->ModelFlutter->Update_Data($where,$repost,'reportvendor');
                
                if($insert_repost) {
                    $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Tambah Link Rumah123 Pra-Listing Berhasil']));
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Catatan Vendor Pra-Listing Gagal']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Link Rumah123 Pra-Listing Gagal']));
            }
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_List_PraListing_Admin(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $search = $this->input->get('search');
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_PraListing_Admin($limit, $offset, $search);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_PraListing_Manager(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $search = $this->input->get('search');
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_PraListing_Manager($limit, $offset, $search);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_PraListing_Agen(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $search = $this->input->get('search');
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_PraListing_Agen($id, $limit, $offset, $search);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_PraListing_Rejected_Agen(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $search = $this->input->get('search');
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_PraListing_Rejected_Agen($id, $limit, $offset, $search);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Meta_PraListing() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Meta_PraListing($id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Image_PraListing() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Image_PraListing($id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Lampiran_PraListing() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Lampiran_PraListing($id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
    
    // Listing ========================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_View(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $sql = "UPDATE listing SET View = View + 1 WHERE IdListing = ?";
            $this->db->query($sql, array($input['IdListing']));
        }
        
        public function Add_Template_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $IdTemplate = $input['IdTemplate'];
            $Template = $input['Template'];
            $TemplateBlank = $input['TemplateBlank'];
            
            $this->db->trans_start();
            
            $template = [
                'Template' => $Template,
                'TemplateBlank' => $TemplateBlank,
            ];
            $where = array('IdTemplate'=> $IdTemplate,);
            $idtemplate = $this->ModelFlutter->Update_Data($where,$template,'template');
            
            if($idtemplate) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Tambah Template Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Template Gagal']));
            }
        }
        
        public function Add_No_Arsip_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Idlisting = $input['IdListing'];
            $NoArsip = $input['NoArsip'];
            
            $this->db->trans_start();
            
            $data = [
                'NoArsip'=> $NoArsip,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Tambah No Arsip Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah No Arsip Listing Gagal']));
            }
        }
        
        public function Add_No_Pjp_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Idlisting = $input['IdListing'];
            $NoPjp = $input['NoPjp'];
            
            $this->db->trans_start();
            
            $data = [
                'Pjp'=> $NoPjp,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Tambah No PJP Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah No PJP Listing Gagal']));
            }
        }
        
        public function Add_Daerah_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'Provinsi' => $input['Provinsi'],
                'Wilayah' => $input['Wilayah'],
                'Daerah' => $input['Daerah'],
                'Area' => $input['Area'],
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Tambah Daerah Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Daerah Listing Gagal']));
            }
        }
        
        public function Add_Rumah123_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'Akun1' => $input['Akun1'],
                'Akun2' => $input['Akun2'],
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Tambah Link Rumah123 Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Link Rumah123 Listing Gagal']));
            }
        }
        
        public function Add_Video_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'Video' => $input['Video'],
                'LinkYoutube' => $input['LinkYoutube'],
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Tambah Video Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Video Listing Gagal']));
            }
        }
        
        public function Add_Single_Open_Listing() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $sql = "UPDATE listing SET IsSingleOpen = IsSingleOpen + 1 WHERE IdListing = ?";
            $this->db->query($sql, array($input['IdListing']));
            
            if ($this->db->affected_rows() > 0) {
                $this->output
                    ->set_status_header(200)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'success', 'message' => 'Tambah Listing Double Single Berhasil']));
            } else {
                $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Tambah Listing Double Single']));
            }
        }
        
        // Update --------------------------------------------------------------
        
        public function Approve_Susulan(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'Pending' => 0,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Approve Susulan Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Approve Susulan Gagal']));
            }
        }
        
        public function Update_Sold_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'Sold' => 1,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Sold Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Sold Listing Gagal']));
            }
        }
        
        public function Update_Sold_Agen_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'SoldAgen' => 1,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Sold Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Sold Listing Gagal']));
            }
        }
        
        public function Update_Rented_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'Rented' => 1,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Rented Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Rented Listing Gagal']));
            }
        }
        
        public function Update_Rented_Agen_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'RentedAgen' => 1,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Rented Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Rented Listing Gagal']));
            }
        }
        
        public function Update_Sold_Report_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'Sold' => 1,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $reportsold = array(
                    'IdListing' => $Idlisting,
                    'Report' => $input['Report'],
                );
                $insert_reportsold = $this->db->insert('reportsold',$reportsold);
                
                if($insert_reportsold) {
                    $this->db->trans_commit();
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(['status' => 'success', 'Sold Listing Berhasil']));
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Report Sold Listing Gagal, Gagal Tambah Report']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Report Sold Listing Gagal']));
            }
        }
        
        public function Update_Rented_Report_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'Rented' => 1,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $reportsold = array(
                    'IdListing' => $Idlisting,
                    'Report' => $input['Report'],
                );
                $insert_reportsold = $this->db->insert('reportsold',$reportsold);
                
                if($insert_reportsold) {
                    $this->db->trans_commit();
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(['status' => 'success', 'Rented Listing Berhasil']));
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Report Rented Listing Gagal, Gagal Tambah Report']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Report Rented Listing Gagal']));
            }
        }
        
        public function Update_Iklan_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'Sold' => 0,
                'SoldAgen' => 0,
                'Rented' => 0,
                'RentedAgen' => 0,
                'TglInput' => date('Y-m-d')
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Update Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Listing Gagal']));
            }
        }
        
        public function Update_Marketable_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'Marketable' => 1,
                'StatusHarga' => 1,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Berhasil Update Marketable']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update Marketable']));
            }
        }
        
        public function Update_Spec_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $currentDate = date('Y-m-d H:i:s');
            
            $Idlisting = $input['IdListing'];
            $NamaListing = $input['NamaListing'];
            $MetaNamaListing = $input['MetaNamaListing'];	
            $Alamat = $input['Alamat'];	
            $AlamatTemplate = $input['AlamatTemplate'];
            $Wilayah = $input['Wilayah'];
            $Daerah = $input['Daerah'];
            $Provinsi = $input['Provinsi'];
            $Wide = $input['Wide'];	
            $Land = $input['Land'];	
            $Dimensi = $input['Dimensi'];	
            $Listrik = $input['Listrik'];	
            $Level = $input['Level'];	
            $Bed = $input['Bed'];	
            $Bath = $input['Bath'];	
            $BedArt = $input['BedArt'];	
            $BathArt = $input['BathArt'];	
            $Garage = $input['Garage'];	
            $Carpot = $input['Carpot'];
            $Hadap = $input['Hadap'];
            $SHM = $input['SHM'];
            $HGB = $input['HGB'];
            $HSHP = $input['HSHP'];
            $PPJB = $input['PPJB'];
            $Stratatitle = $input['Stratatitle'];
            $AJB = $input['AJB'];
            $PetokD = $input['PetokD'];
            $ImgSHM = $input['ImgSHM'];
            $ImgHGB = $input['ImgHGB'];
            $ImgHSHP = $input['ImgHSHP'];
            $ImgPPJB = $input['ImgPPJB'];
            $ImgStratatitle = $input['ImgStratatitle'];
            $ImgAJB = $input['ImgAJB'];
            $ImgPetokD = $input['ImgPetokD'];
            $JenisProperti = $input['JenisProperti'];	
            $SumberAir = $input['SumberAir'];	
            $Kondisi = $input['Kondisi'];
            $RuangTamu = $input['RuangTamu'];
            $RuangMakan = $input['RuangMakan'];
            $Dapur = $input['Dapur'];
            $Jemuran = $input['Jemuran'];
            $Masjid = $input['Masjid'];
            $Taman = $input['Taman'];
            $Playground = $input['Playground'];
            $Cctv = $input['Cctv'];
            $OneGateSystem = $input['OneGateSystem'];
            $KolamRenang = $input['KolamRenang'];
            $SportSpace = $input['SportSpace'];
            $ParkingSpot = $input['ParkingSpot'];
            $Deskripsi = $input['Deskripsi'];
            $MetaDeskripsi = $input['MetaDeskripsi'];	
            $Prabot = $input['Prabot'];	
            $KetPrabot = $input['KetPrabot'];	
            $Priority = $input['Priority'];
            $Banner = $input['Banner'];
            $Size = $input['Size'];
            $TipeHarga = $input['TipeHarga'];
            $Harga = $input['Harga'];	
            $HargaSewa = $input['HargaSewa'];
            $RangeHarga = $input['RangeHarga'];	
            $Fee = $input['Fee'];
            
            $this->db->trans_start();
            
            $data = [
                'NamaListing'=> $NamaListing,
                'MetaNamaListing'=> $MetaNamaListing,	
                'Alamat'=> $Alamat,	
                'AlamatTemplate'=> $AlamatTemplate,
                'Wilayah' => $Wilayah,
                'Daerah' => $Daerah,
                'Provinsi' => $Provinsi,
                'Wide'=> $Wide,	
                'Land'=> $Land,	
                'Dimensi'=> $Dimensi,	
                'Listrik'=> $Listrik,	
                'Level'=> $Level,	
                'Bed'=> $Bed,
                'Bath'=> $Bath,	
                'BedArt'=> $BedArt,	
                'BathArt'=> $BathArt,	
                'Garage'=> $Garage,	
                'Carpot'=> $Carpot,
                'Hadap'=> $Hadap,
                'SHM'=> $SHM,
                'HGB'=> $HGB,
                'HSHP'=> $HSHP,
                'PPJB'=> $PPJB,
                'Stratatitle'=> $Stratatitle,
                'AJB'=> $AJB,
                'PetokD'=> $PetokD,
                'ImgSHM'=> $ImgSHM,
                'ImgHGB'=> $ImgHGB,
                'ImgHSHP'=> $ImgHSHP,
                'ImgPPJB'=> $ImgPPJB,
                'ImgStratatitle'=> $ImgStratatitle,
                'ImgAJB'=> $ImgAJB,
                'ImgPetokD'=> $ImgPetokD,
                'JenisProperti'=> $JenisProperti,	
                'SumberAir'=> $SumberAir,	
                'Kondisi'=> $Kondisi,
                'RuangTamu'=> $RuangTamu,
                'RuangMakan'=> $RuangMakan,
                'Dapur'=> $Dapur,
                'Jemuran'=> $Jemuran,
                'Masjid'=> $Masjid,
                'Taman'=> $Taman,
                'Playground'=> $Playground,
                'Cctv'=> $Cctv,
                'OneGateSystem'=> $OneGateSystem,
                'KolamRenang'=> $KolamRenang,
                'SportSpace'=> $SportSpace,
                'ParkingSpot'=> $ParkingSpot,
                'Deskripsi'=> $Deskripsi,
                'MetaDeskripsi'=> $MetaDeskripsi,	
                'Prabot'=> $Prabot,	
                'KetPrabot'=> $KetPrabot,	
                'Priority'=> $Priority,
                'Banner'=> $Banner,
                'Size'=> $Size,
                'TipeHarga'=> $TipeHarga,
                'Harga'=> $Harga,	
                'HargaSewa'=> $HargaSewa,
                'RangeHarga'=> $RangeHarga,	
                'Fee'=> $Fee,	
                'Pending'=> 1,
                'Area' => $input['Area'],
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $susulan = array(
                    'IdListing' => $Idlisting,
                    'Keterangan' => "Update Spesifikasi",
                    'PoinTambahan' => 0,
                    'PoinBerkurang' => 0,
                    'TglInput' => $currentDate,
                    'IsRead' => 0,
                );
                $insert_susulan = $this->db->insert('susulan',$susulan);
                
                if($insert_susulan) {
                    $this->db->trans_commit();
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(['status' => 'success', 'Update Listing Berhasil']));
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Listing Gagal, Gagal Tambah Susulan']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Listing Gagal, Tidak Ada Data Yang Diupdate']));
            }
        }
        
        public function Update_Lokasi_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $currentDate = date('Y-m-d H:i:s');
            
            $Idlisting = $input['IdListing'];
            $Latitude = $input['Latitude'];
            $Longitude = $input['Longitude'];
            $Location = $input['Location'];
            $IsSelfie = $input['IsSelfie'];
            $IsLokasi = $input['IsLokasi'];
            $Selfie = $input['Selfie'];
            
            $this->db->trans_start();
            
            $data = [
                'Latitude'=> $Latitude,
                'Longitude'=> $Longitude,
                'Location'=> $Location,
                'IsSelfie' => $IsSelfie,
                'IsLokasi' => $IsLokasi,
                'Selfie' => $Selfie,
                'Pending'=> 1,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $susulan = array(
                    'IdListing' => $Idlisting,
                    'Keterangan' => "Update Lokasi dan Selfie",
                    'PoinTambahan' => 0,
                    'PoinBerkurang' => 0,
                    'TglInput' => $currentDate,
                    'IsRead' => 0,
                );
                $insert_susulan = $this->db->insert('susulan',$susulan);
                
                if($insert_susulan) {
                    $this->db->trans_commit();
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(['status' => 'success', 'Update Listing Berhasil']));
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Listing Gagal, Gagal Tambah Susulan']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Listing Gagal, Tidak Ada Data Yang Diupdate']));
            }
        }
        
        public function Update_Selfie_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $currentDate = date('Y-m-d H:i:s');
            
            $Idlisting = $input['IdListing'];
            $IsSelfie = $input['IsSelfie'];
            $Selfie = $input['Selfie'];
            
            $this->db->trans_start();
            
            $data = [
                'IsSelfie' => $IsSelfie,
                'Selfie' => $Selfie,
                'Pending' => 1,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $susulan = array(
                    'IdListing' => $Idlisting,
                    'Keterangan' => "Update Selfie",
                    'PoinTambahan' => 0,
                    'PoinBerkurang' => 0,
                    'TglInput' => $currentDate,
                    'IsRead' => 0,
                );
                $insert_susulan = $this->db->insert('susulan',$susulan);
                
                if($insert_susulan) {
                    $this->db->trans_commit();
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(['status' => 'success', 'Update Listing Berhasil']));
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Listing Gagal, Gagal Tambah Susulan']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Listing Gagal, Tidak Ada Data Yang Diupdate']));
            }
        }
        
        public function Update_CoList_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $currentDate = date('Y-m-d H:i:s');
            
            $Idlisting = $input['IdListing'];
            $IdAgenCo = $input['IdAgenCo'];
            
            $this->db->trans_start();
            
            $data = [
                'IdAgenCo' => $IdAgenCo,
                'Pending' => 1,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $susulan = array(
                    'IdListing' => $Idlisting,
                    'Keterangan' => "Update Co Listing",
                    'PoinTambahan' => 0,
                    'PoinBerkurang' => 0,
                    'TglInput' => $currentDate,
                    'IsRead' => 0,
                );
                $insert_susulan = $this->db->insert('susulan',$susulan);
                
                if($insert_susulan) {
                    $this->db->trans_commit();
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(['status' => 'success', 'Update Listing Berhasil']));
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Listing Gagal, Gagal Tambah Susulan']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Listing Gagal, Tidak Ada Data Yang Diupdate']));
            }
        }
        
        public function Update_Pjp_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $currentDate = date('Y-m-d H:i:s');
            
            $Idlisting = $input['IdListing'];
            $ImgPjp = $input['ImgPjp'];
            $ImgPjp1 = $input['ImgPjp1'];
            
            $this->db->trans_start();
            
            $data = [
                'IdListing' => $Idlisting,
                'ImgPjp1'=> $input['ImagePjp1'],
                'ImgPjp2'=> $input['ImagePjp2'],
            ];
			$this->db->insert('pjp',$data);
			$insert_id = $this->db->insert_id();
            
            if($insert_id) {
                $data = [
                    'ImgPjp'=> $ImgPjp,
                    'ImgPjp1'=> $ImgPjp1,
                    'Pending' => 1,
                ];
                $where = array('IdListing'=> $Idlisting,);
                $update_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
                
                if($update_id) {
                    $susulan = array(
                        'IdListing' => $Idlisting,
                        'Keterangan' => "Update PJP",
                        'PoinTambahan' => 0,
                        'PoinBerkurang' => 0,
                        'TglInput' => $currentDate,
                        'IsRead' => 0,
                    );
                    $insert_susulan = $this->db->insert('susulan',$susulan);
                    
                    if($insert_susulan) {
                        $this->db->trans_commit();
                            $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode(['status' => 'success', 'Update Listing Berhasil']));
                    } else {
                        $this->db->trans_rollback();
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(500)
                            ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Listing Gagal, Gagal Tambah Susulan']));
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Listing Gagal, Tidak Ada Data Yang Diupdate']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Simpan Backup PJP Gagal']));
            }
        }
        
        public function Update_Gambar_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $currentDate = date('Y-m-d H:i:s');
            
            $Idlisting = $input['IdListing'];
            $Img1 = $input['Img1'];
            $Img2 = $input['Img2'];
            $Img3 = $input['Img3'];
            $Img4 = $input['Img4'];
            $Img5 = $input['Img5'];
            $Img6 = $input['Img6'];
            $Img7 = $input['Img7'];
            $Img8 = $input['Img8'];
            $Img9 = $input['Img9'];
            $Img10 = $input['Img10'];
            $Img11 = $input['Img11'];
            $Img12 = $input['Img12'];
            
            $this->db->trans_start();
            
            $data = [
				'Img1'=> $Img1,
                'Img2'=> $Img2,
                'Img3'=> $Img3,
                'Img4'=> $Img4,
                'Img5'=> $Img5,
                'Img6'=> $Img6,
                'Img7'=> $Img7,
                'Img8'=> $Img8,
                'Img9'=> $Img9,
                'Img10'=> $Img10,
                'Img11'=> $Img11,
                'Img12'=> $Img12,
                'Pending' => 1,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $susulan = array(
                    'IdListing' => $Idlisting,
                    'Keterangan' => "Update Gambar",
                    'PoinTambahan' => 0,
                    'PoinBerkurang' => 0,
                    'TglInput' => $currentDate,
                    'IsRead' => 0,
                );
                $insert_susulan = $this->db->insert('susulan',$susulan);
                
                if($insert_susulan) {
                    $this->db->trans_commit();
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(['status' => 'success', 'Update Listing Berhasil']));
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Listing Gagal, Gagal Tambah Susulan']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Listing Gagal, Tidak Ada Data Yang Diupdate']));
            }
        }
        
        public function Update_Agen_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $currentDate = date('Y-m-d H:i:s');
            
            $Idlisting = $input['IdListing'];
            $IdAgen = $input['IdAgen'];
            
            $this->db->trans_start();
            
            $data = [
                'IdAgen' => $IdAgen,
                'Pending' => 1,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $susulan = array(
                    'IdListing' => $Idlisting,
                    'Keterangan' => "Update Agen Listing",
                    'PoinTambahan' => 0,
                    'PoinBerkurang' => 0,
                    'TglInput' => $currentDate,
                    'IsRead' => 0,
                );
                $insert_susulan = $this->db->insert('susulan',$susulan);
                
                if($insert_susulan) {
                    $this->db->trans_commit();
                        $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(['status' => 'success', 'Update Listing Berhasil']));
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Listing Gagal, Gagal Tambah Susulan']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Listing Gagal, Tidak Ada Data Yang Diupdate']));
            }
        }
        
        // Delete --------------------------------------------------------------
        
        public function Delete_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'IsDelete' => 1,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Listing Dihapus']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Listing Gagal Dihapus']));
            }
        }
        
        public function Double_Template_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = [
                'IdListing' => 0,
            ];
            $where = array('IdTemplate'=> $input['IdTemplate'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'template');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Template Dihapus']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Template Gagal Dihapus']));
            }
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_List_Listing_Agen() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            $data = $this->ModelFlutter->Get_List_Listing_Agen($limit, $offset, $id, $search);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Listing_Terbaru_Pagination() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_terbaru_Pagination($limit, $offset);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Listing_Terbaru_Jual() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_terbaru_Jual($limit, $offset);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Listing_Terbaru_Sewa() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_terbaru_Sewa($limit, $offset);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Listing_Terbaru_JualSewa() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_terbaru_JualSewa($limit, $offset);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Listing_Exclusive() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Listing_Exclusive($limit, $offset);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Listing_Sold() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Listing_Sold($limit, $offset);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Listing_Pencarian() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $search = $this->input->get('search');
            $priority = $this->input->get('priority');
            $sold = $this->input->get('sold');
            $rented = $this->input->get('rented');
            $soldagen = $this->input->get('soldagen');
            $rentedagen = $this->input->get('rentedagen');
            $status = $this->input->get('status');
            $jenis = $this->input->get('jenis');
            $kota = $this->input->get('kota');
            $wilayah = $this->input->get('wilayah');
            $prabot = $this->input->get('prabot');
            $bed = $this->input->get('bed');
            $bath = $this->input->get('bath');
            $hargaMin = $this->input->get('hargaMin');
            $hargaMax = $this->input->get('hargaMax');
            $landMin = $this->input->get('landMin');
            $landMax = $this->input->get('landMax');
            $wideMin = $this->input->get('wideMin');
            $wideMax = $this->input->get('wideMax');
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            
            $data = $this->ModelFlutter->Get_List_Listing_Pencarian($limit, $offset, $search, $priority, $sold, $rented, $soldagen, $rentedagen, $status, $jenis, $kota, $wilayah, $prabot, $bed, $bath, $hargaMin, $hargaMax, $landMin, $landMax, $wideMin, $wideMax);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Listing_Sold_Pencarian() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $search = $this->input->get('search');
            $priority = $this->input->get('priority');
            $sold = $this->input->get('sold');
            $rented = $this->input->get('rented');
            $soldagen = $this->input->get('soldagen');
            $rentedagen = $this->input->get('rentedagen');
            $status = $this->input->get('status');
            $jenis = $this->input->get('jenis');
            $kota = $this->input->get('kota');
            $wilayah = $this->input->get('wilayah');
            $prabot = $this->input->get('prabot');
            $bed = $this->input->get('bed');
            $bath = $this->input->get('bath');
            $hargaMin = $this->input->get('hargaMin');
            $hargaMax = $this->input->get('hargaMax');
            $landMin = $this->input->get('landMin');
            $landMax = $this->input->get('landMax');
            $wideMin = $this->input->get('wideMin');
            $wideMax = $this->input->get('wideMax');
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            
            $data = $this->ModelFlutter->Get_List_Listing_Sold_Pencarian($limit, $offset, $search, $priority, $sold, $rented, $soldagen, $rentedagen, $status, $jenis, $kota, $wilayah, $prabot, $bed, $bath, $hargaMin, $hargaMax, $landMin, $landMax, $wideMin, $wideMax);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Listing_Pending() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Listing_Pending($limit, $offset);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Listing_Selection() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $search = $this->input->get('search');
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            
            $data = $this->ModelFlutter->Get_List_Listing_Selection($limit, $offset, $search);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Susulan() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Susulan($id, $limit, $offset);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Agen_Listing() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Agen_Listing($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Meta_Listing() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Meta_Listing($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Image_Listing() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Image_Listing($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Lampiran_Listing() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Lampiran_Listing($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Cek_Double_Template_Listing(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Cek_Double_Template_Listing($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Double_Template_Listing(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Double_Template_Listing($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
    // Info ===========================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Info(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
            
            $data = [
                'IdAgen' => $input['IdAgen'],
                'JenisProperty' => $input['JenisProperty'],
                'StatusProperty' => $input['StatusProperty'],
                'StatusNarahubung' => $input['StatusNarahubung'],
                'Narahubung' => $input['Narahubung'],
                'NoTelp' => $input['NoTelp'],
                'LTanah' => $input['LuasTanah'],
                'LBangunan' => $input['LuasBangunan'],
                'Keterangan' => $input['Keterangan'],
                'Harga' => $input['HargaJual'],
                'HargaSewa' => $input['HargaSewa'],
                'Lokasi' => $input['Lokasi'],
                'Alamat' => $input['Alamat'],
                'Latitude' => $input['Latitude'],
                'Longitude' => $input['Longitude'],
                'ImgSelfie' => $input['ImgSelfie'],
                'ImgProperty' => $input['ImgProperty'],
                'IsSpek' => $input['IsSpek'],
                'TglInput' => $currentDate,
				'JamInput' => $currentTime,
				'IsListing' => 0,
            ];
            
            $insert_id = $this->ModelFlutter->Input_Data($data, 'infoproperty');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Info Gagal']));
            }
        }
        
        public function Add_Info_Tampungan(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
            
            $IdShareLokasi = $input['IdShareLokasi'];
            
            $this->db->trans_start();
            
            $data = array(
				'IdAgen' => $input['IdAgen'],
                'JenisProperty' => $input['JenisProperty'],
                'StatusProperty' => $input['StatusProperty'],
                'StatusNarahubung' => $input['StatusNarahubung'],
                'Narahubung' => $input['Narahubung'],
                'NoTelp' => $input['NoTelp'],
                'LTanah' => $input['LuasTanah'],
                'LBangunan' => $input['LuasBangunan'],
                'Keterangan' => $input['Keterangan'],
                'Harga' => $input['HargaJual'],
                'HargaSewa' => $input['HargaSewa'],
                'Lokasi' => $input['Lokasi'],
                'Alamat' => $input['Alamat'],
                'Latitude' => $input['Latitude'],
                'Longitude' => $input['Longitude'],
                'ImgSelfie' => $input['ImgSelfie'],
                'ImgProperty' => $input['ImgProperty'],
                'IsSpek' => $input['IsSpek'],
                'TglInput' => $currentDate,
				'JamInput' => $currentTime,
				'IsListing' => 0,
			);
            
            $insert_id = $this->ModelFlutter->Input_Data($data, 'infoproperty');
            
            if($insert_id) {
                $tampungan = [
                    'IsListing' => 1,
                ];
                $where = array('IdShareLokasi'=> $IdShareLokasi,);
                $updatetampungan = $this->ModelFlutter->Update_Data($where,$tampungan,'sharelokasi');
                
                if($updatetampungan) {
                    $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'user_id' => $updatetampungan]));
                } else {
                    $this->db->trans_rollback();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(500)
                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Info Gagal']));
                }
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Info Gagal']));
            }
        }
        
        // Update --------------------------------------------------------------
        
        public function Update_Info(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
            
            $data = [
                'JenisProperty' => $input['JenisProperty'],
                'StatusProperty' => $input['StatusProperty'],
                'StatusNarahubung' => $input['StatusNarahubung'],
                'Narahubung' => $input['Narahubung'],
                'NoTelp' => $input['NoTelp'],
                'LTanah' => $input['LuasTanah'],
                'LBangunan' => $input['LuasBangunan'],
                'Keterangan' => $input['Keterangan'],
                'Harga' => $input['HargaJual'],
                'HargaSewa' => $input['HargaSewa'],
                'Lokasi' => $input['Lokasi'],
                'Alamat' => $input['Alamat'],
                'Latitude' => $input['Latitude'],
                'Longitude' => $input['Longitude'],
                'ImgSelfie' => $input['ImgSelfie'],
                'ImgProperty' => $input['ImgProperty'],
                'IsSpek' => $input['IsSpek'],
				'IsListing' => 0,
            ];
            
            $where = array('IdInfo'=> $input['IdInfo'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'infoproperty');
            
            if($insert_id) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Update Info Berhasil']));
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Info Gagal']));
            }
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_List_Info() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            $data = $this->ModelFlutter->Get_List_Info($limit, $offset, $search);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Info_Agen() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Info_Agen($id, $limit, $offset);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Info_Jual() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Info_Jual($limit, $offset);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Info_Sewa() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Info_Sewa($limit, $offset);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Info_JualSewa() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Info_JualSewa($limit, $offset);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Image_Info() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Image_Info($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Lampiran_Info() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Lampiran_Info($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        // Delete --------------------------------------------------------------
        
        public function Delete_Info() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            if (!isset($input['IdInfo'])) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'IdInfo tidak diberikan']));
                return;
            }
            
            $this->db->trans_start();
            
            $this->db->where('IdInfo', $input['IdInfo']);
            $deleteSuccessful = $this->db->delete('infoproperty');
            
            if ($deleteSuccessful) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'message' => 'Info berhasil dihapus']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal menghapus Info']));
            }
        }
    
    // Primary ========================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = array(
				'Nama' => $input['Nama'],
                'JenisProperty' => $input['JenisProperty'],
                'Developer' => $input['Developer'],
				'Kota' => $input['Kota'],
				'Area' => $input['Area'],
				'LuasTanah' => $input['LuasTanah'],
				'LuasBangunan' => $input['LuasBangunan'],
				'MinHarga' => $input['MinHarga'],
				'MaxHarga' => $input['MaxHarga'],
				'Deskripsi' => $input['Deskripsi'],
				'Benefit' => $input['Benefit'],
				'Payment' => $input['Payment'],
				'Brosur' => '0',
				'Siteplan' => '0',
				'Pricelist' => '0',
				'Img' => $input['Img'],
				'ImgDetail' => $input['ImgDetail'],
			);
			$this->db->insert('listingnew',$data);
			$idprimary = $this->db->insert_id();
    
            if($idprimary) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $idprimary]));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Listing Gagal']));
            }
        }
        
        public function Add_Tipe_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $currentDate = date('Y-m-d');
            
            $this->db->trans_start();
            
            $data = [
                'IdNew' => $input['IdNew'],
                'IdAgen' => 0,
                'IdAgenCo' => 0,
                'IdInput' => 0,
                'IdVendor' => 0,
                'NamaListing'=> $input['NamaListing'],
                'MetaNamaListing'=> $input['MetaNamaListing'],
                'Alamat'=> $input['Alamat'],
                'AlamatTemplate'=> $input['AlamatTemplate'],
                'Latitude'=> 0,
                'Longitude'=> 0,
                'Location'=> 0,
                'Wilayah' => $input['Wilayah'],
                'Daerah' => $input['Daerah'],
                'Wide'=> $input['Wide'],
                'Land'=> $input['Land'],
                'Dimensi'=> $input['Dimensi'],
                'Listrik'=> $input['Listrik'],
                'Level'=> $input['Level'],
                'Bed'=> $input['Bed'],
                'Bath'=> $input['Bath'],
                'BedArt'=> $input['BedArt'],
                'BathArt'=> $input['BathArt'],
                'Garage'=> $input['Garage'],
                'Carpot'=> $input['Carpot'],
                'Hadap'=> $input['Hadap'],
                'SHM'=> 0,
                'HGB'=> 0,
                'HSHP'=> 0,
                'PPJB'=> 0,
                'Stratatitle'=> 0,
                'AJB'=> 0,
                'PetokD'=> 0,
                'ImgSHM'=> 0,
                'ImgHGB'=> 0,
                'ImgHSHP'=> 0,
                'ImgPPJB'=> 0,
                'ImgStratatitle'=> 0,
                'ImgAJB'=> 0,
                'ImgPetokD'=> 0,
                'ImgPjp'=> 0,
                'ImgPjp1'=> 0,
                'JenisProperti'=> $input['JenisProperti'],
                'SumberAir'=> $input['SumberAir'],
                'Kondisi'=> 0,
                'RuangTamu'=> $input['RuangTamu'],
                'RuangMakan'=> $input['RuangMakan'],
                'Dapur'=> $input['Dapur'],
                'Jemuran'=> $input['Jemuran'],
                'Masjid'=> $input['Masjid'],
                'Taman'=> $input['Taman'],
                'Playground'=> $input['Playground'],
                'Cctv'=> $input['Cctv'],
                'OneGateSystem'=> $input['OneGateSystem'],
                'KolamRenang'=> $input['KolamRenang'],
                'SportSpace'=> $input['SportSpace'],
                'ParkingSpot'=> $input['ParkingSpot'],
                'Deskripsi'=> $input['Deskripsi'],
                'MetaDeskripsi'=> $input['MetaDeskripsi'],
				'Prabot'=> $input['Prabot'],
				'KetPrabot'=> $input['KetPrabot'],
				'Priority'=> 0,
				'Banner'=> 0,
				'Size'=> 0,
				'TipeHarga'=> 0,
				'Harga'=> $input['Harga'],
				'HargaSewa'=> 0,
				'RangeHarga'=> 0,
				'TglInput'=> $currentDate,
				'Img1'=> $input['Img1'],
                'Img2'=> $input['Img2'],
                'Img3'=> $input['Img3'],
                'Img4'=> $input['Img4'],
                'Img5'=> $input['Img5'],
                'Img6'=> $input['Img6'],
                'Img7'=> $input['Img7'],
                'Img8'=> $input['Img8'],
                'Img9'=> $input['Img9'],
                'Img10'=> $input['Img10'],
                'Img11'=> $input['Img11'],
                'Img12'=> $input['Img12'],
				'Video'=> 0,
				'LinkFacebook'=> 0,
				'LinkTiktok'=> 0,
				'LinkInstagram'=> 0,
				'LinkYoutube'=> 0,
				'Fee'=> 0,
				'IsAdmin' => 0,
				'IsManager' => 0,
				'Marketable' => 0,
				'StatusHarga' => 0,
                'IsSelfie' => 0,
                'IsLokasi' => 0,
                'Selfie' => 0,
                'NoKtp' => 0,
                'ImgKtp' => 0,
                'Pending' => 0,
            ];
            
		    $this->db->insert('tipenewlisting',$data);
		    $insert_id = $this->db->insert_id();
		    
		    if($insert_id) {
		        $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Tipe Gagal']));
            }
        }
        
        // Update --------------------------------------------------------------
        
        public function Update_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = array(
				'Nama' => $input['Nama'],
                'JenisProperty' => $input['JenisProperty'],
                'Developer' => $input['Developer'],
				'Kota' => $input['Kota'],
				'Area' => $input['Area'],
				'LuasTanah' => $input['LuasTanah'],
				'LuasBangunan' => $input['LuasBangunan'],
				'MinHarga' => $input['MinHarga'],
				'MaxHarga' => $input['MaxHarga'],
				'Deskripsi' => $input['Deskripsi'],
				'Benefit' => $input['Benefit'],
				'Payment' => $input['Payment'],
				'Img' => $input['Img'],
				'ImgDetail' => $input['ImgDetail'],
			);
            $where = array('IdNew'=> $input['IdNew'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listingnew');
    
            if($insert_id) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Update Primary Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Primary Gagal']));
            }
        }
        
        public function Update_Berkas_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = array(
				'Brosur' => $input['Brosur'],
				'Siteplan' => $input['Siteplan'],
				'Pricelist' => $input['Pricelist'],
			);
            $where = array('IdNew'=> $input['IdNew'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listingnew');
    
            if($insert_id) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Update Primary Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Primary Gagal']));
            }
        }
        
        public function Update_Rumah123_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = array(
				'Akun1' => $input['Akun1'],
				'Akun2' => $input['Akun2'],
			);
            $where = array('IdNew'=> $input['IdNew'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listingnew');
    
            if($insert_id) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Update Link Rumah 123 Primary Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Link Rumah 123 Primary Gagal']));
            }
        }
        
        public function Update_Tipe_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $currentDate = date('Y-m-d');
            
            $this->db->trans_start();
            
            $data = [
                'NamaListing'=> $input['NamaListing'],
                'Wide'=> $input['Wide'],
                'Land'=> $input['Land'],
                'Dimensi'=> $input['Dimensi'],
                'Listrik'=> $input['Listrik'],
                'Level'=> $input['Level'],
                'Bed'=> $input['Bed'],
                'Bath'=> $input['Bath'],
                'BedArt'=> $input['BedArt'],
                'BathArt'=> $input['BathArt'],
                'Garage'=> $input['Garage'],
                'Carpot'=> $input['Carpot'],
                'SumberAir'=> $input['SumberAir'],
                'Hadap'=> $input['Hadap'],
				'Prabot'=> $input['Prabot'],
                'RuangTamu'=> $input['RuangTamu'],
                'RuangMakan'=> $input['RuangMakan'],
                'Dapur'=> $input['Dapur'],
                'Jemuran'=> $input['Laundry'],
				'Harga'=> $input['Harga'],
            ];
            $where = array('IdListing'=> $input['IdListing'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'tipenewlisting');
		    
		    if($insert_id) {
		        $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Update Tipe Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Tipe Gagal']));
            }
        }
        
        public function Update_Gambar_Tipe_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = [
                
				'Img1'=> $input['Img1'],
                'Img2'=> $input['Img2'],
                'Img3'=> $input['Img3'],
                'Img4'=> $input['Img4'],
                'Img5'=> $input['Img5'],
                'Img6'=> $input['Img6'],
                'Img7'=> $input['Img7'],
                'Img8'=> $input['Img8'],
                'Img9'=> $input['Img9'],
                'Img10'=> $input['Img10'],
                'Img11'=> $input['Img11'],
                'Img12'=> $input['Img12'],
            ];
            $where = array('IdListing'=> $input['IdListing'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'tipenewlisting');
		    
		    if($insert_id) {
		        $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Update Tipe Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Tipe Gagal']));
            }
        }
        
        public function Update_Video_Tipe_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = [
                
				'Video'=> $input['Video'],
                'LinkYoutube'=> $input['LinkYoutube'],
            ];
            $where = array('IdListing'=> $input['IdListing'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'tipenewlisting');
		    
		    if($insert_id) {
		        $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Update Tipe Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Tipe Gagal']));
            }
        }
        
        public function Update_Template_Tipe_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = [
				'ImgTemplate'=> $input['ImgTemplate'],
            ];
            $where = array('IdListing'=> $input['IdListing'],);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'tipenewlisting');
		    
		    if($insert_id) {
		        $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Update Template Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Template Gagal']));
            }
        }
        
        public function Update_Brosur_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = array(
				'IdNew' => $input['IdNew'],
				'Brosur' => $input['Brosur'],
			);
			$this->db->insert('brosurprimary',$data);
			$insert_id = $this->db->insert_id();
			
            if($insert_id) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Update Brosur Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Brosur Gagal']));
            }
        }
        
        public function Update_Siteplan_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = array(
				'IdNew' => $input['IdNew'],
				'Siteplan' => $input['Siteplan'],
			);
			$this->db->insert('siteplanprimary',$data);
			$insert_id = $this->db->insert_id();
			
            if($insert_id) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Update Siteplan Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Siteplan Gagal']));
            }
        }
        
        public function Update_Pricelist_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = array(
				'IdNew' => $input['IdNew'],
				'Pricelist' => $input['Pricelist'],
			);
			$this->db->insert('pricelistprimary',$data);
			$insert_id = $this->db->insert_id();
			
            if($insert_id) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Update Pricelist Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Pricelist Gagal']));
            }
        }
        
        public function Update_File_Brosur_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = array(
				'Brosur' => $input['Brosur'],
			);
			$where = array('IdBrosur' => $input['IdBrosur']);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'brosurprimary');
			
            if($insert_id) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Update Brosur Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Brosur Gagal']));
            }
        }
        
        public function Update_File_Siteplan_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = array(
				'Siteplan' => $input['Siteplan'],
			);
			$where = array('IdSiteplan' => $input['IdSiteplan']);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'siteplanprimary');
			
            if($insert_id) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Update Siteplan Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Siteplan Gagal']));
            }
        }
        
        public function Update_File_Pricelist_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = array(
				'Pricelist' => $input['Pricelist'],
			);
			$where = array('IdPricelist' => $input['IdPricelist']);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'pricelistprimary');
			
            if($insert_id) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'Update Pricelist Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Update Pricelist Gagal']));
            }
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_List_Listing_Primary() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            $data = $this->ModelFlutter->Get_List_Listing_Primary($limit, $offset, $search);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Detail_Primary() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Detail_Primary($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Image_Primary() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Image_Primary($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Tipe_Listing_Primary() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_List_Tipe_Listing_Primary($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Brosur_Primary() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_List_Brosur_Primary($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Siteplan_Primary() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_List_Siteplan_Primary($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_List_Pricelist_Primary() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_List_Pricelist_Primary($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
    
    // SOP ============================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Sop(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $this->db->trans_start();
            
            $data = array(
				'FileSop' => $input['FileSop'],
				'JudulSop' => $input['JudulSop']
			);
			$this->db->insert('sop',$data);
			$idsop = $this->db->insert_id();
    
            if($idsop) {
                $this->db->trans_commit();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(['status' => 'success', 'user_id' => $idsop]));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah SOP Gagal']));
            }
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_Sop() {
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_Sop($limit, $offset);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        
    // Count ==========================================================================================================================================================================================
    
        public function Count_Pelamar(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = $this->ModelFlutter->Count_Pelamar();
            
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($data));
        }
        
        public function Count_Pralisting_Admin(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = $this->ModelFlutter->Count_Pralisting_Admin();
            
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($data));
        }
        
        public function Count_Pralisting_Manager(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = $this->ModelFlutter->Count_Pralisting_Manager();
            
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($data));
        }
        
        public function Count_Pralisting_Rejected(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Count_Pralisting_Rejected($id);
            
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($data));
        }
        
        public function Count_Listing_Pending(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = $this->ModelFlutter->Count_Listing_Pending();
            
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($data));
        }
        
        public function Count_Report_Vendor(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = $this->ModelFlutter->Count_Report_Vendor();
            
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($data));
        }
        
        public function Count_Report_Closing(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = $this->ModelFlutter->Count_Report_Closing();
            
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($data));
        }
        
        public function Count_Pasang_Banner(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = $this->ModelFlutter->Count_Pasang_Banner();
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Count_Pasang_Banner_Agen(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Count_Pasang_Banner_Agen($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Count_Pasang_Banner_Hari_Ini(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = $this->ModelFlutter->Count_Pasang_Banner_Hari_Ini();
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Count_Report_Buyer(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $data = $this->ModelFlutter->Count_Report_Buyer();
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Count_Report_Buyer_Agen(){
            $authHeader = $this->input->get_request_header('Authorization', TRUE);
            
            if ($authHeader !== "Bearer $this->validApiKey") {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Unauthorized']));
                return;
            }
            
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Count_Report_Buyer_Agen($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
}