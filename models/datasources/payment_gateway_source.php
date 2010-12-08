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
		$this->Http =& new HttpSocket(); // TODO Deprecated in PHP 5.3?
		$this->settings = Configure::read('Cart.' . $this->config['driver']);
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
		if (isset($this->config['testing'])) {
			$map = array_merge($this->settings['default'], $this->settings['testing']);
		} else {
			$map = $this->settings['default'];
		}
		foreach ($map as $slot => $slotAlias) {
			if (isset($data[$slotAlias])) {
				$data[$slot] = $data[$slotAlias];
				unset($data[$slotAlias]);
			}
		}
		return $result;
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