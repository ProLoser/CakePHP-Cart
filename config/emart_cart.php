<?php
$config['Cart']['EmartCart'] = array(
	'defaults' => array(
		// Server URL for cart submission
		'server' 		=> 'http://emartcart.com/cart/odb/cart.odb',
		// cart field	=> cart-value-slot or value-default
		'CartCfg'		=> 'password',
		'bName'			=> 'username',
		'FixedQty'		=> '1',
		'NoGiftCerts'	=> '1',
	),
);