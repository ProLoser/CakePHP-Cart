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
		App::import('HttpSocket');
		$this->Http =& new HttpSocket();
	}
  
	/**
	 * !!!Override this method!!!
	 * Checks with the server to confirm if the notification is legitimate
	 *
	 * @param mixed $data
	 * @return boolean
	 * @author Dean
	 */
	public function isValid($data) {
		return false;
	}
	
	/**
	 * Iterates through the post-back data of the IPN and converts the Order Information to a Cake-friendly array
	 *
	 * @param string $data 
	 * @return mixed $lineItems a formatted array of line items from the ipn post-back data
	 * @author Dean
	 */
	public function uniform($data) {
		App::import('Config', $this->config['driver'] . 'Config');
		$result = array();
		if (isset($this->config['test'])) {
			$map = array_merge($PaypalConfig->testSettings['default'], $PaypalConfig->testSettings['testing']);
		} else {
			$map = $PaypalConfig->settings['default'];
		}
		debug($map);
		return $result;
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