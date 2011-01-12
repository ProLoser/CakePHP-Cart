<?php
/**
 * CartPaymentsController
 * 
 * [Short Description]
 *
 * @package Cart Plugin
 * @author Dean
 * @version $Id$
 * @copyright 
 **/

class CartPaymentsController extends AppController {
	var $name = 'CartPayments';

	var $helpers = array('Html', 'Form');
	var $components = array('Session');
	
	function ipn($type = null) {
		$this->CartPayment->processIpn($_POST, $type);
	}

	/**
	 * Index action
	 *
	 * @access public
	 */
	function index() {
		$this->Payment->recursive = 0;
		$this->set('cartPayments', $this->paginate());
	}
	
	/**
	 * View action
	 *
	 * @access public
	 * @param integer $id ID of record
	 */
	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Payment', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('payment', $this->Payment->read(null, $id));
	}
	
	/**
	 * Add action
	 *
	 * @access public
	 */
	function add() {
		if (!empty($this->data)) {
			$this->Payment->create();
			if ($this->Payment->save($this->data)) {
				$this->Session->setFlash(__('The Payment has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Payment could not be saved. Please, try again.', true));
			}
		}
	}
	
	/**
	 * Edit action
	 *
	 * @access public
	 * @param integer $id ID of record
	 */
	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Payment', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Payment->save($this->data)) {
				$this->Session->setFlash(__('The Payment has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Payment could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Payment->read(null, $id);
		}
	}

	/**
	 * Delete action
	 *
	 * @access public
	 * @param integer $id ID of record
	 */
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Payment', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Payment->delete($id)) {
			$this->Session->setFlash(__('Payment deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Payment was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>