<?php
class Categories_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getAllCategories( $lang = 'az' )
	{
			$this->db->select('*');
			$this->db->select('name_'.$lang.' as name');
			$this->db->select( 'CONCAT("'.base_url().'", image) as image');
			$this->db->from('categories');
			$query = $this->db->get();
			return $query->result_array();
	}

}
?>
