<?php 
class AttendeeStatus extends AppModel {
	public $name = 'AttendeeStatus';
	public $displayField = 'status';

	public $hasMany = array(
		'Attendee' => array(
			'className' => 'Attendee'
		)
	);
}
?>
