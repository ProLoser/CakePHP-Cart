<?php
/**
 * Cart Order Model aka: Invoice
 * 
 * [Short Description]
 *
 * @package Cart Plugin
 * @author Dean
 * @version $Id$
 * @copyright 
 **/
class CartOrder extends CartAppModel {
	var $name = 'CartOrder';
	var $hasMany = array(
		'CartOrderLineItem' => array(
			'className' => 'Cart.CartOrderLineItem',
		),
		'CartPayment' => array(
			'className' => 'Cart.CartPayment',
		),
	);
}
?>