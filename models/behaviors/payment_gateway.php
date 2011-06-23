<?php
/**
 * Payment Gateway Behavior
 *
 * Attaches payment gateway datasources to a model (usually payments) for IPN and other data API features
 *
 * Supported Model Callbacks:
 *  - beforeIpnValidate($data, $gatewayConfig)
 *  - afterIpnValidate($response)
 *
 * @package default
 * @author Dean
 */
class PaymentGatewayBehavior extends ModelBehavior {
	
	/**
	 * Default settings for the behavior
	 *
	 * @var string
	 */
	var $defaults = array(
		// For PaypalExpress Only
		'urls' => array(
			'complete' => 'http://example.com/complete', // Arrays are also allowed
			'cancel' => 'http://example.com/cancel',
			'error' => 'http://example.com/error',
			'notify' => 'http://example.com/ipn',
		)
	);
	
	/**
	 * Settings for the behavior on every model
	 *
	 * @var string
	 */
	var $settings = array();

	/**
	 * Initialize behavior settings
	 *
	 * @param string $Model 
	 * @param string $settings 
	 * @return void
	 * @author Dean
	 */
	function setup(&$Model, $settings = array()) {
		$this->settings[$Model->name] = array_merge($this->defaults, $settings);
	}
	
	/**
	 * If the developer declared the trigger in the model, call it
	 *
	 * @param object $Model instance of model
	 * @param string $trigger name of trigger to call
	 * @access protected
	 */
	function _callback(&$Model, $trigger, $parameters = array()) {
		if (method_exists($Model, $trigger)) {
			return call_user_func_array(array($Model, $trigger), $parameters);
		}
	}
	
	/**
	 * Used for setting the 'cancel_return_url' and/or 'error_return_url' for PaypalExpress
	 *
	 * @param object $Model 
	 * @param mixed $urls array('cancel' => 'http://localhost/cancel') OR 'complete'
	 * @param mixed $value used when setting only 1 url, provide the value url specified for preceeding argument
	 * @return void
	 * @author Dean
	 */
	public function setUrls(&$Model, $urls, $value = null) {
		if (!is_array($urls) && $value) {
			$this->settings[$Model->name]['urls'][$urls] = $value;
		} else {
			$this->settings[$Model->name]['urls'] = $urls;
		}
	}
	
	/**
	 * Returns an instance of the payment gateway
	 *
	 * @return $PaymentGatewayDatasource instance for calling methods
	 * @author Dean
	 */
	public function loadGateway(&$Model) {
		App::import('Model', 'ConnectionManager', false);
		return ConnectionManager::getDataSource($Model->useDbConfig);
	}	
	
	public function purchase(&$Model, $amount, $data) {
		$data['amount'] = $amount;
		$continue = $this->_callback($Model, 'beforePurchase', array($data));
		if ($continue === false) {
			return false;
		} elseif ($continue) {
			$data = $continue;
		}
		$gateway = $this->loadGateway($Model);
		$gateway->setUrls($this->settings[$Model->name]['urls']);
		$success = $gateway->purchase($data['amount'], $data);
		if (!$success) {
			$Model->error = $gateway->error;
		}
		$this->_callback($Model, 'afterPurchase', array($data, $success));
		return $success;
	}
	
	/**
	 * Verifies POST data given by the instant payment notification
	 *
	 * @param array $data Most likely directly $_POST given by the controller.
	 * @return boolean true | false depending on if data received is actually valid from paypal and not from some script monkey
	 */
	public function ipn(&$Model, $data) {
		$continue = $this->_callback($Model, 'beforeIpn', array($data));
		if ($continue === false) {
			return false;
		} elseif ($continue) {
			$data = $continue;
		}
		if(!empty($data)){
			$gateway = $this->loadGateway($Model);
			$gateway->setUrls($this->settings[$Model->name]['urls']);
			$success = $gateway->ipn($data);
			$this->_callback($Model, 'afterIpn', array($data, $success));
			return true;
		}
		return false;
	}
	
	/**
	 * builds the associative array for paypalitems only if it was a cart upload
	 *
	 * @param raw post data sent back from paypal
	 * @return array of cakePHP friendly association array.
	 */
	public function extractLineItems(&$Model, $data) {
		$gateway = $this->loadGateway($Model);
		return $gateway->extractLineItems($data);
	}
}
?>