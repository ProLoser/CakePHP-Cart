<?php
/**
  * Use these settings to set defaults for the Paypal Helper class.
  * The PaypalHelper class will help you create paynow, subscribe, donate, or addtocart buttons for you.
  * 
  * All these options can be set on the fly as well within the helper
  */

///////// General Fields //////////
$config['Cart']['fields'] = array(
	'invoice',
	'notify_url',
	'amount',	
	'address1',	
	'address2',	
	'city',		
	'country',	
	'email',	
	'first_name',
	'last_name',
	'phone1',	
	'phone2',	
	'state',	
	'zip',		
);
  
///////// Paypal //////////
$config['Cart']['Paypal'] = array(
	'defaults' => array(
		// Server URL for cart submission
		'server' 		=> 'https://www.paypal.com/cgi-bin/webscr',
		// cart field	=> cart-value-slot or value-default
		'transaction'	=> 'txn_id',
		'status'		=> 'payment_status',
		'currency_code' => 'USD',
		'address' 		=> '',
		'item_name' 	=> 'ISV Payment',
		'invoice' 		=> 'invoice',
		'notify_url'	=> 'notify_url',
		'complete' 		=> '/',
		'amount' 		=> 'amount',
		'address1' 		=> 'address1',
		'address2' 		=> 'address2',
		'city' 			=> 'city',
		'country' 		=> 'country',
		'email' 		=> 'email',
		'first_name' 	=> 'first_name',
		'last_name' 	=> 'last_name',
		'night_phone_a'	=> 'phone1',
		'night_phone_b'	=> 'phone2',
		'state' 		=> 'state',
		'zip'		 	=> 'zip',
		// {n} will be used for an incremental counter int
		'on{n}'			=> 'option{n}label',
		'os{n}'			=> 'option{n}value',
		'cmd'			=> '_xclick',
		'no_note',
		'business'		=> 'username',
		'return',
	),
	// Testing settings are automatically merged with defaults
	'testing' => array(
		'server'		=> 'https://www.sandbox.paypal.com/cgi-bin/webscr',
	),
);

///////// EmartCart //////////
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

///////// AuthorizeNet //////////
$config['Cart']['AuthorizeNet'] = array(
	'defaults' => array(
		'server' 				=> 'https://secure.authorize.net/gateway/transact.dll',
		'x_login'				=> 'username',
		'x_version'				=> '3.1',
		'x_delim_char'			=> '|',
		'x_delim_data'			=> 'TRUE',
		'x_url'					=> 'FALSE',
		'x_type'				=> 'AUTH_CAPTURE',
		'x_method'				=> 'CC',
		'x_tran_key'			=> 'password',
		'x_relay_response'		=> 'FALSE',
		'x_card_num'			=> '',
		'x_card_code'			=> '',
		'x_exp_date'			=> '',
		'x_description'			=> '',
		'x_amount'				=> 'amount',
		'x_tax'					=> '',
		'x_freight'				=> '',
		'x_first_name'			=> '',
		'x_last_name'			=> '',
		'x_address'				=> 'address1',
		'x_city'				=> 'city',
		'x_state'				=> 'state',
		'x_zip'					=> 'zip',
		'x_country'				=> 'country',
		'x_email'				=> 'email',
		'x_phone'				=> 'phone1',
		'x_ship_to_first_name'	=> '',
		'x_ship_to_last_name'	=> '',
		'x_ship_to_address'		=> '',
		'x_ship_to_city'		=> '',
		'x_ship_to_state'		=> '',
		'x_ship_to_zip'			=> '',
		'x_ship_to_country'		=> '',
	),
	'testing' => array(
		'server' 		=> 'https://certification.authorize.net/gateway/transact.dll',
		//'DEBUGGING'		=> 1, //$DEBUGGING		= 1; // Display additional information to track down problems
		//'TESTING'		=> 1, //$TESTING		= 1; // Set the testing flag so that transactions are not live
	),
);