<?php
/**
 * Wrapper datasource for the Aktive Merchant vendor library found here: https://github.com/akDeveloper/Aktive-Merchant
 *
 * @package Cart
 * @author Dean
 */
class AktiveMerchantSource extends DataSource {
	/**
	 * Description string for this Data Source.
	 *
	 * @var string
	 * @access public
	 */
	var $description = 'Aktive Merchant Datasource';
	
	/**
	 * Holds the database settings for the datasource
	 *
	 * @var array
	 */
	var $config = array();
	
	/**
	 * Stores the 'return_url' and 'cancel_return_url'
	 *
	 * @var array
	 */
	var $urls = array(
		'return_url' => '',
		'cancel_return_url' => '',
	);
	
	/**
	 * Gateway Object
	 *
	 * @var string
	 */
	var $gateway;
	
	/**
	 * Holds the error message if there was one
	 *
	 * @var string
	 */
	var $error = '';
  
	/**
	 * constructer.  Load the HttpSocket into the Http var.
	 */
	function __construct($config){
		parent::__construct($config);
		$this->_load();
	}
	
	/**
	 * Loads a gateway object with the passed options
	 *
	 * @param string $options 
	 * @return void
	 * @author Dean
	 */
	function _load($options = array()) {
		App::Import('Vendor', 'AktiveMerchant', array('file' => 'aktive_merchant'.DS.'lib'.DS.'merchant.php'));
		if (isset($this->config['testing']) && $this->config['testing']) {
			Merchant_Billing_Base::mode('test');
		}
		$gatewayClass = 'Merchant_Billing_' . $this->config['gateway'];
		$this->gateway = new $gatewayClass($this->config);
	}
	
	/**
	 * Creates and returns a credit card object if it is valid
	 *
	 * $data = array(
	 *		'first_name' => 'John',
	 *		'last_name' => 'Doe',
	 *		'number' => '5105105105105100',
	 *		'month' => '12',
	 *		'year' => '2012',
	 *		'verification_value' => '123',
	 *		'type' => 'master',
	 *	);
	 *
	 * @param string $data 
	 * @return $creditCard Merchant_Billing_CreditCard or false if the card is invalid
	 */
	public function creditCard($data) {
		if ($data instanceof Merchant_Billing_CreditCard) {
			if ($data->is_valid()) {
				return $data;
			} else {
				return false;
			}
		} else {
			if (isset($data['credit_card'])) {
				$data = $data['credit_card'];
			}
			$creditCard = new Merchant_Billing_CreditCard($data);
			if ($creditCard->is_valid()) {
				return $creditCard;
			} else {
				return false;
			}
		}
	}
	
	/**
	 * Submits a purchase to the active payment gateway
	 *
	 * @param decimal $amount 
	 * @param array $data requires 'description' and 'address' keys to be properly filled
	 * @return mixed $success Returns the REF ID# if successful, else false
	 */
	public function purchase($amount, $data) {
		try {
			// Paypal Express works in 2 separate parts
			if ($this->config['gateway'] == 'PaypalExpress') {
				if (!isset($_GET['token'])) {
					$this->_startPurchase($amount, $data);
				} else {
					$response = $this->_completePurchase();
				}
			} else {
				$creditCard = $this->creditCard($data);
				if (!$creditCard) {
					$this->error = 'Invalid Credit Card';
					return false;
				}
				
				$options = array(
					'description' => $data['description'],
					'address' => $data['address'],
				);
				
				$response = $this->gateway->purchase($amount, $creditCard, $options);
			}
			if ($response->success()) {
				return $response->authorization();
			} else {
				$this->error = $response->message();
					$this->log('Cart.AktiveMerchantSource: ' . $response->message(), 'cart');
				return false;
			}
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log('Cart.AktiveMerchantSource: ' . $e->getMessage(), 'cart');
			return false;
		}
	}
	
	/**
	 * PaypalExpress: Creates the transaction and sends the user to paypal to login
	 *
	 * @param string $data 
	 * @return void
	 * @author Dean
	 */
	protected function _startPurchase($amount, $data) {
		$pageURL = Router::url(null, true);
		$options = array(
			'description' => $data['description'],
			'return_url' => $pageURL,
		);
		$response = $this->gateway->setup_purchase($amount, array_merge($this->urls, $options));
		die(header('Location: ' . $this->gateway->url_for_token($response->token())));
	}
	
	/**
	 * PaypalExpress: When the user returns from paypal after logging in, the transaction is finalized
	 *
	 * @return void
	 * @author Dean
	 */
	protected function _completePurchase() {
		$response = $this->gateway->get_details_for($_GET['token'], $_GET['PayerID']);
		return $this->gateway->purchase($response->amount());
	}

	/**
	 * Changes the keys for the urls to be compatible with the AktiveMerchant vendor
	 *
	 * @return array $urls
	 */
	public function setUrls($urls) {
		if (isset($urls['complete']))
			$this->urls['return_url'] = $urls['complete'];
		if (isset($urls['cancel']))
			$this->urls['cancel_return_url'] = $urls['cancel'];
	}
	
}
?>