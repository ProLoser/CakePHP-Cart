<?php 
/* SVN FILE: $Id$ */
/* CartProduct Test cases generated on: 2009-07-21 14:07:10 : 1248210310*/
App::import('Model', 'Cart.CartProduct');

class CartProductTestCase extends CakeTestCase {
	var $CartProduct = null;
	var $fixtures = array('plugin.cart.cart_product');

	function startTest() {
		$this->CartProduct =& ClassRegistry::init('CartProduct');
	}

	function testCartProductInstance() {
		$this->assertTrue(is_a($this->CartProduct, 'CartProduct'));
	}

	function testCartProductFind() {
		$this->CartProduct->recursive = -1;
		$results = $this->CartProduct->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('CartProduct' => array(
			'id'  => 1,
			'created'  => '2009-07-21 14:05:10',
			'modified'  => '2009-07-21 14:05:10',
			'name'  => 'Lorem ipsum dolor sit amet',
			'description'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'taxable'  => 1,
			'price'  => 1,
			'visible'  => 1,
			'active'  => 1
		));
		$this->assertEqual($results, $expected);
	}
}
?>