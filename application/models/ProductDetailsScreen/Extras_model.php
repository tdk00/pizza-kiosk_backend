<?php
class Extras_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}

	public function getExtrasBySizeId( $lang = 'az', $size_id = 0 )
	{
		$this->db->select('*');
		$this->db->select('extras.name_'.$lang.' as name');
		$this->db->select('CONCAT("'.base_url().'", extras.image) as image');
		$this->db->from('extras');
		$this->db->join('product_extra_relation', 'product_extra_relation.extra_id = extras.id');
		$this->db->where('product_extra_relation.size_id', $size_id );

		$query = $this->db->get();
		return $query->result_array();
	}

}
?>
