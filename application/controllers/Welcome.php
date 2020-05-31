<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct(){
		parent::__construct();

		$this->load->model('M_welcome');
	}

	public function index()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('uid','Uid','required|trim');
		$this->form_validation->set_rules('pass','Pass','required|trim|min_length[6]');

		if($this->form_validation->run() == FALSE){
			$this->load->view('login');
		}else{
			$this->proses_login();
		}
	}

	private function proses_login(){
		$uid = $this->input->post('uid');
		$password = $this->input->post('pass');

		$cek = $this->M_welcome->cek_user($uid,'user');

		if($cek){
			if(password_verify($password, $cek['password'])){
				echo "password cocok dan login berhasil";
			}else{
				echo "Password tidak cocok";
			}
		}else{
			echo "User tidak terdaftar";
		}
	}

	public function register(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username','Username','trim|required|max_length[15]');
		$this->form_validation->set_rules('pass','Pass','trim|required|min_length[6]');
		$this->form_validation->set_rules('repass','Repass','trim|required|min_length[6]|matches[pass]');

		if($this->form_validation->run() == TRUE){
			$this->proses_register();
		}else{
			$this->load->view('register');
		}
	}

	private function proses_register(){
		$username = htmlspecialchars($this->input->post('username'));
		$pass = htmlspecialchars($this->input->post('pass'));
		$img = $_FILES['dp']['name'];

		if($img == NULL){
			$msg = "Wajib menggunakan gambar";
			$this->session->set_flashdata('message','<div class="alert alert-danger">'.$msg.'</div>');

			redirect('register');
		}else{
			$this->load->library('upload');

			$config['upload_path'] = './img/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['file_ext_tolower'] = TRUE;
			$config['remove_spaces'] = TRUE;
			$config['overwrite'] = TRUE;

			$this->upload->initialize($config);

			if(!$this->upload->do_upload('dp')){
				$msg = $this->upload->display_errors();
				$this->session->set_flashdata('message','<div class="alert alert-danger">'.$msg.'</div>');

				redirect('register');
			}else{
				$data = [
					'username' => $username,
					'password' => password_hash($pass, PASSWORD_DEFAULT),
					'display' => $this->upload->data('file_name')
				];
				$this->M_welcome->create_user($data,'user');

				$msg = "registrasi berhasil";
				$this->session->set_flashdata('message','<div class="alert alert-success">'.$msg.'</div>');

				redirect('login');
			}
		}
	}
}
