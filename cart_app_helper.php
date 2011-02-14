<?php
class CartAppHelper extends AppHelper {
	
	var $config;
	
	function __construct($settings = null) {
		$this->load($settings);
		parent::__construct();
	}
	
	function load($settings) {
		if (empty($settings))
			return false;
		App::Import('ConnectionManager');
        $ds = ConnectionManager::getDataSource($settings);
        $this->config = $ds->config;
	}
}