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
		'gateway' => null,
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
	 * Returns an instance of the currently set payment gateway
	 *
	 * @return $PaymentGatewayDatasource instance for calling methods
	 * @author Dean
	 */
	public function _getGateway(&$Model) {
		App::import('Model', 'ConnectionManager', false);
		return ConnectionManager::getDataSource($this->settings[$Model->name]['gateway']);
	}
	
	/**
	 * Used to adjust the payment gateway before using the behavior
	 *
	 * @param string $Model 
	 * @param string $gatewayConfig 
	 * @return void
	 * @author Dean
	 */
	public function setGateway(&$Model, $gatewayConfig = null) {
		if ($gatewayConfig) {
			$this->settings[$Model->name]['gateway'] = $gatewayConfig;
		}
	}
	
	/**
     * Verifies POST data given by the instant payment notification
     *
     * @param array $data Most likely directly $_POST given by the controller.
     * @return boolean true | false depending on if data received is actually valid from paypal and not from some script monkey
     */
	public function isValid(&$Model, $data) {
		$this->_callback($Model, 'beforeIpnValidate', array($data, $this->settings[$Model->name]['gateway']));
		if(!empty($data)){
			$gateway = $this->_getGateway($Model);
			$result = $gateway->isValid($data);
			$this->_callback($Model, 'afterIpnValidate', array($result));
			return $result;
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
		$gateway = $this->_getGateway($Model);
		return $gateway->extractLineItems($data);
    }
    
    public function test(&$Model, $gatewayConfig = null) {
    	$this->setGateway($Model, $gatewayConfig);
		$gateway = $this->_getGateway($Model);
		return $gateway->test();
    }
}
?>