
<?php
/**
 * Paypal Datasource 1.0
 * 
 * Paypal datasource to communicate with the Paypal NVP api.
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * Install instructions :
 * 
 *  - Place the newest version of paypal_source.php in your app/models/datasources folder
 *  - Add the credidentials to database.php :
 * 		var $paypal = array(
 *			'datasource' => 'paypal',
 *			'environment' => 'sandbox', // 'sandbox' or 'live'
 * 			'currency' => 'CAD',
 *			'username' => '...',
 *			'password' => '...',
 *			'signature' => '...'
 *		);
 *  - Call it from anywhere :
 * 			$paypal = ConnectionManager::getDataSource('paypal');	
 *			$response = $paypal->directPayment($billing, $payment);	
 * 
 * 
 * @author Sbastien Testeau <seb@binaryninja.com>
 * @copyright Copyright 2008, Sbastien Testeau, Ltd.
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @created December 23, 2008
 * @version 1.0
 * 
 */ 

App::import('Core', array('Xml', 'HttpSocket'));

class Paypal extends PaymentGatewaySource 
{  	
  	var $description = "Paypal direct payment API";
	var $environment = '';		
	var $currency = '';
	
	// Set up your API credentials, PayPal end point, and API version.
	var	$userName = "";
	var	$password = "";
	var	$signature = "";
	var $Http = null;
	
	//class var
	var $nvpStr = null;
  	var $nvpreq = null;

	function __construct($config) {
		parent::__construct($config);		
		$this->Http =& new HttpSocket();
		
    	$this->userName = $this->config['username'];
    	$this->password = $this->config['password'];
    	$this->signature = $this->config['signature'];
    	$this->environment = $this->config['environment'];
    	$this->currency = $this->config['currency'];
	}
	
	/**
	 * Returns information about this payment processor.
	 * This is meant to be shown to the user on the payment screen, so that they can select this payment method.
	 *
	 */
	 function info() {
	 	return $this->description;
	 }
	 
	/**
	 * Returns a Model description (metadata) or null if none found.
	 *
	 * @param Model $model
	 * @return mixed
	 */
	function describe($model) {
	
	}
	
	/**
	 * Caches/returns cached results for child instances
	 *
	 * @return array
	 */
	function listSources() {
	
	}
	
	/**
	 * To-be-overridden in subclasses.
	 *
	 * @param unknown_type $model
	 * @param unknown_type $fields
	 * @param unknown_type $values
	 * @return unknown
	 */
	function create($model, $fields = array(), $values = array()) {
		return $this->_doDirectPayment();
	}
	
	/**
	 * To-be-overridden in subclasses.
	 *
	 * @param unknown_type $model
	 * @param unknown_type $queryData
	 * @return unknown
	 */
	function read($model, $queryData = array()) {
		$this->_getTransactionDetails($transaction_id);
		$this->_search($transaction_id, $startDateStr = null, $endDateStr = null);
	}
	
	/**
	 * To-be-overridden in subclasses.
	 *
	 * @param unknown_type $model
	 * @param unknown_type $fields
	 * @param unknown_type $values
	 * @return unknown
	 */
	function update($model, $fields = array(), $values = array()) {
		return false;
	}
	
	/**
	 * To-be-overridden in subclasses.
	 *
	 * @param unknown_type $model
	 * @param unknown_type $id
	 */
	function delete($model, $id = null) {
		return $this->_refundTransaction($transaction_id, $refund_type = 'Full', $amount = null, $memo = null);
	}
	 
