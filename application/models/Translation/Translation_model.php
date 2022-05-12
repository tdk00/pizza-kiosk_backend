<?php
class Translation_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}


	public function getTranslation( $lang = 'az', $word = '' )
	{
		$this->db->select('*');
		$this->db->from('translations');
		$this->db->select($lang.' as translation');
		$this->db->where('word_key', $word );
		$query = $this->db->get();
		return count( $query->result_array() )  > 0 ? $query->result_array()[0][$lang] : "";
	}



}
?>
