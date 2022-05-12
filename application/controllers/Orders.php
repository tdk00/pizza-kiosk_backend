<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class MenuScreen
 * @property  orders_model $OrdersModel
 */
class Orders extends RestController {

	function __construct()
	{
		parent::__construct();

		$this->load->model('Order/orders_model', "OrdersModel");
	}


	public function add_order_post()
	{
		$order_number = $this->generate_order_number();
		if(
			! empty( $this->post('total_amount') ) &&
			! empty( $this->post('payment_type') )  &&
			! empty( $this->post('session_id') ) )
		{
			$total_amount = $this->post('total_amount');
			$payment_type = $this->post('payment_type');
			$session_id = $this->post('session_id');
			$is_takeaway = empty( $this->post('is_takeaway') ) ? 0 : $this->post('is_takeaway') ;

			if( ! empty( $session_id ))
			{
				$this->OrdersModel->insert_order( $order_number, $total_amount, $payment_type, $session_id, $is_takeaway );
			}
			else
			{
				$this->response( [], 404 );
			}

		}
		else
		{
			$this->response( [], 404 );
		}
	}

	private function generate_order_number() {
		$last_order = $this->OrdersModel->getLastOrder();
		if( count( $last_order ) > 0 )
		{
			$earlier = new DateTime($last_order[0]['date']);
			$earlier->setTime(0, 0);
			$later = new DateTime();
			$later->setTime(0, 0);


			$abs_diff = $later->diff($earlier)->format("%a"); //3
			if( (int) $abs_diff > 0  ||  (int) $last_order[0]['order_number'] > 9998 )
			{
				return 1000;
			}
			else
			{
				if( (int) $last_order[0]['order_number'] < 1000 )
				{
					$last_order[0]['order_number'] = 1000;
				}
				return ((int) $last_order[0]['order_number']) + 1;
			}
		}
		else
		{
			return 1000;
		}
	}

}
