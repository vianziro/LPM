<?phpclass Form extends CI_Controller  {	function __construct()	{		parent::__construct();		$this->load->helper(array('form','url', 'text_helper','date'));		$this->load->database();		$this->load->library(array('form_validation','session'));		$this->form_data= new stdClass();		session_start();	}	//fungsi mengecek apakah sistem ini diakses melalui SIA atau tidak	function cek_akses_sistem($status){		if ($status){			return true;		}else{			return false;		}	}	// fungsi untuk mengecek user mahasiswa yang login, 1= mahasiswa	function login_mhs($status2){		//echo"$status2";		if ($status2=='1'){		return true;	}else{		return false;	}	}	// fungsi untuk mengecek user dosen yang login, 2= dosen	function login_dosen($status2){		if ($status2=='2'){			return true;		}else{			return false;		}	}	function index()	{		if(isset($_SESSION['data']['status'])) {			$rischan = $_SESSION['data']['status'];		}else {$rischan="";		}		if ($this->cek_akses_sistem($rischan)){			$kd_kelas2=$_SESSION['data']['kd_kelas'];			$nm_kelas2=$_SESSION['data']['nm_kelas'];			$kd_ta2 =$_SESSION['data']['kd_ta'];			$kd_smt2 =$_SESSION['data']['kd_smt'];			$id_user2 =$_SESSION['data']['id_user'];			$nm_user2 =$_SESSION['data']['nm_user'];			$kd_kur2 =$_SESSION['data']['kd_kur'];			$kd_prodi2 =$_SESSION['data']['kd_prodi'];			$nm_prodi2 =$_SESSION['data']['nm_prodi'];			$kd_mk2 =$_SESSION['data']['kd_mk'];			$sks_mk2 =$_SESSION['data']['sks_mk'];			$status2 =$_SESSION['data']['status'];			$data = array(					'kd_ta' => $kd_ta2,					'kd_smt'=> $kd_smt2,					'id_user' => $id_user2,					'nm_user' => $nm_user2,					'kd_prodi'=> $kd_prodi2,					'nm_prodi' => $nm_prodi2,					'kd_mk' => $kd_mk2,					'status'=> $status2,					'logged_in' => TRUE			);			$this->session->set_userdata($data);			$this->load->model('Form_Model');			$nim =$_SESSION['data']['id_user'];			$kd_mk =$_SESSION['data']['kd_mk'];			$hasil_mk=$this->Form_Model->cek_mk($kd_mk);				if (count($hasil_mk->result_array())>0){					$hasil=$this->Form_Model->cek_sudah($nim);					if (count($hasil->result_array())>0){						foreach($hasil->result() as $items){							$sudah=$items->SUDAH;						}						if($sudah=='0'){						$this->load->view('form/index');						$hasil_mk=$this->Form_Model->cek_mk($kd_mk);							if (count($hasil_mk->result_array())>0){								foreach($hasil_mk->result() as $items){								$ada=$items->KD_FAK;								}								if($ada=='06'){								$this->db = $this->load->database('saintek', TRUE);																										}								else if ($ada=='03'){								$this->db = $this->load->database('syariah', TRUE);																																}								else if ($ada=='05'){								$this->db = $this->load->database('ushuludin', TRUE);																													}								else if ($ada=='01'){								$this->db = $this->load->database('adab', TRUE);																	}								else if ($ada=='04'){								$this->db = $this->load->database('tarbiyah', TRUE);																									}																}							else{								//$this->load->view('form/index');							}														}						else if ($sudah=='1'){							echo "<meta http-equiv='refresh' content='0; url=".base_url()."form/upload'>";						}						else {															echo "<meta http-equiv='refresh' content='0; url=".base_url()."index.php/mahasiswa'>";						}					}					else{						$this->load->view('form/index');					}																	}else {											?><script type="text/javascript">			alert("Anda Belum Menyelesaikan Administrasi KKN..!!!");				window.location = "http://sia.uin-suka.ac.id/"			</script><?php																		}									}					else{			?><script type="text/javascript">			alert("Anda Harus Login Melalui SIA..!!!");				window.location = "http://sia.uin-suka.ac.id/"			</script><?php					}	}	function submit()	{		$username = $this->input->post('usernameteks');		$pwd = $this->input->post('passwordteks');		$this->load->model('Kkn_model');		$hasil = $this->Kkn_model->Data_Login($username,$pwd);		if (count($hasil->result_array())>0){			foreach($hasil->result() as $items){				$session_username=$items->username."|".$items->nama."|".$items->idlink."|".$items->status;				$tanda=$items->status;			}			$_SESSION['username_belajar']=$session_username;			if($tanda=="Mahasiswa"){				echo "<meta http-equiv='refresh' content='0; url=".base_url()."index.php/'>";			}			else if($tanda=="admin"){				echo "<meta http-equiv='refresh' content='0; url=".base_url()."index.php/admin'>";			}			else {				echo "<meta http-equiv='refresh' content='0; url=".base_url()."index.php/dosen'>";			}		}		else{			?><script type="text/javascript">			alert("Username atau Password Yang Anda Masukkan Salah..!!!");						</script><?phpecho "<meta http-equiv='refresh' content='0; url=".base_url()."'>";		}	}	function input(){		$nim =$_SESSION['data']['id_user'];		$this->form_validation->set_rules('agreeCheck', 'Agree to the Terms and Conditions', 'required|isset');		if (!isset($_POST['agreeCheck'])) {			?><script type="text/javascript">			alert("Anda Tidak Setuju dengan Pernyataan ini, anda tidak berhak menuju ke halaman selanjutnya..!!!");				window.location ="<?php echo base_url(); ?>form"			</script><?php		}else {			$this->load->model('Form_Model');			$hasil = $this->Form_Model->lihat_NIM($nim);			if(count($hasil->result()) > 0)			{									$this->load->model('Form_Model','',TRUE);				$mahasiswa = $this->Form_Model->get_by_nim($nim)->row();				$this->form_data->NIM = $mahasiswa->NIM;;				$this->form_data->NAMA = $mahasiswa->NAMA;				$this->form_data->ANGKATAN = $mahasiswa->ANGKATAN;				$this->form_data->J_KELAMIN = $mahasiswa->J_KELAMIN;				$this->form_data->NM_PRODI = $mahasiswa->NM_PRODI;				$this->form_data->NM_FAK = $mahasiswa->NM_FAK;				$this->form_data->HP_MHS = $mahasiswa->HP_MHS;				$this->form_data->TELP_MHS = $mahasiswa->HP_MHS;				$this->form_data->GOL_DARAH = $mahasiswa->GOL_DARAH;				$this->form_data->TINGGI = $mahasiswa->TINGGI;									$this->form_data->BERAT = $mahasiswa->BERAT;				$this->form_data->PEKERJAAN = $mahasiswa->PEKERJAAN;				$this->form_data->STATUS_KAWIN = $mahasiswa->STATUS_KAWIN;									$this->form_data->ALAMAT_MHS = $mahasiswa->ALAMAT_MHS;				$this->form_data->RT = $mahasiswa->RT;				$this->form_data->DESA = $mahasiswa->DESA;				$this->form_data->NM_KAB = $mahasiswa->NM_KAB;				$this->form_data->NM_PROP = $mahasiswa->NM_PROP;														$this->form_data->NO = $mahasiswa->NO;				$this->form_data->TRANSPORTASI = $mahasiswa->TRANSPORTASI;									$this->form_data->PRESTASI = $mahasiswa->PRESTASI;									$this->form_data->ALAMAT_JOGJA = $mahasiswa->ALAMAT_JOGJA;				$this->form_data->RT_JOGJA= $mahasiswa->RT_JOGJA;				$this->form_data->DESA_JOGJA = $mahasiswa->DESA_JOGJA;				$this->form_data->NM_KEC_JOGJA = $mahasiswa->NM_KEC_JOGJA;				$this->form_data->NM_KAB_JOGJA = $mahasiswa->NM_KAB_JOGJA;																		}			else {				$datainput['NIM']=$nim;				$datainput['SUDAH']='0';				$this->Form_Model->Insert_NIM($datainput);									$this->load->model('Form_Model','',TRUE);				$mahasiswa = $this->Form_Model->get_by_nim($nim)->row();				$this->form_data->NIM = $mahasiswa->NIM;;				$this->form_data->NAMA = $mahasiswa->NAMA;				$this->form_data->ANGKATAN = $mahasiswa->ANGKATAN;				$this->form_data->J_KELAMIN = $mahasiswa->J_KELAMIN;				$this->form_data->NM_PRODI = $mahasiswa->NM_PRODI;				$this->form_data->NM_FAK = $mahasiswa->NM_FAK;				$this->form_data->HP_MHS = $mahasiswa->HP_MHS;				$this->form_data->TELP_MHS = $mahasiswa->HP_MHS;				$this->form_data->GOL_DARAH = $mahasiswa->GOL_DARAH;				$this->form_data->TINGGI = $mahasiswa->TINGGI;									$this->form_data->BERAT = $mahasiswa->BERAT;				$this->form_data->PEKERJAAN = $mahasiswa->PEKERJAAN;				$this->form_data->STATUS_KAWIN = $mahasiswa->STATUS_KAWIN;									$this->form_data->ALAMAT_MHS = $mahasiswa->ALAMAT_MHS;				$this->form_data->RT = $mahasiswa->RT;				$this->form_data->DESA = $mahasiswa->DESA;				$this->form_data->NM_KAB = $mahasiswa->NM_KAB;				$this->form_data->NM_PROP = $mahasiswa->NM_PROP;														$this->form_data->NO = $mahasiswa->NO;				$this->form_data->TRANSPORTASI = $mahasiswa->TRANSPORTASI;									$this->form_data->PRESTASI = $mahasiswa->PRESTASI;									$this->form_data->ALAMAT_JOGJA = $mahasiswa->ALAMAT_JOGJA;				$this->form_data->RT_JOGJA= $mahasiswa->RT_JOGJA;				$this->form_data->DESA_JOGJA = $mahasiswa->DESA_JOGJA;				$this->form_data->NM_KEC_JOGJA = $mahasiswa->NM_KEC_JOGJA;				$this->form_data->NM_KAB_JOGJA = $mahasiswa->NM_KAB_JOGJA;				}						$this->load->view('form/input');		}	}	function update(){		$nim =$_SESSION['data']['id_user'];		$this->load->model('Form_Model','',TRUE);		$mahasiswa = $this->Form_Model->get_by_nim($nim)->row();		$this->form_data->NIM = $mahasiswa->NIM;;		$this->form_data->NAMA = $mahasiswa->NAMA;		$this->form_data->ANGKATAN = $mahasiswa->ANGKATAN;		$this->form_data->J_KELAMIN = $mahasiswa->J_KELAMIN;		$this->form_data->NM_PRODI = $mahasiswa->NM_PRODI;		$this->form_data->NM_FAK = $mahasiswa->NM_FAK;		$this->form_data->HP_MHS = $mahasiswa->HP_MHS;		$this->form_data->TELP_MHS = $mahasiswa->HP_MHS;		$this->form_data->GOL_DARAH = $mahasiswa->GOL_DARAH;		$this->form_data->TINGGI = $mahasiswa->TINGGI;					$this->form_data->BERAT = $mahasiswa->BERAT;		$this->form_data->PEKERJAAN = $mahasiswa->PEKERJAAN;		$this->form_data->STATUS_KAWIN = $mahasiswa->STATUS_KAWIN;					$this->form_data->ALAMAT_MHS = $mahasiswa->ALAMAT_MHS;		$this->form_data->RT = $mahasiswa->RT;		$this->form_data->DESA = $mahasiswa->DESA;		$this->form_data->NM_KAB = $mahasiswa->NM_KAB;		$this->form_data->NM_PROP = $mahasiswa->NM_PROP;								$this->form_data->NO = $mahasiswa->NO;		$this->form_data->TRANSPORTASI = $mahasiswa->TRANSPORTASI;					$this->form_data->PRESTASI = $mahasiswa->PRESTASI;					$this->form_data->ALAMAT_JOGJA = $mahasiswa->ALAMAT_JOGJA;		$this->form_data->RT_JOGJA= $mahasiswa->RT_JOGJA;		$this->form_data->DESA_JOGJA = $mahasiswa->DESA_JOGJA;		$this->form_data->NM_KEC_JOGJA = $mahasiswa->NM_KEC_JOGJA;		$this->form_data->NM_KAB_JOGJA = $mahasiswa->NM_KAB_JOGJA;		$this->load->library('form_validation');		$this->form_validation->set_rules('transportasi', 'Transportasi', 'required');		$this->form_validation->set_rules('prestasi', 'Keahlian', 'required');		$this->form_validation->set_rules('alamat_jogja', 'Alamat Jogja', 'required');		//$this->form_validation->set_rules('rt_jogja', 'RT Jogja', 'required');		$this->form_validation->set_rules('desa_jogja', 'Desa Jogja', 'required');		$this->form_validation->set_rules('nm_kec_jogja', 'Kecamatan Jogja', 'required');		$this->form_validation->set_rules('nm_kab_jogja', 'Kabupaten Jogja', 'required');		$this->form_validation->set_rules('hp_mhs', 'No HP Mahasiswa', 'required');		$this->form_validation->set_rules('telp_mhs', 'Telp Ibu/Bpk dari Mahasiswa', 'required');		$this->form_validation->set_rules('gol_darah', 'Golongan Darah', 'required');		$this->form_validation->set_rules('tinggi', 'Tinggi', 'required');		$this->form_validation->set_rules('berat', 'Berat', 'required');		$this->form_validation->set_rules('pekerjaan', 'Pekerjaan', 'required');		$this->form_validation->set_rules('STATUS_KAWIN', 'Status Pernikahan', 'required');		$this->form_validation->set_message('required', 'Field %s tidak boleh kosong!!!');		if ($this->form_validation->run() == FALSE)		{			$this->load->view('form/input');		}		else		{			$mhs = array( 'TRANSPORTASI' => $this->input->post('transportasi',TRUE),					'PRESTASI' => $this->input->post('prestasi',TRUE),					'ALAMAT_JOGJA' => $this->input->post('alamat_jogja',TRUE),					'RT_JOGJA' => $this->input->post('rt_jogja'),					'DESA_JOGJA' => $this->input->post('desa_jogja'),					'NM_KEC_JOGJA' => $this->input->post('nm_kec_jogja'),					'NM_KAB_JOGJA' => $this->input->post('nm_kab_jogja'),					'FAK' => $this->input->post('fak'),					'JK' => $this->input->post('j_kelamin')					/**					 'PATH_SK_DOKTER' => $this->input->post(''),			'PATH_SK_GOLONGAN_DARAH' => $this->input->post(''),			'PATH_SK_CUTI' =>$this->input->post(''),			'PATH_SK_TIDAK_HAMIL' =>$this->input->post(''),			**/			);											$mahasiswa = array('HP_MHS' => $this->input->post('hp_mhs'),					'TELP_MHS' => $this->input->post('telp_mhs'),					'GOL_DARAH' => $this->input->post('gol_darah'),					'TINGGI' => $this->input->post('tinggi'),					'BERAT' => $this->input->post('berat'),					'PEKERJAAN' => $this->input->post('pekerjaan'),					'STATUS_KAWIN' => $this->input->post('STATUS_KAWIN',TRUE)									);											$this->load->model('Form_Model','',TRUE);			$this->Form_Model->update_d_mahasiswa($nim,$mahasiswa);			$this->Form_Model->update_kkn_mhs($nim,$mhs);			$this->Form_Model->ganti_sudah_jadi_1($nim);			echo "<meta http-equiv='refresh' content='0; url=".base_url()."index.php/form/upload'>";										}										}	function upload()	{	//$nim =$_SESSION['data']['id_user'];		$this->load->view('form/doc_upload');	}	public function foto($file)	{		if ($file == '')		{			$this->form_validation->set_message('foto', 'The %s field can not empty');			return FALSE;		}		else		{			return TRUE;		}	}	//upload foto dan doc	function do_upload()	{		if (isset($_FILES['foto']['name']) && $_FILES['foto']['name'] != '') {		$nim =$_SESSION['data']['id_user'];		$pemilik=$nim;		//$pemisah="_";		$bersih=$_FILES['foto']['name'];		$nm=str_replace(" ","_","$bersih");		$pisah=explode(".",$nm);		$nama_murni=$pisah[0];		$ekstensi=$pisah[1];		$sp="_";		//$ubah=$pemilik.$sp.$nama_murni; //tanpa ekstensi		$ubah=$pemilik;		//path where to save the image		$config['upload_path'] = './uploads/foto';		$config['allowed_types'] = 'gif|jpg|png|jpeg|GIF|JPG|JPEG|GIF';		$config['max_size'] = '20048';		$config['overwrite'] = TRUE;		$config['max_width'] = '10024';		$config['max_height'] = '7068';		$config['remove_spaces'] = TRUE;		$config["file_name"]=$ubah; //dengan eekstensi		$foto=$ubah.".".$ekstensi;		$this->load->library('upload', $config);		$this->load->model('Form_Model','',TRUE);		$this->Form_Model->insert_foto($nim,$foto);		if (!$this->upload->do_upload('foto')) {			//echo $this->upload->display_errors();		//	$error = array('error' => $this->upload->display_errors());		//	$this->load->view('form/doc_upload', $error);			$foto_sukses = FALSE;		} else {			$foto_sukses = TRUE;		}	} else {				}			//upload SK Sehat	if (isset($_FILES['sk_sehat']['name']) && $_FILES['sk_sehat']['name'] != '') {		unset($config);		$nim =$_SESSION['data']['id_user'];		$pemilik=$nim;		//$pemisah="_";		$bersih=$_FILES['sk_sehat']['name'];		$nm=str_replace(" ","_","$bersih");		$pisah=explode(".",$nm);		$nama_murni=$pisah[0];		$ekstensi=$pisah[1];		$sp="_";		//$ubah=$pemilik.$sp.$nama_murni; //tanpa ekstensi		$ubah=$pemilik;		//path where to save the SkSehat		$configSkSehat['upload_path'] = './uploads/sk_sehat';		$configSkSehat['max_size'] = '10240';		$configSkSehat['allowed_types'] = 'pdf|jpg|png|JPG|JPEG|PNG|jpeg|doc|docx';		$configSkSehat['overwrite'] = TRUE;		$configSkSehat['remove_spaces'] = TRUE;		$configSkSehat["file_name"]=$ubah; //dengan eekstensi		$sk_sehat = $ubah.".".$ekstensi;		$this->load->library('upload', $configSkSehat);		$this->upload->initialize($configSkSehat);		$this->load->model('Form_Model','',TRUE);		$this->Form_Model->insert_sk_sehat($nim,$sk_sehat);		if (!$this->upload->do_upload('sk_sehat')) {			$sk_sehat_sukses = FALSE;		} else {			$sk_sehat_sukses=TRUE;		}	}					//upload SK Gologan Darah	if (isset($_FILES['sk_gol_darah']['name']) && $_FILES['sk_gol_darah']['name'] != '') {		unset($config);		$nim =$_SESSION['data']['id_user'];		$pemilik=$nim;		//$pemisah="_";		$bersih=$_FILES['sk_gol_darah']['name'];		$nm=str_replace(" ","_","$bersih");		$pisah=explode(".",$nm);		$nama_murni=$pisah[0];		$ekstensi=$pisah[1];		$sp="_";		//$ubah=$pemilik.$sp.$nama_murni; //tanpa ekstensi		$ubah=$pemilik;		//path where to save the SkGolDarah		$configSkGolDarah['upload_path'] = './uploads/sk_gol_darah';		$configSkGolDarah['max_size'] = '10240';		$configSkGolDarah['allowed_types'] = 'pdf|jpg|png|JPG|JPEG|PNG|jpeg|doc|docx';		$configSkGolDarah['overwrite'] = TRUE;		$configSkGolDarah['remove_spaces'] = TRUE;		$configSkGolDarah["file_name"]=$ubah; //dengan eekstensi		$sk_gol_darah=$ubah.".".$ekstensi;		$this->load->library('upload', $configSkGolDarah);		$this->upload->initialize($configSkGolDarah);		$this->load->model('Form_Model','',TRUE);		$this->Form_Model->insert_sk_gol_darah($nim,$sk_gol_darah);		if (!$this->upload->do_upload('sk_gol_darah')) {			$sk_gol_darah_sukses=FALSE;		} else {			$sk_gol_darah_sukses=TRUE;		}	}					//upload SK Cuti Kerja	if (isset($_FILES['sk_cuti_kerja']['name']) && $_FILES['sk_cuti_kerja']['name'] != '') {		unset($config);		$nim =$_SESSION['data']['id_user'];		$pemilik=$nim;		//$pemisah="_";		$bersih=$_FILES['sk_cuti_kerja']['name'];		$nm=str_replace(" ","_","$bersih");		$pisah=explode(".",$nm);		$nama_murni=$pisah[0];		$ekstensi=$pisah[1];		$sp="_";		//$ubah=$pemilik.$sp.$nama_murni; //tanpa ekstensi		$ubah=$pemilik;		//path where to save the SkCutiKerja		$configSkCutiKerja['upload_path'] = './uploads/sk_cuti_kerja';		$configSkCutiKerja['max_size'] = '10240';		$configSkCutiKerja['allowed_types'] = 'pdf|jpg|png|JPG|JPEG|PNG|jpeg|doc|docx';		$configSkCutiKerja['overwrite'] = TRUE;		$configSkCutiKerja['remove_spaces'] = TRUE;		$configSkCutiKerja["file_name"]=$ubah; //dengan eekstensi		$sk_cuti_kerja=$ubah.".".$ekstensi;		$this->load->library('upload', $configSkCutiKerja);		$this->upload->initialize($configSkCutiKerja);		$this->load->model('Form_Model','',TRUE);		$this->Form_Model->insert_sk_cuti_kerja($nim,$sk_cuti_kerja);		if (!$this->upload->do_upload('sk_cuti_kerja')) {			$sk_cuti_kerja_sukses = FALSE;		}else {			$sk_cuti_kerja_sukses = TRUE;		}	}			//upload SK Tidak Hamil	if (isset($_FILES['sk_tidak_hamil']['name']) && $_FILES['sk_tidak_hamil']['name'] != '') {		unset($config);		$nim =$_SESSION['data']['id_user'];		$pemilik=$nim;		//$pemisah="_";		$bersih=$_FILES['sk_tidak_hamil']['name'];		$nm=str_replace(" ","_","$bersih");		$pisah=explode(".",$nm);		$nama_murni=$pisah[0];		$ekstensi=$pisah[1];		$sp="_";		//$ubah=$pemilik.$sp.$nama_murni; //tanpa ekstensi		$ubah=$pemilik;		//path where to save the SkTidakHamil		$configSkTidakHamil['upload_path'] = './uploads/sk_tidak_hamil';		$configSkTidakHamil['max_size'] = '10240';		$configSkTidakHamil['allowed_types'] = 'pdf|jpg|png|JPG|JPEG|PNG|jpeg|doc|docx';		$configSkTidakHamil['overwrite'] = TRUE;		$configSkTidakHamil['remove_spaces'] = TRUE;		$configSkTidakHamil["file_name"]=$ubah; //dengan eekstensi		$sk_tidak_hamil=$ubah.".".$ekstensi;		$this->load->library('upload', $configSkTidakHamil);		$this->upload->initialize($configSkTidakHamil);		$this->load->model('Form_Model','',TRUE);		$this->Form_Model->insert_sk_tidak_hamil($nim,$sk_tidak_hamil);		if (!$this->upload->do_upload('sk_tidak_hamil')) {			$sk_tidak_hamil_sukses=FALSE;		} else {			$sk_tidak_hamil_sukses=TRUE;		}	}					if (($sk_sehat_sukses==FALSE) ||($sk_gol_darah_sukses==FALSE)||($foto_sukses==FALSE)){				?>		<script type="text/javascript">					alert("Jenis File yang anda Upload tidak diijinkan, gunakan file png,jpg,jpeg untuk foto dan pdf,doc,docx untuk document..!!!");								</script>		<?php		echo "<meta http-equiv='refresh' content='0; url=".base_url()."index.php/form/upload'>";		}else {		$nim =$_SESSION['data']['id_user'];		$this->load->model('Form_Model','',TRUE);		$this->Form_Model->ganti_sudah_jadi_2($nim);		echo "<meta http-equiv='refresh' content='0; url=".base_url()."index.php/mahasiswa'>";			}					}}?>