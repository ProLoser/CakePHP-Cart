<?php
/**
 * CartPaymentLineItem Model
 * 
 * [Short Description]
 *
 * @package Cart Plugin
 * @author Dean
 * @version $Id$
 * @copyright 
 **/
class CartPaymentLineItem extends AppModel {
	var $name = 'CartPaymentLineItem';
	var $belongsTo = array(
		'CartPayment' => array(
			'className' => 'Cart.CartPayment',
		),
	);
}
?>