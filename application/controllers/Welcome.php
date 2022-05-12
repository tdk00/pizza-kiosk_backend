<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Welcome
 * @property  customer_model $CustomerModel
 */
class Welcome extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('customer/customer_model', 'CustomerModel');
	}
	public function index()
	{
		$unreadyOrders = $this->CustomerModel->getUnReadyOrders();
		$readyOrders = $this->CustomerModel->getReadyOrders();
		$this->load->view('customer/order_screen' , ['unready_orders' => $unreadyOrders, 'ready_orders' => $readyOrders ]);
	}

	public function get_orders()
	{
		$unreadyOrders = $this->CustomerModel->getUnReadyOrders();
		$readyOrders = $this->CustomerModel->getReadyOrders();
		$result = array("unready_orders"=>"","ready_orders"=>"");
		$result["unready_orders"] = $this->load->view('customer/unready_orders_template', [ 'unready_orders' => $unreadyOrders ], TRUE);
		$result["ready_orders"] = $this->load->view('customer/ready_orders_template', [ 'ready_orders' => $readyOrders ], TRUE);

		echo json_encode($result);

	}
}
