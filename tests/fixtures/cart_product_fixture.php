<?php 
/* SVN FILE: $Id$ */
/* CartProduct Fixture generated on: 2009-07-21 14:07:10 : 1248210310*/

class CartProductFixture extends CakeTestFixture {
	var $name = 'CartProduct';
	var $table = 'cart_products';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'created' => array('type'=>'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type'=>'datetime', 'null' => false, 'default' => NULL),
		'name' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 128),
		'description' => array('type'=>'text', 'null' => false, 'default' => NULL),
		'taxable' => array('type'=>'boolean', 'null' => false, 'default' => NULL),
		'price' => array('type'=>'float', 'null' => false, 'default' => NULL, 'length' => '10,2'),
		'visible' => array('type'=>'boolean', 'null' => false, 'default' => NULL),
		'active' => array('type'=>'boolean', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $records = array(array(
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
}
?>