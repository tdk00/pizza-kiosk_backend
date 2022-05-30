<?php
class Shopping_cart_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}

	public function getShoppingCartProductById($lang = 'az', $product_id = 0 )
	{
		$this->db->select('*');
		$this->db->select('products.name_'.$lang.' as name');
		$this->db->select('product_sizes.name_'.$lang.' as size_name');
		$this->db->select('shopping_cart_products.id as shopping_cart_item_id');
		$this->db->select('CONCAT("'.base_url().'", products.image) as image');
		$this->db->from('shopping_cart_products');
		$this->db->join('product_sizes', 'shopping_cart_products.size_id = product_sizes.id');
		$this->db->join('products', 'products.id = product_sizes.product_id');
		$this->db->where('shopping_cart_products.id', $product_id);
		$query = $this->db->get();
		return $query->result_array();

	}

	public function getSCProductExtras( $lang = 'az', $shopping_cart_product_id = 0 )
	{
		$this->db->select('*');
		$this->db->select('extras.name_'.$lang.' as name');
		$this->db->select('shopping_cart_extras.id as shopping_cart_extra_id');
		$this->db->select('0 as size_id');
		$this->db->from('shopping_cart_extras');
		$this->db->join('extras', 'extras.id = shopping_cart_extras.extra_id');
		$this->db->select('CONCAT("'.base_url().'", extras.image) as image');
		$this->db->where('shopping_cart_product_id', $shopping_cart_product_id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getSCRelatedProductExtras( $lang = 'az', $shopping_cart_product_id = 0 )
	{
		$this->db->select('size_id');
		$this->db->from('shopping_cart_products');
		$this->db->where('id', $shopping_cart_product_id);
		$query = $this->db->get();
		$result = $query->row();
		$size_id = ! empty ( $result->size_id ) ? $result->size_id : 0;

		$this->db->select('extra_id');
		$this->db->from('shopping_cart_extras');
		$this->db->where('shopping_cart_product_id', $shopping_cart_product_id);
		$query = $this->db->get();
		$extrasSC =  $query->result_array();
		$extrasArray = [];
		foreach ($extrasSC as $extraValue) {
			$extrasArray[] = (int)$extraValue['extra_id'];
		}


		$this->db->select('*');
		$this->db->select('extras.name_'.$lang.' as name');
		$this->db->select('CONCAT("'.base_url().'", extras.image) as image');
		$this->db->from('extras');
		$this->db->join('product_extra_relation', 'product_extra_relation.extra_id = extras.id');
		$this->db->where('product_extra_relation.size_id', $size_id );
		if( count( $extrasArray ) > 0 )
		{
			$this->db->where_not_in('extras.id', $extrasArray);
		}


		$query = $this->db->get();
		return $query->result_array();


	}


	public function addProductToShoppingCart( $size_id = 0, $session_id = 0, $count = 0 )
	{
		$data = [
			"session_id" => $session_id,
			"size_id" => $size_id,
			"product_count" => $count,
		];
		$this->db->insert('shopping_cart_products', $data );
		return $this->db->insert_id();
	}

	public function addExtrasToShoppingCart( $shopping_cart_product_id = 0, $extra_id = 0, $count = 0, $session_id = 0, $size_id = 0  )
	{
		$extra_default_count = 0;
		$this->db->select('*');
		$this->db->from('product_extra_relation');
		$this->db->where('extra_id', $extra_id);
		$this->db->where('size_id', $size_id);
		$query = $this->db->get();
		$result = $query->result_array();
		if ( count ( $result ) > 0 )
		{
			$extra_default_count = $result[0]['extra_count'];
		}

			$data = [
				"session_id" => $session_id,
				"extra_id" => $extra_id,
				"extra_default_count" => $extra_default_count,
				"extra_count" => $count,
				"shopping_cart_product_id" => $shopping_cart_product_id
			];
			$this->db->insert('shopping_cart_extras', $data );
			return $this->db->insert_id();




	}

	public function updateShoppingCartProduct( $shopping_cart_product_id = 0, $session_id = "", $count = 0 )
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


	public function updateShoppingCartExtra( $shopping_cart_extra_id = 0, $count = 0 )
	{
		$data = array(
			'extra_count' => $count,
		);

		$this->db->where('id', $shopping_cart_extra_id);
		$this->db->update('shopping_cart_extras', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}

}
?>
