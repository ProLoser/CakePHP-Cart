<?php
class AktiveMerchantSource extends DataSource {
	/**
	 * Description string for this Data Source.
	 *
	 * @var string
	 * @access public
	 */
	var $description = 'Aktive Merchant Datasource';
	
	var $settings = array();
  
	/**
	 * constructer.  Load the HttpSocket into the Http var.
	 */
	function __construct($config){
		parent::__construct($config);
		App::Import('Vendor', 'Cart.AktiveMerchant', array('file' => 'lib'.DS.'merchant.php'));
	}
	
	public function test() {
		Merchant_Billing_Base::mode('test'); # Remove this on production mode
		try {
		  $gateway = new Merchant_Billing_PaypalExpress(array(
			'login' => $this->config['login'],
			'password' => $this->config['password'],
			'signature' => $this->config['signature'],
		  ));

		  # Create a credit card object if you need it.
		  $creditCard = new Merchant_Billing_CreditCard(array(
			'first_name' => 'John', // 291857145
			'last_name' => 'Doe',
			'number' => '5105105105105100',
			'month' => '12',
			'year' => '2012',
			'verification_value' => '123',
			'type' => 'master',
			)
		  );
	
		  # Extra options for transaction
		  $options = array(
			'order_id' => 'REF' . $gateway->generate_unique_id(),
			'description' => 'Test Transaction',
			'address' => array(
			  'address1' => '1234 Street',
			  'zip' => '98004',
			  'state' => 'CA',
			  'country' => 'USA',
			  'city' => 'Yorba Linda',
			),
			'return_url' => 'http://localhost/us/payments/ipn/paypalexpress',
			'cancel_return_url' => 'http://localhost/',
		  );
			
		  if ($creditCard->is_valid()) {
			
			$setupResponse = $gateway->setup_purchase('20', $options);
			debug($gateway->url_for_token($setupResponse->token()));die;
			$options['token'] = $setupResponse->token();
			$options['payer_id'] = $setupResponse->payer_id();
			# Authorize transaction
			$response = $gateway->purchase('20', $options);
			if ( $response->success() ) {
				die('Success Authorize');
			} else {
			  var_dump($response->message());
				die('failure');
			}
		  } else {
				die('invalid');
		  }
		} catch (Exception $e) {
		  var_dump($e->getMessage());
			die('exception');
		}
	}
	
}
?>