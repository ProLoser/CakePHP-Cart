<?php
/**
 * InstantPaymentNotificationComponent
 * 
 * Depends on Payment Gateway Behavior
 *
 * @package default
 * @author Dean
 * @version $Id$
 * @copyright 
 **/

class InstantPaymentNotificationComponent extends Object {

/**
 * Array containing the names of components this component uses. Component names
 * should not contain the "Component" portion of the classname.
 *
 * @var array
 * @access public
 */
	var $components = array();

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
			$settings = $this->__settings[$controller->name];
		}
	}

/**
 * Called after the Controller::beforeFilter() and before the controller action
 *
 * @param object  A reference to the controller
 * @return void
 * @access public
 * @link http://book.cakephp.org/view/65/MVC-Class-Access-Within-Components
 */
	function startup(&$controller) {
	}

/**
 * Called after the Controller::beforeRender(), after the view class is loaded, and before the
 * Controller::render()
 *
 * @param object  A reference to the controller
 * @return void
 * @access public
 */
	function beforeRender(&$controller) {
	}

/**
 * Called after Controller::render() and before the output is printed to the browser.
 *
 * @param object  A reference to the controller
 * @return void
 * @access public
 */
	function shutdown(&$controller) {
	}

/**
 * Called before Controller::redirect()
 *
 * @param object  A reference to the controller
 * @param mixed  A string or array containing the redirect location
 * @access public
 */
	function beforeRedirect(&$controller, $url, $status = null, $exit = true) {
	}
	
	/**
	 * Checks to see if the payment is valid
	 *
	 * @param string $gateway 
	 * @return void
	 * @author Dean
	 */
	public function process(&$controller, $gatewayConfig = null) {
		$controller->{$controller->modelClass}->validIpn($_POST, $gatewayConfig);
	}
}
?>