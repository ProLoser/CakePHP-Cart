<?php
/**
 * CartCreditCard Model
 * 
 * [Short Description]
 *
 * @package Cart
 * @author Dean Sofer
 * @version $Id$
 * @copyright 
 **/
class CartCreditCard extends CartAppModel {
	var $name = 'CartCreditCard';
	var $useTable = false;
	var $actsAs = array(
		'Cart.CreditCard',
	);
}
?>