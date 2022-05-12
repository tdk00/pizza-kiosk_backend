<?php
class Admin_user_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}

	public function check_user( $username = "", $password = "" )
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where( 'username', $username );
		$this->db->where( 'password', $password );
		$query = $this->db->get();
		return $query->result_array();
	}

}

?>
