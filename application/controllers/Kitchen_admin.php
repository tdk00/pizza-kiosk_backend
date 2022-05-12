<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Kitchen_admin
 * @property  admin_kitchen_model $AdminKitchenModel
 */
class Kitchen_admin extends CI_Controller
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
		$this->load->model('admin/kitchen/admin_kitchen_model', 'AdminKitchenModel');

	}

	public function index()
	{
		$unready_orders = $this->AdminKitchenModel->getUnreadyOrders();
		$this->load->view('admin/kitchen/unready_orders', [ 'orders' => $unready_orders ]);
	}

	public function order_details( $id )
	{
		$order_details = $this->AdminKitchenModel->getOrder( $id );
		if( count( $order_details ) > 0  )
		{
			$order_products = $this->AdminKitchenModel->getOrderProductBySessionId( $order_details[0]["session_id"] );
			foreach ( $order_products as $productKey => $productValue )
			{
				$name_and_size_array = $this->AdminKitchenModel->getProductNameWithSizeName( $productValue["size_id"] );
				if( count( $name_and_size_array ) < 1 )
				{
					$order_products[ $productKey ]['product_name'] = "Mehsul tapilmadi";
					$order_products[ $productKey ]['size_name'] = "Olcu tapilmadi";
					$order_products[ $productKey ]['extras'] = [];
				}
				else
				{
					$order_products[ $productKey ]['product_name'] = $name_and_size_array[0]['product_name'];
					$order_products[ $productKey ]['size_name'] = $name_and_size_array[0]['size_name'];
					$order_products[ $productKey ]['extras'] = $this->AdminKitchenModel->getOrdersProductsExtras( $productValue["id"] );
					foreach ( $order_products[ $productKey ]['extras'] as $extraKey => $extraValue )
					{
						$extra_name_array = $this->AdminKitchenModel->getExtraName( $extraValue["extra_id"] );
						if( count( $extra_name_array ) < 1 )
						{
							$order_products[ $productKey ]['extras'][$extraKey]['extra_name'] = "Extra tapilmadi";
						}
						else
						{
							$order_products[ $productKey ]['extras'][$extraKey]['extra_name'] = $extra_name_array[0]['extra_name'];
						}

					}
				}

			}
			$this->load->view('admin/kitchen/order_details', [ 'order_products' => $order_products, "order_details" => $order_details[0] ]);
		}

	}

	public function order_ready_confirm( $id = 0 )
	{
		$this->AdminKitchenModel->orderReadyConfirm( $id );
		redirect('/kitchen_admin');

	}

}
