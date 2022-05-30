<?php
class Orders_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}

	public function insert_order( $order_number = 1000, $total_amount = 0, $payment_type = 'kassa', $session_id = "", $is_takeaway = 0 )
	{
		if( $total_amount > 0 && strlen( $session_id ) > 5 )
		{
			$data = [
				"order_number" => $order_number,
				"session_id" => $session_id,
				"total" => $total_amount,
				"payment_type" => $payment_type,
				"is_takeaway" => $is_takeaway,
				"status" => 1 // TODO: bu deyisib odenishe uygunlasdirilmalidir
			];
			$this->db->insert('orders', $data );
			return $this->db->insert_id();
		}
		return 0;
	}

	public function getLastOrder()
	{
		$this->db->select('*');
		$this->db->from('orders');
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getOrderItems( $orderId = 0 ){
		$query =  $this->db->query(
			'SELECT orders.*, shopping_cart_products.product_count, product_sizes.barkod as barkod, SUM(shopping_cart_products.product_count) as count, product_sizes.price as price FROM `orders` LEFT JOIN shopping_cart_products ON shopping_cart_products.session_id = orders.session_id JOIN product_sizes ON product_sizes.id = shopping_cart_products.size_id WHERE orders.id = ' . $orderId . ' Group By product_sizes.barkod;'
		);

		return $query->result_array();
	}

	public function getOrderExtras( $orderId = 0 ){
		$query =  $this->db->query(
			'SELECT orders.*, shopping_cart_extras.extra_count, shopping_cart_extras.extra_count,  extras.barkod as barkod, SUM( shopping_cart_extras.extra_count - shopping_cart_extras.extra_default_count ) as count, extras.price as price FROM `orders` LEFT JOIN shopping_cart_extras ON shopping_cart_extras.session_id = orders.session_id JOIN extras ON shopping_cart_extras.extra_id = extras.id WHERE  shopping_cart_extras.extra_default_count < shopping_cart_extras.extra_count AND orders.id = ' . $orderId . ' Group By extras.barkod;'
		);

		return $query->result_array();
	}



}

?>
