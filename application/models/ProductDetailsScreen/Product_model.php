<?php
class Product_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}

	public function getProductById($lang = 'az', $product_id = 0 )
	{
		$this->db->select('*');
		$this->db->select('products.name_'.$lang.' as name');
		$this->db->select('CONCAT("'.base_url().'", products.image) as image');
		$this->db->from('products');
		$this->db->where('products.id', $product_id );

		$query = $this->db->get();
		return $query->result_array();
	}

	public function getProductSizesById( $lang = 'az', $product_id = 0 )
	{
		$this->db->select('*');
		$this->db->select('name_'.$lang.' as size_name');
		$this->db->from('product_sizes');
		$this->db->where('product_id', $product_id );

		$query = $this->db->get();
		return $query->result_array();
	}

}
?>
