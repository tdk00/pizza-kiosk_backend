<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Products_admin
 * @property  admin_products_model $AdminProductsModel
 * @property  admin_categories_model $AdminCategoriesModel
 * @property  admin_orders_model $AdminOrdersModel
 * @property  admin_kitchen_model $AdminKitchenModel
 */
class Orders_admin extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/products/admin_products_model', 'AdminProductsModel' );
		$this->load->model('admin/categories/admin_categories_model', 'AdminCategoriesModel' );
		$this->load->model('admin/orders/admin_orders_model', 'AdminOrdersModel' );
		$this->load->model('admin/kitchen/admin_kitchen_model', 'AdminKitchenModel');
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
	}

	public function index( $status = 0 )
	{
		if( $status == 4 )
		{
			$orders = $this->AdminOrdersModel->getAllOrders();
		}
		else
		{
			$orders = $this->AdminOrdersModel->getOrdersByStatus( $status );
		}


		$this->load->view( 'admin/orders/orders_list', [ 'orders' => $orders, 'status' => $status ] );
	}

	public function change_status( $status = 0, $order_id = 0 )
	{
		$allowed_statuses = [0,1,2,3];
		if( in_array( $status, $allowed_statuses ) )
		{
			$this->AdminOrdersModel->updateStatus( $status, $order_id );
			redirect('/orders_admin/index/4');
		}
		else
		{
			redirect('/orders_admin/index/4');
		}
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
			$this->load->view('admin/orders/order_details', [ 'order_products' => $order_products, "order_details" => $order_details[0] ]);
		}

	}
	public function order_details_new( $id )
	{
		$order_details = $this->AdminKitchenModel->getOrder( $id );
		if( count( $order_details ) > 0  )
		{
			$orderItems = $this->AdminKitchenModel->getOrderItems( $order_details[0]["id"] );
			foreach ( $orderItems as $itemKey => $itemValue )
			{
				$orderItems[$itemKey]['extras'] =  $this->AdminKitchenModel->getOrderItemsExtras( $itemValue["id"] );

			}
			$this->load->view('admin/kitchen/order_details', [ 'items' => $orderItems, "order_details" => $order_details[0] ]);
		}

	}


}
