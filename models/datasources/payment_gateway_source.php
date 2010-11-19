<?php
App::import('Core', array('HttpSocket'));

class PaymentGatewaySource extends DataSource {
  
	/**
	 * Http is the HttpSocket Object.
	 * @access public
	 * @var object
	 */
	var $Http = null;
  
	/**
	 * constructer.  Load the HttpSocket into the Http var.
	 */
	function __construct(){
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
	 * !!!Override this method!!!
	 * Iterates through the post-back data of the IPN and converts the lineItems to a Cake-friendly array
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