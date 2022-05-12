<?php
class Settings_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}


	public function getOption( $option_key = "")
	{
		$this->db->select('*');
		$this->db->from('options');
		$this->db->where('option_key', $option_key );
		$query = $this->db->get();
		return count( $query->result_array() )  > 0 ? $query->result_array()[0]['value'] : "";
	}



}
?>
