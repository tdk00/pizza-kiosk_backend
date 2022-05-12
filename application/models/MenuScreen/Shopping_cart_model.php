<?php
class Shopping_cart_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}

	public function getShoppingCartProducts( $lang = 'az', $session_id = 0 )
	{
		$this->db->select('*');
		$this->db->select('products.name_'.$lang.' as name');
		$this->db->select('product_sizes.name_'.$lang.' as size_name');
		$this->db->select('shopping_cart_products.id as shopping_cart_product_id');
		$this->db->select('CONCAT("'.base_url().'", products.image) as image');
		$this->db->from('shopping_cart_products');
		$this->db->join('product_sizes', 'shopping_cart_products.size_id = product_sizes.id');
		$this->db->join('products', 'products.id = product_sizes.product_id');
		$this->db->where('shopping_cart_products.session_id', $session_id);
		$query = $this->db->get();
		return $query->result_array();

	}

	public function getSCProductExtras( $shopping_cart_product_id = 0 )
	{
		$this->db->select('*');
		$this->db->from('shopping_cart_extras');
		$this->db->join('extras', 'extras.id = shopping_cart_extras.extra_id');
		$this->db->select('CONCAT("'.base_url().'", extras.image) as image');
		$this->db->where('shopping_cart_product_id', $shopping_cart_product_id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function removeShoppingCartItem( $session_id = 0, $shopping_cart_product_id = 0 )
	{
		$removed_all = false;
		$removed = $this->db->delete( 'shopping_cart_products', array('id' => $shopping_cart_product_id, 'session_id' => $session_id) );
		if ( $removed )
		{
			$removed_all = $this->db->delete( 'shopping_cart_extras', array('shopping_cart_product_id' => $shopping_cart_product_id) );
		}

		if( $removed_all )
		{
			return true;
		}

		return false;

	}

	public function updateShoppingCartProductCounts( $shopping_cart_product_id = 0, $count = 0 )
	{
		$data = array(
			'product_count' => $count,
		);

		$this->db->where('id', $shopping_cart_product_id);
		$this->db->update('shopping_cart_products', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}



}
?>
