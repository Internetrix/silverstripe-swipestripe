<?php
/**
 * Remove abandoned carts that have not been active for a certain period of time
 * So that stock that is tied up in the carts can be released back if the customers
 * do not go through the checkout process.
 * 
 *  e.g: use crontab -e with a directive like below for task to run every minute
 * * /1 * * * * php /var/www/path/to/project/sapphire/cli-script.php /RemoveAbandonedCartsTask > /var/log/swipestripe.log
 * 
 * Note: remove the space between the * and /1
 * 
 * @author Frank Mullenger <frankmullenger@gmail.com>
 * @copyright Copyright (c) 2011, Frank Mullenger
 * @package swipestripe
 * @subpackage tasks
 */
class SendDispatchNotificationTask extends BuildTask {
	
	protected $title = "Send Dispatch Notification Emails";
	
	protected $description = "Send Dispatch Notification Emails.";

	function run($request) {
		
		$count = 0;
		
		$UnsendOrderUpdates = Order_Update::get()
			->filter(array(
				'Status' 	=> 'Dispatched',
				'SendEmail' => true,
				'DoneEmail' => false	
			));

		if ($UnsendOrderUpdates && $UnsendOrderUpdates->exists()) foreach ($UnsendOrderUpdates as $updateDO) {
			
			$orderDO = $updateDO->Order();
			
			if($orderDO && $orderDO->exists()){
				$customerDO = $orderDO->Member();
				
				if( $customerDO && $customerDO->exists()){
					DispatchEmail::create($customerDO, $orderDO, $updateDO)->send();
				
					$count++;
				}
			}
				
			$updateDO->DoneEmail = true;
			$updateDO->write();
		}
		
		
		echo 'processed '.$count;
	}
	
	
	
}
