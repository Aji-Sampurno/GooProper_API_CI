<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    private $encryption_key;

    public function __construct(){
        // Construct the parent class
        parent::__construct();
        $this->load->model('User_Model');
        $this->encryption_key = 'gpp090922';
        $this->load->helper('encryption');
        $this->load->helper('url');
		$this->load->library('form_validation');
    }
    
    // Akun =========================================================================================================================================================================================

    public function CobaEnkripsi() {
        $data = $this->User_Model->getListing();
        $json_data = json_encode($data);
        $original_data = $json_data;
        $encrypted_data = encrypt_data($original_data, $this->encryption_key);
        echo $encrypted_data;
    }
    
    public function Login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST,  FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            // Coba login sebagai admin
            $userLoggedIn = $this->User_Model->login($_POST['Username'], $_POST['Password']);
            if ($userLoggedIn) {
                $data = [
                    'IdAdmin' => $userLoggedIn['IdAdmin'],
                    'Username' => $userLoggedIn['Username'],
                    'NamaLengkap' => $userLoggedIn['NamaLengkap'],
                    'NoTelp' => $userLoggedIn['NoTelp'],
                    'Alamat' => $userLoggedIn['Alamat'],
                    'TglLahir' => $userLoggedIn['TglLahir'],
                    'Email' => $userLoggedIn['Email'],
                    'Photo' => $userLoggedIn['Photo'],
                    'Password' => $userLoggedIn['Password'],
                    'Status' => $userLoggedIn['Status'],
                    'admin' => true,
                    'status_login' => true
                ];
            } else {
                // Coba login sebagai customer
                $userLoggedIn = $this->User_Model->logincustomer($_POST['Username'], $_POST['Password']);
                if ($userLoggedIn) {
                    $data = [
                        'IdCustomer' => $userLoggedIn['IdCustomer'],
                        'Username' => $userLoggedIn['Username'],
                        'NamaLengkap' => $userLoggedIn['NamaLengkap'],
                        'NoTelp' => $userLoggedIn['NoTelp'],
                        'Alamat' => $userLoggedIn['Alamat'],
                        'TglLahir' => $userLoggedIn['TglLahir'],
                        'Email' => $userLoggedIn['Email'],
                        'Photo' => $userLoggedIn['Photo'],
                        'Password' => $userLoggedIn['Password'],
                        'Status' => $userLoggedIn['Status'],
                        'admin' => false,
                        'customer' => true,
                        'status_login' => true
                    ];
                } else {
                    // Coba login sebagai agen
                    $userLoggedIn = $this->User_Model->loginagen($_POST['Username'], $_POST['Password']);
                    if ($userLoggedIn) {
                        $data = [
                            'IdAgen' => $userLoggedIn['IdAgen'],
                            'Username' => $userLoggedIn['Username'],
                            'Password' => $userLoggedIn['Password'],
                            'Nama' => $userLoggedIn['Nama'],
                            'NoTelp' => $userLoggedIn['NoTelp'],
                            'Email' => $userLoggedIn['Email'],
                            'TglLahir' => $userLoggedIn['TglLahir'],
                            'KotaKelahiran' => $userLoggedIn['KotaKelahiran'],
                            'Pendidikan' => $userLoggedIn['Pendidikan'],
                            'NamaSekolah' => $userLoggedIn['NamaSekolah'],
                            'MasaKerja' => $userLoggedIn['MasaKerja'],
                            'Jabatan' => $userLoggedIn['Jabatan'],
                            'AlamatDomisili' => $userLoggedIn['AlamatDomisili'],
                            'Facebook' => $userLoggedIn['Facebook'],
                            'Instagram' => $userLoggedIn['Instagram'],
                            'NoKtp' => $userLoggedIn['NoKtp'],
                            'ImgKtp' => $userLoggedIn['ImgKtp'],
                            'ImgTtd' => $userLoggedIn['ImgTtd'],
                            'Npwp' => $userLoggedIn['Npwp'],
                            'Photo' => $userLoggedIn['Photo'],
                            'Poin' => $userLoggedIn['Poin'],
                            'Status' => $userLoggedIn['Status'],
                            'IsAkses' => $userLoggedIn['IsAkses'],
                            'admin' => false,
                            'customer' => false,
                            'agen' => true,
                            'status_login' => true
                        ];
                    } else {
                        // Jika semua login gagal
                        $data = [
                            'message' => 'Username atau password salah',
                            'status_login' => false
                        ];
                    }
                }
            }
            
            // Set header JSON
            header('Content-Type: application/json');
            $json = json_encode($data, JSON_PRETTY_PRINT);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Log JSON error
                error_log('JSON encoding error: ' . json_last_error_msg());
                $json = json_encode(['message' => 'Internal server error'], JSON_PRETTY_PRINT);
            }
            echo $json;
        } else {
            // Permintaan tidak valid
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Invalid request'], JSON_PRETTY_PRINT);
        }
    }

    public function UbahSandiAdmin(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'Password' =>  md5($this->input->post('NewPassword')),
            );
            $where = array('IdAdmin' => $this->input->post('IdAdmin'),);
            $this->User_Model->update_data($where,$data,'admin');
            echo json_encode($data);
        }
    }

    public function UbahSandiAgen(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'Password' =>  md5($this->input->post('NewPassword')),
            );
            $where = array('IdAgen' => $this->input->post('IdAgen'),);
            $this->User_Model->update_data($where,$data,'agen');
            echo json_encode($data);
        }
    }

    public function UbahSandiCustomer(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'Password' =>  md5($this->input->post('NewPassword')),
            );
            $where = array('IdCustomer' => $this->input->post('IdCustomer'),);
            $this->User_Model->update_data($where,$data,'customer');
            echo json_encode($data);
        }
    }

    public function Register(){
        $data = array(
            'Username' => $this->input->post('Username'),
            'NamaLengkap' => $this->input->post('NamaLengkap'),
            'NoTelp' => $this->input->post('NoTelp'),
            'Email' => $this->input->post('Email'),
            'Password' => md5($this->input->post('Password')),
        );
        $this->User_Model->input_data($data,'customer');
        unset($data['Username'], $data['Password']);
        echo json_encode($array);
    }

    public function UpdateAkun(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
            'Username' => $this->input->post('Username'),
            'NamaLengkap' => $this->input->post('NamaLengkap'),
            'NoTelp' => $this->input->post('NoTelp'),
            'Email' => $this->input->post('Email'),
        );
        $where = array('IdCustomer' => $this->input->post('IdCustomer'),);
        $this->User_Model->update_data($where,$data,'customer');
        echo json_encode($data);
        }
    }

    public function RegisterAgen(){
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();

            $data = array(
                'Username' => $this->input->post('Username'),
                'Password' => md5($this->input->post('Password')),
                'Nama' => $this->input->post('Nama'),
                'NoTelp' => $this->input->post('NoTelp'),
                'Email' => $this->input->post('Email'),
                'TglLahir' => $this->input->post('TglLahir'),
                'KotaKelahiran' => $this->input->post('KotaKelahiran'),
                'Pendidikan' => $this->input->post('Pendidikan'),
                'NamaSekolah' => $this->input->post('NamaSekolah'),
                'MasaKerja' => $this->input->post('MasaKerja'),
                'Jabatan' => $this->input->post('Jabatan'),
                'Konfirmasi' => $this->input->post('Konfirmasi'),
                'AlamatDomisili' => $this->input->post('AlamatDomisili'),
                'Facebook' => $this->input->post('Facebook'),
                'Instagram' => $this->input->post('Instagram'),
                'Npwp' => $this->input->post('Npwp'),
                'NoKtp' => $this->input->post('NoKtp'),
                'ImgKtp' => $this->input->post('ImgKtp'),
                'Photo' => $this->input->post('Photo'),
                'Status' => 3,
                'IsAkses' => 1,
            );
            $this->db->insert('agen',$data);
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

    public function UpdateAgen(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'Username' => $this->input->post('Username'),
                'Nama' => $this->input->post('Nama'),
                'NoTelp' => $this->input->post('NoTelp'),
                'Email' => $this->input->post('Email'),
                'AlamatDomisili' => $this->input->post('AlamatDomisili'),
                'Facebook' => $this->input->post('Facebook'),
                'Instagram' => $this->input->post('Instagram'),
            );
            $where = array('IdAgen' => $this->input->post('IdAgen'),);
            $this->User_Model->update_data($where,$data,'agen');
            echo json_encode($data);
        }
    }

    public function RegisterMitra(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $target  = "gambar/profile";
            $agen = $this->input->post('Nama');
            $pasfoto  = $this->input->post('Photo');

            $data = array(
                'Username' => $this->input->post('Username'),
                'Password' => md5($this->input->post('Password')),
                'Nama' => $this->input->post('Nama'),
                'NoTelp' => $this->input->post('NoTelp'),
                'Email' => $this->input->post('Email'),
                'TglLahir' => $this->input->post('TglLahir'),
                'KotaKelahiran' => $this->input->post('KotaKelahiran'),
                'Status' => 3,
                'IsAkses' => 2,
            );
            $this->db->insert('agen',$data);
            $idmitra = $this->db->insert_id();
            unset($data['Username'], $data['Password']);
            echo json_encode($data); 
            
            if ($pasfoto == 0) {
                $gambar = 0;
				$data = array(
                    'Photo' => $gambar,
				);
				$where = array('IdAgen' => $idmitra,);
				$this->User_Model->update_data($where,$data,'agen');
				echo json_encode($data);
            } else {
                $targetpasfoto = $target."/".$agen."_".uniqid().".jpeg";
                $gambarpasfoto = "https://app.gooproper.com/".$targetpasfoto;
                
                $config['image_library'] = 'gd2';
                $config['source_image'] = $gambarpasfoto;
                $config['quality'] = 50;
                
				if(file_put_contents($targetpasfoto, base64_decode($pasfoto))){
				    $this->load->library('image_lib', $config);
                    if (!$this->image_lib->resize()) {
                        echo $this->image_lib->display_errors();
                    } else {
                        $data = array(
                            'Photo'=> $gambarpasfoto,
                        );
                        $where = array('IdAgen' => $idmitra,);
                        $this->User_Model->update_data($where,$data,'agen');
                        echo json_encode($data);
                    } 
				} else {
					echo json_encode([
						"Message" => "Gagal",
						"Status" => "Error"
						]);
				}  
            }
        }
    }

    public function UpdateMitra(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
            'Username' => $this->input->post('Username'),
            'Nama' => $this->input->post('Nama'),
            'NoTelp' => $this->input->post('NoTelp'),
            'Email' => $this->input->post('Email'),
            'TglLahir' => $this->input->post('TglLahir'),
            'KotaKelahiran' => $this->input->post('KotaKelahiran'),
        );
        $where = array('IdAgen' => $this->input->post('IdAgen'),);
        $this->User_Model->update_data($where,$data,'agen');
        echo json_encode($data);
        }
    }

    public function RegisterKl(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $target  = "gambar/profile";
            $agen = $this->input->post('Nama');
            $pasfoto  = $this->input->post('Photo');

            $data = array(
                'Username' => $this->input->post('Username'),
                'Password' => md5($this->input->post('Password')),
                'Nama' => $this->input->post('Nama'),
                'NoTelp' => $this->input->post('NoTelp'),
                'Email' => $this->input->post('Email'),
                'Npwp' => $this->input->post('Npwp'),
                'Photo' => $gambar,
                'Status' => 3,
                'IsAkses' => 3,
            );
            $this->db->insert('agen',$data);
            $idkl = $this->db->insert_id();
            unset($data['Username'], $data['Password']);
            echo json_encode($data); 
            
            if ($pasfoto == 0) {
                $gambar = 0;
				$data = array(
                    'Photo' => $gambar,
				);
				$where = array('IdAgen' => $idkl,);
				$this->User_Model->update_data($where,$data,'agen');
				echo json_encode($data);
            } else {
                $targetpasfoto = $target."/".$agen."_".uniqid().".jpeg";
                $gambarpasfoto = "https://app.gooproper.com/".$targetpasfoto;
                
                $config['image_library'] = 'gd2';
                $config['source_image'] = $gambarpasfoto;
                $config['quality'] = 50;
                
				if(file_put_contents($targetpasfoto, base64_decode($pasfoto))){
				    $this->load->library('image_lib', $config);
                    if (!$this->image_lib->resize()) {
                        echo $this->image_lib->display_errors();
                    } else {
                        $data = array(
                            'Photo'=> $gambarpasfoto,
                        );
                        $where = array('IdAgen' => $idkl,);
                        $this->User_Model->update_data($where,$data,'agen');
                        echo json_encode($data);
                    } 
				} else {
					echo json_encode([
						"Message" => "Gagal",
						"Status" => "Error"
						]);
				}  
            }
        }
    }

    public function UpdateKl(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
            'Username' => $this->input->post('Username'),
            'Password' => md5($this->input->post('Password')),
            'Nama' => $this->input->post('Nama'),
            'NoTelp' => $this->input->post('NoTelp'),
            'Email' => $this->input->post('Email'),
            'Npwp' => $this->input->post('Npwp'),
        );
        $where = array('IdAgen' => $this->input->post('IdAgen'),);
        $this->User_Model->update_data($where,$data,'agen');
        echo json_encode($data);
        }
    }

    public function CekAktif(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getAktif($idagen);
        echo json_encode($data);
    }

    public function CekOfficer(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getOfficer($idagen);
        echo json_encode($data);
    }
    
    // Admin ========================================================================================================================================================================================
        
        // Update --------------------------------------------------------------
    
    public function ApproveAgen(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'Approve' => 1,
            );
            $where = array('IdAgen' => $this->input->post('IdAgen'),);
            $this->User_Model->update_data($where,$data,'agen');
            echo json_encode($data);
            
            $currentDate = date('Y-m-d');
            list($year, $month, $day) = explode('-', $currentDate);
            $year = substr($year, -2);
            $dateFormatted = $day . $month . $year;
    
            $add = array(
                'IdAgen' => $this->input->post('IdAgen'),
                'Nama' => $this->input->post('Nama'),
                'Posisi' => "Agen",
                'Kode' => 600,
                'TglMasuk' => $dateFormatted,
                );
            $this->db->insert('karyawan',$add);
			$nourut = $this->db->insert_id();
			echo json_encode($add);
		
			$urut = array(
					'NoKaryawan'=> "600-".$dateFormatted."-".$nourut,	
				);
			$where = array('IdKaryawan' => $nourut,);
			$this->User_Model->update_data($where,$urut,'karyawan');
			echo json_encode($urut); 
			
			$agenkode = "agen600";
		
			$pas = array(
					'Password'=> md5($agenkode.$nourut),	
				);
			$where = array('IdAgen' => $this->input->post('IdAgen'),);
			$this->User_Model->update_data($where,$pas,'agen');
			echo json_encode($pas); 
			
        }
    }
    
    public function RejectAgen(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'Reject' => 1,
            );
            $where = array('IdAgen' => $this->input->post('IdAgen'),);
            $this->User_Model->update_data($where,$data,'agen');
            echo json_encode($data);
			
        }
    }
    
        // Count ---------------------------------------------------------------
    
    public function CountPelamar(){
        $data = $this->User_Model->countPelamar();
        echo json_encode($data);
    }
    
    // Info =========================================================================================================================================================================================
    
    public function AddDataInfo(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
            
            $this->db->trans_start();
			
			$data = array(
				'IdAgen' => $this->input->post('IdAgen'),
                'JenisProperty' => $this->input->post('JenisProperty'),
                'StatusProperty' => $this->input->post('StatusProperty'),
                'StatusNarahubung' => $this->input->post('StatusNarahubung'),
                'Narahubung' => $this->input->post('Narahubung'),
                'NoTelp' => $this->input->post('NoTelp'),
                'LTanah' => $this->input->post('LuasTanah'),
                'LBangunan' => $this->input->post('LuasBangunan'),
                'Keterangan' => $this->input->post('Keterangan'),
                'Harga' => $this->input->post('HargaJual'),
                'HargaSewa' => $this->input->post('HargaSewa'),
                'Lokasi' => $this->input->post('Lokasi'),
                'Alamat' => $this->input->post('Alamat'),
                'Latitude' => $this->input->post('Latitude'),
                'Longitude' => $this->input->post('Longitude'),
                'ImgSelfie' => $this->input->post('ImgSelfie'),
                'ImgProperty' => $this->input->post('ImgProperty'),
                'IsSpek' => $this->input->post('IsSpek'),
                'TglInput' => $currentDate,
				'JamInput' => $currentTime,
				'IsListing' => 0,
			);
			$this->db->insert('infoproperty',$data);
			
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
    
    public function TambahInfo(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
            
            $this->db->trans_start();
			
			$data = array(
				'IdAgen' => $this->input->post('IdAgen'),
                'JenisProperty' => $this->input->post('JenisProperty'),
                'StatusProperty' => $this->input->post('StatusProperty'),
                'Narahubung' => $this->input->post('Narahubung'),
                'NoTelp' => $this->input->post('NoTelp'),
                'LTanah' => $this->input->post('LuasTanah'),
                'LBangunan' => $this->input->post('LuasBangunan'),
                'Keterangan' => $this->input->post('Keterangan'),
                'Harga' => $this->input->post('HargaJual'),
                'HargaSewa' => $this->input->post('HargaSewa'),
                'Lokasi' => $this->input->post('Lokasi'),
                'Alamat' => $this->input->post('Alamat'),
                'Latitude' => $this->input->post('Latitude'),
                'Longitude' => $this->input->post('Longitude'),
                'ImgSelfie' => $this->input->post('ImgSelfie'),
                'ImgProperty' => $this->input->post('ImgProperty'),
                'IsSpek' => $this->input->post('IsSpek'),
                'TglInput' => $currentDate,
				'JamInput' => $currentTime,
				'IsListing' => 0,
			);
			$this->db->insert('infoproperty',$data);
			
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
    
    public function EditInfo(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
			
			$data = array(
                'JenisProperty' => $this->input->post('JenisProperty'),
                'StatusProperty' => $this->input->post('StatusProperty'),
                'Narahubung' => $this->input->post('Narahubung'),
                'NoTelp' => $this->input->post('NoTelp'),
                'LTanah' => $this->input->post('LuasTanah'),
                'LBangunan' => $this->input->post('LuasBangunan'),
                'Keterangan' => $this->input->post('Keterangan'),
                'Harga' => $this->input->post('HargaJual'),
                'HargaSewa' => $this->input->post('HargaSewa'),
                'Lokasi' => $this->input->post('Lokasi'),
                'Alamat' => $this->input->post('Alamat'),
                'Latitude' => $this->input->post('Latitude'),
                'Longitude' => $this->input->post('Longitude'),
                'ImgSelfie' => $this->input->post('ImgSelfie'),
                'ImgProperty' => $this->input->post('ImgProperty'),
				'IsListing' => 0,
			);
            $where = array('IdInfo' => $this->input->post('IdInfo'),);
			$this->User_Model->update_data($where,$data,'infoproperty');
			
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
    
    public function TambahDetailInfo(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
            
            $this->db->trans_start();
            
            $data = array(
                'IsListing' => 1,
			);
            $where = array('IdShareLokasi' => $this->input->post('IdShareLokasi'),);
			$this->User_Model->update_data($where,$data,'sharelokasi');
			
			$data = array(
				'IdAgen' => $this->input->post('IdAgen'),
                'JenisProperty' => $this->input->post('JenisProperty'),
                'StatusProperty' => $this->input->post('StatusProperty'),
                'Narahubung' => $this->input->post('Narahubung'),
                'NoTelp' => $this->input->post('NoTelp'),
                'LTanah' => $this->input->post('LuasTanah'),
                'LBangunan' => $this->input->post('LuasBangunan'),
                'Keterangan' => $this->input->post('Keterangan'),
                'Harga' => $this->input->post('HargaJual'),
                'HargaSewa' => $this->input->post('HargaSewa'),
                'Lokasi' => $this->input->post('Lokasi'),
                'Alamat' => $this->input->post('Alamat'),
                'Latitude' => $this->input->post('Latitude'),
                'Longitude' => $this->input->post('Longitude'),
                'ImgSelfie' => $this->input->post('ImgSelfie'),
                'ImgProperty' => $this->input->post('ImgProperty'),
                'IsSpek' => $this->input->post('IsSpek'),
                'TglInput' => $currentDate,
				'JamInput' => $currentTime,
				'IsListing' => 0,
			);
			$this->db->insert('infoproperty',$data);
			
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
    
    public function UpdateInfo(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
			
			$data = array(
                'LTanah' => $this->input->post('LuasTanah'),
                'LBangunan' => $this->input->post('LuasBangunan'),
                'Keterangan' => $this->input->post('Keterangan'),
                'Harga' => $this->input->post('HargaJual'),
                'HargaSewa' => $this->input->post('HargaSewa'),
                'IsSpek' => 1,
			);
            $where = array('IdInfo' => $this->input->post('IdInfo'),);
			$this->User_Model->update_data($where,$data,'infoproperty');
			
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
    
    public function HideInfo(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
			
			$data = array(
				'IsHide' => 1,
			);
            $where = array('IdInfo' => $this->input->post('IdInfo'),);
			$this->User_Model->update_data($where,$data,'infoproperty');
			
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
    
    public function GetInfo(){
        $data = $this->User_Model->getInfo();
        echo json_encode($data);
    }
    
    public function GetInfoAgen(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getInfoAgen($idagen);
        echo json_encode($data);
    }
    
    public function GetAgenInfo(){
        $idco = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getAgenInfo($idco);
        echo json_encode($data);
    }
    
    public function GetLaporanInfo(){
        $data = $this->User_Model->getLaporanInfo();
        echo json_encode($data);
    }
    
    // Share Lokasi ==================================================================================================================================================================================
    
    public function Sharelokasi(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
			
			$data = array(
			    'IdAgen' => $this->input->post('IdAgen'),	
			    'Alamat' => $this->input->post('Alamat'),
			    'Lokasi' => $this->input->post('Location'),
			    'Latitude' => $this->input->post('Latitude'),
			    'Longitude' => $this->input->post('Longitude'),
			    'Selfie' => $this->input->post('Selfie'),
			    'IsListing' => 0,
			);
			$this->db->insert('sharelokasi',$data);
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
    
    public function GetShareLokasi(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getShareLokasi($idagen);
        echo json_encode($data);
    }
    
    // Pra Listing ==================================================================================================================================================================================
        
        // Add -----------------------------------------------------------------
        
    public function AddDataPraListing(){
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
    
    public function AddDataPraListingLokasi(){
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
                'Alamat' => $this->input->post('Alamat'),
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
				'Wilayah'=> $this->input->post('Wilayah'),
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
			
			$share = array(
                'IsListing' => 1,
			);
			$where = array('IdShareLokasi' => $this->input->post('IdShareLokasi'),);
    		$this->User_Model->update_data($where,$share,'sharelokasi');
			
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
    
    public function AddDataPraListingInfo(){
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
                'Alamat' => $this->input->post('Alamat'),
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
				'Wilayah'=> $this->input->post('Wilayah'),
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
			
			$info = array(
                'IsListing' => 1,
                'IsAgen' => $this->input->post('IdAgen'),
			);
			$where = array('IdInfo' => $this->input->post('IdInfo'),);
    		$this->User_Model->update_data($where,$info,'infoproperty');
			
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
    
        //----------------------------------------------------------------------
    
    public function PraListing(){
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
				'MetaNamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),	
				'AlamatTemplate'=> $this->input->post('AlamatTemplate'),
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
				'Wilayah' => $this->input->post('Wilayah'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
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
                'Img9'=> 0,
                'Img10'=> 0,
                'Img11'=> 0,
                'Img12'=> 0,
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
    
    public function PraListingBaru(){
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
				'MetaNamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),	
				'AlamatTemplate'=> $this->input->post('AlamatTemplate'),
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
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
    
    public function PraListingLokasi(){
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
                'Alamat' => $this->input->post('Alamat'),
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
				'MetaNamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),	
				'AlamatTemplate'=> $this->input->post('AlamatTemplate'),
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
				'Wilayah'=> $this->input->post('Wilayah'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
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
                'Img9'=> 0,
                'Img10'=> 0,
                'Img11'=> 0,
                'Img12'=> 0,
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
			
			$share = array(
                'IsListing' => 1,
			);
			$where = array('IdShareLokasi' => $this->input->post('IdShareLokasi'),);
    		$this->User_Model->update_data($where,$share,'sharelokasi');
			
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
    
    public function PraListingLokasiBaru(){
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
                'Alamat' => $this->input->post('Alamat'),
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
				'MetaNamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),	
				'AlamatTemplate'=> $this->input->post('AlamatTemplate'),
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
				'Wilayah'=> $this->input->post('Wilayah'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
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
			
			$share = array(
                'IsListing' => 1,
			);
			$where = array('IdShareLokasi' => $this->input->post('IdShareLokasi'),);
    		$this->User_Model->update_data($where,$share,'sharelokasi');
			
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
    
    public function PraListingFinalLokasiBaru(){
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
                'Alamat' => $this->input->post('Alamat'),
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
				'MetaNamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),	
				'AlamatTemplate'=> $this->input->post('AlamatTemplate'),
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
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
                'Img9'=> 0,
                'Img10'=> 0,
                'Img11'=> 0,
                'Img12'=> 0,
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
			
			$share = array(
                'IsListing' => 1,
			);
			$where = array('IdShareLokasi' => $this->input->post('IdShareLokasi'),);
    		$this->User_Model->update_data($where,$share,'sharelokasi');
			
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
    
    public function PraListingInfo(){
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
                'Alamat' => $this->input->post('Alamat'),
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
				'MetaNamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),	
				'AlamatTemplate'=> $this->input->post('AlamatTemplate'),
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
				'Wilayah'=> $this->input->post('Wilayah'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
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
                'Img9'=> 0,
                'Img10'=> 0,
                'Img11'=> 0,
                'Img12'=> 0,
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
			
			$info = array(
                'IsListing' => 1,
                'IsAgen' => $this->input->post('IdAgen'),
			);
			$where = array('IdInfo' => $this->input->post('IdInfo'),);
    		$this->User_Model->update_data($where,$info,'infoproperty');
			
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
    
    public function PraListingInfoBaru(){
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
                'Alamat' => $this->input->post('Alamat'),
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
				'MetaNamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),	
				'AlamatTemplate'=> $this->input->post('AlamatTemplate'),
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
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
			
			$info = array(
                'IsListing' => 1,
                'IsAgen' => $this->input->post('IdAgen'),
			);
			$where = array('IdInfo' => $this->input->post('IdInfo'),);
    		$this->User_Model->update_data($where,$info,'infoproperty');
			
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
    
    public function UpdateDataPraListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
				'NamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),
				'AlamatTemplate'=> $this->input->post('AlamatTemplate'),
				'Wilayah' => $this->input->post('Wilayah'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
				'Prabot'=> $this->input->post('Prabot'),	
				'KetPrabot'=> $this->input->post('KetPrabot'),	
				'Priority'=> $this->input->post('Priority'),
				'Banner'=> $this->input->post('Banner'),
				'Size'=> $this->input->post('Size'),
				'Harga'=> $this->input->post('Harga'),	
				'HargaSewa'=> $this->input->post('HargaSewa'),
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
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function UpdateDataLokasiPralisting(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $idpralisting = $this->input->post('IdPraListing');
            
			$data = array(
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'IsSelfie' => 1,
				'IsLokasi' => 1,
                'Selfie' => $this->input->post('Selfie'),
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function UpdateDataSelfiePraListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $idpralisting = $this->input->post('IdPraListing');
            
            $data = array(
				'IsSelfie' => 1,
				'Selfie' => $this->input->post('Selfie'),
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function UpdateDataBannerPralisting(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $idpralisting = $this->input->post('IdPraListing');
			
			$data = array(
				'Banner'=> $this->input->post('Banner'),
				'Size'=> $this->input->post('Size'),
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function UpdateDataPJPPralisting(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $idpralisting = $this->input->post('IdPraListing');
			
			$data = array(
				'ImgPjp'=> $this->input->post('ImgPjp'),
                'ImgPjp1'=> $this->input->post('ImgPjp1'),
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function UpdateDataGambarPralisting(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $idpralisting = $this->input->post('IdPraListing');
            
            $data = array(
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
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function UpdateDataColistPralisting(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $idpralisting = $this->input->post('IdPraListing');
			
			$data = array(
				'IdAgenCo'=> $this->input->post('IdAgenCo'),
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function UpdateDataMarketablePralisting(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $idpralisting = $this->input->post('IdPraListing');
			
			$data = array(
				'Marketable'=> 0,
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function UpdateDataStatusHargaPralisting(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $idpralisting = $this->input->post('IdPraListing');
			
			$data = array(
				'StatusHarga'=> 0,
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
        //----------------------------------------------------------------------
    
    public function UpdatePraListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
				'NamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),
				'AlamatTemplate'=> $this->input->post('AlamatTemplate'),
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
				'Prabot'=> $this->input->post('Prabot'),	
				'KetPrabot'=> $this->input->post('KetPrabot'),	
				'Priority'=> $this->input->post('Priority'),
				'Banner'=> $this->input->post('Banner'),
				'Size'=> $this->input->post('Size'),
				'Harga'=> $this->input->post('Harga'),	
				'HargaSewa'=> $this->input->post('HargaSewa'),
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
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function UpdatePraListingFinal(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
				'NamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),
				'AlamatTemplate'=> $this->input->post('AlamatTemplate'),
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
				'Prabot'=> $this->input->post('Prabot'),	
				'KetPrabot'=> $this->input->post('KetPrabot'),	
				'Priority'=> $this->input->post('Priority'),
				'Banner'=> $this->input->post('Banner'),
				'Size'=> $this->input->post('Size'),
				'Harga'=> $this->input->post('Harga'),	
				'HargaSewa'=> $this->input->post('HargaSewa'),
				'Img1'=> $this->input->post('Img1'),
                'Img2'=> $this->input->post('Img2'),
                'Img3'=> $this->input->post('Img3'),
                'Img4'=> $this->input->post('Img4'),
                'Img5'=> $this->input->post('Img5'),
                'Img6'=> $this->input->post('Img6'),
                'Img7'=> $this->input->post('Img7'),
                'Img8'=> $this->input->post('Img8'),
                'Img9'=> 0,
                'Img10'=> 0,
                'Img11'=> 0,
                'Img12'=> 0,
				'Video'=> $this->input->post('Video'),	
				'LinkFacebook'=> $this->input->post('LinkFacebook'),	
				'LinkTiktok'=> $this->input->post('LinkTiktok'),	
				'LinkInstagram'=> $this->input->post('LinkInstagram'),	
				'LinkYoutube'=> $this->input->post('LinkYoutube'),	
				'Fee'=> $this->input->post('Fee'),
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function UpdatePraListingAgenFinal(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
				'NamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
				'Prabot'=> $this->input->post('Prabot'),	
				'KetPrabot'=> $this->input->post('KetPrabot'),	
				'Priority'=> $this->input->post('Priority'),
				'Banner'=> $this->input->post('Banner'),
				'Size'=> $this->input->post('Size'),
				'Harga'=> $this->input->post('Harga'),	
				'HargaSewa'=> $this->input->post('HargaSewa'),
				'Img1'=> $this->input->post('Img1'),
                'Img2'=> $this->input->post('Img2'),
                'Img3'=> $this->input->post('Img3'),
                'Img4'=> $this->input->post('Img4'),
                'Img5'=> $this->input->post('Img5'),
                'Img6'=> $this->input->post('Img6'),
                'Img7'=> $this->input->post('Img7'),
                'Img8'=> $this->input->post('Img8'),
                'Img9'=> 0,
                'Img10'=> 0,
                'Img11'=> 0,
                'Img12'=> 0,
				'Video'=> $this->input->post('Video'),	
				'LinkFacebook'=> $this->input->post('LinkFacebook'),	
				'LinkTiktok'=> $this->input->post('LinkTiktok'),	
				'LinkInstagram'=> $this->input->post('LinkInstagram'),	
				'LinkYoutube'=> $this->input->post('LinkYoutube'),	
				'Fee'=> $this->input->post('Fee'),
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function UpdatePraListingAgenFinall(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
				'NamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
				'Prabot'=> $this->input->post('Prabot'),	
				'KetPrabot'=> $this->input->post('KetPrabot'),	
				'Priority'=> $this->input->post('Priority'),
				'Banner'=> $this->input->post('Banner'),
				'Size'=> $this->input->post('Size'),
				'Harga'=> $this->input->post('Harga'),	
				'HargaSewa'=> $this->input->post('HargaSewa'),
				'Img1'=> $this->input->post('Img1'),
                'Img2'=> $this->input->post('Img2'),
                'Img3'=> $this->input->post('Img3'),
                'Img4'=> $this->input->post('Img4'),
                'Img5'=> $this->input->post('Img5'),
                'Img6'=> $this->input->post('Img6'),
                'Img7'=> $this->input->post('Img7'),
                'Img8'=> $this->input->post('Img8'),
                'Img9'=> 0,
                'Img10'=> 0,
                'Img11'=> 0,
                'Img12'=> 0,
				'Video'=> $this->input->post('Video'),	
				'LinkFacebook'=> $this->input->post('LinkFacebook'),	
				'LinkTiktok'=> $this->input->post('LinkTiktok'),	
				'LinkInstagram'=> $this->input->post('LinkInstagram'),	
				'LinkYoutube'=> $this->input->post('LinkYoutube'),	
				'Fee'=> $this->input->post('Fee'),
				'Marketable' => $this->input->post('IsMarketable'),
				'StatusHarga' => $this->input->post('IsHarga'),
                'IsSelfie' => $this->input->post('IsSelfie'),
                'IsLokasi' => $this->input->post('IsLokasi'),
                'Selfie' => $this->input->post('Selfie'),
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function UpdatePraListingAgen(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
				'NamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
				'Prabot'=> $this->input->post('Prabot'),	
				'KetPrabot'=> $this->input->post('KetPrabot'),	
				'Priority'=> $this->input->post('Priority'),
				'Banner'=> $this->input->post('Banner'),
				'Size'=> $this->input->post('Size'),
				'Harga'=> $this->input->post('Harga'),	
				'HargaSewa'=> $this->input->post('HargaSewa'),
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
				'Marketable' => $this->input->post('IsMarketable'),
				'StatusHarga' => $this->input->post('IsHarga'),
                'IsSelfie' => $this->input->post('IsSelfie'),
                'IsLokasi' => $this->input->post('IsLokasi'),
                'Selfie' => $this->input->post('Selfie'),
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function UpdateMapsPraListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $target = "gambar/selfie";
            
			$idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Selfie'=> $this->input->post('Selfie'),
                'IsLokasi' => 1,
                'IsSelfie' => 1,
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function UpdateSelfiePraListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $target = "gambar/selfie";
            
			$idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
                'IsSelfie' => 1,
				'Selfie'=> $this->input->post('Selfie'),
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
        // Delete --------------------------------------------------------------
    
    public function DeletePraListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $where = array('IdPraListing' => $this->input->post('IdPraListing'),);
            $result = $this->User_Model->hapus_data($where,'pralisting');
            
            if ($result) {
                echo json_encode([
					"Message" => "Berhasil",
					"Status" => "Sukses"
					]);
            } else {
                echo json_encode([
    				"Message" => "Gagal",
    				"Status" => "Error"
    				]);
            }
        }
    }
    
        // Get -----------------------------------------------------------------
        
    public function GetDataPraListingForAdmin(){
        $data = $this->User_Model->getDataPraListingForAdmin();
        echo json_encode($data);
    }
    
    public function GetDataPraListingForManager(){
        $data = $this->User_Model->getDataPraListingForManager();
        echo json_encode($data);
    }
        
        //----------------------------------------------------------------------
    
    public function GetPraListingAdmin(){
        $data = $this->User_Model->getPraListingAdmin();
        echo json_encode($data);
    }
    
    public function GetPraListingAdminBaru(){
        $data = $this->User_Model->getPraListingAdminBaru();
        echo json_encode($data);
    }
    
    public function GetPraListingManager(){
        $data = $this->User_Model->getPraListingManager();
        echo json_encode($data);
    }
    
    public function GetPraListingManagerBaru(){
        $data = $this->User_Model->getPraListingManagerBaru();
        echo json_encode($data);
    }
    
    public function GetPraListingAgen(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getPraListingAgen($idagen);
        echo json_encode($data);
    }
    
    public function GetPraListingTerdekat(){
        $data = $this->User_Model->getPraListingTerdekat();
        echo json_encode($data);
    }
    
    public function GetPraListingSurvey(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getPraListingSurvey($id);
        echo json_encode($data);
    }
    
    public function GetSurveyorPraListing(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getSurveyorPraListing($id);
        echo json_encode($data);
    }
    
    public function GetRejectedAgen(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getRejectedAgen($id);
        echo json_encode($data);
    }
    
    public function GetRejectedAdmin(){
        $data = $this->User_Model-> getPraListingRejected();
        echo json_encode($data);
    }
    
        // Approve -------------------------------------------------------------
	
	public function ApproveDataFromAdmin(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $currentDate = date('Y-m-d');
            
            $this->db->trans_start();
            
            $data = array(
                'IdAgen' => $this->input->post('IdAgen'),
                'Pjp' => $this->input->post('Pjp'),
                'IsAdmin' => 1,
            );
            
            $where = array('IdPraListing' => $this->input->post('IdPraListing'),);
            $this->User_Model->update_data($where,$data,'pralisting');
            echo json_encode($data);
        }
    }
    
    public function ApproveDataFromManager(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'IsManager' => 1,
            );
            $where = array('IdPraListing' => $this->input->post('IdPraListing'),);
            $this->User_Model->update_data($where,$data,'pralisting');
            $updatedRows = $this->db->affected_rows();
            
            if ($updatedRows > 0) {
                $idpralisting = $this->input->post('IdPraListing');
                $newId = $this->User_Model->addListing($idpralisting);
                
                if($newId){
                    $dataup = array(
                        'IdListing' => $newId,
                    );
                    $whereup = array('IdListing' => $this->input->post('IdPraListing'));
                    $this->User_Model->update_data($whereup,$dataup,'template');
                    $updatedRows = $this->db->affected_rows();
                    
                    if ($updatedRows > 0) {
                        $this->db->trans_complete();
                        echo json_encode(array('status' => 'success', 'message' => 'success'));
                    } else {
                        $this->db->trans_rollback();
                        echo json_encode(array('status' => 'error', 'message' => 'gagal'));
                    }
                } else {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => 'error', 'message' => 'Error updating data.'));
                }
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 'error', 'message' => 'Error updating data.'));
            }
        }
    }
    
        //----------------------------------------------------------------------
	
    public function ApproveAdmin(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $currentDate = date('Y-m-d');
            
            $this->db->trans_start();
            
            $data = array(
                'IdAgen' => $this->input->post('IdAgen'),
                'Pjp' => $this->input->post('Pjp'),
                'IsAdmin' => 1,
                'Marketable' => $this->input->post('Marketable'),
                'StatusHarga' => $this->input->post('StatusHarga'),
                'IsSelfie' => $this->input->post('IsSelfie'),
                'IsLokasi' => $this->input->post('IsLokasi'),
            );
            
            $where = array('IdPraListing' => $this->input->post('IdPraListing'),);
            $this->User_Model->update_data($where,$data,'pralisting');
            $updatedRows = $this->db->affected_rows();
            
            if ($updatedRows > 0) {
                $idpralisting = $this->input->post('IdPraListing');
                $newId = $this->User_Model->addListing($idpralisting);
                
                if($newId){
                    $dataup = array(
                        'IdListing' => $newId,
                    );
                    $whereup = array('IdListing' => $this->input->post('IdPraListing'));
                    $this->User_Model->update_data($whereup,$dataup,'template');
                    $updatedRows = $this->db->affected_rows();
                    
                    if ($updatedRows > 0) {
                        $this->db->trans_complete();
                        echo json_encode(array('status' => 'success', 'message' => 'success'));
                    } else {
                        $this->db->trans_rollback();
                        echo json_encode(array('status' => 'error', 'message' => 'gagal'));
                    }
                } else {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => 'error', 'message' => 'Error updating data.'));
                }
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 'error', 'message' => 'Error updating data.'));
            }
        }
    }
	
    public function ApproveAdminfinal(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $currentDate = date('Y-m-d');
            
            $this->db->trans_start();
            
            $data = array(
                'IdAgen' => $this->input->post('IdAgen'),
                'Pjp' => $this->input->post('Pjp'),
                'IsAdmin' => 1,
                'Marketable' => $this->input->post('Marketable'),
                'StatusHarga' => $this->input->post('StatusHarga'),
                'IsSelfie' => $this->input->post('IsSelfie'),
                'IsLokasi' => $this->input->post('IsLokasi'),
            );
            
            $where = array('IdPraListing' => $this->input->post('IdPraListing'),);
            $this->User_Model->update_data($where,$data,'pralisting');
            $updatedRows = $this->db->affected_rows();
            
            if ($updatedRows > 0) {
                $idpralisting = $this->input->post('IdPraListing');
                $newId = $this->User_Model->addListingFinal($idpralisting);
                
                if($newId){
                    $dataup = array(
                        'IdListing' => $newId,
                    );
                    $whereup = array('IdListing' => $this->input->post('IdPraListing'));
                    $this->User_Model->update_data($whereup,$dataup,'template');
                    $updatedRows = $this->db->affected_rows();
                    
                    if ($updatedRows > 0) {
                        $this->db->trans_complete();
                        echo json_encode(array('status' => 'success', 'message' => 'success'));
                    } else {
                        $this->db->trans_rollback();
                        echo json_encode(array('status' => 'error', 'message' => 'gagal'));
                    }
                } else {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => 'error', 'message' => 'Error updating data.'));
                }
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 'error', 'message' => 'Error updating data.'));
            }
        }
    }
    
    public function ApproveManager(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'IdAgen' => $this->input->post('IdAgen'),
                'IsManager' => 1,
                'Marketable' => $this->input->post('Marketable'),
                'StatusHarga' => $this->input->post('StatusHarga'),
                'IsSelfie' => $this->input->post('IsSelfie'),
                'IsLokasi' => $this->input->post('IsLokasi'),
            );
            $where = array('IdPraListing' => $this->input->post('IdPraListing'),);
            $this->User_Model->update_data($where,$data,'pralisting');
            echo json_encode($data);
        }
    }
    
    public function ApproveManagerFinal(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'IsManager' => 1,
                'Marketable' => $this->input->post('Marketable'),
                'StatusHarga' => $this->input->post('StatusHarga'),
                'IsSelfie' => $this->input->post('IsSelfie'),
                'IsLokasi' => $this->input->post('IsLokasi'),
            );
            $where = array('IdPraListing' => $this->input->post('IdPraListing'),);
            $this->User_Model->update_data($where,$data,'pralisting');
            echo json_encode($data);
        }
    }
    
        // Rejected ------------------------------------------------------------
    
    public function Rejected(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'IsRejected' => 1,
            );
            $where = array('IdPraListing' => $this->input->post('IdPraListing'));
            $this->User_Model->update_data($where,$data,'pralisting');
            $updatedRows = $this->db->affected_rows();
            if ($updatedRows > 0) {
                $ketrej = array(
        			'IdPralisting' => $this->input->post('IdPraListing'),
                    'Keterangan' => $this->input->post('Keterangan'),
        		);
        		$this->db->insert('keteranganreject',$ketrej);
                echo json_encode(array('status' => 'success', 'message' => 'Listing Rejected'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Error Rejected Listing.'));
            }
            echo json_encode($data);
        }
    }
    
    public function GetKeteranganRejected(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getKeteranganRejected($id);
        echo json_encode($data);
    }
    
    public function AjukanUlang(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'IsRejected' => 0,
            );
            $where = array('IdPraListing' => $this->input->post('IdPraListing'));
            $this->User_Model->update_data($where,$data,'pralisting');
            $updatedRows = $this->db->affected_rows();
            if ($updatedRows > 0) {
                echo json_encode(array('status' => 'success', 'message' => 'Ajukan Ulang Listing'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Gagal Ajukan Ulang Listing.'));
            }
            echo json_encode($data);
        }
    }
    
        // Template ------------------------------------------------------------
    
    public function UploadTemplate(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'IdListing' => $this->input->post('IdPraListing'),
                'Template' => $this->input->post('TemplateFull'),
                'TemplateBlank' => $this->input->post('TemplateBlank'),
            );
            $this->db->insert('template',$data);
            $updatedRows = $this->db->affected_rows();
            if ($updatedRows > 0) {
                echo json_encode(array('status' => 'success', 'message' => 'success'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'gagal'));
            }
            echo json_encode($data);
        }
    }
    
    public function UploadUpdateTemplate(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'IdTemplate' => $this->input->post('IdTemplate'),
                'Template' => $this->input->post('TemplateFull'),
                'TemplateBlank' => $this->input->post('TemplateBlank'),
            );
            $where = array('IdTemplate' => $this->input->post('IdTemplate'));
            $this->User_Model->update_data($where,$data,'template');
            $updatedRows = $this->db->affected_rows();
            if ($updatedRows > 0) {
                echo json_encode(array('status' => 'success', 'message' => 'success'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'gagal'));
            }
            echo json_encode($data);
        }
    }
    
        // Count ---------------------------------------------------------------
    
    public function CountPralistingTerdekat(){
        $data = $this->User_Model->countPralistingTerdekat();
        echo json_encode($data);
    }
    
    public function CountPralistingAdmin(){
        $data = $this->User_Model->countPralistingAdmin();
        echo json_encode($data);
    }
    
    public function CountPralistingManager(){
        $data = $this->User_Model->countPralistingManager();
        echo json_encode($data);
    }
    
    public function CountPralistingAgen(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->countPralistingAgen($id);
        echo json_encode($data);
    }
    
    public function CountPralistingAgenRejected(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->countPralistingAgenRejected($id);
        echo json_encode($data);
    }
    
    public function CountPralistingRejected(){
        $data = $this->User_Model->countPralistingRejected();
        echo json_encode($data);
    }
    
    // Listing =======================================================================================================================================================================================
    
        // Add -----------------------------------------------------------------
    
    public function AddViews(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $where = array('IdListing' => $this->input->post('IdListing'),);
            $sql = "UPDATE listing SET View = View + 1 WHERE IdListing = ?";
            $this->db->query($sql, $where);
        }
    }
	
    public function AddSeen(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'IdListing' => $this->input->post('IdListing'),
                'IdCustomer' => $this->input->post('IdCustomer'),
                'IdAgen' => $this->input->post('IdAgen'),
			);
			$this->db->insert('view',$data);
            echo json_encode($data);
        }
    }
	
    public function AddFavorite(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'IdCustomer' => $this->input->post('IdCustomer'),
                'IdAgen' => $this->input->post('IdAgen'),
                'IdListing' => $this->input->post('IdListing'),
			);
			$this->db->insert('favorite',$data);
            echo json_encode($data);
        }
    }
    
    public function TambahNoArsip(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $data = array(
                'NoArsip' => $this->input->post('NoArsip'),
            );
            $where = array('IdListing' => $this->input->post('IdListing'),);
            $this->User_Model->update_data($where,$data,'listing');
            
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
    
    public function TambahGambarListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $idlisting = $this->input->post('IdListing');
            
            $currentDate = date('Y-m-d H:i:s');
            
            $this->db->trans_start();
			
			$data = array(
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
			);
            $where = array('IdListing' => $this->input->post('IdListing'),);
    		$this->User_Model->update_data($where,$data,'listing');
    		
    		$susulan = array(
			    'IdListing' => $idlisting,
			    'Keterangan' => $this->input->post('Keterangan'),
			    'PoinTambahan' => $this->input->post('PoinTambahan'),
			    'PoinBerkurang' => $this->input->post('PoinBerkurang'),
			    'TglInput' => $currentDate,
			    'IsRead' => 0,
			);
			$this->db->insert('susulan',$susulan);
			
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
    
    public function AddPasangBanner(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $idlisting = $this->input->post('IdListing');
            
            $this->db->trans_start();
			
			$data = array(
				'IsPasangBanner'=> 1,
			);
			$where = array('IdListing' => $idlisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
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
    
    public function UpdateDataListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $idpralisting = $this->input->post('IdListing');
            
            $this->db->trans_start();
			
			$data = array(
				'NamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),
				'AlamatTemplate'=> $this->input->post('AlamatTemplate'),
				'Wilayah' => $this->input->post('Wilayah'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
				'Prabot'=> $this->input->post('Prabot'),	
				'KetPrabot'=> $this->input->post('KetPrabot'),	
				'Priority'=> $this->input->post('Priority'),
				'Banner'=> $this->input->post('Banner'),
				'Size'=> $this->input->post('Size'),
				'Harga'=> $this->input->post('Harga'),	
				'HargaSewa'=> $this->input->post('HargaSewa'),
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
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
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
    
    public function UpdateDataLokasiListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			$currentDate = date('Y-m-d H:i:s');
            
            $this->db->trans_start();
            
            $idpralisting = $this->input->post('IdListing');
            
			$data = array(
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'IsSelfie' => 1,
				'IsLokasi' => 1,
                'Selfie' => $this->input->post('Selfie'),
				'Pending'=> 1,
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
			if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo json_encode([
						"Message" => "Gagal",
						"Status" => "Error"
						]);
            } else {
                $susulan = array(
                    'IdListing' => $idpralisting,
                    'Keterangan' => $this->input->post('Keterangan'),
                    'PoinTambahan' => $this->input->post('PoinTambahan'),
                    'PoinBerkurang' => $this->input->post('PoinBerkurang'),
                    'TglInput' => $currentDate,
                    'IsRead' => 0,
                );
                $this->db->insert('susulan', $susulan);
                
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
    }
    
    public function UpdateDataSelfieListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			$currentDate = date('Y-m-d H:i:s');
            
            $this->db->trans_start();
            
            $idpralisting = $this->input->post('IdListing');
            
            $data = array(
				'IsSelfie' => 1,
				'Selfie' => $this->input->post('Selfie'),
				'Pending'=> 1,
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
			if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo json_encode([
						"Message" => "Gagal",
						"Status" => "Error"
						]);
            } else {
                $susulan = array(
                    'IdListing' => $idpralisting,
                    'Keterangan' => $this->input->post('Keterangan'),
                    'PoinTambahan' => $this->input->post('PoinTambahan'),
                    'PoinBerkurang' => $this->input->post('PoinBerkurang'),
                    'TglInput' => $currentDate,
                    'IsRead' => 0,
                );
                $this->db->insert('susulan', $susulan);
                
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
    }
    
    public function UpdateDataBannerListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			$currentDate = date('Y-m-d H:i:s');
            
            $this->db->trans_start();
            
            $idpralisting = $this->input->post('IdListing');
			
			$data = array(
				'Banner'=> $this->input->post('Banner'),
				'Size'=> $this->input->post('Size'),
				'Pending'=> 1,
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
			if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo json_encode([
						"Message" => "Gagal",
						"Status" => "Error"
						]);
            } else {
                $susulan = array(
                    'IdListing' => $idpralisting,
                    'Keterangan' => $this->input->post('Keterangan'),
                    'PoinTambahan' => $this->input->post('PoinTambahan'),
                    'PoinBerkurang' => $this->input->post('PoinBerkurang'),
                    'TglInput' => $currentDate,
                    'IsRead' => 0,
                );
                $this->db->insert('susulan', $susulan);
                
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
    }
    
    public function UpdateDataPJPListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			$currentDate = date('Y-m-d H:i:s');
            
            $this->db->trans_start();
            
            $idpralisting = $this->input->post('IdListing');
			
			$data = array(
				'ImgPjp'=> $this->input->post('ImgPjp'),
                'ImgPjp1'=> $this->input->post('ImgPjp1'),
				'Pending'=> 1,
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
			if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo json_encode([
						"Message" => "Gagal",
						"Status" => "Error"
						]);
            } else {
                $susulan = array(
                    'IdListing' => $idpralisting,
                    'Keterangan' => $this->input->post('Keterangan'),
                    'PoinTambahan' => $this->input->post('PoinTambahan'),
                    'PoinBerkurang' => $this->input->post('PoinBerkurang'),
                    'TglInput' => $currentDate,
                    'IsRead' => 0,
                );
                $this->db->insert('susulan', $susulan);
                
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
    }
    
    public function UpdateDataGambarListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			$currentDate = date('Y-m-d H:i:s');
            
            $this->db->trans_start();
            
            $idpralisting = $this->input->post('IdListing');
            
            $data = array(
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
				'Pending'=> 1,
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
			if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo json_encode([
						"Message" => "Gagal",
						"Status" => "Error"
						]);
            } else {
                $susulan = array(
                    'IdListing' => $idpralisting,
                    'Keterangan' => $this->input->post('Keterangan'),
                    'PoinTambahan' => $this->input->post('PoinTambahan'),
                    'PoinBerkurang' => $this->input->post('PoinBerkurang'),
                    'TglInput' => $currentDate,
                    'IsRead' => 0,
                );
                $this->db->insert('susulan', $susulan);
                
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
    }
    
    public function UpdateDataColistListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			$currentDate = date('Y-m-d H:i:s');
            
            $this->db->trans_start();
            
            $idpralisting = $this->input->post('IdListing');
			
			$data = array(
				'IdAgenCo'=> $this->input->post('IdAgenCo'),
				'Pending'=> 1,
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
			if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo json_encode([
						"Message" => "Gagal",
						"Status" => "Error"
						]);
            } else {
                $susulan = array(
                    'IdListing' => $idpralisting,
                    'Keterangan' => $this->input->post('Keterangan'),
                    'PoinTambahan' => $this->input->post('PoinTambahan'),
                    'PoinBerkurang' => $this->input->post('PoinBerkurang'),
                    'TglInput' => $currentDate,
                    'IsRead' => 0,
                );
                $this->db->insert('susulan', $susulan);
                
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
    }
    
        //----------------------------------------------------------------------
    
    public function UpdateListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $idlisting = $this->input->post('IdListing');
            
            $this->db->trans_start();
			
			$data = array(
				'NamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
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
                'Pjp'=> $this->input->post('Pjp'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
				'Prabot'=> $this->input->post('Prabot'),	
				'KetPrabot'=> $this->input->post('KetPrabot'),	
				'Priority'=> $this->input->post('Priority'),
				'Banner'=> $this->input->post('Banner'),
				'Size'=> $this->input->post('Size'),
				'Harga'=> $this->input->post('Harga'),	
				'HargaSewa'=> $this->input->post('HargaSewa'),
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
			);
			$where = array('IdListing' => $idlisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
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
    
    public function UpdateListingBaru(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $idlisting = $this->input->post('IdListing');
            
            $this->db->trans_start();
			
			$data = array(
				'NamaListing'=> $this->input->post('NamaListing'),	
				'Alamat'=> $this->input->post('Alamat'),
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
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
                'Pjp'=> $this->input->post('Pjp'),
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
				'Deskripsi'=> $this->input->post('Deskripsi'),	
				'Prabot'=> $this->input->post('Prabot'),	
				'KetPrabot'=> $this->input->post('KetPrabot'),	
				'Priority'=> $this->input->post('Priority'),
				'Banner'=> $this->input->post('Banner'),
				'Size'=> $this->input->post('Size'),
				'Harga'=> $this->input->post('Harga'),	
				'HargaSewa'=> $this->input->post('HargaSewa'),
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
			);
			$where = array('IdListing' => $idlisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
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
    
    public function UpdateMapsListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $target = "gambar/selfie";
            
			$idpralisting = $this->input->post('IdListing');
            
            $this->db->trans_start();
			
			$data = array(
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Selfie'=> $this->input->post('Selfie'),
				'IsSelfie' => 1,
                'IsLokasi' => 1,
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
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
    
    public function UpdateMapsListingFinal(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

			$idpralisting = $this->input->post('IdListing');
			
			$currentDate = date('Y-m-d H:i:s');
            
            $this->db->trans_start();
			
			$data = array(
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Selfie'=> $this->input->post('Selfie'),
				'IsSelfie' => 1,
                'IsLokasi' => 1,
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
    		
    		$susulan = array(
			    'IdListing' => $idpralisting,
			    'Keterangan' => $this->input->post('Keterangan'),
			    'TglInput' => $currentDate,
			    'IsRead' => 0,
			);
			$this->db->insert('susulan',$susulan);
			
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
    
    public function UpdateSelfieListing(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $target = "gambar/selfie";
            
			$idpralisting = $this->input->post('IdListing');
            
            $this->db->trans_start();
			
			$data = array(
				'Selfie'=> $this->input->post('Selfie'),
                'IsSelfie' => 1,
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
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
    
    public function UpdateSelfieListingFinal(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
			$idpralisting = $this->input->post('IdListing');
			
			$currentDate = date('Y-m-d H:i:s');
            
            $this->db->trans_start();
			
			$data = array(
				'Selfie'=> $this->input->post('Selfie'),
                'IsSelfie' => 1,
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
			$susulan = array(
			    'IdListing' => $idpralisting,
			    'Keterangan' => $this->input->post('Keterangan'),
			    'TglInput' => $currentDate,
			    'IsRead' => 0,
			);
			$this->db->insert('susulan',$susulan);
			
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
    
    public function ApprovePending(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idlisting = $this->input->post('IdListing');
            
            $this->db->trans_start();
			
			$data = array(
				'Pending'=> 0,
			);
			$where = array('IdListing' => $idlisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
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
    
    public function TambahBanner(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
			$idpralisting = $this->input->post('IdListing');
			
			$currentDate = date('Y-m-d H:i:s');
            
            $this->db->trans_start();
            
            $data = array(
				'Banner'=> $this->input->post('Banner'),
				'Size'=> $this->input->post('Size'),
				'Pending'=> 1,
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
			$susulan = array(
			    'IdListing' => $idpralisting,
			    'Keterangan' => $this->input->post('Keterangan'),
			    'PoinTambahan' => $this->input->post('PoinTambahan'),
			    'PoinBerkurang' => $this->input->post('PoinBerkurang'),
			    'TglInput' => $currentDate,
			    'IsRead' => 0,
			);
			$this->db->insert('susulan',$susulan);
			
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
    
    public function TambahCoList(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
			$idpralisting = $this->input->post('IdListing');
			
			$currentDate = date('Y-m-d H:i:s');
            
            $this->db->trans_start();
            
            $data = array(
				'IdAgenCo'=> $this->input->post('IdAgenCo'),
				'Pending'=> 1,
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
			$susulan = array(
			    'IdListing' => $idpralisting,
			    'Keterangan' => $this->input->post('Keterangan'),
			    'PoinTambahan' => $this->input->post('PoinTambahan'),
			    'PoinBerkurang' => $this->input->post('PoinBerkurang'),
			    'TglInput' => $currentDate,
			    'IsRead' => 0,
			);
			$this->db->insert('susulan',$susulan);
			
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
    
    public function TambahPjp(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
			$idpralisting = $this->input->post('IdListing');
			
			$currentDate = date('Y-m-d H:i:s');
            
            $this->db->trans_start();
            
            $data = array(
				'ImgPjp'=> $this->input->post('ImgPjp'),
                'ImgPjp1'=> $this->input->post('ImgPjp1'),
                'Pjp'=> $this->input->post('Pjp'),
				'Pending'=> 1,
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
			$susulan = array(
			    'IdListing' => $idpralisting,
			    'Keterangan' => $this->input->post('Keterangan'),
			    'PoinTambahan' => $this->input->post('PoinTambahan'),
			    'PoinBerkurang' => $this->input->post('PoinBerkurang'),
			    'TglInput' => $currentDate,
			    'IsRead' => 0,
			);
			$this->db->insert('susulan',$susulan);
			
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
    
    public function TambahSelfie(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
			$idpralisting = $this->input->post('IdListing');
			
			$currentDate = date('Y-m-d H:i:s');
            
            $this->db->trans_start();
            
            $data = array(
				'Selfie'=> $this->input->post('Selfie'),
                'IsSelfie' => 1,
				'Pending'=> 1,
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
			$susulan = array(
			    'IdListing' => $idpralisting,
			    'Keterangan' => $this->input->post('Keterangan'),
			    'PoinTambahan' => $this->input->post('PoinTambahan'),
			    'PoinBerkurang' => $this->input->post('PoinBerkurang'),
			    'TglInput' => $currentDate,
			    'IsRead' => 0,
			);
			$this->db->insert('susulan',$susulan);
			
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
    
    public function TambahMaps(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
			$idpralisting = $this->input->post('IdListing');
			
			$currentDate = date('Y-m-d H:i:s');
            
            $this->db->trans_start();
            
            $data = array(
				'Latitude'=> $this->input->post('Latitude'),
				'Longitude'=> $this->input->post('Longitude'),
				'Location'=> $this->input->post('Location'),
				'Selfie'=> $this->input->post('Selfie'),
				'IsSelfie' => 1,
                'IsLokasi' => 1,
				'Pending'=> 1,
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
			$susulan = array(
			    'IdListing' => $idpralisting,
			    'Keterangan' => $this->input->post('Keterangan'),
			    'PoinTambahan' => $this->input->post('PoinTambahan'),
			    'PoinBerkurang' => $this->input->post('PoinBerkurang'),
			    'TglInput' => $currentDate,
			    'IsRead' => 0,
			);
			$this->db->insert('susulan',$susulan);
			
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
    
    public function TambahWilayah(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
			$idpralisting = $this->input->post('IdListing');
			
			$currentDate = date('Y-m-d H:i:s');
            
            $this->db->trans_start();
            
            $data = array(
				'Wilayah'=> $this->input->post('Wilayah'),
			);
			$where = array('IdListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
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
    
        // Duplicate -----------------------------------------------------------
    
    public function ListingDouble(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idlisting = $this->input->post('IdListing');
            
            $this->db->trans_start();
			
			$data = array(	
				'IsDouble'=> 1,
			);
			$where = array('IdListing' => $idlisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
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
    
    public function ListingDelete(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idlisting = $this->input->post('IdListing');
            
            $this->db->trans_start();
			
			$data = array(	
				'IsDelete'=> 1,
			);
			$where = array('IdListing' => $idlisting,);
    		$this->User_Model->update_data($where,$data,'listing');
			
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
    
    public function TemplateDouble(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idlisting = $this->input->post('IdTemplate');
            
            $this->db->trans_start();
			
			$data = array(	
				'IdListing'=> 0,
			);
			$where = array('IdTemplate' => $idlisting,);
    		$this->User_Model->update_data($where,$data,'template');
			
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
    
        // GET -----------------------------------------------------------------
        
    public function GetDataListingBaru(){
        $page = $this->input->get('page');
        $pageSize = $this->input->get('pageSize');
    
        if (!$page) {
            $page = 1;
        }
    
        if (!$pageSize) {
            $pageSize = 10;
        }
    
        $result = $this->User_Model->getDataListingBaru($page, $pageSize);
        echo json_encode($result);
    }
    
    public function GetListing(){
        $data = $this->User_Model->getListing();
        echo json_encode($data);
    }
    
    public function GetListingSold(){
        $data = $this->User_Model->getListingSold();
        echo json_encode($data);
    }
    
    public function GetListingHot(){
        $data = $this->User_Model->getListingHot();
        echo json_encode($data);
    }
    
    public function GetListingNew(){
        $data = $this->User_Model->getListingTerbaru();
        echo json_encode($data);
    }
    
    public function GetListingAgen(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getListingAgen($idagen);
        echo json_encode($data);
    }
    
    public function GetListingDeep(){
        $idlisting = filter_var($_GET['idlisting'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getListingDeep($idlisting);
        echo json_encode($data);
    }
    
    public function GetListingPending(){
        $data = $this->User_Model->getListingPending();
        echo json_encode($data);
    }
    
    public function GetListingFinal(){
        $data = $this->User_Model->getListingFinal();
        echo json_encode($data);
    }
    
    public function GetListingSoldFinal(){
        $data = $this->User_Model->getListingSoldFinal();
        echo json_encode($data);
    }
    
    public function GetListingHotFinal(){
        $data = $this->User_Model->getListingHotFinal();
        echo json_encode($data);
    }
    
    public function GetListingNewFinal(){
        $data = $this->User_Model->getListingTerbaruFinal();
        echo json_encode($data);
    }
    
    public function GetListingAgenFinal(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getListingAgenFinal($idagen);
        echo json_encode($data);
    }
    
    public function GetListingDeepFinal(){
        $idlisting = filter_var($_GET['idlisting'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getListingDeepFinal($idlisting);
        echo json_encode($data);
    }
    
    public function GetListingSekitar(){
        $wilayah = filter_var($_GET['Wilayah'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $jenis = filter_var($_GET['Jenis'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $kondisi = filter_var($_GET['Kondisi'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getListingSekitar($wilayah, $jenis, $kondisi);
        echo json_encode($data);
    }
    
    public function GetListingTerkait(){
        $jenis = filter_var($_GET['Jenis'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $kondisi = filter_var($_GET['Kondisi'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getListingTerkait($jenis, $kondisi);
        echo json_encode($data);
    }
    
    public function GetCoListing(){
        $idco = filter_var($_GET['idco'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getCoListing($idco);
        echo json_encode($data);
    }
    
    public function GetLaporanListing(){
        $data = $this->User_Model->getLaporanListing();
        echo json_encode($data);
    }
    
    public function GetLaporanPraListing(){
        $data = $this->User_Model->getLaporanPraListing();
        echo json_encode($data);
    }
    
    public function GetFavorite(){
        $idcustomer = filter_var($_GET['idcustomer'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getFavorite($idcustomer);
        echo json_encode($data);
    }
    
    public function GetFavoriteAgen(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getFavoriteAgen($idagen);
        echo json_encode($data);
    }
    
    public function GetSeen(){
        $idcustomer = filter_var($_GET['idcustomer'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getSeen($idcustomer);
        echo json_encode($data);
    }
    
    public function GetSeenAgen(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getSeenAgen($idagen);
        echo json_encode($data);
    }
    
    public function GetSusulan(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getSusulan($id);
        echo json_encode($data);
    }
    
    public function GetIsCekLokasi(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getIsCekLokasi($id);
        echo json_encode($data);
    }
    
    public function GetNoArsip(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getNoArsip($id);
        echo json_encode($data);
    }
    
    public function GetTemplateDouble(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getTemplateDouble($id);
        echo json_encode($data);
    }
    
    public function GetListingPDF(){
        $idlisting = filter_var($_GET['idlisting'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getListingPDF($idlisting);
        echo json_encode($data);
    }
    
    public function GetPasangBanner(){
        $data = $this->User_Model->getPasangBanner();
        echo json_encode($data);
    }
    
        // Count ---------------------------------------------------------------
    
    public function CountLike(){
        $idlisting = filter_var($_GET['idlisting'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->countLike($idlisting);
        echo json_encode($data);
    }
    
    public function CountSewa(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->countSewa($idagen);
        echo json_encode($data);
    }
    
    public function CountJual(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->countJual($idagen);
        echo json_encode($data);
    }
    
    public function CountListing(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->countListing($idagen);
        echo json_encode($data);
    }
    
    public function CountListingPending(){
        $data = $this->User_Model->countListingPending();
        echo json_encode($data);
    }
    
    public function CountPasangBanner(){
        $data = $this->User_Model->countPasangBanner();
        echo json_encode($data);
    }
    
    // Primary ======================================================================================================================================================================================
    
    public function TambahPrimary(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
			
			$data = array(
			    'JudulListingPrimary' => $this->input->post('JudulListingPrimary'),
			    'HargaListingPrimary' => $this->input->post('HargaListingPrimary'),
			    'DeskripsiListingPrimary' => $this->input->post('DeskripsiListingPrimary'),
			    'AlamatListingPrimary' => $this->input->post('AlamatListingPrimary'),
			    'LatitudeListingPrimary' => $this->input->post('LatitudeListingPrimary'),
			    'LongitudeListingPrimary' => $this->input->post('LongitudeListingPrimary'),
			    'LocationListingPrimary' => $this->input->post('LocationListingPrimary'),
			    'KontakPerson1' => $this->input->post('KontakPerson1'),
			    'KontakPerson2' => $this->input->post('KontakPerson2'),
			    'Img1' => $this->input->post('Img1'),
			    'Img2' => $this->input->post('Img2'),
			    'Img3' => $this->input->post('Img3'),
			    'Img4' => $this->input->post('Img4'),
			    'Img5' => $this->input->post('Img5'),
			    'Img6' => $this->input->post('Img6'),
			    'Img7' => $this->input->post('Img7'),
			    'Img8' => $this->input->post('Img8'),
			    'Img9' => $this->input->post('Img9'),
			    'Img10' => $this->input->post('Img10'),
			);
			$this->db->insert('listingprimary',$data);
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
						"Status" => "Sukses",
						"Id" => $idpralisting
						]);
            }
        }
    }
    
    public function TambahTipePrimary(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
			
			$data = array(
			    'IdListingPrimary' => $this->input->post('IdListingPrimary'),
			    'NamaTipe' => $this->input->post('NamaTipe'),
			    'DeskripsiTipe' => $this->input->post('DeskripsiTipe'),
			    'HargaTipe' => $this->input->post('HargaTipe'),
			    'GambarTipe' => $this->input->post('GambarTipe'),
			);
			$this->db->insert('tipeprimary',$data);
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
						"Status" => "Sukses",
						]);
            }
        }
    }
	
    public function UpdatePrimary(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $currentDate = date('Y-m-d');
            $currentTime = date("H:i:s");
            
            $data = array(
                'JudulListingPrimary' => $this->input->post('JudulListingPrimary'),
			    'HargaListingPrimary' => $this->input->post('HargaListingPrimary'),
			    'DeskripsiListingPrimary' => $this->input->post('DeskripsiListingPrimary'),
			    'AlamatListingPrimary' => $this->input->post('AlamatListingPrimary'),
			    'LatitudeListingPrimary' => $this->input->post('LatitudeListingPrimary'),
			    'LongitudeListingPrimary' => $this->input->post('LongitudeListingPrimary'),
			    'LocationListingPrimary' => $this->input->post('LocationListingPrimary'),
			    'KontakPerson1' => $this->input->post('KontakPerson1'),
			    'KontakPerson2' => $this->input->post('KontakPerson2'),
			    'Img1' => $this->input->post('Img1'),
			    'Img2' => $this->input->post('Img2'),
			    'Img3' => $this->input->post('Img3'),
			    'Img4' => $this->input->post('Img4'),
			    'Img5' => $this->input->post('Img5'),
			    'Img6' => $this->input->post('Img6'),
			    'Img7' => $this->input->post('Img7'),
			    'Img8' => $this->input->post('Img8'),
			    'Img9' => $this->input->post('Img9'),
			    'Img10' => $this->input->post('Img10'),
			);
			$where = array('IdListingPrimary' => $this->input->post('IdListingPrimary'),);
			$this->User_Model->update_data($where,$data,'listingprimary');
			
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
						"Status" => "Sukses",
						]);
            }
        }
    }
	
    public function UpdateTipePrimary(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $currentDate = date('Y-m-d');
            $currentTime = date("H:i:s");
            
            $data = array(
                'IdListingPrimary' => $this->input->post('IdListingPrimary'),
			    'NamaTipe' => $this->input->post('NamaTipe'),
			    'DeskripsiTipe' => $this->input->post('DeskripsiTipe'),
			    'HargaTipe' => $this->input->post('HargaTipe'),
			    'GambarTipe' => $this->input->post('GambarTipe'),
			);
			$where = array('IdTipePrimary' => $this->input->post('IdTipePrimary'),);
			$this->User_Model->update_data($where,$data,'tipeprimary');
			
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
						"Status" => "Sukses",
						]);
            }
        }
    }
    
    public function GetPrimary(){
        $data = $this->User_Model->getPrimary();
        echo json_encode($data);
    }
    
    public function GetTipePrimary(){
        $idprimary = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getTipePrimary($idprimary);
        echo json_encode($data);
    }
    
    public function DeletePrimary(){
        
    }
    
    // Follow Up ====================================================================================================================================================================================
	
        // Listing -------------------------------------------------------------
        
    public function AddFlowup(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
			
			$data = array(
			    'IdAgen' => $this->input->post('IdAgen'),
                'IdInput' => $this->input->post('IdInput'),
                'IdListing' => $this->input->post('IdListing'),
                'NamaBuyer' => $this->input->post('NamaBuyer'),
                'TelpBuyer' => $this->input->post('TelpBuyer'),
                'SumberBuyer' => $this->input->post('SumberBuyer'),
                'Tanggal' => $this->input->post('Tanggal'),
                'Jam' => $this->input->post('Jam'),
                'Chat' => $this->input->post('Chat'),
                'Survei' => $this->input->post('Survei'),
                'Tawar' => $this->input->post('Tawar'),
                'Lokasi' => $this->input->post('Lokasi'),
                'Deal' => $this->input->post('Deal'),
                'Selfie'=> $this->input->post('Selfie'),
			);
			$this->db->insert('flowup',$data);
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
						"Status" => "Sukses",
						]);
            }
        }
    }
	
    public function UpdateFlowup(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $currentDate = date('Y-m-d');
            $currentTime = date("H:i:s");
            
            $data = array(
                'Chat' => $this->input->post('Chat'),
                'Survei' => $this->input->post('Survei'),
                'Tawar' => $this->input->post('Tawar'),
                'Lokasi' => $this->input->post('Lokasi'),
                'Deal' => $this->input->post('Deal'),
                'Selfie'=> $this->input->post('Selfie'),
			);
			$where = array('IdFlowup' => $this->input->post('IdFlowup'),);
			$this->User_Model->update_data($where,$data,'flowup');
			
			$dataupdate = array(
			    'IdFlowup' => $this->input->post('IdFlowup'),
                'Tanggal' => $currentDate,
                'Jam' => $currentTime,
                'Chat' => $this->input->post('Chat'),
                'Survei' => $this->input->post('Survei'),
                'Tawar' => $this->input->post('Tawar'),
                'Lokasi' => $this->input->post('Lokasi'),
                'Deal' => $this->input->post('Deal'),
			);
			$this->db->insert('updatefollowup',$dataupdate);
			
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
						"Status" => "Sukses",
						]);
            }
        }
    }
	
    public function CloseFlowup(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $data = array(
                'IsClose'=> 1,
			);
			$where = array('IdFlowup' => $this->input->post('IdFlowup'),);
			$this->User_Model->update_data($where,$data,'flowup');
			
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
						"Status" => "Sukses",
						]);
            }
        }
    }
    
    public function GetFlowUpAgen(){
        $data = $this->User_Model->getFlowUpAgen();
        echo json_encode($data);
    }
    
    public function GetFlowUp(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getFlowUp($idagen);
        echo json_encode($data);
    }
    
    public function GetUpdateFlowUp(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getUpdateFlowUp($id);
        echo json_encode($data);
    }
    
        // Primary -------------------------------------------------------------
    
    public function AddFlowupPrimary(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
			
			$data = array(
			    'IdAgen' => $this->input->post('IdAgen'),
                'IdInput' => $this->input->post('IdInput'),
                'IdListingPrimary' => $this->input->post('IdFlowupPrimary'),
                'NamaBuyer' => $this->input->post('NamaBuyer'),
                'TelpBuyer' => $this->input->post('TelpBuyer'),
                'SumberBuyer' => $this->input->post('SumberBuyer'),
                'Tanggal' => $this->input->post('Tanggal'),
                'Jam' => $this->input->post('Jam'),
                'Chat' => $this->input->post('Chat'),
                'Survei' => $this->input->post('Survei'),
                'Tawar' => $this->input->post('Tawar'),
                'Lokasi' => $this->input->post('Lokasi'),
                'Deal' => $this->input->post('Deal'),
                'Selfie'=> $this->input->post('Selfie'),
			);
			$this->db->insert('flowupprimary',$data);
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
						"Status" => "Sukses",
						]);
            }
        }
    }
	
    public function UpdateFlowupPrimary(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $currentDate = date('Y-m-d');
            $currentTime = date("H:i:s");
            
            $data = array(
                'Chat' => $this->input->post('Chat'),
                'Survei' => $this->input->post('Survei'),
                'Tawar' => $this->input->post('Tawar'),
                'Lokasi' => $this->input->post('Lokasi'),
                'Deal' => $this->input->post('Deal'),
                'Selfie'=> $this->input->post('Selfie'),
			);
			$where = array('IdFlowupPrimary' => $this->input->post('IdFlowupPrimary'),);
			$this->User_Model->update_data($where,$data,'flowupprimary');
			
			$dataupdate = array(
			    'IdFlowup' => $this->input->post('IdFlowupPrimary'),
                'Tanggal' => $currentDate,
                'Jam' => $currentTime,
                'Chat' => $this->input->post('Chat'),
                'Survei' => $this->input->post('Survei'),
                'Tawar' => $this->input->post('Tawar'),
                'Lokasi' => $this->input->post('Lokasi'),
                'Deal' => $this->input->post('Deal'),
			);
			$this->db->insert('updatefollowupprimary',$dataupdate);
			
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
						"Status" => "Sukses",
						]);
            }
        }
    }
	
    public function CloseFlowupPrimary(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $data = array(
                'IsClose'=> 1,
			);
			$where = array('IdFlowupPrimary' => $this->input->post('IdFlowupPrimary'),);
			$this->User_Model->update_data($where,$data,'flowupprimary');
			
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
						"Status" => "Sukses",
						]);
            }
        }
    }
    
    public function GetFlowUpPrimaryAgen(){
        $data = $this->User_Model->getFlowUpPrimaryAgen();
        echo json_encode($data);
    }
    
    public function GetFlowUpPrimary(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getFlowUpPrimary($idagen);
        echo json_encode($data);
    }
    
    public function GetUpdateFlowUpPrimary(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getUpdateFlowUpPrimary($id);
        echo json_encode($data);
    }
    
        // Info ----------------------------------------------------------------
    
    public function AddFlowupInfo(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
			
			$data = array(
			    'IdAgen' => $this->input->post('IdAgen'),
			    'IdInfo' => $this->input->post('IdInfo'),
                'Tanggal' => $this->input->post('Tanggal'),
                'Jam' => $this->input->post('Jam'),
                'KeteranganFollowUp' => $this->input->post('Keterangan'),
			);
			$this->db->insert('flowupinfo',$data);
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
						"Status" => "Sukses",
						]);
            }
        }
    }
    
    public function GetFlowUpInfoAgen(){
        $data = $this->User_Model->getFlowUpinfoAgen();
        echo json_encode($data);
    }
    
    public function GetFlowUpInfo(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getFlowUpInfo($idagen);
        echo json_encode($data);
    }
    
    public function GetHistoryFollowUp(){
        $data = $this->User_Model->getHistoryFollowUp();
        echo json_encode($data);
    }
    
    // Closing ======================================================================================================================================================================================
    
    public function Closing(){
        $data = array('IdListing' => "1",);
	    $this->db->insert('closing',$data);
		$idclosing = $this->db->insert_id();
	    echo json_encode($data); 
        
        $config['upload_path'] = './closing/';
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 10240;
        $config['encrypt_name'] = TRUE;
        
        $this->load->library('upload', $config);
            
        if ($this->upload->do_upload('File1')) {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];
                
            $where = array('IdClosing' => $idclosing,);
			$data = array('SuratKesepakatan' => $file_name);
			$this->User_Model->update_data($where,$data,'closing');
			echo json_encode($data);
        }
        
        if ($this->upload->do_upload('File2')) {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];
            
			$where = array('IdClosing' => $idclosing,);
			$data = array('TandaJadi' => $file_name);
			$this->User_Model->update_data($where,$data,'closing');
        }
    }
    
    public function Sold(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'Sold' => 1,
            );
            $where = array('IdListing' => $this->input->post('IdListing'),);
            $this->User_Model->update_data($where,$data,'listing');
            echo json_encode($data);
        }
    }
    
    public function Rented(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'Rented' => 1,
            );
            $where = array('IdListing' => $this->input->post('IdListing'),);
            $this->User_Model->update_data($where,$data,'listing');
            echo json_encode($data);
        }
    }
    
    public function SoldAgen(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'SoldAgen' => 1,
            );
            $where = array('IdListing' => $this->input->post('IdListing'),);
            $this->User_Model->update_data($where,$data,'listing');
            echo json_encode($data);
        }
    }
    
    public function RentedAgen(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'RentedAgen' => 1,
            );
            $where = array('IdListing' => $this->input->post('IdListing'),);
            $this->User_Model->update_data($where,$data,'listing');
            echo json_encode($data);
        }
    }
    
    // TBO ==========================================================================================================================================================================================
    
    public function AddTbo(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $data = array(
                'IdAgen' => $this->input->post('IdAgen'),
                'Info' => $this->input->post('Info'),
                'Listing' => $this->input->post('Listing'),
                'Open' => $this->input->post('Open'),
                'Exclusive' => $this->input->post('Exclusive'),
                'Banner' => $this->input->post('Banner'),
                'Poin' => $this->input->post('Poin'),
                'Keterangan' => $this->input->post('Keterangan'),
            );
			
			$this->db->insert('tbo',$data);
			
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
						"Status" => "Sukses",
						]);
            }
        }
    }
    
    public function GetTbo(){
        $data = $this->User_Model->getAgen();
        echo json_encode($data);
    }
    
    public function GetCountListingAgenBulanLalu(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getCountListingAgenBulanLalu($idagen);
        echo json_encode($data);
    }
    
    public function GetCountInfoAgenBulanLalu(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getCountInfoAgenBulanLalu($idagen);
        echo json_encode($data);
    }
    
    public function GetCountOpenAgenBulanLalu(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getCountOpenAgenBulanLalu($idagen);
        echo json_encode($data);
    }
    
    public function GetCountExclusiveAgenBulanLalu(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getCountExclusiveAgenBulanLalu($idagen);
        echo json_encode($data);
    }
    
    public function GetCountBannerAgenBulanLalu(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getCountBannerAgenBulanLalu($idagen);
        echo json_encode($data);
    }
    
    public function GetCountListingAgenBulanIni(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getCountListingAgenBulanIni($idagen);
        echo json_encode($data);
    }
    
    public function GetCountInfoAgenBulanIni(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getCountInfoAgenBulanIni($idagen);
        echo json_encode($data);
    }
    
    public function GetCountOpenAgenBulanIni(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getCountOpenAgenBulanIni($idagen);
        echo json_encode($data);
    }
    
    public function GetCountExclusiveAgenBulanIni(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getCountExclusiveAgenBulanIni($idagen);
        echo json_encode($data);
    }
    
    public function GetCountBannerAgenBulanIni(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getCountBannerAgenBulanIni($idagen);
        echo json_encode($data);
    }
    
    public function GetSumPoinBulanLalu(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getSumPoinBulanLalu($idagen);
        echo json_encode($data);
    }
    
    public function GetSumPoinBulanIni(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getSumPoinBulanIni($idagen);
        echo json_encode($data);
    }
    
    public function GetSumPoinInfoBulanLalu(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getSumPoinInfoBulanLalu($idagen);
        echo json_encode($data);
    }
    
    public function GetSumPoinInfoBulanIni(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getSumPoinInfoBulanIni($idagen);
        echo json_encode($data);
    }
    
    public function GetSumTotalPoinBulanLalu(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getSumTotalPoinBulanLalu($idagen);
        echo json_encode($data);
    }
    
    public function GetSumTotalPoinBulanIni(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getSumTotalPoinBulanIni($idagen);
        echo json_encode($data);
    }
    
    // Laporan ======================================================================================================================================================================================

        // Count ---------------------------------------------------------------
    
    public function CountListingReady(){
        $data = $this->User_Model->countListingReady();
        echo json_encode($data);
    }
    
    public function CountListingSolded(){
        $data = $this->User_Model->countListingSolded();
        echo json_encode($data);
    }
    
    public function CountListingRented(){
        $data = $this->User_Model->countListingRented();
        echo json_encode($data);
    }
    
    public function CountListingRent(){
        $data = $this->User_Model->countListingRent();
        echo json_encode($data);
    }
    
    public function CountListingSold(){
        $data = $this->User_Model->countListingSold();
        echo json_encode($data);
    }
    
    public function CountListingSoldRent(){
        $data = $this->User_Model->countListingSoldRent();
        echo json_encode($data);
    }
    
    public function CountListingAll(){
        $data = $this->User_Model->countListingAll();
        echo json_encode($data);
    }
    
    public function CountListingTahun(){
        $data = $this->User_Model->countListingTahun();
        echo json_encode($data);
    }
    
    public function CountListingBulan(){
        $data = $this->User_Model->countListingBulan();
        echo json_encode($data);
    }

        // Rekap ---------------------------------------------------------------
    
    public function GetLaporanSusulan(){
        $data = $this->User_Model->getLaporanSusulan();
        echo json_encode($data);
    }
    
    // Jenis Property ===============================================================================================================================================================================
    
    public function GetJenisProperty(){
        $data = $this->User_Model->getJenisProperty();
        echo json_encode($data);
    }
    
    // User =========================================================================================================================================================================================
    
    public function AddBest(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $this->db->trans_start();
            
            $data = array(
                'IdAgen' => $this->input->post('IdAgen'),
                'NamaBest' => $this->input->post('NamaBest'),
                'Bulan' => $this->input->post('Bulan'),
                'Tahun' => $this->input->post('Tahun'),
            );
			
			$this->db->insert('best',$data);
			
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
						"Status" => "Sukses",
						]);
            }
        }
    }
    
    public function AddKaryawan(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $currentDate = date('Y-m-d');
            list($year, $month, $day) = explode('-', $currentDate);
            $year = substr($year, -2);
            $dateFormatted = $day . $month . $year;
            
            $add = array(
                'IdAgen' => $this->input->post('IdAgen'),
                'Nama' => $this->input->post('Nama'),
                'Posisi' => $this->input->post('Posisi'),
                'Kode' => 600,
                'TglMasuk' => $dateFormatted,
                );
            $this->db->insert('karyawan',$add);
			$nourut = $this->db->insert_id();
			echo json_encode($add);
		
			$urut = array(
					'NoKaryawan'=> "600-".$dateFormatted."-".$nourut,	
				);
			$where = array('IdKaryawan' => $nourut,);
			$this->User_Model->update_data($where,$urut,'karyawan');
			echo json_encode($urut); 
        }
    }
    
    public function GetAgenDeep(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getAgenDeep($idagen);
        echo json_encode($data);
    }
    
    public function GetWilayah(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getWilayah($id);
        echo json_encode($data);
    }
    
    public function GetDaerah(){
        $data = $this->User_Model->getDaerah();
        echo json_encode($data);
    }
    
    public function GetAgen(){
        $data = $this->User_Model->getAgen();
        echo json_encode($data);
    }
    
    public function GetUltahAgen(){
        $data = $this->User_Model->getUltahAgen();
        echo json_encode($data);
    }
    
    public function GetDaftarAgen(){
        $data = $this->User_Model->getDaftarAgen();
        echo json_encode($data);
    }
    
    public function GetPelamarAgen(){
        $data = $this->User_Model->getPelamarAgen();
        echo json_encode($data);
    }
    
    public function GetPelamarMitra(){
        $data = $this->User_Model->getPelamarMitra();
        echo json_encode($data);
    }
    
    public function GetPelamarKantorLain(){
        $data = $this->User_Model->getPelamarKantorLain();
        echo json_encode($data);
    }
    
    public function GetAgenNew(){
        $data = $this->User_Model->getAgenNew();
        echo json_encode($data);
    }
    
    public function AddDevice(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$devicedata = $this->User_Model->device($_POST['Token']);
			if ($devicedata) {
                $add = array(
                    'IdAdmin' => $this->input->post('IdAdmin'),
                    'Status' => $this->input->post('Status'),
                    'Token' => $this->input->post('Token'),
                    );
                $where = array('Token' => $this->input->post('Token'),);
                $this->User_Model->update_data($where,$add,'device');
    			echo json_encode($add);
            } else {
                $add = array(
                    'IdAdmin' => $this->input->post('IdAdmin'),
                    'Status' => $this->input->post('Status'),
                    'Token' => $this->input->post('Token'),
                    );
                $this->db->insert('device',$add);
    			echo json_encode($add);
            }
        }
    }
    
    public function GetDevice(){
        $data = $this->User_Model->getDevice();
        echo json_encode($data);
    }
    
    public function DeleteDevice(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $where = array('Token' => $this->input->post('Token'),);
            $this->User_Model->hapus_data($where,'device');
        }
    }
    
    public function AddDeviceAgen(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$devicedata = $this->User_Model->deviceagen($_POST['Token']);
			if ($devicedata) {
                $add = array(
                    'IdAgen' => $this->input->post('IdAgen'),
                    'Status' => $this->input->post('Status'),
                    'Token' => $this->input->post('Token'),
                    );
                $where = array('Token' => $this->input->post('Token'),);
                $this->User_Model->update_data($where,$add,'deviceagen');
    			echo json_encode($add);
            } else {
                $add = array(
                    'IdAgen' => $this->input->post('IdAgen'),
                    'Status' => $this->input->post('Status'),
                    'Token' => $this->input->post('Token'),
                    );
                $this->db->insert('deviceagen',$add);
    			echo json_encode($add);
            }
        }
    }
    
    public function GetDeviceAgen(){
        $data = $this->User_Model->getDeviceAgen();
        echo json_encode($data);
    }
    
    public function GetDeviceByAgen(){
        $idagen = filter_var($_GET['idagen'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getDeviceByAgen($idagen);
        echo json_encode($data);
    }
    
    public function DeleteDeviceAgen(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $where = array('Token' => $this->input->post('Token'),);
            $this->User_Model->hapus_data($where,'deviceagen');
        }
    }
    
    public function AddDeviceCustomer(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$devicedata = $this->User_Model->devicecustomer($_POST['Token']);
			if ($devicedata) {
                $add = array(
                    'IdCustomer' => $this->input->post('IdCustomer'),
                    'Status' => $this->input->post('Status'),
                    'Token' => $this->input->post('Token'),
                    );
                $where = array('Token' => $this->input->post('Token'),);
                $this->User_Model->update_data($where,$add,'devicecustomer');
    			echo json_encode($add);
            } else {
                $add = array(
                    'IdCustomer' => $this->input->post('IdCustomer'),
                    'Status' => $this->input->post('Status'),
                    'Token' => $this->input->post('Token'),
                    );
                $this->db->insert('devicecustomer',$add);
    			echo json_encode($add);
            }
        }
    }
    
    public function GetDeviceCustomer(){
        $data = $this->User_Model->getDeviceCustomer();
        echo json_encode($data);
    }
    
    public function DeleteDeviceCustomer(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $where = array('Token' => $this->input->post('Token'),);
            $this->User_Model->hapus_data($where,'devicecustomer');
        }
    }
    
    public function GetImg(){
        $data = $this->User_Model->getImg();
        echo json_encode($data);
    }
    
    public function DeleteImages1(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
				'Img1'=> 0
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function DeleteImages2(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
				'Img2'=> 0
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function DeleteImages3(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
				'Img3'=> 0
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function DeleteImages4(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
				'Img4'=> 0
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function DeleteImages5(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
				'Img5'=> 0
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function DeleteImages6(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
				'Img6'=> 0
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function DeleteImages7(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
				'Img7'=> 0
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function DeleteImages8(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idpralisting = $this->input->post('IdPraListing');
            
            $this->db->trans_start();
			
			$data = array(
				'Img8'=> 0
			);
			$where = array('IdPraListing' => $idpralisting,);
    		$this->User_Model->update_data($where,$data,'pralisting');
			
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
    
    public function LastSeen(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $currentDate = date('Y-m-d');
            $data = array(
                'LastSeen' => $currentDate,
            );
            $where = array('IdAgen' => $this->input->post('IdAgen'),);
            $this->User_Model->update_data($where,$data,'agen');
            echo json_encode($data);
        }
    }
    
    public function LastSeenAgenAktif(){
        $data = $this->User_Model->getLastSeenAgenAktif();
        echo json_encode($data);
    }
    
    public function LastSeenAgenTidakAktif(){
        $data = $this->User_Model->getLastSeenAgenTidakAktif();
        echo json_encode($data);
    }
    
    public function GetReportAgen(){
        $data = $this->User_Model->getReportAgen();
        echo json_encode($data);
    }
	
    public function TambahReport(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
            
            $this->db->trans_start();
			
			$data = array(
			    'IdAgen' => $this->input->post('IdAgen'),
                'Tanggal' => $currentDate,
                'Jam' => $currentTime,
                'Kinerja' => $this->input->post('Kinerja'),
                'Keterangan' => $this->input->post('Keterangan'),
                'Lokasi' => $this->input->post('Lokasi'),
                'Alamat' => $this->input->post('Alamat'),
                'Latitude' => $this->input->post('Latitude'),
                'Longitude' => $this->input->post('Longitude'),
                'ImgSelfie' => $this->input->post('ImgSelfie'),
                'ImgProperty' => $this->input->post('ImgProperty'),
			);
			$this->db->insert('reportkinerja',$data);
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
						"Status" => "Sukses",
						]);
            }
        }
    }
	
    public function TambahCekLokasi(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
            
            $this->db->trans_start();
            
            $listing = array(
				'IsCekLokasi' => 1,
			);
			$where = array('IdListing' => $this->input->post('IdListing'),);
    		$this->User_Model->update_data($where,$listing,'listing');
			
			$data = array(
			    'IdAgen' => $this->input->post('IdAgen'),
			    'IdListing' => $this->input->post('IdListing'),
                'Tanggal' => $currentDate,
                'Jam' => $currentTime,
                'Kinerja' => $this->input->post('Kinerja'),
                'Keterangan' => $this->input->post('Keterangan'),
                'Lokasi' => $this->input->post('Lokasi'),
                'Alamat' => $this->input->post('Alamat'),
                'Latitude' => $this->input->post('Latitude'),
                'Longitude' => $this->input->post('Longitude'),
                'ImgSelfie' => $this->input->post('ImgSelfie'),
                'ImgProperty' => $this->input->post('ImgProperty'),
			);
			$this->db->insert('reportkinerja',$data);
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
						"Status" => "Sukses",
						]);
            }
        }
    }
	
    public function TambahCekSurvey(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
            
            $this->db->trans_start();
            
            $listing = array(
				'IsCekLokasi' => 1,
				'Surveyor' => $this->input->post('IdAgen'),
			);
			$where = array('IdPraListing' => $this->input->post('IdPraListing'),);
    		$this->User_Model->update_data($where,$listing,'pralisting');
			
			$data = array(
			    'IdAgen' => $this->input->post('IdAgen'),
			    'IdListing' => $this->input->post('IdPraListing'),
                'Tanggal' => $currentDate,
                'Jam' => $currentTime,
                'Kinerja' => $this->input->post('Kinerja'),
                'Keterangan' => $this->input->post('Keterangan'),
                'Lokasi' => $this->input->post('Lokasi'),
                'Alamat' => $this->input->post('Alamat'),
                'Latitude' => $this->input->post('Latitude'),
                'Longitude' => $this->input->post('Longitude'),
                'ImgSelfie' => $this->input->post('ImgSelfie'),
                'ImgProperty' => $this->input->post('ImgProperty'),
			);
			$this->db->insert('reportkinerja',$data);
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
						"Status" => "Sukses",
						]);
            }
        }
    }
    
    public function GetReportKinerjaOfficer(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getReportKinerjaOfficer($idagen);
        echo json_encode($data);
    }
    
    public function GetUraianKerjaOfficer(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getUraianKerjaOfficer($idagen);
        echo json_encode($data);
    }
    
    public function GetCallOfficerOfficer(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getCallOfficerOfficer($idagen);
        echo json_encode($data);
    }
    
    public function GetFollowUpInfoOfficer(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getFollowUpInfoOfficer($idagen);
        echo json_encode($data);
    }
    
    public function GetFollowUpVendorOfficer(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getFollowUpVendorOfficer($idagen);
        echo json_encode($data);
    }
    
    public function GetFollowUpBuyerOfficer(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getFollowUpBuyerOfficer($idagen);
        echo json_encode($data);
    }
    
    public function GetTinjauLokasiOfficer(){
        $idagen = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = $this->User_Model->getTinjauLokasiOfficer($idagen);
        echo json_encode($data);
    }
    
}
