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

	public function proses_login(){
		$this->load->model('M_welcome');
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
}
