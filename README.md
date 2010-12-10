# "One Plugin to rule them all"

This is an attempt to become the defacto solution for shopping cart 
implementation in CakePHP. Not to say this in of itself will be the 
best shopping cart around, but in fact give developers the resource
to build their own custom shopping cart Application to their own
specifications, allowing you to extrapolate from that point forwards.

 
## Installation:

#### Add the gateway configurations to your <code>database.php</code>. 
You can use multiple configurations for the same gateway. The datasource and driver must be correct.
<pre>
var $paypal = array(        
	'datasource' => 'Cart.PaymentGateway',
	'driver' => 'Cart.Paypal',
	'login' => 'standard',        
	'password' => 'password',    
);
var $paypaldonations = array(        
	'datasource' => 'Cart.PaymentGateway',
	'driver' => 'Cart.Paypal',
	'login' => 'donations',        
	'password' => 'password',    
);
var $google = array(        
	'datasource' => 'Cart.PaymentGateway',
	'driver' => 'Cart.GoogleCheckout',
	'login' => 'username',        
	'password' => 'password',    
);
</pre>

####Bind the PaymentGateway behavior to whatever model you choose
<pre>
Class Payment extends AppModel {
	var $hasMany = array('LineItem');
	var $actsAs = array(
		'Cart.PaymentGateway' => array(
			'default' => 'paypaldonations', // [Optional] Useful if you only need 1 gateway
			'urls' => array(
				'cancel_return_url' => 'http://example.com/payments/cancel',
			),
		),
	);
}
</pre>

####Add an IPN action
<pre>
Class PaymentsController extends AppController {
	function process($gatewayConfig = null) {
		
		// This would refer to the db configurations (paypal, paypalDonations, google, etc)
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
		if (this->purchase($amount, $data, $gatewayConfig)) {
			$this->Session->setFlash('Transaction completed successfully!');
			// TODO Send Email
			$this->redirect(array('action' => 'complete'));
		} else {
			$this->Session->setFlash('Error: ' . $this->Payment->error . '. Please try again.');
			$this->redirect(array('action' => 'index'));
		}
	}
}
</pre>

####The beauty is you have full control! 
You can send emails from the controller or relocate the save/formatting logic to the model callbacks:
<pre>
Class PaymentModel extends AppModel {
	beforeIpnValidate($data, $gatewayConfig = null){}
	afterIpnValidate($response){
		if ($response) {
			// format data and save it
		} else {
			// log it
		}
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
	
### Datasource:
 - PaymentGateway
	- Works as a wrapper for individual payment gateway datasources
	- Standardizes the methods and data used for individual datasources
	- Stores configurations of different payment gateways

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