<?php
class Coderbay_Payza_Model_Standard extends Mage_Payment_Model_Method_Abstract {
	protected $_code = 'payza';
	
	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = true;
	protected $_canUseForMultishipping  = false;
	
	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl('payza/payment/redirect', array('_secure' => true));
	}
}
?>