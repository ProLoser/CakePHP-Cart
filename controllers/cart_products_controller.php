<?php
class CartProductsController extends CartAppController {

	var $name = 'CartProducts';
	var $components = array('Cart.CartSession');
	var $helpers = array('Html', 'Form');
	var $scaffold;
	
	function addItem($id = null, $options = null) {
		$this->CartSession->addItem($id);
	}
	
}
?>