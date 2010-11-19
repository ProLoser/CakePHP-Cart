<?php
/**
 * Cart Product Model
 *
 * @package Cart Plugin
 * @author Dean
 */
class CartProduct extends CartAppModel {
	var $name = 'CartProduct';
	
	var $validate = array(
		'name' => array('notempty'),
		'taxable' => array('boolean'),
		'price' => array('decimal'),
		'visible' => array('boolean'),
		'active' => array('boolean')
	);

}
?>