<?php 
class Attendee extends AppModel {
	public $name = 'Attendee';

	public $belongsTo = array(
		'User' => array(
			'className' => 'User'
		),
		'Game' => array(
			'className' => 'Game'
		),
		'AttendeeStatus' => array(
			'className' => 'AttendeeStatus'
		)
	);
}
?>
