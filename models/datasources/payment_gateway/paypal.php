<?php
/**
 * PayPal Datasource
 *
 * @link http://bakery.cakephp.org/articles/webtechnick/2009/08/11/paypal-ipn-instant-payment-notification-plugin-complete-with-paypalhelper
 * @package Cart.Datasource
 * @author Dean
 */
class Paypal extends InstantPaymentNotificationSource {
	
	/**
	 * Verifies POST data given by the paypal instant payment notification
	 *
	 * @param array $data Most likely directly $_POST given by the controller.
	 * @return boolean $valid depending on if data received is actually valid from paypal and not from some script monkey
	 */
	function ipn($data) {		
		$data['cmd'] = '_notify-validate';
		if (
			((isset($data['test_ipn']) && !empty($this->config['testing'])) 
			|| (!isset($data['test_ipn']) && empty($this->config['testing'])))
			&& parent::ipn($data) && $this->_checkEmail($data)
		) {
			return true;
		} else {
			return false;
		}
	}
	
	protected function _checkEmail($data) {
		return ($data['receiver_email'] == $this->config['email']);
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
		// TODO Does 1 cart item return a different array?
		if (isset($data['item_name'])) {
			$results[0]['item_name']	= $data['item_name'];
			$results[0]['item_number']	= $data['item_number'];
			$results[0]['quantity']		= $data['quantity'];
			$results[0]['mc_shipping']	= $data['mc_shipping'];
			$results[0]['mc_handling']	= $data['mc_handling'];
			$results[0]['mc_gross'] 	= $data['mc_gross'];
			$results[0]['tax'] 			= $data['tax'];
		}
		if (isset($data['num_cart_items']) && $data['num_cart_items'] > 1) {
			for ($i = 1; $i <= $data['num_cart_items']; $i++) {
				$results[$i]['item_name']	= $data["item_name$i"];
				$results[$i]['item_number']	= $data["item_number$i"];
				$results[$i]['quantity']	= $data["quantity$i"];
				$results[$i]['mc_shipping']	= $data["mc_shipping$i"];
				$results[$i]['mc_handling']	= $data["mc_handling$i"];
				$results[$i]['mc_gross'] 	= $data["mc_gross$i"];
				$results[$i]['tax'] 		= $data["tax$i"];
			}
		}
		return $results;
	}
}