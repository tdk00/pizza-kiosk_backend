<?php
class Orders_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}

	public function getOrder( $orderId = 0 ){

		$this->db->select('*');
		$this->db->where('id', $orderId);
		$this->db->from('orders');
		$query = $this->db->get();
		return $query->result_array();
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

	public function insert_item(){

	}

	public function insert_order_new( $order_number = 1000, $total_amount = 0, $payment_type = 'kassa', $is_takeaway = 0 )
	{
		if( $total_amount > 0 )
		{
			$data = [
				"order_number" => $order_number,
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

	public function insert_order_item_new( $productName = "", $productSizeName = "", $productImage ="" ,
										   $productTotal ="" , $productPrice ="" , $productCount = "", $productSizeBarcode ="", $orderId = 0)
	{
			$data = [
				"product_name" => $productName,
				"product_size_name" => $productSizeName,
				"product_image" => $productImage,
				"item_total" => $productTotal,
				"item_price" => $productPrice,
				"item_count" => $productCount,
				"item_barkod" => $productSizeBarcode,
				"orderId" => $orderId
			];
			$this->db->insert('order_item', $data );
			return $this->db->insert_id();
	}

	public function insert_order_item_extra_new( $extraName, $extraImage, $extraDefaultCount, $extraCount, $extraBarcode, $orderId, $extraOrderItemId, $extraPrice )
	{
		$data = [
			"itemExtraName" => $extraName,
			"itemExtraImage" => $extraImage,
			"itemExtraDefaultCount" => $extraDefaultCount,
			"itemExtraCount" => $extraCount,
			"itemExtraBarcode" => $extraBarcode,
			"orderId" => $orderId,
			"orderItemId" => $extraOrderItemId,
			"extraPrice" => $extraPrice
		];
		$this->db->insert('order_item_extra', $data );
		return $this->db->insert_id();
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

	public function setOrderSuccess( $orderId )
	{

		$data = [
			"status" => 1
		];


		$this->db->where('id', $orderId  );
		$this->db->update('orders', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}

	public function setOrderError( $orderId, $errorMsg )
	{
		$data = [
			"status" => -1,
			"errorMsg" => $errorMsg
		];


		$this->db->where('id', $orderId  );
		$this->db->update('orders', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
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


	public function getOrderItemsNew( $orderId = 0 ){
		$this->db->select('*');
		$this->db->where('orderId', $orderId);
		$this->db->from('order_item');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getOrderExtrasNew( $orderId = 0 ){
		$this->db->select('*');
		$this->db->where('orderId', $orderId);
		$this->db->from('order_item_extra');
		$query = $this->db->get();
		return $query->result_array();
	}



}

?>
