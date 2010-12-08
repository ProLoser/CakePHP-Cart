<?php
/**
 * Authorize.net Datasource
 *
 * @link http://bakery.cakephp.org/articles/gstoner/2008/03/12/authorize-net-aim-integration-component
 * @package Cart.Datasource
 * @author Dean
 */
class AuthorizeNet extends PaymentGatewaySource {
	
	function send($ccnum, $ccexpmonth, $ccexpyear, $ccver, $live, $amount, $tax, $shipping, $desc, $billinginfo, $email, $phone, $shippinginfo) {
	 
		$settings = $this->settings;
		
		// setup variables 
		$ccexp = $ccexpmonth . '/' . $ccexpyear; 
		 
		$DEBUGGING		= 1; // Display additional information to track down problems
		$TESTING		= 1; // Set the testing flag so that transactions are not live
		$ERROR_RETRIES	= 2; // Number of transactions to post if soft errors occur
	 
		$data = array( 
			"x_login"				=> $settings['login'], 
			"x_version"				=> "3.1", 
			"x_delim_char"			=> "|",
			"x_delim_data"			=> "TRUE", 
			"x_url"					=> "FALSE", 
			"x_type"				=> "AUTH_CAPTURE", 
			"x_method"				=> "CC", 
			"x_tran_key"			=> $settings['password'], 
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
		
		return parent::verify($data);
	 }
		
  
	/**
	 * Verifies POST data given by the paypal instant payment notification
	 *
	 * @param array $data Most likely directly $_POST given by the controller.
	 * @return boolean $valid depending on if data received is actually valid from paypal and not from some script monkey
	 */
	function verify($data) {		
		
		
		return parent::verify($data);
	}
	
	/**
	 * Scans the returned response from $this->verify() and gives an understandable response
	 *
	 * @param string $response 
	 * @return boolean
	 * @author Dean
	 */
	public function checkResponse($response) {
		// Parse through response string 
		 
		$h = substr_count($response, "|"); 
		$h++; 
		$responsearray = array(); 

		for ($j=1; $j <= $h; $j++) { 

			$p = strpos($response, "|"); 

			if ($p === false) { // note: three equal signs 
				//  x_delim_char is obviously not found in the last go-around 
				// This is final response string 
				$responsearray[$j] = $response; 
			} 
			else { 
				$p++; 
				//  get one portion of the response at a time 
				$pstr = substr($response, 0, $p); 

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
				$response = substr($response, $p); 

			} // end if $p === false 

		} // end parsing for loop 
		 
		return $responsearray; 
	}
	
}
?>