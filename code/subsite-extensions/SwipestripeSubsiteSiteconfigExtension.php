<?php
class SwipestripeSubsiteSiteconfigExtension extends DataExtension {
	
	private static $db = array(
		'EnableShopFeatures' => 'Boolean'
	);
	
	public function updateCMSFields(FieldList $fields) {

		$fields->addFieldsToTab('Root.Main', ToggleCompositeField::create(
			'ShopFeatures', 
			'Shop Features Setting',		
			array(
				CheckboxField::create('EnableShopFeatures', 'Enable shop features and create require pages ? ( run \'/dev/build\' after it\'s checked and saved. )')
		)));
		
		if($this->owner->EnableShopFeatures 
			&& ( ! CartPage::get()->first() || ! CheckoutPage::get()->first() || ! AccountPage::get()->first())
		){
			$buildURL = Director::absoluteURL('dev/build');
			$fields->addFieldsToTab('Root.Main', LiteralField::create(
				'dev', '<p class="message warning">Please run <strong>\'dev/build\'</strong> to create the required shop pages.</p>'
			), 'ShopFeatures');
		}
		
	}
	
	public function onAfterWrite(){
		
		if($this->owner->isChanged('EnableShopFeatures', 2)){
			if($this->owner->EnableShopFeatures){
				singleton('CartPage')->CreateDefaultPage($this->owner);
					
				singleton('CheckoutPage')->CreateDefaultPage($this->owner);
					
				singleton('AccountPage')->CreateDefaultPage($this->owner);
			}else{
				//TODO unpublished CartPage etc ?
			}
		}
		
	}
	
	
	
}