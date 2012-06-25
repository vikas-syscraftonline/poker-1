<?php 
/* SVN FILE: $Id$ */
/* CustomersController Test cases generated on: 2010-02-10 15:10:46 : 1265839846*/
App::import('Controller', 'Customers');

class TestCustomers extends CustomersController {
	var $autoRender = false;
}

class CustomersControllerTest extends CakeTestCase {
	var $Customers = null;

	function startTest() {
		$this->Customers = new TestCustomers();
		$this->Customers->constructClasses();
	}

	function testCustomersControllerInstance() {
		$this->assertTrue(is_a($this->Customers, 'CustomersController'));
	}

	function endTest() {
		unset($this->Customers);
	}
}
?>