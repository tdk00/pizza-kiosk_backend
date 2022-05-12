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



}
?>
