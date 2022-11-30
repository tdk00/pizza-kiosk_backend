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


	public function add_order_to_olympos_post( $orderId ) {


		$order = $this->OrdersModel->getOrder( $orderId );
		if( empty($order) ) {
			return false;
		}

		$items = $this->OrdersModel->getOrderItemsNew( $orderId );
		$orderObj = new OlypmosOrder( $orderId );

		foreach ( $items as $itemKey => $itemValue )
		{
			if( $itemValue['item_count'] == 0 || $itemValue['item_total'] == 0 )
			{
				continue;
			}
			$olymposPrice = $this->getOlymposPrice( $itemValue['item_barkod'] );
			if( $olymposPrice !== false)
			{
				$items[ $itemKey ]['item_price'] = $olymposPrice;
				$itemValue['item_price'] = $olymposPrice;
			}
			$orderItem = new OlypmosOrderItem( $itemValue['item_barkod'], $itemValue['item_count'], $itemValue['item_price'] );
			$orderObj->addItemToOrder( $orderItem );

		}

		$extras = $this->OrdersModel->getOrderExtrasNew( $orderId );
		foreach ( $extras as $extraKey => $extraValue )
		{
			$extraValue['itemExtraCount'] = $extraValue['itemExtraCount'] - $extraValue['itemExtraDefaultCount'] > 0 ? $extraValue['itemExtraCount'] : 0;
			if( $extraValue['itemExtraCount'] == 0 || $extraValue['extraPrice'] == 0 )
			{
				continue;
			}
			$olymposPrice = $this->getOlymposPrice( $extraValue['itemExtraBarcode'] );
			if( $olymposPrice !== false)
			{
				$extras[ $extraKey ]['extraPrice'] = $olymposPrice;
				$extraValue['extraPrice'] = $olymposPrice;
			}
			$orderItem = new OlypmosOrderItem( $extraValue['itemExtraBarcode'], $extraValue['itemExtraCount'], $extraValue['extraPrice'] );
			$orderObj->addItemToOrder( $orderItem );

		}
		$responseArr = $this->curPostRequestAddOrderToOlympos(json_encode([$orderObj]));

		$errorMsg = "";

		if( $responseArr['http_code'] != 200 ) {

			$errorMsg = $responseArr['result'];
			return ["status" => false, "error_msg" => $errorMsg];
		}
		else {
			return ["status" => true, "error_msg" => ''];
		}


	}

	public function add_order_new_post()
	{
		$order_number = $this->generate_order_number();
		$orderObj = $this->post('order');

		if( empty($orderObj) ) {
			$this->response( [], 404 );
		}
		else {
			if(
				! isset( $orderObj['total'] ) ||
				! isset( $orderObj['is_takeaway'] ) ||
				! isset( $orderObj['payment_type'] ) ||
				! isset( $orderObj['status'] )
			)
			{
				$this->response( [], 404 );
			}
		}

		$total_amount =  $orderObj['total'];
		$payment_type = $orderObj['payment_type'];
		$is_takeaway = empty( $orderObj['is_takeaway'] ) ? 0 : $orderObj['is_takeaway'];


		$productsArray =  json_decode($orderObj['products'], true);

		$orderId = $this->OrdersModel->insert_order_new( $order_number, $total_amount, $payment_type, $is_takeaway );

		if( $orderId > 0 )
		{
			foreach ( $productsArray as $productKey => $productValue ) {
				if( !empty( json_decode($productValue['sizes'], true) ) ) {

					$productName = $productValue['name'];
					$productImage = $productValue['image'];
					$productSize = json_decode($productValue['sizes'], true)[0];
					$productSizeName = $productSize['size_name'];
					$productCount = $productSize['count'];
					$productTotal = $productSize['total_price'];
					$productPrice = $productSize['price'];
					$productSizeBarcode = $productSize['barkod'];

					$orderItemId = $this->OrdersModel->insert_order_item_new( $productName, $productSizeName, $productImage, $productTotal, $productPrice, $productCount, $productSizeBarcode, $orderId );

					if( $orderItemId > 0 ) {
						$extras = $productSize['extras'];
						foreach ($extras as  $extraKey => $extraValue) {
							$extraName = $extraValue['name'];
							$extraImage = $extraValue['image'];
							$extraDefaultCount = $extraValue['extra_default_count'];
							$extraCount = $extraValue['extra_count'];
							$extraBarcode = $extraValue['barkod'];
							$extraPrice = $extraValue['price'];
							$extraOrderItemId = $orderItemId;
							$itemExtraId = $this->OrdersModel->insert_order_item_extra_new( $extraName, $extraImage, $extraDefaultCount, $extraCount, $extraBarcode, $orderId, $extraOrderItemId, $extraPrice );

						}
					}

				}

			}

			$olymposResponse = $this->add_order_to_olympos_post( $orderId );
			if( $olymposResponse['status'] ) {
				$this->OrdersModel->setOrderSuccess( $orderId );
				$this->response( [ 'status' => true, 'orderNumber' => $order_number, 'orderId' => $orderId ], 200 );
			}
			else {
				$errorMsg =$olymposResponse['error_msg'];
				$this->OrdersModel->setOrderError( $orderId, $errorMsg );
			}
		}
		$this->response( [ 'status' => false, 'orderNumber' => "0", 'orderId' => "0" ], 200 );
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

		$result = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);




		return ["http_code" => $httpcode, "result" => $result];
	}

	private function getOlymposPrice( $barkod )
	{
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
