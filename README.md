# "One Plugin to rule them all"

This is an attempt to become the defacto solution for shopping cart 
implementation in CakePHP. Not to say this in of itself will be the 
best shopping cart around, but in fact give developers the resource
to build their own custom shopping cart Application to their own
specifications, allowing you to extrapolate from that point forwards.

 
## Installation:

1. Add the gateway configurations to your <code>database.php</code>. 
	You can use multiple configurations for the same gateway. The datasource and driver must be correct.
<pre>
var $paypal = array(        
	'datasource' => 'Cart.PaymentGateway',
	'driver' => 'Cart.Paypal',
	'login' => 'standard',        
	'password' => 'password',    
);
var $paypalDonations = array(        
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

2. Bind the PaymentGateway behavior to whatever model you choose
<pre>
Class Payment extends AppModel {
	var $hasMany = array('LineItem');
	var $actsAs = array(
		'Cart.PaymentGateway' => array(
			'default' => 'paypal', // Useful if you only need 1 gateway
		),
	);
}
</pre>

3. Add an IPN action
<pre>
Class PaymentsController extends AppController {
	function ipn($gatewayConfig = null) {
		$this->Payment->setGateway($gatewayConfig); // If you want to override the default or didn't specify. 
		// This would refer to the db configurations (paypal, paypalDonations, google, etc)
		
		if ($this->Payment->isValid($_POST)) {
			// go crazy
			$data['Payment'] = $this->Payment->uniform($_POST); // standardizes the fieldnames
			$data['LineItem'] = $this->Payment->extractLineItems($_POST); // If you submit orders with multiple products
			$this->Payment->saveAll($data);
			// send email... etc.
		}
	}
}
</pre>

The beauty is you have full control! You can send emails from the controller or relocate the save/formatting logic to the model callbacks:
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