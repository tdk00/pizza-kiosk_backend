<?php
class Products_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}

	public function getProductsByCategoryId($lang = 'az', $category_id = 0 )
	{
		$this->db->select('*');
		$this->db->select('name_'.$lang.' as name');
		$this->db->select('CONCAT("'.base_url().'", image) as image');
		$this->db->from('products');
		$this->db->where('category_id', $category_id );
		$query = $this->db->get();
		return $query->result_array();
	}

	public function ifSizeExists( $productId = 0 )
	{
		$this->db->select('id');
		$this->db->from('product_sizes');
		$this->db->where('product_id', $productId );
		$query = $this->db->get();
		if( count( $query->result_array() ) > 0 )
		{
			return true;
		}
		return false;
	}

}
?>
