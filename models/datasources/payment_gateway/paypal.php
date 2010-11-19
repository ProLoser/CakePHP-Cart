<?php
/**
 * Paypal DataSource
 *
 * Used for reading and writing to Twitter, through models.
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
class Paypal extends PaymentGatewaySource {
  
	/**
	 * Verifies POST data given by the paypal instant payment notification
	 *
	 * @param array $data Most likely directly $_POST given by the controller.
	 * @return boolean $valid depending on if data received is actually valid from paypal and not from some script monkey
	 */
	function isValid($data){		
		$data['cmd'] = '_notify-validate';
		
		if (isset($data['test_ipn'])) {
			$server = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		} else {
			$server = 'https://www.paypal.com/cgi-bin/webscr';
		}
		
		$response = $this->Http->post($server, $data);
		
		if ($response == "VERIFIED") {
			return true;
		}
		
		if (!$response) {
			$this->log('HTTP Error in PaypalDatasource::isValid while posting back to PayPal', 'paypal');
		}
		
		return false;
	}
	
	/**
	 * Iterates through the post data and extracts all the lineItems in the order if any
	 *
	 * @param string $data 
	 * @return array $results
	 * @author Dean
	 */
	public function extractLineItems($data) {
		$results = array();
		if (isset($post['num_cart_items']) && $post['num_cart_items'] > 1) { // TODO is 1 cart item return a different array?
			for ($i = 1; $i <= $post['num_cart_items']; $i++) {
				$results[$i]['item_name']	= $post["item_name$i"];
				$results[$i]['item_number']	= $post["item_number$i"];
				$results[$i]['quantity']	= $post["quantity$i"];
				$results[$i]['mc_shipping']	= $post["mc_shipping$i"];
				$results[$i]['mc_handling']	= $post["mc_handling$i"];
				$results[$i]['mc_gross'] 	= $post["mc_gross_$i"];
				$results[$i]['tax'] 		= $post["tax$i"];
			}
		}
		return $results;
	}
}