<?php
/**
 * CartPayment Model
 * 
 * A payment towards an order. Usually covers the entire order at once.
 *
 * @package Cart Plugin
 * @author Dean
 * @version $Id$
 * @copyright 
 **/
class CartPayment extends AppModel {
	var $name = 'CartPayment';
	var $actsAs = array(
		'Cart.PaymentGateway',
	);
	var $hasMany = array(
		'CartPaymentLineItem' => array(
			'className' => 'Cart.CartPaymentLineItem',
		),
	);
	var $belongsTo = array(
		'CartOrder' => array(
			'className' => 'Cart.CartOrder',
		),
	);
	
	/**
	 * On IPN post-back the data is checked against the payment gateway and then saved to the database
	 *
	 * @param string $data 
	 * @param string $gatewayConfig 
	 * @return void
	 * @author Dean
	 */
	public function process($data, $gatewayConfig = null) {
		if ($this->Payment->isValid($data, $gatewayConfig)) {
			$payment['CartPayment'] = $data;
			if (isset($data['invoice'])) {
				$payment['CartPayment']['cart_order_id'] = $data['invoice'];
			}
			$payment['CartPaymentLineItem'] = $this->extractLineItems($data, $gatewayConfig);
			$this->Payment->saveAll($payment);
		}
	}
}
?>