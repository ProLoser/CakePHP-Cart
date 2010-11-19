<?php
/**
 * CartOrderLineItem Model
 * 
 * [Short Description]
 *
 * @package Cart Plugin
 * @author Dean
 * @version $Id$
 * @copyright 
 **/
class CartOrderLineItem extends AppModel {
	var $name = 'CartOrderLineItem';
	var $belongsTo = array(
		'CartOrder' => array(
			'className' => 'Cart.CartOrder',
		),
	);
	
}
?>