<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
/**
 * Class MenuScreen
 * @property  translation_model $TranslationModel
 */
class Translation extends RestController
{

	function __construct()
	{
		parent::__construct();

		$this->load->model('Translation/translation_model', "TranslationModel");
	}

	public function translate_get()
	{
		$lang = $this->get( 'lang' );
		$word = $this->get( 'word' );

		if( ! in_array($lang, ['az', 'en', 'ru']) )
		{
			$this->response( [], 404 );
		}
		else
		{
			$translation = $this->TranslationModel->getTranslation($lang, $word);
			$this->response( $translation, 200 );
		}
	}
}
