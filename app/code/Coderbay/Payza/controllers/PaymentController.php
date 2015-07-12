<?php

class Coderbay_Payza_PaymentController extends Mage_Core_Controller_Front_Action {
	
	/**
	*	The redirect action is triggered 
	*	when someone places an order
	**/
	public function redirectAction() 
	{
		$session = Mage::getSingleton('checkout/session');
        $order = Mage::getModel('sales/order');
        $order->loadByIncrementId($session->getLastRealOrderId());
        $order->addStatusToHistory($order->getStatus(), Mage::helper('payza')->__('Customer was redirected to Payza.'));
        $order->save();
		
		$this->loadLayout();
        $block = $this->getLayout()->createBlock('Mage_Core_Block_Template','payza',array('template' => 'payza/redirect.phtml'));
		$this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
	}
	
	/**
	*	Success Action Here
	*	User Is redirected to here after successfuly payment
	**/	
	public function successAction()
	{
		$this->_redirect('checkout/onepage/success', array('_secure'=>true));
	}
	
	/**
	*	Failed Action Here
	*	User Is redirected to here after Failed/Canceled payment
	**/
	public function cancelAction()
	{
		$this->_redirect('checkout/onepage/failure');
	}
	
	/**
	*	The response action is triggered when your gateway 
	*	sends back a response after processing the customer's payment
	**/	
	public function responseAction()
	{
		$validated = true;
		$orderId = $_POST['orderNo'];// Generally sent by gateway
		
		if($validated) {
			// Payment was successful, so update the order's state, send order email and move to the success page
			$order = Mage::getModel('sales/order');
			$order->loadByIncrementId($orderId);
			$order->addStatusToHistory($order->getStatus(), Mage::helper('payza')->__('Customer successfully returned from Gateway'));
			$order->sendNewOrderEmail();
			$order->setEmailSent(true);
			$order->save();
			Mage::getSingleton('checkout/session')->unsQuoteId();
			$this->_redirect('checkout/onepage/success', array('_secure'=>true));
		}
		else {
			// There is a problem in the response we got
			$this->_redirect('checkout/onepage/failure');
		}
	}
}