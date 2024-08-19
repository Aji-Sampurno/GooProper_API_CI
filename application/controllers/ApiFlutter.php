<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiFlutter extends CI_Controller
{
    public function __construct(){
        // Construct the parent class
        parent::__construct();
        $this->load->model('ModelFlutter');
        $this->encryption_key = 'gpp090922';
        $this->load->helper('encryption');
        $this->load->helper('url');
		$this->load->library('form_validation');
    }
    
    // Authentication ================================================================================================================================================================================
    
    public function Login() {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
    
        if (!isset($input['Username']) || !isset($input['Password'])) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Username dan Password']));
            return;
        }
    
        $username = $input['Username'];
        $password = $input['Password'];
    
        if (empty($username) || empty($password)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Username dan Password']));
            return;
        }
    
        $userAdmin = $this->ModelFlutter->Login_Admin($username, $password);
    
        if ($userAdmin) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(['status' => 'success', 'user' => $userAdmin]));
        } else {
            $userAgen = $this->ModelFlutter->Login_Agen($username, $password);
            
            if ($userAgen) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(['status' => 'success', 'user' => $userAgen]));
            } else {
                $userCustomer = $this->ModelFlutter->Login_Customer($username, $password);
                
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
    
    // Customer ----------------------------------------------------------------
    
    public function Registrasi_Customer() {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        
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
    
    public function Update_Customer() {
        $username = $this->input->post('Username');
        $namalengkap = $this->input->post('NamaLengkap');
        $notelp = $this->input->post('NoTelp');
        $email = $this->input->post('Email');

        if(empty($username) || empty($namalengkap) || empty($notelp) || empty($email)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Data']));
            return;
        }

        $data = [
            'Username' => $username,
            'NamaLengkap' => $namalengkap,
            'NoTelp' => $notelp,
            'Email' => $email,
        ];

        $where = array('IdCustomer' => $this->input->post('IdCustomer'),);
        $insert_id = $this->ModelFlutter->Update_Data($where,$data,'customer');

        if($insert_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(201)
                ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update Akun']));
        }
    }
    
    public function Update_Password_Customer() {
        $password = $this->input->post('Password');

        if(empty($password)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Password']));
            return;
        }

        $data = [
            'Password' => $password,
        ];

        $where = array('IdCustomer' => $this->input->post('IdCustomer'),);
        $insert_id = $this->ModelFlutter->Update_Data($where,$data,'customer');

        if($insert_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(201)
                ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update Password']));
        }
    }
    
    // Agen --------------------------------------------------------------------
    
    public function Registrasi_Agen() {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        
        $Username = $input['Username'];
        $Password = md5($input['Password']);
        $Nama = $input['Nama'];
        $NoTelp = $input['NoTelp'];
        $Email = $input['Email'];
        $TglLahir = $input['TglLahir'];
        $KotaKelahiran = $input['KotaKelahiran'];
        $Pendidikan = $input['Pendidikan'];
        $NamaSekolah = $input['NamaSekolah'];
        $MasaKerja = $input['MasaKerja'];
        $Jabatan = $input['Jabatan'];
        $Konfirmasi = $input['Konfirmasi'];
        $AlamatDomisili = $input['AlamatDomisili'];
        $Facebook = $input['Facebook'];
        $Instagram = $input['Instagram'];
        $Npwp = $input['Npwp'];
        $NoKtp = $input['NoKtp'];
        $ImgKtp = $input['ImgKtp'];
        $Photo = $input['Photo'];

        if(empty($Username)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Data']));
            return;
        }

        $hashed_password = md5($Password);

        $data = [
            'Username' => $Username,
            'Password' => $hashed_password,
            'Nama' => $Nama,
            'NoTelp' => $NoTelp,
            'Email' => $Email,
            'TglLahir' => $TglLahir,
            'KotaKelahiran' => $KotaKelahiran,
            'Pendidikan' => $Pendidikan,
            'NamaSekolah' => $NamaSekolah,
            'MasaKerja' => $MasaKerja,
            'Jabatan' => $Jabatan,
            'Konfirmasi' => $Konfirmasi,
            'AlamatDomisili' => $AlamatDomisili,
            'Facebook' => $Facebook,
            'Instagram' => $Instagram,
            'Npwp' => $Npwp,
            'NoKtp' => $NoKtp,
            'ImgKtp' => $ImgKtp,
            'Photo' => $Photo,
            'Status' => 3,
            'IsAkses' => 1,
        ];

        $insert_id = $this->ModelFlutter->Input_Data($data, 'agen');

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
    
    public function Update_Agen() {
        $Username = $this->input->post('Username');
        $Nama = $this->input->post('Nama');
        $NoTelp = $this->input->post('NoTelp');
        $Email = $this->input->post('Email');
        $TglLahir = $this->input->post('TglLahir');
        $KotaKelahiran = $this->input->post('KotaKelahiran');
        $Pendidikan = $this->input->post('Pendidikan');
        $NamaSekolah = $this->input->post('NamaSekolah');
        $MasaKerja = $this->input->post('MasaKerja');
        $Jabatan = $this->input->post('Jabatan');
        $AlamatDomisili = $this->input->post('AlamatDomisili');
        $Facebook = $this->input->post('Facebook');
        $Instagram = $this->input->post('Instagram');
        $Npwp = $this->input->post('Npwp');

        if(empty($username) || empty($namalengkap) || empty($notelp) || empty($email)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Data']));
            return;
        }

        $data = [
            'Username' => $Username,
            'Nama' => $Nama,
            'NoTelp' => $NoTelp,
            'Email' => $Email,
            'TglLahir' => $TglLahir,
            'KotaKelahiran' => $KotaKelahiran,
            'Pendidikan' => $Pendidikan,
            'NamaSekolah' => $NamaSekolah,
            'MasaKerja' => $MasaKerja,
            'Jabatan' => $Jabatan,
            'AlamatDomisili' => $AlamatDomisili,
            'Facebook' => $Facebook,
            'Instagram' => $Instagram,
            'Npwp' => $Npwp,
        ];

        $where = array('IdAgen' => $this->input->post('IdAgen'),);
        $insert_id = $this->ModelFlutter->Update_Data($where,$data,'agen');

        if($insert_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(201)
                ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update Akun']));
        }
    }
    
    public function Update_Ktp_Agen() {
        $NoKtp = $this->input->post('NoKtp');
        $ImgKtp = $this->input->post('ImgKtp');

        if(empty($NoKtp) || empty($ImgKtp)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Data']));
            return;
        }

        $data = [
            'NoKtp' => $NoKtp,
            'ImgKtp' => $ImgKtp,
        ];

        $where = array('IdAgen' => $this->input->post('IdAgen'),);
        $insert_id = $this->ModelFlutter->Update_Data($where,$data,'agen');

        if($insert_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(201)
                ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update KTP']));
        }
    }
    
    public function Update_Photo_Agen() {
        $Photo = $this->input->post('Photo');

        if(empty($Photo)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Data']));
            return;
        }

        $data = [
            'Photo' => $Photo,
        ];

        $where = array('IdAgen' => $this->input->post('IdAgen'),);
        $insert_id = $this->ModelFlutter->Update_Data($where,$data,'agen');

        if($insert_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(201)
                ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update Photo']));
        }
    }
    
    public function Update_Kantor_Agen() {
        $KotaAgen = $this->input->post('KotaAgen');

        if(empty($KotaAgen)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Data']));
            return;
        }

        $data = [
            'KotaAgen' => $KotaAgen,
        ];

        $where = array('IdAgen' => $this->input->post('IdAgen'),);
        $insert_id = $this->ModelFlutter->Update_Data($where,$data,'agen');

        if($insert_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(201)
                ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update Kantor Agen']));
        }
    }
    
    public function Update_Password_Agen() {
        $Password = $this->input->post('Password');

        if(empty($Password)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Data']));
            return;
        }
        
        $hashed_password = md5($password);

        $data = [
            'Password' => $hashed_password,
        ];

        $where = array('IdAgen' => $this->input->post('IdAgen'),);
        $insert_id = $this->ModelFlutter->Update_Data($where,$data,'agen');

        if($insert_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(201)
                ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update Password']));
        }
    }
    
    // Data ==========================================================================================================================================================================================
    
    public function Get_Jenis_Properti(){
        $data = $this->ModelFlutter->Get_Jenis_Properti();
        echo json_encode($data);
    }
    
    public function Get_Wilayah(){
        $data = $this->ModelFlutter->Get_Wilayah();
        echo json_encode($data);
    }
    
    public function Get_Daerah(){
        $data = $this->ModelFlutter->Get_Daerah();
        echo json_encode($data);
    }
    
    public function Get_Agen(){
        $data = $this->ModelFlutter->Get_Agen();
        echo json_encode($data);
    }
    
    public function Get_Detail_Agen(){
        $data = $this->ModelFlutter->Get_Detail_Agen();
        echo json_encode($data);
    }
    
    // Tampungan =====================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Tampungan() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
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
        
        // Get -----------------------------------------------------------------
        
        public function Get_List_Tampungan(){
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_List_Tampungan($id);
            echo json_encode($data);
        }
        
    // Pralisting ====================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_PraListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $inputDate = $this->input->post('TglInput');
            $currentDate = date('Y-m-d');
            
            if($inputDate == 0){
                $makeDate = $currentDate;
            } else{
                $makeDate = $inputDate;
            }
            
            $this->db->trans_start();
            
            $vendor = array(
				'NamaLengkap' => $this->input->post('NamaLengkap'),
                'NoTelp' => $this->input->post('NoTelp'),
                'Alamat' => $this->input->post('AlamatVendor'),
				'TglLahir' => $this->input->post('TglLahir'),
				'Nik' => $this->input->post('Nik'),
				'NoRekening' => $this->input->post('NoRekening'),
				'Bank' => $this->input->post('Bank'),
				'AtasNama' => $this->input->post('AtasNama'),
			);
			$this->db->insert('vendor',$vendor);
			$idvendor = $this->db->insert_id();
			
			$data = array(
				'IdAgen' => $this->input->post('IdAgen'),
				'IdAgenCo' => $this->input->post('IdAgenCo'),
				'IdInput' => $this->input->post('IdInput'),
				'IdVendor' => $idvendor,
				'NamaListing'=> $this->input->post('NamaListing'),
				'MetaNamaListing'=> $this->input->post('MetaNamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),	
				'AlamatTemplate'=> $this->input->post('AlamatTemplate'),
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
				'Wilayah' => $this->input->post('Wilayah'),
				'Daerah' => $this->input->post('Daerah'),
				'Wide'=> $this->input->post('Wide'),	
				'Land'=> $this->input->post('Land'),	
				'Dimensi'=> $this->input->post('Dimensi'),	
				'Listrik'=> $this->input->post('Listrik'),	
				'Level'=> $this->input->post('Level'),	
				'Bed'=> $this->input->post('Bed'),	
				'Bath'=> $this->input->post('Bath'),	
				'BedArt'=> $this->input->post('BedArt'),	
				'BathArt'=> $this->input->post('BathArt'),	
				'Garage'=> $this->input->post('Garage'),	
				'Carpot'=> $this->input->post('Carpot'),
                'Hadap'=> $this->input->post('Hadap'),
                'SHM'=> $this->input->post('SHM'),
                'HGB'=> $this->input->post('HGB'),
                'HSHP'=> $this->input->post('HSHP'),
                'PPJB'=> $this->input->post('PPJB'),
                'Stratatitle'=> $this->input->post('Stratatitle'),
                'AJB'=> $this->input->post('AJB'),
                'PetokD'=> $this->input->post('PetokD'),
                'ImgSHM'=> $this->input->post('ImgSHM'),
                'ImgHGB'=> $this->input->post('ImgHGB'),
                'ImgHSHP'=> $this->input->post('ImgHSHP'),
                'ImgPPJB'=> $this->input->post('ImgPPJB'),
                'ImgStratatitle'=> $this->input->post('ImgStratatitle'),
                'ImgAJB'=> $this->input->post('ImgAJB'),
                'ImgPetokD'=> $this->input->post('ImgPetokD'),
                'ImgPjp'=> $this->input->post('ImgPjp'),
                'ImgPjp1'=> $this->input->post('ImgPjp1'),
				'JenisProperti'=> $this->input->post('JenisProperti'),	
				'SumberAir'=> $this->input->post('SumberAir'),	
				'Kondisi'=> $this->input->post('Kondisi'),
				'RuangTamu'=> $this->input->post('RuangTamu'),
				'RuangMakan'=> $this->input->post('RuangMakan'),
				'Dapur'=> $this->input->post('Dapur'),
				'Jemuran'=> $this->input->post('Jemuran'),
                'Masjid'=> $this->input->post('Masjid'),
                'Taman'=> $this->input->post('Taman'),
                'Playground'=> $this->input->post('Playground'),
                'Cctv'=> $this->input->post('Cctv'),
                'OneGateSystem'=> $this->input->post('OneGateSystem'),
                'KolamRenang'=> $this->input->post('KolamRenang'),
                'SportSpace'=> $this->input->post('SportSpace'),
                'ParkingSpot'=> $this->input->post('ParkingSpot'),
				'Deskripsi'=> $this->input->post('Deskripsi'),
				'MetaDeskripsi'=> $this->input->post('MetaDeskripsi'),	
				'Prabot'=> $this->input->post('Prabot'),	
				'KetPrabot'=> $this->input->post('KetPrabot'),	
				'Priority'=> $this->input->post('Priority'),
				'Banner'=> $this->input->post('Banner'),
				'Size'=> $this->input->post('Size'),
				'TipeHarga'=> $this->input->post('TipeHarga'),
				'Harga'=> $this->input->post('Harga'),	
				'HargaSewa'=> $this->input->post('HargaSewa'),
				'RangeHarga'=> $this->input->post('RangeHarga'),	
				'TglInput'=> $makeDate,
				'Img1'=> $this->input->post('Img1'),
                'Img2'=> $this->input->post('Img2'),
                'Img3'=> $this->input->post('Img3'),
                'Img4'=> $this->input->post('Img4'),
                'Img5'=> $this->input->post('Img5'),
                'Img6'=> $this->input->post('Img6'),
                'Img7'=> $this->input->post('Img7'),
                'Img8'=> $this->input->post('Img8'),
                'Img9'=> $this->input->post('Img9'),
                'Img10'=> $this->input->post('Img10'),
                'Img11'=> $this->input->post('Img11'),
                'Img12'=> $this->input->post('Img12'),
				'Video'=> $this->input->post('Video'),	
				'LinkFacebook'=> $this->input->post('LinkFacebook'),	
				'LinkTiktok'=> $this->input->post('LinkTiktok'),	
				'LinkInstagram'=> $this->input->post('LinkInstagram'),	
				'LinkYoutube'=> $this->input->post('LinkYoutube'),	
				'Fee'=> $this->input->post('Fee'),
				'IsAdmin' => 0,
				'IsManager' => 0,
				'Marketable' => $this->input->post('IsMarketable'),
				'StatusHarga' => $this->input->post('IsHarga'),
                'IsSelfie' => $this->input->post('IsSelfie'),
                'IsLokasi' => $this->input->post('IsLokasi'),
                'Selfie' => $this->input->post('Selfie'),
                'NoKtp' => $this->input->post('NoKtp'),
                'ImgKtp' => $this->input->post('ImgKtp'),
                'Pending' => 0,
			);
			$this->db->insert('pralisting',$data);
			$idpralisting = $this->db->insert_id();
			if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo json_encode([
						"Message" => "Gagal",
						"Status" => "Error"
						]);
            } else {
                $this->db->trans_commit();
                echo json_encode([
						"Message" => "Berhasil",
						"Status" => "Sukses"
						]);
            }
        }
    }
        
        // Update --------------------------------------------------------------
        // Get -----------------------------------------------------------------
        
        public function Get_List_PraListing_Officer(){
            $data = $this->ModelFlutter->Get_List_PraListing_Officer();
            echo json_encode($data);
        }
        
        public function Get_List_PraListing_Admin(){
            $data = $this->ModelFlutter->Get_List_PraListing_Admin();
            echo json_encode($data);
        }
        
        public function Get_List_PraListing_Manager(){
            $data = $this->ModelFlutter->Get_List_PraListing_Manager();
            echo json_encode($data);
        }
        
        public function Get_List_PraListing_Agen(){
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_PraListing_Agen($id, $limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_PraListing_Rejected_Agen(){
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_PraListing_Rejected_Agen($id, $limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_PraListing_Rejected(){
            $data = $this->ModelFlutter->Get_List_PraListing_Rejected();
            echo json_encode($data);
        }
        
        public function Get_Detail_PraListing(){
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Detail_PraListing($id);
            echo json_encode($data);
        }
        
        // Delete --------------------------------------------------------------
        // Reject --------------------------------------------------------------
        // Template ------------------------------------------------------------
        // Count ---------------------------------------------------------------
    
    // Listing ========================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        // Update --------------------------------------------------------------
        // Get -----------------------------------------------------------------
        
        public function Get_List_Listing_Agen() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_List_Listing_Agen($id);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Terbaru_Pagination() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 30;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_terbaru_Pagination($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Terbaru_Jual() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_terbaru_Jual($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Terbaru_Sewa() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_terbaru_Sewa($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Terbaru_JualSewa() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_terbaru_JualSewa($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Exclusive() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Listing_Exclusive($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Sold() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Listing_Sold($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Rumah() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_rumah($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Ruko() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_ruko($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Tanah() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_tanah($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Gudang() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_gudang($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_RuangUsaha() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_ruangusaha($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Villa() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_villa($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Apartemen() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_Appartemen($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Pabrik() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_Pabrik($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Kantor() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_kantor($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Hotel() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_hotel($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Rukost() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_rukost($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_Detail_Listing() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Detail_Listing($id);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Filter() {
            $filters = array(
                'Alamat' => $this->input->post('Alamat'),
                'Wilayah' => $this->input->post('Wilayah'),
                'Daerah' => $this->input->post('Daerah'),
                'Wide' => $this->input->post('Wide'),
                'Land' => $this->input->post('Land'),
                'Bed' => $this->input->post('Bed'),
                'Bath' => $this->input->post('Bath'),
                'JenisProperti' => $this->input->post('JenisProperti'),
                'Kondisi' => $this->input->post('Kondisi'),
                'HargaMin' => $this->input->post('HargaMin'),
                'HargaMax' => $this->input->post('HargaMax'),
            );
            
            $listings = $this->ModelFlutter->Get_List_Listing_Filter($filters);
            
            echo json_encode($listings);
        }
        
        public function Get_List_Listing_Pencarian() {
            $keyword = $this->input->get('search');
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            
            if ($keyword !== null && $keyword !== '') {
                $keywords = explode(' ', $keyword);
            } else {
                $keywords = [];
            }
            
            $result = $this->ModelFlutter->Get_List_Listing_Pencarian($limit, $offset, $keywords);
            
            echo json_encode($result);
        }
        
        // Delete --------------------------------------------------------------
        // Reject --------------------------------------------------------------
        // Template ------------------------------------------------------------
        // Count ---------------------------------------------------------------
    
    // Info ===========================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        // Update --------------------------------------------------------------
        // Get -----------------------------------------------------------------
        
        public function Get_List_Info_Jual() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Info_Jual($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Info_Sewa() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Info_Sewa($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Info_JualSewa() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Info_JualSewa($limit, $offset);
            echo json_encode($data);
        }
        
        // Delete --------------------------------------------------------------
        // Count ---------------------------------------------------------------
    
    // Primary ========================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        // Update --------------------------------------------------------------
        // Get -----------------------------------------------------------------
        
        public function Get_List_Listing_Primary() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Listing_Primary($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Tipe_Listing_Primary() {
            $data = $this->ModelFlutter->Get_List_Tipe_Listing_Primary();
            echo json_encode($data);
        }
        
        // Delete --------------------------------------------------------------
        // Reject --------------------------------------------------------------
        // Template ------------------------------------------------------------
        // Count ---------------------------------------------------------------
}
