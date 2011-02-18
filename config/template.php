<?php
/**
 * This is a template configuration file
 *
 * @author Dean
 */
$config['Cart']['<Template>'] = array(
	'defaults' => array(
		// Server URL for cart submission
		'server' 		=> 'https://www.paypal.com/cgi-bin/webscr',
		'responses' => array(
			'verified' => 'VERIFIED',
		),
		// Default field values
		'fields' => array(
			'currency_code'	=> 'USD',
			''			=> '',
			''			=> '',
			''			=> '',
		),
	),
	// Testing settings are automatically merged with defaults
	'testing' => array(
		'server'		=> 'https://www.sandbox.paypal.com/cgi-bin/webscr',
	),
	// Used to map plugin-field-names to gateway-specific names
	'fieldmap' => array(
		// cart field	=> cart-value-slot or value-default
		'transaction'	=> 'txn_id',
		'status'		=> 'payment_status',
		'currency_code' => 'USD',
		'address' 		=> '',
		'item_name' 	=> 'ISV Payment',
		'invoice' 		=> 'invoice',
		'notify_url'	=> 'notify_url',
		'complete_url' 	=> 'complete_url',
		'cancel_url'	=> 'cancel_url',
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
);