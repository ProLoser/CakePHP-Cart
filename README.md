# "One Plugin to rule them all"

This is an attempt to become the defacto solution for shopping cart 
implementation in CakePHP. Not to say this in of itself will be the 
best shopping cart around, but in fact give developers the resource
to build their own custom shopping cart Application to their own
specifications, allowing you to extrapolate from that point forwards.

 
## Installation:

### Add the plugin and vendor
<code>git submodule add git://github.com/ProLoser/CakePHP-Cart.git plugins/cart</code> or download to <code>plugins/cart</code>

#### To use on-site CC collection (usually pro accounts)

<code>cd plugins/cart</code>

<code>git submodule update --init</code> or download https://github.com/akDeveloper/Aktive-Merchant to <code>plugins/cart/vendors/aktive_merchant</code>

### Add the gateway configurations to your <code>database.php</code>. 
You can use multiple configurations for the same gateway. The datasource and driver must be correct.
<pre>
// 'authorize' is the name of this gatewayConfig
var $authorize = array(        
	'gateway' => 'AuthorizeNet',
	'datasource' => 'Cart.AktiveMerchant',
	'login' => 'username',        
	'password' => 'password',    
);
// 'paypal' is the name of this gatewayConfig
var $paypal = array(     
	'gateway' => 'Paypal',   
	'datasource' => 'Cart.AktiveMerchant',
	'login' => 'standard',        
	'password' => 'password',
	'signature' => 'api-signature',
);
// 'paypalexpressdonations' is the name of this gatewayConfig
var $paypalexpressdonations = array( 
	'gateway' => 'PaypalExpress',    
	'datasource' => 'Cart.AktiveMerchant',
	'login' => 'donations',   
	'password' => 'password',
	'signature' => 'api-signature', 
	'currency' => 'USD',
);

// IPN DOES NOT REQUIRE AktiveMerchant
// 'paypalipn' is the name of this gatewayConfig
var $paypalipn = array(
	'driver' => 'Cart.Paypal'
	'datasource' => 'Cart.PaymentGateway',
	'login' => 'test@example.com',
);
</pre>

### Bind the PaymentGateway behavior to whatever model you choose
<pre>
Class Payment extends AppModel {
	var $hasMany = array('LineItem');
	var $actsAs = array(
		'Cart.PaymentGateway' => array(
			// gatewayConfig [Optional] Useful if you only use 1 gateway
			'default' => 'paypaldonations', 
			
			// urls are only used for Paypal Express (currently) and you may choose to use setUrls() instead
			'urls' => array( 
				'cancel_return_url' => 'http://example.com/payments/cancel',
			),
		),
	);
}
</pre>

### Add an processing action
<pre>
Class PaymentsController extends AppController {
	// $gatewayConfig is useful for multiple payment gateways (paypal, paypalDonations, authorize, etc) but you can just use default instead too
	function process($gatewayConfig = null) {
		
		// Populate your gateway data however you want
		$data = array(
			'description' => 'Test Transaction',
			'address' => array(
				'address1' => '1234 Street',
				'zip' => '98004',
				'state' => 'WA',
				'city' => 'Yorba Linda',
				'country' => 'United States',
			),
			'credit_card' => array(
				'first_name' => 'John',
				'last_name' => 'Doe',
				'number' => '5105105105105100',
				'month' => '12',
				'year' => '2012',
				'verification_value' => '123',
				'type' => 'master',
			),
		);
		$amount = 10;
		// Remember: setGateway() is optional for multi-gateway support
		$this->Payment->setGateway($gatewayConfig);
		if (this->Payment->purchase($amount, $data)) { 
			$this->Session->setFlash('Transaction completed successfully!');
			// Send Email
			$this->redirect(array('action' => 'complete'));
		} else {
			$this->Session->setFlash('Error: ' . $this->Payment->error . '. Please try again.');
		}
	}
}
</pre>

### Or setup IPN
<pre>
Class PaymentsController extends AppController {
	// Remember: setGateway() is optional for multi-gateway support
	function ipn($gatewayConfig = null) {
		$this->Payment->setGateway($gatewayConfig);
		if (this->Payment->ipn($_POST)) { 
			// Send Email
		}
	}
}
</pre>

### Go crazy from the model layer with callbacks!
You can send emails from the controller or relocate the save/formatting logic to the model callbacks:
<pre>
Class PaymentModel extends AppModel {
	var $actsAs = array(
		'Cart.PaymentGateway',
		'Cart.CreditCard',
	);
	function process($amount, $data, $gatewayConfig = null) {
		$this->setGateway($gatewayConfig);
		// You can find extra validation rules and data-prep functions in the CreditCard Behavior
		$data = $this->formatData($data['Payment']); // CreditCardBehavior::formatData() moves address and cc fields into sub-arrays for you
		return $this->purchase($amount, $data);
	}
}
</pre>

## Included Tools

### Components:
 - Cart Session
	- Helps track orders for standard carts
	- Convenience only, not required
 - Instant Payment Notification
	- Processes ipn for multiple gateways
	- Takes $gateway as an argument
	- Relies on the PaymentGatewayBehavior at the model level
	
### Behavior:
 - PaymentGateway
	- Binds different gateway datasources to the model for IPN and order processing
	- Relies on the PaymentGatewayDatasource
 - CreditCard
	- Validation Rules
	- Data formatting functions
	
### Datasource:
 - PaymentGateway
	- Works as a wrapper for individual payment gateway datasources
	- Standardizes the methods and data used for individual datasources
	- Stores configurations of different payment gateways
 - AktiveMerchant
	- An alternative to the original PaymentGateway behavior (master)
	- Relies on the aktiveMerchant vendor

## Expected Features:
 - Shopping cart session component
 - Shopping cart session helper
 - Shipping / Tax handling (feature RFC)
 - Payment Gateway Behavior
 - Optional Scaffolding: (for those who only need basic functionality)
	- ??? Products MVC
	- Orders MVC
	- OrderLineItems MVC
	- Payments (With IPN)
	- ??? PaymentLineItems MVC