	/**
	 * Called by the checkout routine for payment processing to initialize payment data
	 *
	 * @param unknown_type $payment
	 * @param unknown_type $billing
	 * @param unknown_type $shipping
	 * @return mixed    array if success, false otherwise.
	 */
	function _doDirectPayment($payment, $billing, $shipping = null) {
		// Set request-specific fields.
		$paymentType = urlencode('Sale');				// Authorization or 'Sale'
		$firstName = urlencode($billing['firstname']);
		$lastName = urlencode($billing['lastname']);
		$creditCardType = urlencode($payment['cc_type']);
		$creditCardNumber = urlencode($payment['cc']);
		$expDateMonth = $payment['expiration']['month'];
		// Month must be padded with leading zero
		$padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));
		
		$expDateYear = urlencode($payment['expiration']['year']);
		$cvv2Number = urlencode($payment['security_code']);
		$address1 = urlencode($billing['address']);
		$address2 = "";
		$city = urlencode($billing['city']);
		$state = urlencode($billing['state']);
		$zip = urlencode($billing['postcode']);
		$country = urlencode($billing['country']);				// US or other valid country code
		$amount = urlencode($payment['amount']);
		$currencyID = urlencode('CAD');							// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
		
		// Add request-specific fields to the request string.
		$this->nvpStr =	"&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
					"&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
					"&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";
		
		return $this->query("doDirectPayment");
	}
	
	
	function _getBalance() {		
		// Add request-specific fields to the request string.
		$this->nvpStr =	"";		
		return $this->query("GetBalance");
	}
	
	/**
	 * Refund the transaction 
	 * 
	 * @param $transaction_id
	 * @param String $refund_type
	 * @param float $amount
	 * @param String $memo
	 * @return mixed    array if success, false otherwise.
	 */
	function _refundTransaction($transaction_id, $refund_type = 'Full', $amount = null, $memo = null) {		
		// Set request-specific fields.
		$transactionID = urlencode($transaction_id);
		$refundType = urlencode($refund_type);					// or 'Partial'
		$currencyID = urlencode($this->currency);				
		
		if(isset($amount) && isset($memo) && $refund_type === 'Partial') {
			$this->nvpStr = "&TRANSACTIONID=$transactionID&REFUNDTYPE=$refundType&CURRENCYCODE=$currencyID&AMOUNT=$amount&MEMO=$memo";
		} else {
			$this->nvpStr = "&TRANSACTIONID=$transactionID&REFUNDTYPE=$refundType&CURRENCYCODE=$currencyID";
		}		
			
		return $this->query("RefundTransaction");
	}
	
	/**
	 * Get the details of a transaction
	 *
	 * @param  $transaction_id
	 * @return mixed    array if success, false otherwise.
	 */
	function _getTransactionDetails($transaction_id) {		
		// Set request-specific fields.
		$transactionID = urlencode($transaction_id);
		$this->nvpStr = "&TRANSACTIONID=$transactionID";	
			
		return $this->query("RefundTransaction");
	}
	
	/**
	 * Search for a transaction during the specified time frame.
	 *
	 * @param $transaction_id
	 * @param $startDateStr
	 * @param $endDateStr
	 * @return mixed    array if success, false otherwise.
	 */
	function _search($transaction_id, $startDateStr = null, $endDateStr = null) {
		// Set request-specific fields.
		$transactionID = urlencode($transaction_id);
		
		// Add request-specific fields to the request string.
		$this->nvpStr = "&TRANSACTIONID=$transactionID";
		
		// Set additional request-specific fields and add them to the request string.
		if(isset($startDateStr)) {
		   $start_time = strtotime($startDateStr);
		   $iso_start = date('Y-m-d\T00:00:00\Z',  $start_time);
		   $this->nvpStr .= "&STARTDATE=$iso_start";
		  }
		
		if(isset($endDateStr)&&$endDateStr!='') {
		   $end_time = strtotime($endDateStr);
		   $iso_end = date('Y-m-d\T24:00:00\Z', $end_time);
		   $this->nvpStr .= "&ENDDATE=$iso_end";
		}
		return $this->query("TransactionSearch");
	}
	
    /**
     * Performs the communication with the paypal api
     * 
     * In case of error it returns false 
     * @return mixed array if success, false otherwise.
     * 
     */
    function _query($methodName) {
    	// Set up your API credentials, PayPal end point, and API version.
		$API_UserName = urlencode($this->userName);
		$API_Password = urlencode($this->password);
		$API_Signature = urlencode($this->signature);
				
		$API_Endpoint = "https://api-3t.paypal.com/nvp";		
		if("sandbox" === $this->environment || "beta-sandbox" === $this->environment) {
			$API_Endpoint = "https://api-3t.$this->environment.paypal.com/nvp";
		}
		$version = urlencode('51.0');
	
		// Set the API operation, version, and API signature in the request.
		$this->nvpreq = "METHOD=$methodName&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$this->nvpStr";
		
		//call the web service
		$response = $this->Http->post($API_Endpoint, $this->nvpreq);
			
		if(!$response) {
			return false;
		}
	
		// Extract the response details.
		$httpResponseAr = explode("&", $response);
	
		$httpParsedResponseAr = array();
		foreach ($httpResponseAr as $i => $value) {
			$tmpAr = explode("=", $value);
			if(sizeof($tmpAr) > 1) {
				$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
			}
		}
	
		if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
			return false;
		}
		return $httpParsedResponseAr;		
    }
    
    
}
?> 