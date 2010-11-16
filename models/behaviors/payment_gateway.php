<?php
/* SVN FILE: $Id: tree.php 8120 2009-03-19 20:25:10Z gwoo $ */
/**
 * Tree behavior class.
 *
 * Enables a model object to act as a node-based tree.
 *
 * PHP versions 4 and 5
 *
 * CakePHP :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2006-2008, Cake Software Foundation, Inc.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2006-2008, Cake Software Foundation, Inc.
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP Project
 * @package       cake
 * @subpackage    cake.cake.libs.model.behaviors
 * @since         CakePHP v 1.2.0.4487
 * @version       $Revision: 8120 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2009-03-19 13:25:10 -0700 (Thu, 19 Mar 2009) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Tree Behavior.
 *
 * Enables a model object to act as a node-based tree. Using Modified Preorder Tree Traversal
 * 
 * @see http://en.wikipedia.org/wiki/Tree_traversal
 * @package       cake
 * @subpackage    cake.cake.libs.model.behaviors
 */
class PaymentGatewayBehavior extends ModelBehavior {
	
	/**
	 * Default settings for the behavior
	 *
	 * @var string
	 */
	var $settings = array(
		'default' => null,
	);

	function setup(&$Model, $settings) {
		$this->settings = array_merge($this->settings, $settings);
	}
	
	/**
	 * If the developer declared the trigger in the model, call it
	 *
	 * @param object $Model instance of model
	 * @param string $trigger name of trigger to call
	 * @access protected
	 */
	function _trigger(&$Model, $trigger) {
		if (method_exists($Model, $trigger)) {
			return call_user_func(array($Model, $trigger));
		}
	}
	
	/**
     * Verifies POST data given by the instant payment notification
     *
     * @param array $data Most likely directly $_POST given by the controller.
     * @return boolean true | false depending on if data received is actually valid from paypal and not from some script monkey
     */
	public function validIpn(&$Model, $data, $gatewayConfig = null) {
      if(!empty($data) && ($gatewayConfig || $this->settings['default'])){
		App::import('Model', 'ConnectionManager', false);
		$gateway =& ConnectionManager::getDataSource($gatewayConfig);
        return $gateway->validIpn($data);
      }
      return false;
	}
}
?>