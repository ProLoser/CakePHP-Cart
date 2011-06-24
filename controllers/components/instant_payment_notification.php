<?php
/**
 * InstantPaymentNotificationComponent
 *
 * @package Cart Plugin
 **/
class InstantPaymentNotificationComponent extends Object {
	
	var $config;
	var $map;

	/**
	 * Called before the Controller::beforeFilter().
	 *
	 * @param object  A reference to the controller
	 * @return void
	 * @access public
	 * @link http://book.cakephp.org/view/65/MVC-Class-Access-Within-Components
	 */
	function initialize(&$controller, $settings = array()) {
		if (!isset($this->__settings[$controller->name])) {
			$this->__settings[$controller->name] = $settings;
		}
	}
	
	/**
	 * Loads the datasource map and configuration into the helper for reference
	 *
	 * @param string $dbConfig name of db configuration to reference for the datasource 
	 * @author Dean Sofer
	 */
	function load($dbConfig) {
		if (empty($dbConfig))
			return false;
		App::Import('ConnectionManager');
        $ds = ConnectionManager::getDataSource($settings);
        $this->config = $ds->config;
		$this->map = $ds->map;
	}
	
	/**
	 * Returns a payment url with all the details about the payment as $_GET variables.
	 * Useful if you want to save a transaction before payment seamlessly to the user.
	 *
	 * WHAT? Paypal buttons POST to paypal, you can't save the form beforehand.
	 * This is a workaround. Refer to https://github.com/ProLoser/CakePHP-Cart/wiki/Examples
	 *
	 * @param string $data
	 */
	public function paymentUrl($data, $urls = array()) {
		// TODO This is the paypal-specific keys used for each value. Needs to be abstracted
		$urlAliases = array(
			'complete' => 'return',
			'notify' => 'notify_url',
			'cancel' => 'cancel_return',
			'error' => '',
		);
		$url = $this->map['server'];
		$params = array(
			'cmd' => '_xclick',
			'business' => $this->config['email'],
			'currency_code' => $this->config['currency'],
		);
		foreach ($urlAliases as $alias => $key) {
			if (isset($urls[$alias])) {
				if (is_array($urls[$alias])) {
					$urls[$alias] = Router::url($urls[$alias]);
				}
				$params[$key] = $urls[$alias];
			}
		}
		$url .= '?' . http_build_query(array_merge($params, $data));
		return $url;
	}
}
?>