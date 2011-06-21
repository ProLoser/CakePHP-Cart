<?php
class InstantPaymentNotificationSource extends DataSource {
	/**
	 * Description string for this Data Source.
	 *
	 * @var string
	 * @access public
	 */
	var $description = 'Instant Payment Notification Datasource';
	
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
	 * Holds the field map for the gateway
	 *
	 * @var string
	 */
	var $map = array();
  
	/**
	 * Constructer.  Load the HttpSocket into the Http var.
	 */
	function __construct($config){
		parent::__construct($config);
		$this->map = $this->_map();
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
	protected function _map($mode = null) {
        $config = explode('.', $this->config['driver']);
        $config[1] = Inflector::underscore($config[1]);
        Configure::load(implode('.', $config));
		$map = Configure::read($this->config['driver']);
		if (isset($this->config['testing']) && isset($map['testing'])) {
			$map = array_merge($map['defaults'], $map['testing']);
		} elseif ($mode && $mode != 'defaults') {
			$map = array_merge($map['defaults'], $map[$mode]);
		} else {
			$map = $map['defaults'];
		}
		return $map;
	}
	
	/**
	 * Iterates through the post-back data of the IPN and converts the Order Information to a Cake-friendly array
	 * TODO: currently broken due to an inconsistent config file 
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
				} elseif (!in_array($generalField, $this->map, true)) {
					$result[$gatewayField] = $generalField;
				}
				unset($data[$generalField]);
			} elseif (in_array($generalField, $this->map, true)) {
				$result[$generalField] = $data[$gatewayField];
				unset($data[$gatewayField]);
			}
		}
		return $result;
	}
  
	/**
	 * Checks with the server to confirm if the notification is legitimate
	 *
	 * @param mixed $data
	 * @return boolean
	 * @author Dean
	 */
	public function ipn($data = null) {
		if (empty($data)) {
			$data = $_POST;
		}
		$response = $this->submit($data);
		
		return ($this->checkResponse($response) && $this->checkEmail($data));
	}
	
	/**
	 * Submits the data for verification
	 *
	 * @param string $data 
	 * @return void
	 * @author Dean
	 */
	public function submit($data) {
		return $this->Http->post($this->map['server'], $data);
	}
	
	/**
	 * Checks Paypal postback to see if it is valid
	 *
	 * @param string $response 
	 * @return boolean
	 * @author Dean
	 */
	public function checkResponse($response) {
		if ($response == $this->map['responses']['verified']) {
			return true;
		}
		if (!$response) {
			$this->log('HTTP Error in PaymentGatewayDatasource::checkResponse while posting back to gateway', 'cart');
		}
		return false;
	}
	
	/**
	 * Checks to see if the email in the ipn data matches the database config
	 *
	 * @param string $response 
	 * @return boolean
	 * @author Dean
	 */
	public function checkEmail($data) {
		if ($data[$this->map['ipn']['email_fieldname']] == $this->config['email']) {
			return true;
		}
		return false;
	}
	
	/**
	 * Checks to see if the testing flag is set in the ipn data
	 *
	 * @param string $response 
	 * @return boolean
	 * @author Dean
	 */
	public function checkTesting($data) {
		if (empty($data[$this->map['ipn']['testing_fieldname']])) {
			return false;
		}
		return true;
	}
}
?>