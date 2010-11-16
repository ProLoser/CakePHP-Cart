<?php
/**
 * Paypal DataSource
 *
 * Used for reading and writing to Twitter, through models.
 *
 * PHP Version 5.x
 *
 * CakePHP(tm) : Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2009, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class PaymentGatewayPaypal extends PaymentGatewaySource {
  
  /************************
    * verifies POST data given by the paypal instant payment notification
    * @param array $data Most likely directly $_POST given by the controller.
    * @return boolean true | false depending on if data received is actually valid from paypal and not from some script monkey
    */
  function validIpn($data){
    $this->Http =& new HttpSocket();
        
    $data['cmd'] = '_notify-validate';
  
    if(isset($data['test_ipn'])) {
      $server = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    } else {
      $server = 'https://www.paypal.com/cgi-bin/webscr';
    }
    
    $response = $this->Http->post($server, $data);
    
    if($response == "VERIFIED"){
      return true;
    }
    
    if(!$response){
      $this->log('HTTP Error in PaypalDatasource::isValid while posting back to PayPal', 'paypal');
    }
    
    return false;
  }
}