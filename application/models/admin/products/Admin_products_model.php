<?php
class Admin_products_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}

	public function getAllProducts()
	{
		$this->db->select('*');
		$this->db->from('products');
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getProductById( $id = 0 )
	{
		$this->db->select('*');
		$this->db->from('products');
		$this->db->where('id', $id );
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getSizesByProductId( $id = 0 )
	{
		$this->db->select('*');
		$this->db->from('product_sizes');
		$this->db->where('product_id',$id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function insertProduct( $name_az = "", $name_en = "", $name_ru = "", $image = "", $category_id = 0 )
	{
		$data = [
			"name_az" => $name_az,
			"name_en" => $name_en,
			"name_ru" => $name_ru,
			"image" => $image,
			"category_id" => $category_id
		];
		$this->db->insert('products', $data );
		return $this->db->insert_id();
	}

	public function insertProductSize( $name_az = "", $name_en = "", $name_ru = "", $price = 0, $barkod = "", $product_id = 0 )
	{
		$data = [
			"name_az" => $name_az,
			"name_en" => $name_en,
			"name_ru" => $name_ru,
			"price" => $price,
			"barkod" => $barkod,
			"product_id" => $product_id
		];
		$this->db->insert('product_sizes', $data );
		return $this->db->insert_id();
	}

	public function updateProduct( $product_id = 0, $name_az = "", $name_en = "", $name_ru = "", $product_category_id = 0, $image = "" )
	{
		$data = [
			"name_az" => $name_az,
			"name_en" => $name_en,
			"name_ru" => $name_ru,
			"category_id" => $product_category_id
		];

		if( ! empty( $image ) )
		{
			$data['image'] = $image;
		}

		$this->db->where('id', $product_id );
		$this->db->update('products', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}

	public function deleteProduct( $product_id = 0 )
	{
		$removed_all = false;
		$removed = $this->db->delete( 'products', array('id' => $product_id ) );
		if ( $removed )
		{
			$removed_all = $this->db->delete( 'product_sizes', array('product_id' => $product_id) );
		}

		if( $removed_all )
		{
			return true;
		}

		return false;

	}

	public function deleteAllProductSizes( $product_id = 0 )
	{
		$this->db->delete('product_sizes', array('product_id' => $product_id) );
		return true;
	}


}

?>
