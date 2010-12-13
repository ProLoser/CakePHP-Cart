<?php
/**
 * CreditCard Model Behavior
 * 
 * [Short Description]
 *
 * @package Cart
 * @author Dean Sofer
 * @version $Id$
 * @copyright 
 **/
class CreditCardBehavior extends ModelBehavior {

	/**
	 * Contains configuration settings for use with individual model objects.
	 * Individual model settings should be stored as an associative array, 
	 * keyed off of the model name.
	 *
	 * @var array
	 * @access public
	 * @see Model::$alias
	 */
	var $settings = array();
	
	/**
	 * undocumented variable
	 *
	 * @var Merchant_Billing_CreditCard
	 */
	var $creditCard;

	/**
	 * Initiate CreditCard Behavior
	 *
	 * @param object $model
	 * @param array $config
	 * @return void
	 * @access public
	 */
	function setup(&$model, $config = array()) {
		App::Import('Vendor', 'Cart.AktiveMerchant', array('file' => 'lib'.DS.'merchant.php'));
	}
	
	/**
	 * Before validate callback
	 *
	 * @param object $model Model using this behavior
	 * @return boolean True if validate operation should continue, false to abort
	 * @access public
	 */
	function beforeValidate(&$model) { 
		$this->creditCard = new Merchant_Billing_CreditCard($data);
		return true;
	}
}