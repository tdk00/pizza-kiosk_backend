<?php
class Admin_extras_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}

	public function getAllExtras()
	{
		$this->db->select('*');
		$this->db->from('extras');
		$this->db->order_by('id','desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getExtraById( $id = 0 )
	{
		$this->db->select('*');
		$this->db->where( 'id', $id );
		$this->db->from('extras');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getRelatedExtrasBySizeId( $size_id = 0 )
	{
		$this->db->select('*');
		$this->db->where( 'size_id', $size_id );
		$this->db->from('product_extra_relation');
		$query = $this->db->get();

		$relations = $query->result_array();

		foreach ( $relations as $relationKey => $relationValue )
		{
			$this->db->select('*');
			$this->db->where( 'id', $relationValue['extra_id'] );
			$this->db->from('extras');
			$query = $this->db->get();
			$extras = $query->result_array();
			if( empty( $extras )  )
			{
				exit("Səhv yarandı");
			}

			$relations[ $relationKey ][ 'extra_name' ] = $extras[0]['name_az'];
		}

		return $relations;
	}

	public function getSizeAndProductInfo( $size_id = 0 )
	{

		$this->db->select('*');
		$this->db->where( 'id', $size_id );
		$this->db->from('product_sizes');
		$query = $this->db->get();

		$sizeDetails = $query->result_array();

		if ( empty( $sizeDetails ) )
		{
			exit("Ölçü tapılmadı");
		}

		$this->db->select('*');
		$this->db->where( 'id', $sizeDetails[0]['product_id'] );
		$this->db->from('products');
		$query = $this->db->get();
		$productDetails = $query->result_array();
		if( empty( $productDetails )  )
		{
			exit("Məhsul tapılmadı");
		}

		$sizeDetails[ 0 ][ 'product_details' ] = $productDetails[0];


		return $sizeDetails[ 0 ];
	}

	public function insertExtra( $name_az = "", $name_en = "", $name_ru = "", $extra_price = 0, $extra_barkod = "", $image = "" )
	{
		$data = [
			"name_az" => $name_az,
			"name_en" => $name_en,
			"name_ru" => $name_ru,
			"price" => $extra_price,
			"barkod" => $extra_barkod,
			"image" => $image
		];
		$this->db->insert('extras', $data );
		return $this->db->insert_id();
	}

	public function updateExtra( $category_id = 0, $name_az = "", $name_en = "", $name_ru = "", $extra_price = 0, $extra_barkod = "", $image = "" )
	{
		$data = [
			"name_az" => $name_az,
			"name_en" => $name_en,
			"name_ru" => $name_ru,
			"price" => $extra_price,
			"barkod" => $extra_barkod
		];

		if( ! empty( $image ) )
		{
			$data['image'] = $image;
		}

		$this->db->where('id', $category_id );
		$this->db->update('extras', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}

	public function deleteExtra( $extra_id = 0 )
	{
		$removed_all = false;
		$removed = $this->db->delete( 'extras', array('id' => $extra_id ) );
		if ( $removed )
		{
			$removed_all = $this->db->delete( 'product_extra_relation', array('extra_id' => $extra_id) );
		}

		if( $removed_all )
		{
			return true;
		}

		return false;

	}

	public function insertRelation( $size_id = 0, $extra_id = 0, $extra_count = 0 )
	{
		$data = [
			"size_id" => $size_id,
			"extra_id" => $extra_id,
			"extra_count" => $extra_count
		];
		$this->db->insert('product_extra_relation', $data );
		return $this->db->insert_id();
	}

	public function deleteAllRelationsBySizeId( $size_id = 0 )
	{
		$this->db->delete('product_extra_relation', array('size_id' => $size_id) );
		return true;
	}
}

?>
