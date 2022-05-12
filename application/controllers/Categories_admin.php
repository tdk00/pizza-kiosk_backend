<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Categories_admin
 * @property  admin_categories_model $AdminCategoriesModel
 */
class Categories_admin extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/categories/admin_categories_model', 'AdminCategoriesModel' );
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

		$config['upload_path']          = './assets/images/categories/';
		$config['allowed_types']        = 'jpg|png|jpeg';
		$config['max_size']             = 10000;
		$config['max_width']            = 20000;
		$config['max_height']           = 15000;
		$config['encrypt_name'] = true;

		$this->load->library('upload', $config);
	}

	public function index()
	{
		$categories = $this->AdminCategoriesModel->getAllCategories();
		$this->load->view( 'admin/categories/categories_list', [ 'categories' => $categories ] );
	}
	public function add_new()
	{
		$this->load->view( 'admin/categories/add_new');
	}

	public function edit ( $id = 0 )
	{
		$categoryData = $this->AdminCategoriesModel->getCategoryById( $id );
		if( count( $categoryData ) > 0 )
		{
			$this->load->view( 'admin/categories/edit', [ 'categoryData' => $categoryData ]);
		}
		else
		{
			redirect("categories_admin", 'refresh');
		}

	}

	public function insert()
	{
		$category_name_az = $this->input->post('category_name_az');
		$category_name_en = $this->input->post('category_name_en');
		$category_name_ru = $this->input->post('category_name_ru');

		if( !empty( $category_name_az ) )
		{
			if ( ! $this->upload->do_upload('category_image'))
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
					$this->AdminCategoriesModel->insertCategory(
						$category_name_az,
						$category_name_en,
						$category_name_ru,
						"assets/images/categories/".$file_name
					);

				if( $insert_id > 0)
				{
					redirect("categories_admin", 'refresh');
				}

			}
		}
		else
		{
			echo "Kateqoriya adı (Azərbaycanca) boş qoyula bilməz ";
		}

	}

	public function update( $id = 0 )
	{
		$category_name_az = $this->input->post('category_name_az');
		$category_name_en = $this->input->post('category_name_en');
		$category_name_ru = $this->input->post('category_name_ru');


		if( !empty( $category_name_az ) )
		{
				if ( ! $this->upload->do_upload('category_image'))
				{
					$file_name = "";
				}
				else
				{
					$data = array('upload_data' => $this->upload->data());
					$file_name = ! empty( $data['upload_data']['file_name'] ) ? "assets/images/categories/".$data['upload_data']['file_name'] : "";
				}

			$updated =
				$this->AdminCategoriesModel->updateCategory(
					$id,
					$category_name_az,
					$category_name_en,
					$category_name_ru,
					$file_name
				);

			if( $updated > 0 )
			{
				redirect("categories_admin", 'refresh');
			}
		}
		else
		{
			echo "Kateqoriya adı (Azərbaycanca) boş qoyula bilməz ";
		}

	}

	public function delete ( $id = 0 )
	{
		$removed = $this->AdminCategoriesModel->deleteCategory( $id );

		if( $removed )
		{
			redirect("categories_admin", 'refresh');
		}

	}
}
