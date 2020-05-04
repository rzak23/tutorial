<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_welcome extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function cek_user($user,$table){
		$this->db->where('username',$user);
		return $this->db->get($table)->row_array();
	}

}

/* End of file M_welcom.php */
/* Location: ./application/models/M_welcom.php */