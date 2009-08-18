<?php
class CartHelper extends AppHelper {
	var $session = null;
	
	function init($session) {
		$this->session = $session;
	}
	
	function hasItems() {
		$returnValue = false;
		
		if ($this->calculateQuantity() > 0) {
			$returnValue = true;
		}
		
		return $returnValue;
	}
	
	function getValue($key) {
		$returnValue = 0;
		
/*		App::import('Component', 'Session');
		$this->session = new SessionComponent();*/
		
		if ($key == 'quantity') {
			$returnValue = $this->calculateQuantity();
		} else {
			if ($this->session->check('Order.Totals.' . $key)) {
				$returnValue = $this->session->read('Order.Totals.' . $key);
			}
		}
		
		return $returnValue;
	}
	
	function calculateQuantity() {
		$quantity = 0;
		
		if ($this->session->check('Order')) {
			foreach ($this->session->read('Order.LineItem') as $item) {
				$quantity += $item['Totals']['quantity'];	
			}
		}
		
		return $quantity;
	}
}
?>