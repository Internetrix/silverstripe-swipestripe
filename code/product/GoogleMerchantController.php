<?php

class GoogleMerchantController extends Controller {

	/**
	 * @var array
	 */
	private static $allowed_actions = array(
		'index'
	);

	/**
	 * Default controller action for the products.xml file. 
	 *
	 * @return mixed
	 */
	public function index($url) {
		if(GoogleSitemap::enabled()) {
			Config::inst()->update('SSViewer', 'set_source_file_comments', false);
			
			$this->getResponse()->addHeader('Content-Type', 'application/xml; charset="utf-8"');

			//get all products
			$pageCurr 			= new ProductListPage_Controller(ProductListPage::get()->first());
			$productsPagList 	= $pageCurr->ProductsList();
			$list				= $productsPagList->getList();
			
			$this->extend('updateGoogleMerchantProducts', $list);

			return array(
				'Products' => $list
			);
		} else {
			return new SS_HTTPResponse('Page not found', 404);
		}
	}

}
