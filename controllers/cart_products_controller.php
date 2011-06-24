<?php
class CartProductsController extends CartAppController {
	var $name = 'CartProducts';
	var $scaffold = 'admin';
	
	function addItem($id = null, $options = null) {
		$this->CartSession->addItem($id);
	}
	
}
?>