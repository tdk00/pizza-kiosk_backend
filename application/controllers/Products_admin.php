<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Products_admin
 * @property  admin_products_model $AdminProductsModel
 * @property  admin_categories_model $AdminCategoriesModel
 */
class Products_admin extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/products/admin_products_model', 'AdminProductsModel' );
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

		$config['upload_path']          = './assets/images/products/';
		$config['allowed_types']        = 'jpg|png|jpeg';
		$config['max_size']             = 10000;
		$config['max_width']            = 20000;
		$config['max_height']           = 15000;
		$config['encrypt_name'] = true;

		$this->load->library('upload', $config);
	}

	public function index()
	{
		$products = $this->AdminProductsModel->getAllProducts();
		$this->load->view( 'admin/products/products_list', [ 'products' => $products ] );
	}

	public function add_new()
	{
		$categories = $this->AdminCategoriesModel->getAllCategories();
		$this->load->view( 'admin/products/add_new', [ 'categories' => $categories ]);
	}

	public function edit( $id = 0 )
	{
		$categories = $this->AdminCategoriesModel->getAllCategories();
		$productData = $this->AdminProductsModel->getProductById( $id );
		$productSizes = $this->AdminProductsModel->getSizesByProductId( $id );

		if( count( $productData ) > 0 )
		{
			$this->load->view(
				'admin/products/edit',
				[ 'categories' => $categories,
				  'productData' => $productData,
				  'sizes' => $productSizes ]);
		}
		else
		{
			redirect("products_admin", 'refresh');
		}
	}

	public function insert()
	{
		$product_name_az = $this->input->post('product_name_az');
		$product_name_en = $this->input->post('product_name_en');
		$product_name_ru = $this->input->post('product_name_ru');
		$product_category_id = $this->input->post('product_category');



		$product_sizes_array = $this->input->post('sizes');

		if( empty( $product_sizes_array) )
		{
			die("Ən azı 1 ölçü daxil edilməlidir");
		}

		foreach ( $product_sizes_array as $product_size )
		{
			if( empty( $product_size['size_name_az'] ) || empty( $product_size['size_price'] ) || empty( $product_size['size_barkod'] ) || ! $this->is_string_float( $product_size['size_price'] ) )
			{
				die('Ölçü adı (Azərbaycanca) və qiymət və barkod xanalarını düzgün doldurun');
			}
		}


		if( !empty( $product_name_az ) && !empty( $product_category_id ) )
		{
			if ( ! $this->upload->do_upload('product_image'))
			{
				$error = array('error' => $this->upload->display_errors());
				echo "Şəklin yüklənməsində səhv yarandı, zəhmət olmasa yenidən yoxlayın <br> <br>";
				echo($error['error']);
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$file_name = ! empty( $data['upload_data']['file_name'] ) ? $data['upload_data']['file_name'] : "";
				$inserted_product_id =
					$this->AdminProductsModel->insertProduct(
						$product_name_az,
						$product_name_en,
						$product_name_ru,
						"assets/images/products/".$file_name,
						$product_category_id
					);

				if( $inserted_product_id > 0)
				{
					foreach ( $product_sizes_array as $product_size )
					{
						$this->AdminProductsModel->insertProductSize(
							$product_size['size_name_az'],
							$product_size['size_name_en'],
							$product_size['size_name_ru'],
							$product_size['size_price'],
							$product_size['size_barkod'],
							$inserted_product_id
						);
					}

					redirect("products_admin", 'refresh');
				}

			}
		}
		else
		{
			echo "Məhsul adı(Azərbaycanca) və məhsul kateqoriyası boş qoyula bilməz ";
		}

	}

	public function update( $id = 0 )
	{
		$product_name_az = $this->input->post('product_name_az');
		$product_name_en = $this->input->post('product_name_en');
		$product_name_ru = $this->input->post('product_name_ru');
		$product_category_id = $this->input->post('product_category');



		$product_sizes_array = $this->input->post('sizes');

		if( empty( $product_sizes_array) )
		{
			die("Ən azı 1 ölçü daxil edilməlidir");
		}

		foreach ( $product_sizes_array as $product_size )
		{
			if( empty( $product_size['size_name_az'] ) || empty( $product_size['size_price'] ) || empty( $product_size['size_barkod'] ) || ! $this->is_string_float( $product_size['size_price'] ) )
			{
				die('Ölçü adı (Azərbaycanca) və qiymət və barkod xanalarını düzgün doldurun');
			}
		}


		if( !empty( $product_name_az ) && !empty( $product_category_id ) )
		{
			if ( ! $this->upload->do_upload('product_image'))
			{
				$file_name = "";
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$file_name = ! empty( $data['upload_data']['file_name'] ) ? "assets/images/products/".$data['upload_data']['file_name'] : "";
			}

			$updated =
				$this->AdminProductsModel->updateProduct(
					$id,
					$product_name_az,
					$product_name_en,
					$product_name_ru,
					$product_category_id,
					$file_name
				);

			if( $updated > 0 )
			{
				$this->AdminProductsModel->deleteAllProductSizes( $id );
				foreach ( $product_sizes_array as $product_size )
				{
					$this->AdminProductsModel->insertProductSize(
						$product_size['size_name_az'],
						$product_size['size_name_en'],
						$product_size['size_name_ru'],
						$product_size['size_price'],
						$product_size['size_barkod'],
						$id
					);
				}

				redirect("products_admin", 'refresh');
			}
		}
		else
		{
			echo "Məhsul adı(Azərbaycanca) və məhsul kateqoriyası boş qoyula bilməz ";
		}

	}

	public function delete ( $id = 0 )
	{
		$removed = $this->AdminProductsModel->deleteProduct( $id );

		if( $removed )
		{
			redirect("products_admin", 'refresh');
		}

	}

	public function get_sizes_by_product_id ( $id = 0 )
	{
		$sizes = $this->AdminProductsModel->getSizesByProductId( $id );

		if( count( $sizes ) > 0 )
		{
			echo json_encode(['status'=> true, 'data' => $sizes ]);
		}
		else
		{
			echo json_encode(['status'=> false, 'data' => [] ]);
		}

	}

	private function is_string_float($string) {
		if(is_numeric($string)) {
			$val = $string+0;

			return is_float($val) || is_integer($val);
		}

		return false;
	}
}
