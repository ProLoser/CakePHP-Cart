<?php
/**
 * CartShell
 * 
 * [Short Description]
 *
 * @package default
 * @author Dean
 * @version $Id$
 * @copyright 
 **/
class CartShell extends Shell {

	var $uses = array('Payment');

	function welcome() {
		parent::welcome();
	}

	function main() {
	}
	
	function help() {
	}

}