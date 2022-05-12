<?php
class Customer_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getUnReadyOrders()
	{
		$this->db->select('*');
		$this->db->from('orders');
		$this->db->where('status', 1); // status 1 - hazirlanir statusunda olan sifarislerdir
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getReadyOrders()
	{
		$this->db->select('*');
		$this->db->from('orders');
		$this->db->where('status', 2); // status 2 - hazirdir statusunda olan sifarislerdir
		$query = $this->db->get();
		return $query->result_array();
	}
}
