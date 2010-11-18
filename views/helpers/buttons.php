<?php
/**
 * ButtonHelper
 * 
 * [Short Description]
 *
 * @package default
 * @author Dean
 * @version $Id$
 * @copyright 
 **/

class ButtonHelper extends AppHelper {

	/**
	 * An array containing the names of helpers this controller uses. The array elements should
	 * not contain the "Helper" part of the classname.
	 *
	 * @var mixed A single name as a string or a list of names as an array.
	 * @access protected
	 */
	var $helpers = array('Html', 'Form');

	/**
	 * Called after the controller action is run, but before the view is rendered.
	 *
	 * @access public
	 */
	function beforeRender() {
		
	}
	
	
}