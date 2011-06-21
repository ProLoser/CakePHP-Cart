<?php
// https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_Appx_websitestandard_htmlvariables
$config['Cart']['Paypal'] = array(
	'defaults' => array(
		// Server URL for cart submission
		'server' 		=> 'https://www.paypal.com/cgi-bin/webscr',
		'responses' => array(
			'verified' => 'VERIFIED',
		),
		// Default values
		'fields' => array(
			
		),
		// Other Options
		'ipn' => array(
			'email_fieldname' => 'receiver_email',
			'testing_fieldname' => 'test_ipn',
		),
	),
	// Testing settings are automatically merged with defaults
	'testing' => array(
		'server'		=> 'https://www.sandbox.paypal.com/cgi-bin/webscr',
	),
	'fieldmap' => array(
		// cart field	=> cart-value-slot or value-default
		'login'			=> 'business',
		'transaction'	=> 'txn_id',
		'status'		=> 'payment_status',
		'currency_code' => 'currency_code',
		'item_name' 	=> 'item_name',
		'invoice' 		=> 'invoice',
		'notify_url'	=> 'notify_url',
		'complete_url' 	=> 'complete_url',
		'cancel_url' 	=> 'cancel_url',
		'return_url'	=> 'return',
		'amount' 		=> 'amount',
		'address1' 		=> 'address1',
		'address2' 		=> 'address2',
		'city' 			=> 'city',
		'country' 		=> 'country',
		'email' 		=> 'email',
		'first_name' 	=> 'first_name',
		'last_name' 	=> 'last_name',
		'phone1'		=> 'night_phone_a',
		'phone2'		=> 'night_phone_b',
		'state' 		=> 'state',
		'zip'		 	=> 'zip',
		'action'		=> 'cmd',
		// {n} will be used for an incremental counter int
		'on{n}'			=> 'option{n}label',
		'os{n}'			=> 'option{n}value',
		'no_note',
	),
);