<?php
/**
 * CartPaymentsController
 * 
 * [Short Description]
 *
 * @package Cart Plugin
 * @author Dean
 **/

class CartPaymentsController extends CartAppController {
	var $name = 'CartPayments';
	var $scaffold = 'admin';
	
	/**
	 * Processes the ipn and saves the payment and related line items
	 */
	function ipn() {
		$this->CartPayment->processIpn($_POST);
	}
}
?>