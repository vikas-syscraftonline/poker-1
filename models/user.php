<?php

class User extends AppModel {
	public $name = 'User';
	public $primaryKey = 'id';
	public $displayField = 'username';
	public $order = 'username';

	//public $hasMany = array();
	public $validate = array(
			'username'		=> array(
				'notEmpty' 	=> array(
					'rule'		=>'notEmpty',
					'required'	=> true,
					'message'	=> 'You must enter a username'
				),
				'unique'	=> array(
					'rule'		=> 'isUnique',
					'message'	=> 'Sorry, that username already exists'
				)
			),
			'password'	=> array(
				'length'	=> array(
					'rule'		=> array('minLength', 6),
					//'required'	=> true,
					'message'	=> 'Your password must be at least 6 characters'
				)
			),
			'email'		=> array(
				'validEmail'		=> array(
					'rule'		=> 'email',
					'required'	=> true,
					'message'	=> 'Please enter a valid email'
				),
				'unique'	=> array(
					'rule'		=> 'isUnique',
					'message'	=> 'An account with that email already exists'
				)
			),
			'phone'		=> array(
				'numberic'		=> array(
					'rule'		=> 'numeric',
					'required'	=> false,
					'allowEmpty'	=> true,
					'message'	=> 'Only enter numbers'
				),
				'length10'		=> array(
					'rule'		=> array('minLength', 10),
					'required'	=> false,
					'allowEmpty'	=> true,
					'message'	=> 'Please enter only your complete 10 digit phone number'
				)
			)
		);

	public $hasMany = array(
		'Game'	=> array(
			'className'	=> 'Game'
		),
		'Comment' => array(
			'className'	=> 'Comment',
			'dependent'     => true //make this delete the attendee records when the user is deleted
		),
		'Attendee' => array(
			'className'	=> 'Attendee',
			'dependent'     => true //make this delete the attendee records when the user is deleted
		)
	);

	//method used by the Auth component to hash user passwords (standard md5 hash)
/*
	public function hashPasswords($data) {
		if (isset($data['User']['password'])) {
			$data['User']['password'] = md5($data['User']['password']); //user password hashing
		}
		return $data;
	}
*/
}

?>
