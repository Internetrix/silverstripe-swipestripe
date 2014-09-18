<?php
/**
 * A notification email that is sent to an email address specified in {@link ShopConfig}, usually
 * a site administrator or owner. 
 * 
 * @author Frank Mullenger <frankmullenger@gmail.com>
 * @copyright Copyright (c) 2011, Frank Mullenger
 * @package swipestripe
 * @subpackage emails
 */
class DispatchEmail extends ProcessedEmail {

	/**
	 * Create the new notification email.
	 * 
	 * @param Member $customer
	 * @param Order $order
	 * @param String $from
	 * @param String $to
	 * @param String $subject
	 * @param String $body
	 * @param String $bounceHandlerURL
	 * @param String $cc
	 * @param String $bcc
	 */
	public function __construct(Member $customer, Order $order, Order_Update $OrderUpdate, $from = null, $to = null, $subject = null, $body = null, $bounceHandlerURL = null, $cc = null, $bcc = null) {

		$siteConfig = ShopConfig::get()->first();
		if ($customer->Email) $this->to = $customer->Email;
		if ($siteConfig->DispatchSubject) $this->subject = $siteConfig->DispatchSubject . ' - Order #'.$order->ID;
		
		if ($siteConfig->DispatchFrom) $this->from = $siteConfig->DispatchFrom;
		elseif (Email::getAdminEmail()) $this->from = Email::getAdminEmail();
		else $this->from = 'no-reply@' . $_SERVER['HTTP_HOST'];
		
		if($siteConfig->DispatchBcc){
			$bcc = $siteConfig->DispatchBcc;
		}
		
		if ($siteConfig->DispatchFooter) $this->signature = $siteConfig->DispatchFooter;
		
		//Get css for Email by reading css file and put css inline for emogrification
		$this->setTemplate('Order_DispatchEmail');
		
		if (file_exists(Director::getAbsFile($this->ThemeDir().'/css/ShopEmail.css'))) {
			$css = file_get_contents(Director::getAbsFile($this->ThemeDir().'/css/ShopEmail.css'));
		}
		else {
			$css = file_get_contents(Director::getAbsFile('swipestripe/css/ShopEmail.css'));
		}
		
		$this->populateTemplate(
				array(
						'Message' => $siteConfig->DispatchBody,
						'Order' => $order,
						'OrderUpdate' => $OrderUpdate,
						'Customer' => $customer,
						'InlineCSS' => "<style>$css</style>",
						'Signature' => $this->signature,
						'SiteConfig' => SiteConfig::get()->first()
				)
		);
		
		parent::__construct($from, null, $subject, $body, $bounceHandlerURL, $cc, $bcc);
	}
}
