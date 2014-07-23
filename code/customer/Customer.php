<?php
/**
 * Represents a {@link Customer}, a type of {@link Member}.
 * 
 * @author Frank Mullenger <frankmullenger@gmail.com>
 * @copyright Copyright (c) 2011, Frank Mullenger
 * @package swipestripe
 * @subpackage customer
 */
class Customer extends Member {

	private static $db = array(
		'Phone' 		=> 'Varchar(255)',
		'Fax' 			=> 'Varchar(255)',
		'Company' 		=> 'Varchar',
		'Address' 		=> 'Varchar(255)',
		'AddressLine2' 	=> 'Varchar(255)',
		'City' 			=> 'Varchar(100)',
		'PostalCode' 	=> 'Varchar(30)',
		'State' 		=> 'Varchar(100)',
		//De-normalise these values in case region or country is deleted
		'CountryName' 	=> 'Varchar',
		'CountryCode' 	=> 'Varchar(2)', //ISO 3166 
		'RegionName' 	=> 'Varchar',
		'RegionCode' 	=> 'Varchar(2)',
			
		'IsGuest' 		=> 'Boolean',	
			
		'Code' 			=> 'Int' //Just to trigger creating a Customer table
	);
	
	/**
	 * Link customers to {@link Address}es and {@link Order}s.
	 * 
	 * @var Array
	 */
	private static $has_many = array(
		'Orders' => 'Order'
	);

	private static $searchable_fields = array(
		'Surname',
		'Email'
	);
	
	/**
	 * Prevent customers from being deleted.
	 * 
	 * @see Member::canDelete()
	 */
	public function canDelete($member = null) {

		$orders = $this->Orders();
		if ($orders && $orders->exists()) {
			return false;
		}
		return Permission::check('ADMIN', 'any', $member);
	}

	public function delete() {
		if ($this->canDelete(Member::currentUser())) {
			parent::delete();
		}
	}

	function requireDefaultRecords() {
		parent::requireDefaultRecords();

		//Create a new group for customers
		$allGroups = DataObject::get('Group');
		$existingCustomerGroup = $allGroups->find('Title', 'Customers');
		if (!$existingCustomerGroup) {
			
			$customerGroup = new Group();
			$customerGroup->Title = 'Customers';
			$customerGroup->setCode($customerGroup->Title);
			$customerGroup->write();

			Permission::grant($customerGroup->ID, 'VIEW_ORDER');
		}
	}

	/**
	 * Add some fields for managing Members in the CMS.
	 * 
	 * @return FieldList
	 */
	public function getCMSFields() {

		$fields = new FieldList();

		$fields->push(new TabSet('Root', 
			Tab::create('Customer')
		));

		$password = new ConfirmedPasswordField(
			'Password', 
			null, 
			null, 
			null, 
			true // showOnClick
		);
		$password->setCanBeEmpty(true);
		if(!$this->ID) $password->showOnClick = false;

		$fields->addFieldsToTab('Root.Customer', array(
			new TextField('FirstName'),
			new TextField('Surname'),
			new EmailField('Email'),
			new ConfirmedPasswordField('Password'),
			$password
		));

		$this->extend('updateCMSFields', $fields);

		return $fields;
	}
	
	/**
	 * Overload getter to return only non-cart orders
	 * 
	 * @return ArrayList Set of previous orders for this member
	 */
	public function Orders() {
		return Order::get()
			->where("\"MemberID\" = " . $this->ID . " AND \"Order\".\"Status\" != 'Cart'")
			->sort("\"Created\" DESC");
	}
	
	/**
	 * Returns the current logged in customer
	 *
	 * @return bool|Member Returns the member object of the current logged in
	 *                     user or FALSE.
	 */
	static function currentUser() {
		$id = Member::currentUserID();
		if($id) {
			$customerRecord = DataObject::get_one("Customer", "\"Member\".\"ID\" = $id");
			
			if($customerRecord && $customerRecord->ID){
				return $customerRecord;
			}else{
				//member exists, mark it as customer
				$memberRecord = DataObject::get_one("Member", "\"Member\".\"ID\" = $id");
				$memberRecord->ClassName = 'Customer';
				$memberRecord->write();
				
				return DataObject::get_one("Customer", "\"Member\".\"ID\" = $id");
			}
		}
	}
}
