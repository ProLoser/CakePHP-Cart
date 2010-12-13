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
class CartCreditCard extends AppModel {
	var $name = 'CartCreditCard';
	var $useTable = false;
	var $actsAs = array(
		'Cart.CreditCard',
	);
}
?>