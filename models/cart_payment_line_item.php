<?php
/**
 * CartPaymentLineItem Model
 * 
 * [Short Description]
 *
 * @package Cart Plugin
 **/
class CartPaymentLineItem extends CartAppModel {
	var $name = 'CartPaymentLineItem';
	var $belongsTo = array(
		'CartPayment' => array(
			'className' => 'Cart.CartPayment',
		),
	);
}
?>