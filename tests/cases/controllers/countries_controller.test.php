<?php 
/* SVN FILE: $Id$ */
/* CountriesController Test cases generated on: 2010-02-09 11:26:58 : 1265740018*/
App::import('Controller', 'Countries');

class TestCountries extends CountriesController {
	var $autoRender = false;
}

class CountriesControllerTest extends CakeTestCase {
	var $Countries = null;

	function startTest() {
		$this->Countries = new TestCountries();
		$this->Countries->constructClasses();
	}

	function testCountriesControllerInstance() {
		$this->assertTrue(is_a($this->Countries, 'CountriesController'));
	}

	function endTest() {
		unset($this->Countries);
	}
}
?>