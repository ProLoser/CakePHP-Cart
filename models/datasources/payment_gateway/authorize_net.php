<?php
/**
 * Authorize.net DataSource
 *
 * Based off of the Authorize Component: http://bakery.cakephp.org/articles/gstoner/2008/03/12/authorize-net-aim-integration-component
 *
 * PHP Version 5.x
 *
 * CakePHP(tm) : Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2009, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class AuthorizeNet extends PaymentGatewaySource {
	
	function chargeCard($loginid, $trankey, $ccnum, $ccexpmonth, $ccexpyear, $ccver, $live, $amount, $tax, $shipping, $desc, $billinginfo, $email, $phone, $shippinginfo) {
	 
		// setup variables 
		$ccexp = $ccexpmonth . '/' . $ccexpyear; 
		 
		$DEBUGGING                    = 1;                # Display additional information to track down problems
		$TESTING                    = 1;                # Set the testing flag so that transactions are not live
		$ERROR_RETRIES                = 2;                # Number of transactions to post if soft errors occur
	 
		$auth_net_login_id            = $loginid; 
		$auth_net_tran_key            = $trankey; 
		### $auth_net_url                = "https://certification.authorize.net/gateway/transact.dll";
		#  Uncomment the line ABOVE for test accounts or BELOW for live merchant accounts 
		$auth_net_url                = "https://secure.authorize.net/gateway/transact.dll";
		 
		$authnet_values                = array 
		( 
			"x_login"				=> $auth_net_login_id, 
			"x_version"				=> "3.1", 
			"x_delim_char"			=> "|", 
			"x_delim_data"			=> "TRUE", 
			"x_url"					=> "FALSE", 
			"x_type"				=> "AUTH_CAPTURE", 
			"x_method"				=> "CC", 
			"x_tran_key"			=> $auth_net_tran_key, 
			"x_relay_response"		=> "FALSE", 
			"x_card_num"			=> str_replace(" ", "", $ccnum), 
			"x_card_code"			=> $ccver, 
			"x_exp_date"			=> $ccexp, 
			"x_description"			=> $desc, 
			"x_amount"				=> $amount, 
			"x_tax"					=> $tax, 
			"x_freight"				=> $shipping, 
			"x_first_name"			=> $billinginfo["fname"], 
			"x_last_name"			=> $billinginfo["lname"], 
			"x_address"				=> $billinginfo["address"], 
			"x_city"				=> $billinginfo["city"], 
			"x_state"				=> $billinginfo["state"], 
			"x_zip"					=> $billinginfo["zip"], 
			"x_country"				=> $billinginfo["country"], 
			"x_email"				=> $email, 
			"x_phone"				=> $phone, 
			"x_ship_to_first_name"	=> $shippinginfo["fname"], 
			"x_ship_to_last_name"	=> $shippinginfo["lname"], 
			"x_ship_to_address"		=> $shippinginfo["address"], 
			"x_ship_to_city"		=> $shippinginfo["city"], 
			"x_ship_to_state"		=> $shippinginfo["state"], 
			"x_ship_to_zip"			=> $shippinginfo["zip"], 
			"x_ship_to_country"		=> $shippinginfo["country"], 
		); 
		 
		$fields = ""; 
		foreach ( $authnet_values as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		 
		/////////////////////////////////////////////////////////// 
		 
		// Post the transaction (see the code for specific information) 
		 
		 
		### $ch = curl_init("https://certification.authorize.net/gateway/transact.dll"); 
		###  Uncomment the line ABOVE for test accounts or BELOW for live merchant accounts
		$ch = curl_init("https://secure.authorize.net/gateway/transact.dll");   
		### curl_setopt($ch, CURLOPT_URL, "https://secure.authorize.net/gateway/transact.dll");
		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
		 
		### Go Daddy Specific CURL Options 
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);  
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);  
		   curl_setopt($ch, CURLOPT_PROXY, 'http://proxy.shr.secureserver.net:3128');  
		curl_setopt($ch, CURLOPT_TIMEOUT, 120); 
		   ### End Go Daddy Specific CURL Options 
			
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
		$resp = curl_exec($ch); //execute post and get results 
		curl_close ($ch); 
		 
		// Parse through response string 
		 
		$text = $resp; 
		$h = substr_count($text, "|"); 
		$h++; 
		$responsearray = array(); 




		for($j=1; $j <= $h; $j++){ 

			$p = strpos($text, "|"); 

			if ($p === false) { // note: three equal signs 
				//  x_delim_char is obviously not found in the last go-around 
				// This is final response string 
				$responsearray[$j] = $text; 
			} 
			else { 
				$p++; 
				//  get one portion of the response at a time 
				$pstr = substr($text, 0, $p); 

				//  this prepares the text and returns one value of the submitted 
				//  and processed name/value pairs at a time 
				//  for AIM-specific interpretations of the responses 
				//  please consult the AIM Guide and look up 
				//  the section called Gateway Response API 
				$pstr_trimmed = substr($pstr, 0, -1); // removes "|" at the end 

				if($pstr_trimmed==""){ 
					$pstr_trimmed=""; 
				} 

				$responsearray[$j] = $pstr_trimmed; 

				// remove the part that we identified and work with the rest of the string
				$text = substr($text, $p); 

			} // end if $p === false 

		} // end parsing for loop 
		 
		return $responsearray; 
		 
	}
	
}
?>