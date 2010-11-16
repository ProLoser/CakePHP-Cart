<?php
/************
  * Use these settings to set defaults for the Paypal Helper class.
  * The PaypalHelper class will help you create paynow, subscribe, donate, or addtocart buttons for you.
  * 
  * All these options can be set on the fly as well within the helper
  */
  
class PaypalConfig {

  /**
    * Each key will be passed to the payment gateway. Exclude key/value pairs that are not needed for the gateway.
    * Values refer to slots in the settings array. If the slot is not found, the literal value will be used as a default.
    */
	var $settings = array(
		// Server URL for cart submission
		'server' 		=> 'http://emartcart.com/cart/odb/cart.odb',
		// cart field	=> cart-value-slot or value-default
		'CartCfg'		=> 'password',
		'bName'			=> 'username',
		'FixedQty'		=> '1',
		'NoGiftCerts'	=> '1',
	);
}