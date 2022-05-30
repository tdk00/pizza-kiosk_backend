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
				$orderId = $this->OrdersModel->insert_order( $order_number, $total_amount, $payment_type, $session_id, $is_takeaway );
				if( $orderId > 0 )
				{
					$items = $this->OrdersModel->getOrderItems( $orderId );
					$orderObj = new OlypmosOrder( $orderId );
					foreach ( $items as $itemKey => $itemValue )
					{
						if( $itemValue['count'] == 0 || $itemValue['price'] == 0 )
						{
							continue;
						}
						$olymposPrice = $this->getOlymposPrice( $itemValue['barkod'] );
						if( $olymposPrice !== false)
						{
							$items[ $itemKey ]['price'] = $olymposPrice;
							$itemValue['price'] = $olymposPrice;
						}
						$orderItem = new OlypmosOrderItem( $itemValue['barkod'], $itemValue['count'], $itemValue['price'] );
						$orderObj->addItemToOrder( $orderItem );

					}
					$extras = $this->OrdersModel->getOrderExtras( $orderId );
					foreach ( $extras as $extraKey => $extraValue )
					{
						if( $extraValue['count'] == 0 || $extraValue['price'] == 0 )
						{
							continue;
						}
						$olymposPrice = $this->getOlymposPrice( $extraValue['barkod'] );
						if( $olymposPrice !== false)
						{
							$extras[ $extraKey ]['price'] = $olymposPrice;
							$extraValue['price'] = $olymposPrice;
						}
						$orderItem = new OlypmosOrderItem( $extraValue['barkod'], $extraValue['count'], $extraValue['price'] );
						$orderObj->addItemToOrder( $orderItem );

					}
					$statusCode = $this->curPostRequestAddOrderToOlympos(json_encode([$orderObj]));

					$this->response( [ 'orderObj' => json_encode([$orderObj]), 'orderNumber' => $order_number, 'orderId' => $orderId ], $statusCode );
				}
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

	private function curPostRequestAddOrderToOlympos( $data )
	{
//		return 200;
		/* Endpoint */
		$url = 'http://192.168.100.97:8080/ords/olympos/olympos/sepet/';

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);


		return $httpcode;
	}

	private function getOlymposPrice( $barkod )
	{
//		return false;
		$url = 'http://192.168.100.97:8080/ords/olympos/olympos/fiyat/' . $barkod;

		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		if( $httpcode == 200 )
		{
			$result = json_decode($result);
			if( !empty( $result->items) )
			{
				if( !empty( $result->items[0]->fiyat) && $result->items[0]->fiyat > 0 )
				{
					return $result->items[0]->fiyat;
				}
			}

		}


		return false;
	}

}


class OlypmosOrder implements JsonSerializable {
	private $orderId;
	private $pcode = "KPIZ";
	private $sepet = [];


	public function __construct( $orderId )
	{
		$this->orderId = $orderId;
	}

	public function addItemToOrder( $item ){
		$this->sepet[] = $item;
	}

	public function jsonSerialize() {
		return [
			'orderId' => $this->orderId,
			'pcode' => $this->pcode,
			'sepet' => $this->sepet
		];
	}

}

class OlypmosOrderItem implements JsonSerializable{
	private $barkod;
	private $miktar;
	private $fiyat;
	public function __construct( $barkod, $miktar, $fiyat )
	{
		$this->barkod =  $barkod;
		$this->miktar = $miktar;
		$this->fiyat = $fiyat;
	}

	public function jsonSerialize() {
		return [
			'barkod' => $this->barkod,
			'miktar' => $this->miktar,
			'fiyat' => $this->fiyat
		];
	}
}
