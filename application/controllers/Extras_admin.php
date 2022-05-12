<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Categories_admin
 * @property  admin_extras_model $AdminExtrasModel
 */
class Extras_admin extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/extras/admin_extras_model', 'AdminExtrasModel' );

		$this->load->library('session');
		$this->load->helper('url');
		if ( !$this->session->userdata('logged_in'))
		{
			redirect('/admin/login');
		}
		else
		{
			if( $this->session->userdata('role') !== "kitchen" &&  $this->session->userdata('role') !== "admin"  )
			{
				redirect('/admin/login');
			}

			if( $this->session->userdata('role') == "kitchen" )
			{
				redirect('/kitchen_admin/');
			}
		}

		$config['upload_path']          = './assets/images/extras/';
		$config['allowed_types']        = 'jpg|png|jpeg';
		$config['max_size']             = 10000;
		$config['max_width']            = 20000;
		$config['max_height']           = 15000;
		$config['encrypt_name'] = true;

		$this->load->library('upload', $config);
	}

	public function index()
	{
		$extras = $this->AdminExtrasModel->getAllExtras();
		$this->load->view( 'admin/extras/extras_list', [ 'extras' => $extras ] );
	}
	public function add_new()
	{
		$this->load->view( 'admin/extras/add_new');
	}

	public function per_add_new() // product_extra_relation elave etmek sehifesi
	{
		if( empty( $this->input->post('product_size') ) ||  ! is_numeric( $this->input->post('product_size') ) )
		{
			die( 'Ölçü düzgün seçilməmişdir' );
		}
		$size_id = $this->input->post('product_size');
		$size_and_product_details = $this->AdminExtrasModel->getSizeAndProductInfo( $size_id );
		$related_extras = $this->AdminExtrasModel->getRelatedExtrasBySizeId( $size_id );
		$all_extras = $this->AdminExtrasModel->getAllExtras();

		$this->load->view( 'admin/extras/product_extra_relation_add_new',
			[
				'related_extras' => $related_extras,
				'size_and_product_details'=> $size_and_product_details,
				'all_extras' => $all_extras
			]
		);
	}

	public function edit ( $id = 0 )
	{
		$extraData = $this->AdminExtrasModel->getExtraById( $id );
		if( count( $extraData ) > 0 )
		{
			$this->load->view( 'admin/extras/edit', [ 'extraData' => $extraData ]);
		}
		else
		{
			redirect("extras_admin", 'refresh');
		}

	}

	public function insert()
	{

		if( empty( $this->input->post('extra_name_az') ) || empty( $this->input->post('extra_price') )  || empty( $this->input->post('extra_barkod') ) )
		{
			die('Extra adı (Azərbaycanca) və qiymət və barkod xanalarını düzgün doldurun');
		}

		$extra_name_az = $this->input->post('extra_name_az');
		$extra_name_en = $this->input->post('extra_name_en');
		$extra_name_ru = $this->input->post('extra_name_ru');
		$extra_price = $this->input->post('extra_price');
		$extra_barkod = $this->input->post('extra_barkod');

		if ( ! $this->upload->do_upload('extra_image'))
		{
			$error = array('error' => $this->upload->display_errors());
			echo "Şəklin yüklənməsində səhv yarandı, zəhmət olmasa yenidən yoxlayın <br> <br>";
			echo($error['error']);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$file_name = ! empty( $data['upload_data']['file_name'] ) ? $data['upload_data']['file_name'] : "";
			$insert_id =
				$this->AdminExtrasModel->insertExtra(
					$extra_name_az,
					$extra_name_en,
					$extra_name_ru,
					$extra_price,
					$extra_barkod,
					"assets/images/extras/".$file_name
				);

			if( $insert_id > 0)
			{
				redirect("extras_admin", 'refresh');
			}

		}

	}

	public function update( $id = 0 )
	{
		if( empty( $this->input->post('extra_name_az') ) || empty( $this->input->post('extra_price') )  || empty( $this->input->post('extra_barkod') ) )
		{
			die('Extra adı (Azərbaycanca) və qiymət və barkod xanalarını düzgün doldurun');
		}


		$extra_name_az = $this->input->post('extra_name_az');
		$extra_name_en = $this->input->post('extra_name_en');
		$extra_name_ru = $this->input->post('extra_name_ru');
		$extra_price = $this->input->post('extra_price');
		$extra_barkod = $this->input->post('extra_barkod');

			if ( ! $this->upload->do_upload('extra_image'))
			{
				$file_name = "";
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$file_name = ! empty( $data['upload_data']['file_name'] ) ? "assets/images/extras/".$data['upload_data']['file_name'] : "";
			}

			$updated =
				$this->AdminExtrasModel->updateExtra(
					$id,
					$extra_name_az,
					$extra_name_en,
					$extra_name_ru,
					$extra_price,
					$extra_barkod,
					$file_name
				);

			if( $updated > 0 )
			{
				redirect("extras_admin", 'refresh');
			}


	}

	public function delete ( $id = 0 )
	{
		$removed = $this->AdminExtrasModel->deleteExtra( $id );

		if( $removed )
		{
			redirect("extras_admin", 'refresh');
		}

	}

	public function update_relation( $size_id = 0 ) {

		if( empty( $size_id ) || !is_numeric( $size_id ) )
		{
			redirect("products_admin", 'refresh');
		}

		$extras_array = $this->input->post('extras');

		if( empty( $extras_array) )
		{
			die("Ən azı 1 extra daxil edilməlidir");
		}

		foreach ( $extras_array as $extra )
		{
			if( empty( $extra['extra_id'] ) || empty( $extra['extra_count'] ) || ! is_numeric( $extra['extra_id'] ) || ! is_numeric( $extra['extra_count'] ) )
			{
				die('Extranın məlumatlarını düzgün doldurun');
			}
		}

			$this->AdminExtrasModel->deleteAllRelationsBySizeId( $size_id );
			foreach ( $extras_array as $extra )
			{
				$this->AdminExtrasModel->insertRelation(
					$size_id,
					$extra['extra_id'],
					$extra['extra_count']
				);
			}

			redirect("products_admin", 'refresh');

	}

	private function is_string_float($string) {
		if(is_numeric($string)) {
			$val = $string+0;

			return is_float($val) || is_integer($val);
		}

		return false;
	}
}
