<?php
class PaymentGatewaySource extends DataSource {
	/**
	 * Description string for this Data Source.
	 *
	 * @var string
	 * @access public
	 */
	var $description = 'Payment Gateway Datasource';
	
	var $_generalFields = array();
	
	/**
	 * Http is the HttpSocket Object.
	 * @access public
	 * @var object
	 */
	var $Http = null;
	
	/**
	 * Holds the database settings for the datasource
	 *
	 * @var array
	 */
	var $config = array();
  
	/**
	 * constructer.  Load the HttpSocket into the Http var.
	 */
	function __construct($config){
		parent::__construct($config);
		$this->config = $this->_config();
		$this->_generalFields = Configure::read('Cart.fields');
		App::import('HttpSocket');
		$this->Http = new HttpSocket();
		Configure::load('Cart.config');
	}
	
	/**
	 * Returns the proper settings map from the config files
	 *
	 * @param string $mode null override 'defaults' or 'testing' detection by passing the specific settings name
	 * @return array $settings
	 * @author Dean
	 */
	public function _config($mode = null) {
		Configure::load($this->config['driver']);
		$config = Configure::read($this->config['driver']);
		if (isset($this->config['testing']) && isset($config['testing'])) {
			$config = array_merge($config['defaults'], $config['testing']);
		} elseif (!$mode && $mode != 'defaults') {
			$config = array_merge($config['defaults'], $config[$mode]);
		} else {
			$config = $config['defaults'];
		}
		return $settings;
	}
	
	/**
	 * Iterates through the post-back data of the IPN and converts the Order Information to a Cake-friendly array
	 *
	 * @param string $data
	 * @param boolean $reverse false Set to true to go from GeneralFormat -> GatewayFormat
	 * @return mixed $lineItems a formatted array of line items from the ipn post-back data
	 * @author Dean
	 */
	public function uniform($data, $reverse = false) {
		$result = array();
		foreach ($this->settings as $gatewayField => $generalField) {
			if ($reverse) {
				// Uses the default value if the general field isn't found
				if (isset($data[$generalField])) {
					$result[$gatewayField] = $data[$generalField];
				} elseif (!in_array($generalField, $this->_generalFields, true)) {
					$result[$gatewayField] = $generalField;
				}
				unset($data[$generalField]);
			} elseif (in_array($generalField, $this->_generalFields, true)) {
				$result[$generalField] = $data[$gatewayField];
				unset($data[$gatewayField]);
			}
		}
		return $result;
	}
	
	/**
	 * Submits a payment to the payment gateway
	 *
	 * @param string $data 
	 * @return void
	 * @author Dean
	 */
	public function send($data) {
		$data = $this->uniform($data, true);
		
		$response = $this->Http->post($settings['server'], $data);
		
		return $this->checkResponse($response);
	}
  
	/**
	 * Checks with the server to confirm if the notification is legitimate
	 *
	 * @param mixed $data
	 * @return boolean
	 * @author Dean
	 */
	public function ipn($data) {
		$response = $this->verify($data);
		
		return $this->checkResponse($response);
	}
	
	/**
	 * Submits the data for verification
	 *
	 * @param string $data 
	 * @return void
	 * @author Dean
	 */
	public function verify($data) {
		return $this->Http->post($this->config['server'], $data);
	}
	
	/**
	 * Scans the returned response from $this->verify() and gives an understandable response
	 *
	 * @param string $response 
	 * @return boolean
	 * @author Dean
	 */
	public function checkResponse($response) {
		if ($response == $this->config['responses']['verified']) {
			return true;
		}
		if (!$response) {
			$this->log('HTTP Error in PaymentGatewayDatasource::checkResponse while posting back to gateway', 'cart');
		}
		return false;
	}
}
?>