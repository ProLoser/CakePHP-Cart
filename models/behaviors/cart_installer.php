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
class CartInstallerBehavior extends ModelBehavior {

	/**
	 * Errors
	 *
	 * @var array
	 */
	var $errors = array();
	/**
	 * Defaults
	 *
	 * @var array
	 * @access protected
	 */
	var $_defaults = array();
	/**
	 * User Model
	 *
	 * @var array
	 * @access protected
	 */
	var $userModel = false;
	
	/**
	 * User Model
	 *
	 * @var array
	 * @access protected
	 */
	var $User = null;
	
	/**
	 * User Model
	 *
	 * @var array
	 * @access protected
	 */
	var $triggers = array();

	
	var $relationships = array(
		/*'hasMany'=> array(
			'Answer' => array('className' => 'Answers.Answer'),				
			'Question'=> array('className' => 'Answers.Question'),
			'BestAnswer'=> array('className' => 'Answers.BestAnswer'),
			'Report'=> array('className' => 'Answers.Report'),
			'Point'=> array('className' => 'Answers.Point'),
			'FavoriteQuestion'=> array('className' => 'Answers.FavoriteQuestion')
		),
		'hasOne' => array(
			'UserAnswerProfile'=> array('className' => 'Answers.UserAnswerProfile'),
			'UserStatistic'=> array('className' => 'Answers.UserStatistic')
		)*/
	);
	

	/**
	 * Initiate Installation behavior
	 *
	 * @param object $Model instance of model
	 * @param array $fields array of configuration settings.
	 * @return void
	 * @access public
	 */
	function setup(&$Model, $settings) {
		if (isset($settings['userModel']) && $settings['userModel']) {
			$this->userModel = $Model->alias;
			// Store the user model reference
			$this->User = $Model;
			$this->bindUserRelationships($Model);
		}
	}
	
	/**
	 * Bind all related models in the plugin to the User model
	 *
	 * @return boolean success
	 * @access public
	 */
	function bindUserRelationships(&$Model) {
		$success = $Model->bindModel($this->relationships, false);
		
		return $success;
	}
	
}
?>