<?php 
/* SVN FILE: $Id$ */
/* CartProductsController Test cases generated on: 2009-07-21 14:07:21 : 1248210381*/
App::import('Controller', 'Cart.CartProducts');

class TestCartProducts extends CartProductsController {
	var $autoRender = false;
}

class CartProductsControllerTest extends CakeTestCase {
	var $CartProducts = null;

	function startTest() {
		$this->CartProducts = new TestCartProducts();
		$this->CartProducts->constructClasses();
	}

	function testCartProductsControllerInstance() {
		$this->assertTrue(is_a($this->CartProducts, 'CartProductsController'));
	}

	function endTest() {
		unset($this->CartProducts);
	}
}
?>