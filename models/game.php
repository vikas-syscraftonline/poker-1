<?php 
class Game extends AppModel {
	public $name = 'Game';
	public $displayField = 'date';

	public $validate = array(
		'date'	=> array(
			'unique' => array(
				'rule'		=> 'isUnique',
				'message'	=> 'A poker game for that date already exists'
			)
		)
	);

	public $belongsTo = array(
		'User'	=> array(
			'className'	=> 'User'
		),
		'Host'	=> array(
			'className'	=> 'User'
		),
	);

	public $hasMany = array(
		'Comment' => array(
			'className'	=> 'Comment',
			'order'		=> 'Comment.created ASC',
			'dependent'	=> true
		),
		'Attendee' => array(
			'className'	=> 'Attendee',
			'order'		=> 'Attendee.attendee_status_id DESC, Attendee.id ASC',
			'dependent'	=> true //make this delete the attendee records when the game is deleted
		)
	);
}
?>
