<?php 
class Comment extends AppModel {
	public $name = 'Comment';

	public $belongsTo = array(
		'Game'	=> array(
			'className'	=> 'Game'
		),
		'User'	=> array(
			'className'	=> 'User'
		)
	);
}
 ?>
