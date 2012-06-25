<?php 
/* SVN FILE: $Id$ */
/* GamesController Test cases generated on: 2010-03-25 15:32:55 : 1269556375*/
App::import('Controller', 'Games');

class TestGames extends GamesController {
	var $autoRender = false;
}

class GamesControllerTest extends CakeTestCase {
	var $Games = null;

	function startTest() {
		$this->Games = new TestGames();
		$this->Games->constructClasses();
	}

	function testGamesControllerInstance() {
		$this->assertTrue(is_a($this->Games, 'GamesController'));
	}

	function endTest() {
		unset($this->Games);
	}
}
?>