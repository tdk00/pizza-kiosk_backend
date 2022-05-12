<?php
class Admin_orders_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}

	public function getAllOrders()
	{
		$this->db->select('*');
		$this->db->from('orders');
		$this->db->order_by('id','desc');
		$this->db->limit(100);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getOrdersByStatus( $status = 0 )
	{
		$this->db->select('*');
		$this->db->from('orders');
		$this->db->where('status', $status );
		$this->db->order_by('id','desc');
		$this->db->limit(100);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function updateStatus( $status = 0, $order_id = 0 )
	{
		$data = [
			"status" => $status
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
