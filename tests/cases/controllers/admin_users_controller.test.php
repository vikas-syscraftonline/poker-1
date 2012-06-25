<?php 
/* SVN FILE: $Id$ */
/* AdminUsersController Test cases generated on: 2010-02-10 15:48:45 : 1265842125*/
App::import('Controller', 'AdminUsers');

class TestAdminUsers extends AdminUsersController {
	var $autoRender = false;
}

class AdminUsersControllerTest extends CakeTestCase {
	var $AdminUsers = null;

	function startTest() {
		$this->AdminUsers = new TestAdminUsers();
		$this->AdminUsers->constructClasses();
	}

	function testAdminUsersControllerInstance() {
		$this->assertTrue(is_a($this->AdminUsers, 'AdminUsersController'));
	}

	function endTest() {
		unset($this->AdminUsers);
	}
}
?>