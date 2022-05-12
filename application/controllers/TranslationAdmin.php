<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Kitchen_admin
 * @property  admin_translation_model $AdminTranslationModel
 */
class TranslationAdmin extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		if ( ! $this->session->userdata('logged_in') )
		{
			redirect('/admin/login');
		}
		$this->load->model('admin/translation/admin_translation_model', 'AdminTranslationModel');

	}

	public function index()
	{
		$words = $this->AdminTranslationModel->getAllWords();
		$this->load->view('admin/translation/edit', [ 'words' => $words ]);
	}

	public function update( $wordkey = "", $lang = "" )
	{
		$word = !empty($this->input->post('word')) ? $this->input->post('word') : "";

		if( ! empty( $wordkey ) && ! empty( $lang ) )
		{
			$status = $this->AdminTranslationModel->updateWord( $wordkey, $lang, $word );

			echo json_encode( ["status" => $status ] ) ;
		}
		else
		{
			echo json_encode( ["status" => false ] ) ;
		}
	}


}
