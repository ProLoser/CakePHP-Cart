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
		//$this->creditCard = new Merchant_Billing_CreditCard($model->data);
		return true;
	}
	
	/**
	 * Return an array of possible credit card types
	 *
	 * @param boolean $formatted false if set to true will create a Select-element friendly array
	 * @return array
	 * @author Dean
	 */
	public function creditCardTypes(&$model, $formatted = false) {
		$ccTypes = Merchant_Billing_CreditCard::CARD_COMPANIES();
		if (!$formatted) {
			return $ccTypes;
		} else {
			$ccTypes = array_keys($ccTypes);
			foreach ($ccTypes as $ccType) {
				if ($ccType === 'master') {
					$ccInflected[] = 'Mastercard';
				} else {
					$ccInflected[] = Inflector::humanize($ccType);
				}
			}
			$creditCards = array_combine($ccTypes, $ccInflected);
			return $creditCards;
		}
	}
	
	public function validMonth(&$model, $check) {
		return Merchant_Billing_CreditCard::valid_month(array_pop($check));
	}
	
	public function validExpiryYear(&$model, $check) {
		return Merchant_Billing_CreditCard::valid_expiry_year(array_pop($check));
	}
	
	public function validCardNumber(&$model, $check) {
		return Merchant_Billing_CreditCard::valid_number(array_pop($check));
	}
	
	public function validCardType(&$model, $check) {
		$ccTypes = $this->creditCardTypes($model);
		return isset($ccTypes[array_pop($check)]);
	}
	
	public function matchCardType(&$model, $check, $numberField) {
		return Merchant_Billing_CreditCard::matching_type($this->data[$model->alias][$numberField], array_pop($check));
	}
	
	/**
	 * Pulls out a aktive-friendly data array from a flat data array
	 *
	 * @param string $data 
	 * @return void
	 * @author Dean
	 */
	public function formatData(&$model, $data = null) {
		if (!$data) {
			$data = $model->data;
		}
		$reference = array(
			'description',
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
				'number' => '4007000000027',
				'month' => '12',
				'year' => '2012',
				'verification_value' => '123',
				'type' => 'visa',
			),
		);
		if (isset($data['description'])) {
			$result['description'] = $data['description'];
		}
		$result['address'] = array_intersect_key($data, $reference['address']);
		$result['credit_card'] = array_intersect_key($data, $reference['credit_card']);
		return $result;
	}
}