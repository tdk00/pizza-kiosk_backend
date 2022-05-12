<?php
class Admin_translation_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}

	public function getAllWords()
	{
		$this->db->select('*');
		$this->db->from('translations');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function updateWord( $wordKey, $lang, $word )
	{

		$data = [
			$lang => $word,
		];

		if( ! empty( $image ) )
		{
			$data['image'] = $image;
		}

		$this->db->where('word_key', $wordKey );
		$this->db->update('translations', $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}
}

?>
