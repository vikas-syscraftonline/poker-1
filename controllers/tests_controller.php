<?php

class TestsController extends AppController {
	public $name = 'Tests';
	public $uses = array();

	function index() {
	}

	function encryption() {
	}

	function misc() {
		App::Import('Model', 'Attendee');
		$Attendee = new Attendee();
		$attendees = $Attendee->find('all', array(
			'conditions' => array(
				'User.send_email' => 1,
				'Attendee.game_id' => 44,
				'Attendee.user_id !=' => 1,
				'OR' => array(
					'Attendee.attendee_status_id = 1',
					'Attendee.attendee_status_id = 2'
				),
			)
		));
		debug($attendees);
	}

}

?>
