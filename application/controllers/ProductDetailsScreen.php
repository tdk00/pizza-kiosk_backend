<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class MenuScreen
 * @property  categories_model $CategoriesModel
 * @property  product_model $ProductModel
 * @property  extras_model $ExtrasModel
 * @property  shopping_cart_model $ShoppingCartModel
 */
class ProductDetailsScreen extends RestController {

	function __construct()
	{
		parent::__construct();

		$this->load->model('ProductDetailsScreen/product_model', "ProductModel");
		$this->load->model('ProductDetailsScreen/extras_model', "ExtrasModel");
		$this->load->model('ProductDetailsScreen/shopping_cart_model', "ShoppingCartModel");
	}

	public function product_details_by_id_get()
	{
		$lang = $this->get( 'lang' );
		$product_id = (int)$this->get( 'product_id' ) > 0  ? $this->get( 'product_id' ) : 0;

		if( ! in_array($lang, ['az', 'en', 'ru']) )
		{
			$lang = 'az';
		}
		$productsDetails = $this->ProductModel->getProductById( $lang, $product_id );

		foreach ($productsDetails as $detailKey => $detailValue)
		{
			$productSizes = $this->ProductModel->getProductSizesById( $lang, $product_id );
			$productsDetails[ $detailKey ] ['sizes'] = $productSizes;
		}

		if( count($productsDetails) > 0 )
		{
			$this->response( $productsDetails, 200 );
		}
		else
		{
			$this->response( [], 404 );
		}

	}

	public function get_extras_by_product_id_get()
	{
		$lang = $this->get( 'lang' );
		$size_id = (int)$this->get( 'size_id' ) > 0  ? $this->get( 'size_id' ) : 0;

		if( ! in_array($lang, ['az', 'en', 'ru']) )
		{
			$lang = 'az';
		}
		$extras = $this->ExtrasModel->getExtrasByProductId( $lang, $size_id );

		if( count($extras) > 0 )
		{
			$this->response( $extras, 200 );
		}
		else
		{
			$this->response( [], 404 );
		}

	}

	public function shopping_cart_product_details_by_id_get()
	{
		$lang = $this->get( 'lang' );
		$shopping_cart_product_id = (int)$this->get( 'shopping_cart_product_id' ) > 0  ? $this->get( 'shopping_cart_product_id' ) : 0;

		if( ! in_array($lang, ['az', 'en', 'ru']) )
		{
			$lang = 'az';
		}
		$productsDetails = $this->ShoppingCartModel->getShoppingCartProductById( $lang, $shopping_cart_product_id );
		$extras = $this->ShoppingCartModel->getSCProductExtras( $lang, $shopping_cart_product_id );

		foreach ( $productsDetails as $productsDetailKey => $productsDetailValue )
		{
			$productsDetails [ $productsDetailKey ] [ 'price_including_extras' ] = (string) $this->calculatePriceIncludingExtras( $productsDetailValue['price'], $extras );
		}


		if( count($productsDetails) > 0 )
		{
			$this->response( $productsDetails, 200 );
		}
		else
		{
			$this->response( [], 404 );
		}

	}


	public function get_extras_by_shopping_cart_product_id_get()
	{
		$lang = $this->get( 'lang' );
		$shopping_cart_product_id = (int)$this->get( 'shopping_cart_product_id' ) > 0  ? $this->get( 'shopping_cart_product_id' ) : 0;

		if( ! in_array($lang, ['az', 'en', 'ru']) )
		{
			$lang = 'az';
		}

		$extrasAdded = $this->ShoppingCartModel->getSCProductExtras( $lang, $shopping_cart_product_id );
		$extrasRelated = $this->ShoppingCartModel->getSCRelatedProductExtras( $lang, $shopping_cart_product_id );

		$extras = array_merge($extrasAdded, $extrasRelated);

		if( count( $extras ) > 0 )
		{
			$this->response( $extras, 200 );
		}
		else
		{
			$this->response( [], 404 );
		}

	}

	public function add_product_to_shopping_cart_post()
	{
		if( ! empty( $this->post('size_id') ) &&
			! empty( $this->post('count') ) &&
			! empty( $this->post('extras') ) &&
			! empty( $this->post('session_id') ) )
		{
			$size_id = (int)$this->post('size_id');
			$count = (int)$this->post('count');
			$session_id = $this->post('session_id');
			$extras = json_decode( $this->post('extras') );

			if( $size_id > 0 && $count > 0 && ! empty( $session_id ))
			{
				$insert_id = $this->ShoppingCartModel->addProductToShoppingCart( $size_id, $session_id, $count );

				if( $insert_id > 0 )
				{
					foreach ( $extras as $extra )
					{
						$this->ShoppingCartModel->addExtrasToShoppingCart( $insert_id, $extra->id, $extra->count, $session_id, $size_id );
					}
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

	public function update_shopping_cart_product_post()
	{
		if( ! empty( $this->post('shopping_cart_product_id') ) &&
			! empty( $this->post('count') ) &&
			! empty( $this->post('extras') ) &&
			! empty( $this->post('session_id') ) )
		{
			$shopping_cart_product_id = (int)$this->post('shopping_cart_product_id');
			$count = (int)$this->post('count');
			$session_id = $this->post('session_id');
			$extras = json_decode( $this->post('extras') );

			if( $shopping_cart_product_id > 0 && $count > 0 && ! empty( $session_id ))
			{
				$productUpdated = $this->ShoppingCartModel->updateShoppingCartProduct( $shopping_cart_product_id, $session_id, $count );
				foreach ( $extras as $extra )
				{
					$this->ShoppingCartModel->updateShoppingCartExtra( $extra->shopping_cart_extra_id, $extra->count );
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
		foreach ( $extrasArray as $extra )
		{
			if( $extra['extra_count'] >= $extra['extra_default_count'] )
			{
				$productPrice += ( $extra['extra_count'] -  $extra['extra_default_count'] ) * $extra['price'] * 100 ;
			}

		}
		return $productPrice / 100;
	}


}
