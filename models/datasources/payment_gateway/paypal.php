<?php
/**
 * PayPal Datasource
 *
 * @link http://bakery.cakephp.org/articles/webtechnick/2009/08/11/paypal-ipn-instant-payment-notification-plugin-complete-with-paypalhelper
 * @package Cart.Datasource
 * @author Dean
 */
class Paypal extends PaymentGatewaySource {
  
	/**
	 * Verifies POST data given by the paypal instant payment notification
	 *
	 * @param array $data Most likely directly $_POST given by the controller.
	 * @return boolean $valid depending on if data received is actually valid from paypal and not from some script monkey
	 */
	function verify($data) {		
		$data['cmd'] = '_notify-validate';
		
		if (isset($data['test_ipn'])) {
			$settings = $this->_settings('testing');
		}
		
		$response = $this->Http->post($settings['server'], $data);
		
		return $this->checkResponse($response);
	}
	
	/**
	 * Scans the returned response from $this->verify() and gives an understandable response
	 *
	 * @param string $response 
	 * @return boolean
	 * @author Dean
	 */
	public function checkResponse($response) {
		if ($response == "VERIFIED") {
			return true;
		}
		if (!$response) {
			$this->log('HTTP Error in PaypalDatasource::checkResponse while posting back to PayPal', 'paypal');
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