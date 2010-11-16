<?php
App::import('Core', array('HttpSocket'));

class PaymentGatewaySource extends DataSource {
  
  /********
    * Http is the HttpSocket Object.
    * @access public
    * @var object
    */
  var $Http = null;
  
  /********
    * constructer.  Load the HttpSocket into the Http var.
    */
  function __construct(){
    $this->Http =& new HttpSocket();
  }
  
  /**
   * !!!Override this method!!!
   * Checks with the server to confirm if the notification is legitimate
   *
   * @param mixed $data
   * @return boolean
   * @author Dean
   */
  public function validIpn($data) {
  	return false;
  }
}
?>