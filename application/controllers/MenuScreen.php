<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class MenuScreen
 * @property  categories_model $CategoriesModel
 * @property  products_model $ProductsModel
 * @property  shopping_cart_model $ShoppingCartModel
 */
class MenuScreen extends RestController {

	function __construct()
	{
		parent::__construct();

		$this->load->model('MenuScreen/categories_model', "CategoriesModel");
		$this->load->model('MenuScreen/products_model', "ProductsModel");
		$this->load->model('MenuScreen/shopping_cart_model', "ShoppingCartModel");
	}

	public function category_list_get()
	{
		$lang = $this->get( 'lang' );

		if( ! in_array($lang, ['az', 'en', 'ru']) )
		{
			$lang = 'az';
		}
		$categories = $this->CategoriesModel->getAllCategories( $lang );

		if( count($categories) > 0 )
		{
			$this->response( $categories, 200 );
		}
		else
		{
			$this->response( [], 404 );
		}

	}

	public function products_list_by_category_get()
	{
		$lang = $this->get( 'lang' );
		$category_id = (int)$this->get( 'category_id' ) > 0  ? $this->get( 'category_id' ) : 0;

		if( ! in_array($lang, ['az', 'en', 'ru']) )
		{
			$lang = 'az';
		}
		$products = $this->ProductsModel->getProductsByCategoryId( $lang, $category_id );

		foreach ( $products as $productKey => $productValue )
		{
			if( ! $this->ProductsModel->ifSizeExists( $productValue['id'] ) )
			{
				unset( $products[ $productKey ] );
			}
		}
		$products = array_values($products);

		if( count($products) > 0 )
		{
			$this->response( $products, 200 );
		}
		else
		{
			$this->response( [], 404 );
		}

	}

	public function shopping_cart_get()
	{
		$lang = $this->get( 'lang' );
		$session_key = strlen( (string)$this->get( 'session_id' ) ) > 0  ? $this->get( 'session_id' ) : "0";

		if( ! in_array($lang, ['az', 'en', 'ru']) )
		{
			$lang = 'az';
		}
		$total = 0;
		$products = $this->ShoppingCartModel->getShoppingCartProducts( $lang, $session_key );

		foreach ( $products as $productKey => $productValue )
		{
			$olymposPrice = $this->getOlymposPrice( $productValue['barkod'] );
			if( $olymposPrice !== false)
			{
				$products[$productKey]['price'] = $olymposPrice;
				$productValue['price'] = $olymposPrice;
			}
			$products[ $productKey ][ 'extras' ] = $this->ShoppingCartModel->getSCProductExtras( $productValue [ 'shopping_cart_product_id' ] );
			$total += $this->calculateShoppingCartTotal( $productValue['price'], $productValue['product_count'], $products[ $productKey ][ 'extras' ]);
		}

		foreach ( $products as $productKey => $productValue )
		{
			$olymposPrice = $this->getOlymposPrice( $productValue['barkod'] );
			if( $olymposPrice !== false)
			{
				$products[$productKey]['price'] = $olymposPrice;
				$productValue['price'] = $olymposPrice;
			}
			$products[ $productKey ][ 'extras' ] = $this->ShoppingCartModel->getSCProductExtras( $productValue [ 'shopping_cart_product_id' ] );
			$products [ $productKey ] [ 'price_including_extras' ] = $this->calculatePriceIncludingExtras( $productValue['price'], $products[ $productKey ][ 'extras' ]);
			$products [ $productKey ] [ 'total' ] = $total;
		}

		if( count($products) > 0 )
		{
			$this->response( $products, 200 );
		}
		else
		{
			$this->response( [], 404 );
		}

	}

	public function remove_shopping_cart_item_get()
	{
		$session_key = strlen( (string)$this->get( 'session_id' ) ) > 0  ? $this->get( 'session_id' ) : "0";
		$shopping_cart_product_id = ! empty ( $this->get( 'shopping_cart_product_id' ) )  ? $this->get( 'shopping_cart_product_id' ) : "0";

		$removed = $this->ShoppingCartModel->removeShoppingCartItem( $session_key, $shopping_cart_product_id );


		if( $removed )
		{
			$this->response( $shopping_cart_product_id, 200 );
		}
		else
		{
			$this->response( [], 404 );
		}
	}

	public function update_shopping_cart_product_counts_post()
	{

		if( ! empty( $this->post('product_list') ) && ! empty( $this->post('session_id') ) )
		{
			$session_id = $this->post('session_id');
			$product_list = json_decode( $this->post('product_list') );

			if( ! empty( $session_id ))
			{
				foreach ( $product_list as $product )
				{
					var_dump( $this->ShoppingCartModel->updateShoppingCartProductCounts( $product->id, $product->count ) );
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

	private function calculatePriceIncludingExtras( $productPrice = 0, $extrasArray = [])
	{
		$productPrice = $productPrice * 100;
		foreach ( $extrasArray as $extraKey => $extra )
		{
			$olymposPrice = $this->getOlymposPrice( $extra['barkod'] );
			if( $olymposPrice !== false)
			{
				$extrasArray[ $extraKey ]['price'] = $olymposPrice;
				$extra['price'] = $olymposPrice;
			}
			if( $extra['extra_count'] >= $extra['extra_default_count'] )
			{
				$productPrice += ( $extra['extra_count'] -  $extra['extra_default_count'] ) * $extra['price'] * 100 ;
			}
		}
		return $productPrice / 100;
	}

	private function calculateShoppingCartTotal( $productPrice = 0, $productCount = 0, $extrasArray = [])
	{
		$productPrice = $productPrice * 100;
		foreach ( $extrasArray as $extraKey => $extra )
		{
			$olymposPrice = $this->getOlymposPrice( $extra['barkod'] );
			if( $olymposPrice !== false)
			{
				$extrasArray[ $extraKey ]['price'] = $olymposPrice;
				$extra['price'] = $olymposPrice;
			}
			
			if( $extra['extra_count'] >= $extra['extra_default_count'] )
			{
				$productPrice += (( $extra['extra_count'] -  $extra['extra_default_count'] ) * $extra['price'] * 100) ;
			}
		}
		return ($productPrice / 100 ) * $productCount;
	}
	
	private function getOlymposPrice( $barkod )
	{
		return false;
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
