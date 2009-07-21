<?php
class CartHelper extends AppHelper {
	var $helpers = array('Html', 'Form', 'Session');
	
	function renderCart() {
		$result = null;
		
		if ($this->Session->check('Order')) {
			debug($this->Session->read('Order'));
			$result = true;
		}
		
		return $result;
	}

    function makeEdit($title, $url) {
        // Logic to create specially formatted link goes here...
    }
}
?>