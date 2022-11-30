<?php
class Admin_kitchen_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getUnreadyOrders()
	{
		$this->db->select('*');
		$this->db->from('orders');
		$this->db->where('status', 1); // status 1 - hazirlanir statusunda olan sifarislerdir
		$this->db->order_by('id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getOrder( $id )
	{
		$this->db->select('*');
		$this->db->from('orders');
		$this->db->where('id', $id );
		$query = $this->db->get();
		return $query->result_array();
	}


	public function getOrderItems( $orderId = 0 )
	{
		$this->db->select('*');
		$this->db->from('order_item');
		$this->db->where('orderId', $orderId );
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getOrderItemsExtras( $orderItemId = 0 )
	{
		$this->db->select('*');
		$this->db->from('order_item_extra');
		$this->db->where('orderItemId', $orderItemId );
		$query = $this->db->get();
		return $query->result_array();
	}






















	public function getOrderProductBySessionId( $session_id = 0 )
	{
		$this->db->select('*');
		$this->db->from('shopping_cart_products');
		$this->db->where('session_id', $session_id );
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getProductNameWithSizeName( $size_id = 0 )
	{
		$this->db->select('*');
		$this->db->select('products.name_az as product_name');
		$this->db->select('product_sizes.name_az as size_name');
		$this->db->from('products');
		$this->db->join('product_sizes', 'products.id = product_sizes.product_id');
		$this->db->where('product_sizes.id', $size_id );

		$query = $this->db->get();
		return $query->result_array();
	}

	public function getOrdersProductsExtras( $shopping_cart_product_id = 0 )
	{
		$this->db->select('*');
		$this->db->from('shopping_cart_extras');
		$this->db->where('shopping_cart_product_id', $shopping_cart_product_id );
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getExtraName( $extra_id = 0 )
	{
		$this->db->select('*');
		$this->db->select('name_az as extra_name');
		$this->db->from('extras');
		$this->db->where('id', $extra_id );

		$query = $this->db->get();
		return $query->result_array();
	}

	public function orderReadyConfirm( $order_id )
	{
		$data = [
			"status" => 2
		];


		$this->db->where('id', $order_id );
		$this->db->update('orders', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}



}

?>
