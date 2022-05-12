<?php
class Admin_categories_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}

	public function getAllCategories()
	{
		$this->db->select('*');
		$this->db->from('categories');
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getCategoryById( $id = 0 )
	{
		$this->db->select('*');
		$this->db->where( 'id', $id );
		$this->db->from('categories');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function insertCategory( $name_az = "", $name_en = "", $name_ru = "", $image = "" )
	{
		$data = [
			"name_az" => $name_az,
			"name_en" => $name_en,
			"name_ru" => $name_ru,
			"image" => $image
		];
		$this->db->insert('categories', $data );
		return $this->db->insert_id();
	}

	public function updateCategory( $category_id = 0, $name_az = "", $name_en = "", $name_ru = "", $image = "" )
	{
		$data = [
			"name_az" => $name_az,
			"name_en" => $name_en,
			"name_ru" => $name_ru,
		];

		if( ! empty( $image ) )
		{
			$data['image'] = $image;
		}

		$this->db->where('id', $category_id );
		$this->db->update('categories', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}

	public function deleteCategory( $category_id = 0 )
	{
		$removed_all = false;
		$removed = $this->db->delete( 'categories', array('id' => $category_id ) );
		if ( $removed )
		{
			$removed_all = $this->db->delete( 'products', array('category_id' => $category_id) );
		}

		if( $removed_all )
		{
			return true;
		}

		return false;

	}
}

?>
