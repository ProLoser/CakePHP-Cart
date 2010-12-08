<?php
class PaymentGatewaySource extends DataSource {
	/**
	 * Description string for this Data Source.
	 *
	 * @var string
	 * @access public
	 */
	var $description = 'Payment Gateway Datasource';
	
	/**
	 * Http is the HttpSocket Object.
	 * @access public
	 * @var object
	 */
	var $Http = null;
  
	/**
	 * constructer.  Load the HttpSocket into the Http var.
	 */
	function __construct($config){
		parent::__construct($config);
		$this->settings = $this->_settings();
		App::import('HttpSocket');
		$this->Http = new HttpSocket();
	}
	
	/**
	 * Returns the proper settings map from the config files
	 *
	 * @param string $mode null override 'defaults' or 'testing' detection by passing the specific settings name
	 * @return array $settings
	 * @author Dean
	 */
	public function _settings($mode = null) {
		$settings = Configure::read('Cart.' . $this->config['driver']);
		if (isset($this->config['testing'])) {
			$settings = array_merge($settings['defaults'], $settings['testing']);
		} elseif (!$mode && $mode != 'defaults') {
			$settings = array_merge($settings['defaults'], $settings[$mode]);
		} else {
			$settings = $settings['defaults'];
		}
		return $settings;
	}
	
	/**
	 * Iterates through the post-back data of the IPN and converts the Order Information to a Cake-friendly array
	 *
	 * @param string $data 
	 * @return mixed $lineItems a formatted array of line items from the ipn post-back data
	 * @author Dean
	 */
	public function uniform($data) {
		$result = array();
		foreach ($this->settings as $slot => $slotAlias) {
			if (isset($data[$slot])) {
				$data[$slotAlias] = $data[$slot];
				unset($data[$slot]);
			}
		}
		return $result;
	}
  
	/**
	 * !!!OVERRIDE ME!!!
	 * Checks with the server to confirm if the notification is legitimate
	 *
	 * @param mixed $data
	 * @return boolean
	 * @author Dean
	 */
	public function verify($data) {
		return false;
	}
	
	/**
	 * !!!OVERRIDE ME!!!
	 * Scans the returned response from $this->verify() and gives an understandable response
	 *
	 * @param string $response 
	 * @return boolean
	 * @author Dean
	 */
	public function checkResponse($response) {
		return false;
	}
	
	/**
	 * !!!Override this method!!!
	 * Iterates through the post-back data of the IPN and converts the Line Items to a Cake-friendly array
	 *
	 * @param string $data 
	 * @return mixed $lineItems a formatted array of line items from the ipn post-back data
	 * @author Dean
	 */
	public function extractLineItems($data) {
		return false;
	}
}
?>