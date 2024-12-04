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
                
                $kodeRandom = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
                $kodeBaru = 'agen' . $kodeRandom;
                
                $data = [
                    'Password' => md5($kodeBaru),
                    'KodeVerifikasi' => $kodeRandom,
                ];
        
                $where = array('IdAgen' => $idAgen,);
                $this->ModelFlutter->Update_Data($where,$data,'agen');
                
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
            
            $kodeRandom = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $kodeBaru = 'agen' . $kodeRandom;
            
            $data = [
                'Password' => md5($kodeBaru),
                'KodeVerifikasi' => $kodeRandom,
            ];
            
            $where = array('IdAgen' => $idAgen,);
            $this->ModelFlutter->Update_Data($where,$data,'agen');
            
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
    
    // Customer ======================================================================================================================================================================================
    
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
    
    // Agen ==========================================================================================================================================================================================
    
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
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        
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
    
    public function Get_Agen(){
        $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
        $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
        $search = $this->input->get('search');
    
        $data = $this->ModelFlutter->Get_Agen($limit, $offset, $search);
    
        echo json_encode($data);
    }
    
    public function Get_Agen_List(){
        $data = $this->ModelFlutter->Get_Agen_List();
        echo json_encode($data);
    }
    
    public function Get_Agen_Kode_List(){
        $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
        $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
        $search = $this->input->get('search');
    
        $data = $this->ModelFlutter->Get_Agen_Kode_List($limit, $offset, $search);
    
        echo json_encode($data);
    }
    
    public function Get_Detail_Agen(){
        $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->ModelFlutter->Get_Detail_Agen($id);
        echo json_encode($data);
    }
    
    public function Get_Pelamar(){
        $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
        $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
        $data = $this->ModelFlutter->Get_Pelamar($limit, $offset);
        echo json_encode($data);
    }
        
    public function Get_Detail_Pelamar(){
        $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->ModelFlutter->Get_Detail_Pelamar($id);
        echo json_encode($data);
    }
    
    public function Approve_Agen(){
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        
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
    
    // Data ==========================================================================================================================================================================================
    
    public function Add_Device(){
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        
        $IdAdmin = $input['IdAdmin'];
        $Status = $input['Status'];
        $Token = $input['Token'];
        
        $data = [
            'IdAdmin' => $IdAdmin,
            'Status' => $Status,
            'Token' => $Token,
        ];
        
        $insert_id = $this->ModelFlutter->Input_Data($data, 'device');
        
        if($insert_id) {
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
    
    public function Get_Jenis_Properti(){
        $data = $this->ModelFlutter->Get_Jenis_Properti();
        echo json_encode($data);
    }
    
    public function Get_Wilayah(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->ModelFlutter->Get_Wilayah($id);
        echo json_encode($data);
    }
    
    public function Get_Daerah(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->ModelFlutter->Get_Daerah($id);
        echo json_encode($data);
    }
    
    public function Get_Provinsi(){
        $data = $this->ModelFlutter->Get_Provinsi();
        echo json_encode($data);
    }
    
    // Closing =======================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Closing() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
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
            
            $sql = "UPDATE reportsold SET IsRead = '1' WHERE IdListing = ?";
            $this->db->query($sql, array($input['IdListing']));
        }
        
        // Get -----------------------------------------------------------------
        
        public function Get_Closing_Agen() {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            $data = $this->ModelFlutter->Get_Closing_Agen($id, $limit, $offset, $search);
            echo json_encode($data);
        }
        
        public function Get_Closing() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            $data = $this->ModelFlutter->Get_Closing($limit, $offset, $search);
            echo json_encode($data);
        }
        
        public function Get_Detail_Closing() {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Detail_Closing($id);
            echo json_encode($data);
        }
        
        public function Get_Report_Closing() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            $data = $this->ModelFlutter->Get_Report_Closing($limit, $offset, $search);
            echo json_encode($data);
        }
        
    // Report Buyer ==================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Report_Buyer() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
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
                'AlamatProperti' => $input['AlamatProperti'],
                'SumberInformasi' => $input['SumberBuyer'],
                'StatusFollowUp' => $input['StatusFollowUp']
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
            
            if (!isset($input['NamaBuyer'], $input['NoTelp'], $input['JenisProperti'], $input['AlamatProperti'], $input['SumberBuyer'], $input['StatusFollowUp'])) {
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
                'JenisProperti' => $input['JenisProperti'],
                'AlamatProperti' => $input['AlamatProperti'],
                'SumberInformasi' => $input['SumberBuyer'],
                'StatusFollowUp' => $input['StatusFollowUp'],
                'TglReport' => date('Y-m-d H:i:s')
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
        
        public function Close_Report_Buyer() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            if (!isset($input['NamaBuyer'], $input['NoTelp'], $input['JenisProperti'], $input['AlamatProperti'], $input['SumberBuyer'], $input['StatusFollowUp'])) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Data tidak lengkap']));
                return;
            }
            
            date_default_timezone_set('Asia/Jakarta');
            
            $data = [
                'StatusFollowUp' => "$currentDate Close Report",
                'TglReport' => date('Y-m-d H:i:s'),
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
                        'AlamatProperti' => $data->AlamatProperti,
                        'SumberInformasi' => $data->SumberInformasi,
                        'StatusFollowUp' => $data->StatusFollowUp,
                        'TglReport' => $data->TglReport,
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
        
    // Report Listing ================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Report_Listing_Buyer() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
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
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $status_data = $this->ModelFlutter->Get_Detail_Report_Listing_Buyer($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($status_data));
        }
        
    // Report Vendor =================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Repost_Vendor() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
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
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            
            $data = $this->ModelFlutter->Get_Report_Vendor($limit, $offset);
            
            echo json_encode($data);
        }
        
        public function Get_Data_Report_Vendor() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Data_Report_Vendor($id);
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
        
        // Delete ---------------------------------------------------------------
        
        public function Delete_Tampungan() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
        
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
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_List_Tampungan($id);
            echo json_encode($data);
        }
        
    // Pralisting ====================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
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
                                    $this->db->trans_rollback();
                                    $this->output
                                        ->set_content_type('application/json')
                                        ->set_status_header(500)
                                        ->set_output(json_encode(['status' => 'fail', 'message' => 'Approve Pra-Listing Gagal, Report Vendor Gagal Diupdate']));
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
        
        // Approve -------------------------------------------------------------
        
        public function Approve_Admin_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
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
        
        public function Approve_Manager_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $IdPralisting = $input['IdPraListing'];
            
            $this->db->trans_start();
            
            $data = [
                'IsManager'=> 1,
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
                                $this->db->trans_rollback();
                                $this->output
                                    ->set_content_type('application/json')
                                    ->set_status_header(500)
                                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Approve Pra-Listing Gagal, Report Vendor Gagal Diupdate']));
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
        }
        
        // Reject --------------------------------------------------------------
        
        public function Reject_PraListing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
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
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_PraListing_Admin($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_PraListing_Manager(){
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_PraListing_Manager($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_PraListing_Agen(){
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_PraListing_Agen($id, $limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_PraListing_Rejected_Agen(){
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
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
        
        public function Get_Spec_PraListing() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Spec_PraListing($id);
            echo json_encode($data);
        }
        
        public function Get_Agen_PraListing() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Agen_PraListing($id);
            echo json_encode($data);
        }
        
        public function Get_Meta_PraListing() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Meta_PraListing($id);
            echo json_encode($data);
        }
        
        public function Get_Image_PraListing() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Image_PraListing($id);
            echo json_encode($data);
        }
        
        public function Get_Lampiran_PraListing() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Lampiran_PraListing($id);
            echo json_encode($data);
        }
    
    // Listing ========================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_View(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $sql = "UPDATE listing SET View = View + 1 WHERE IdListing = ?";
            $this->db->query($sql, array($input['IdListing']));
        }
        
        public function Add_Template_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
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
        
        // Update --------------------------------------------------------------
        
        public function Approve_Susulan(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
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
        
        public function Update_Kondisi_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'Sold' => 0,
                'SoldAgen' => 0,
                'Rented' => 0,
                'RentedAgen' => 0,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
                $this->db->trans_commit();
                    $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'success', 'Up Listing Berhasil']));
            } else {
                $this->db->trans_rollback();
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Up Listing']));
            }
        }
        
        public function Update_Sold_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
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
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'Sold' => 0,
                'SoldAgen' => 0,
                'Rented' => 0,
                'RentedAgen' => 0,
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
            
            $currentDate = date('Y-m-d H:i:s');
            
            $Idlisting = $input['IdListing'];
            $ImgPjp = $input['ImgPjp'];
            $ImgPjp1 = $input['ImgPjp1'];
            
            $this->db->trans_start();
            
            $data = [
                'ImgPjp'=> $ImgPjp,
                'ImgPjp1'=> $ImgPjp1,
                'Pending' => 1,
            ];
            $where = array('IdListing'=> $Idlisting,);
            $insert_id = $this->ModelFlutter->Update_Data($where,$data,'listing');
            
            if($insert_id) {
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
        }
        
        public function Update_Gambar_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
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
        
        public function Double_Listing(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
            $Idlisting = $input['IdListing'];
            
            $this->db->trans_start();
            
            $data = [
                'IsDouble' => 1,
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
        
        // Get -----------------------------------------------------------------
        
        public function Get_List_Listing_Agen() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            $data = $this->ModelFlutter->Get_List_Listing_Agen($limit, $offset, $id, $search);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Terbaru_Pagination() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_terbaru_Pagination($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Terbaru_Jual() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_terbaru_Jual($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Terbaru_Sewa() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_terbaru_Sewa($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Terbaru_JualSewa() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->get_list_listing_terbaru_JualSewa($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Exclusive() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Listing_Exclusive($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Sold() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Listing_Sold($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Pencarian() {
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
            
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Sold_Pencarian() {
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
            
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Pending() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Listing_Pending($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Listing_Selection() {
            $search = $this->input->get('search');
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            
            $data = $this->ModelFlutter->Get_List_Listing_Selection($limit, $offset, $search);
            
            echo json_encode($data);
        }
        
        public function Get_List_Susulan() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Susulan($id, $limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_Agen_Listing() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Agen_Listing($id);
            echo json_encode($data);
        }
        
        public function Get_Meta_Listing() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Meta_Listing($id);
            echo json_encode($data);
        }
        
        public function Get_Image_Listing() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Image_Listing($id);
            echo json_encode($data);
        }
        
        public function Get_Lampiran_Listing() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Lampiran_Listing($id);
            echo json_encode($data);
        }
        
    // Info ===========================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
        
        public function Add_Info(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
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
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            $data = $this->ModelFlutter->Get_List_Info($limit, $offset, $search);
            echo json_encode($data);
        }
        
        public function Get_List_Info_Agen() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Info_Agen($id, $limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Info_Jual() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Info_Jual($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Info_Sewa() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Info_Sewa($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_List_Info_JualSewa() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $data = $this->ModelFlutter->Get_List_Info_JualSewa($limit, $offset);
            echo json_encode($data);
        }
        
        public function Get_Image_Info() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Image_Info($id);
            echo json_encode($data);
        }
        
        public function Get_Lampiran_Info() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Lampiran_Info($id);
            echo json_encode($data);
        }
        
        // Delete --------------------------------------------------------------
        
        public function Delete_Info() {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
        
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
        
        public function Update_Tipe_Primary(){
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, TRUE);
            
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
        
        // Get -----------------------------------------------------------------
        
        public function Get_List_Listing_Primary() {
            $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
            $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
            $search = $this->input->get('search');
            $data = $this->ModelFlutter->Get_List_Listing_Primary($limit, $offset, $search);
            echo json_encode($data);
        }
        
        public function Get_Detail_Primary() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Detail_Primary($id);
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
        
        public function Get_Image_Primary() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_Image_Primary($id);
            echo json_encode($data);
        }
        
        public function Get_List_Tipe_Listing_Primary() {
            $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = $this->ModelFlutter->Get_List_Tipe_Listing_Primary($id);
            echo json_encode($data);
        }
        
    // Count ==========================================================================================================================================================================================
    
    public function Count_Pelamar(){
        $data = $this->ModelFlutter->Count_Pelamar();
        echo json_encode($data);
    }
    
    public function Count_Pralisting_Admin(){
        $data = $this->ModelFlutter->Count_Pralisting_Admin();
        echo json_encode($data);
    }
    
    public function Count_Pralisting_Manager(){
        $data = $this->ModelFlutter->Count_Pralisting_Manager();
        echo json_encode($data);
    }
    
    public function Count_Pralisting_Rejected(){
        $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->ModelFlutter->Count_Pralisting_Rejected($id);
        echo json_encode($data);
    }
    
    public function Count_Listing_Pending(){
        $data = $this->ModelFlutter->Count_Listing_Pending();
        echo json_encode($data);
    }
    
    public function Count_Report_Vendor(){
        $data = $this->ModelFlutter->Count_Report_Vendor();
        echo json_encode($data);
    }
    
    public function Count_Report_Closing(){
        $data = $this->ModelFlutter->Count_Report_Closing();
        echo json_encode($data);
    }
    
    // UnUsed =========================================================================================================================================================================================
    
    // public function Update_Customer() {
    //     $username = $this->input->post('Username');
    //     $namalengkap = $this->input->post('NamaLengkap');
    //     $notelp = $this->input->post('NoTelp');
    //     $email = $this->input->post('Email');

    //     if(empty($username) || empty($namalengkap) || empty($notelp) || empty($email)) {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Data']));
    //         return;
    //     }

    //     $data = [
    //         'Username' => $username,
    //         'NamaLengkap' => $namalengkap,
    //         'NoTelp' => $notelp,
    //         'Email' => $email,
    //     ];

    //     $where = array('IdCustomer' => $this->input->post('IdCustomer'),);
    //     $insert_id = $this->ModelFlutter->Update_Data($where,$data,'customer');

    //     if($insert_id) {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(201)
    //             ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
    //     } else {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(500)
    //             ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update Akun']));
    //     }
    // }
    
    // public function Update_Password_Customer() {
    //     $password = $this->input->post('Password');

    //     if(empty($password)) {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Password']));
    //         return;
    //     }

    //     $data = [
    //         'Password' => $password,
    //     ];

    //     $where = array('IdCustomer' => $this->input->post('IdCustomer'),);
    //     $insert_id = $this->ModelFlutter->Update_Data($where,$data,'customer');

    //     if($insert_id) {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(201)
    //             ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
    //     } else {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(500)
    //             ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update Password']));
    //     }
    // }
    
    // public function Update_Ktp_Agen() {
    //     $NoKtp = $this->input->post('NoKtp');
    //     $ImgKtp = $this->input->post('ImgKtp');

    //     if(empty($NoKtp) || empty($ImgKtp)) {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Data']));
    //         return;
    //     }

    //     $data = [
    //         'NoKtp' => $NoKtp,
    //         'ImgKtp' => $ImgKtp,
    //     ];

    //     $where = array('IdAgen' => $this->input->post('IdAgen'),);
    //     $insert_id = $this->ModelFlutter->Update_Data($where,$data,'agen');

    //     if($insert_id) {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(201)
    //             ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
    //     } else {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(500)
    //             ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update KTP']));
    //     }
    // }
    
    // public function Update_Photo_Agen() {
    //     $Photo = $this->input->post('Photo');

    //     if(empty($Photo)) {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Data']));
    //         return;
    //     }

    //     $data = [
    //         'Photo' => $Photo,
    //     ];

    //     $where = array('IdAgen' => $this->input->post('IdAgen'),);
    //     $insert_id = $this->ModelFlutter->Update_Data($where,$data,'agen');

    //     if($insert_id) {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(201)
    //             ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
    //     } else {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(500)
    //             ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update Photo']));
    //     }
    // }
    
    // public function Update_Kantor_Agen() {
    //     $KotaAgen = $this->input->post('KotaAgen');

    //     if(empty($KotaAgen)) {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Data']));
    //         return;
    //     }

    //     $data = [
    //         'KotaAgen' => $KotaAgen,
    //     ];

    //     $where = array('IdAgen' => $this->input->post('IdAgen'),);
    //     $insert_id = $this->ModelFlutter->Update_Data($where,$data,'agen');

    //     if($insert_id) {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(201)
    //             ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
    //     } else {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(500)
    //             ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update Kantor Agen']));
    //     }
    // }
    
    // public function Update_Password_Agen() {
    //     $Password = $this->input->post('Password');

    //     if(empty($Password)) {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode(['status' => 'fail', 'message' => 'Harap Masukkan Data']));
    //         return;
    //     }
        
    //     $hashed_password = md5($password);

    //     $data = [
    //         'Password' => $hashed_password,
    //     ];

    //     $where = array('IdAgen' => $this->input->post('IdAgen'),);
    //     $insert_id = $this->ModelFlutter->Update_Data($where,$data,'agen');

    //     if($insert_id) {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(201)
    //             ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
    //     } else {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(500)
    //             ->set_output(json_encode(['status' => 'fail', 'message' => 'Gagal Update Password']));
    //     }
    // }
    
    // public function Add_Report_Listing() {
    //     $inputJSON = file_get_contents('php://input');
    //     $input = json_decode($inputJSON, TRUE);
        
    //     if (!isset($input['IdListing'], $input['IdAgenListing'], $input['IdAgenCoListing'], $input['IdAgenBuyer'], $input['StatusReport'])) {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(400)
    //             ->set_output(json_encode(['status' => 'fail', 'message' => 'Data tidak lengkap']));
    //         return;
    //     }
        
    //     $data = [
    //         'IdListing' => $input['IdListing'],
    //         'IdAgenListing' => $input['IdAgenListing'],
    //         'IdAgenCoListing' => $input['IdAgenCoListing'],
    //         'IdAgenBuyer' => $input['IdAgenBuyer'],
    //         'StatusReport' => $input['StatusReport']
    //     ];
        
    //     $insert_id = $this->ModelFlutter->Input_Data($data, 'reportlisting');
        
    //     if($insert_id) {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode(['status' => 'success', 'user_id' => $insert_id]));
    //     } else {
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(500)
    //             ->set_output(json_encode(['status' => 'fail', 'message' => 'Tambah Report Buyer Gagal']));
    //     }
    // }
    
    // public function Get_List_PraListing_Officer(){
    //     $data = $this->ModelFlutter->Get_List_PraListing_Officer();
    //     echo json_encode($data);
    // }
        
    // public function Get_List_Listing_Rumah() {
    //     $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
    //     $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
    //     $data = $this->ModelFlutter->get_list_listing_rumah($limit, $offset);
    //     echo json_encode($data);
    // }
    
    // public function Get_List_Listing_Ruko() {
    //     $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
    //     $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
    //     $data = $this->ModelFlutter->get_list_listing_ruko($limit, $offset);
    //     echo json_encode($data);
    // }
    
    // public function Get_List_Listing_Tanah() {
    //     $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
    //     $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
    //     $data = $this->ModelFlutter->get_list_listing_tanah($limit, $offset);
    //     echo json_encode($data);
    // }
    
    // public function Get_List_Listing_Gudang() {
    //     $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
    //     $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
    //     $data = $this->ModelFlutter->get_list_listing_gudang($limit, $offset);
    //     echo json_encode($data);
    // }
    
    // public function Get_List_Listing_RuangUsaha() {
    //     $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
    //     $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
    //     $data = $this->ModelFlutter->get_list_listing_ruangusaha($limit, $offset);
    //     echo json_encode($data);
    // }
    
    // public function Get_List_Listing_Villa() {
    //     $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
    //     $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
    //     $data = $this->ModelFlutter->get_list_listing_villa($limit, $offset);
    //     echo json_encode($data);
    // }
    
    // public function Get_List_Listing_Apartemen() {
    //     $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
    //     $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
    //     $data = $this->ModelFlutter->get_list_listing_Appartemen($limit, $offset);
    //     echo json_encode($data);
    // }
    
    // public function Get_List_Listing_Pabrik() {
    //     $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
    //     $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
    //     $data = $this->ModelFlutter->get_list_listing_Pabrik($limit, $offset);
    //     echo json_encode($data);
    // }
    
    // public function Get_List_Listing_Kantor() {
    //     $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
    //     $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
    //     $data = $this->ModelFlutter->get_list_listing_kantor($limit, $offset);
    //     echo json_encode($data);
    // }
    
    // public function Get_List_Listing_Hotel() {
    //     $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
    //     $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
    //     $data = $this->ModelFlutter->get_list_listing_hotel($limit, $offset);
    //     echo json_encode($data);
    // }
    
    // public function Get_List_Listing_Rukost() {
    //     $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
    //     $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
    //     $data = $this->ModelFlutter->get_list_listing_rukost($limit, $offset);
    //     echo json_encode($data);
    // }
    
    // public function Get_Detail_Listing() {
    //     $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    //     $data = $this->ModelFlutter->Get_Detail_Listing($id);
    //     echo json_encode($data);
    // }
        
    // public function Get_List_Listing_Filter() {
    //     $filters = array(
    //         'Alamat' => $this->input->post('Alamat'),
    //         'Wilayah' => $this->input->post('Wilayah'),
    //         'Daerah' => $this->input->post('Daerah'),
    //         'Wide' => $this->input->post('Wide'),
    //         'Land' => $this->input->post('Land'),
    //         'Bed' => $this->input->post('Bed'),
    //         'Bath' => $this->input->post('Bath'),
    //         'JenisProperti' => $this->input->post('JenisProperti'),
    //         'Kondisi' => $this->input->post('Kondisi'),
    //         'HargaMin' => $this->input->post('HargaMin'),
    //         'HargaMax' => $this->input->post('HargaMax'),
    //     );
        
    //     $listings = $this->ModelFlutter->Get_List_Listing_Filter($filters);
        
    //     echo json_encode($listings);
    // }
        
    // public function Get_Spec_Listing() {
    //     $id = filter_var($_GET['Id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    //     $data = $this->ModelFlutter->Get_Spec_Listing($id);
    //     echo json_encode($data);
    // }
    
}
