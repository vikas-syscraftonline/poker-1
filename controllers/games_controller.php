<?php
class GamesController extends AppController {

	public $name = 'Games';
	public $helpers = array('Html', 'Form');
	public $components = array('Email');
	public $paginate = array(
		'order'	=> 'date >= CURDATE() DESC, if(date >= CURDATE(), UNIX_TIMESTAMP(Game.date), -UNIX_TIMESTAMP(Game.date))',
		'limit' => 10,
	);

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->authError = 'Please log in to access the site';
	}

	public function index() {
debug($this->Session->read('Auth.User.id'));
		//fetch games
		$this->Game->recursive = 1;
		if(isset($this->params['named']['games']) && $this->params['named']['games'] == 'all') {
			$games = $this->paginate('Game');
		} else {
			$games = $this->paginate('Game', array('date >= CURDATE()'));
		}

		$this->set(compact('games'));
	}

	public function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Game.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Game->recursive=1;
		$game = $this->Game->find('first', array('conditions'=>array('Game.id'=>$id)));

		#$this->loadModel('Comment');
		#$this->Comment->recursive = 0;
		$comments = $this->Game->Comment->find('all', array('conditions'=>array('Comment.game_id'=>$id),'order'=>'Comment.created ASC'));
		$attendees = $this->Game->Attendee->find('all', array('conditions'=>array('Attendee.game_id'=>$id)));
		$attendeeStatuses = $this->Game->Attendee->AttendeeStatus->find('list');

		$this->set(compact('game','comments','attendees','attendeeStatuses'));
	}

	public function add() {
		if (!empty($this->data)) {
			$this->Game->create();

			//sanitize the notes
			$this->sanitizeString($this->data['Game']['notes']);

			if ($this->Game->save($this->data)) {
				//tell the user the game was saved
				$this->Session->setFlash(__('The Game has been saved', true));

				//if you're adding the game, you're attending by default
				$this->data['Attendee']['game_id'] = $this->Game->getInsertId();
				$this->data['Attendee']['user_id'] = $this->Session->read('Auth.User.id');
				$this->data['Attendee']['attendee_status_id'] = 1;
				$this->loadModel('Attendee');
				$this->Attendee->save($this->data);

				//email all users about the new game
				$this->_sendNewGameEmail($this->Game->getInsertId());

				//redirect the user to the new game's view page
				$this->redirect(array('action'=>'view', $this->Game->getInsertId()));
			} else {
				$this->Session->setFlash(__('The Game could not be saved. Please, try again.', true));
			}
		}

		$hosts = $this->Game->User->find('list',array('conditions'=>array('User.active'=>1)));//, array('fields'=>array('User.id', 'User.username')));
		$this->set(compact('hosts'));
	}

	public function edit($id = null) {
		//check for a valid id in the url and passed data
		if (!$id) {
			$this->Session->setFlash(__('Invalid Game', true));
			$this->redirect(array('action'=>'index'));
		}

		//data was passed
		if (!empty($this->data)) {
			//make sure the id in the data matches the id in the url
			if($id != $this->data['Game']['id']) {
				$this->Session->setFlash(__('Invalid game passed, no changes made', true));
				$this->redirect(array('action'=>'view', $id));
			}

			//fetch the game info from the db
			$game = $this->Game->read(NULL, $id);

			//ensure proper permissions
			if($game['Game']['user_id'] != $this->Session->read('Auth.User.id') && !$this->Session->read('Auth.User.admin')) {
				$this->Session->setFlash(__("You are not authorized to edit this game", true));
				$this->redirect(array('action'=>'view', $id));
			}

			//sanitize the notes
			$this->sanitizeString($this->data['Game']['notes']);

			if ($this->Game->save($this->data)) {
				//alert the user to changes
				$this->Session->setFlash(__('The Game has been updated', true));

				//email users about changes
				$this->_sendEditGameEmail($game, $id);

				//redirect user to the view page
				$this->redirect(array('action'=>'view', $this->data['Game']['id']));
			} else {
				$this->Session->setFlash(__('The Game could not be saved. Please, try again.', true));
			}
		}

		if (empty($this->data)) {
			$this->data = $this->Game->read(null, $id);
			if($this->data['Game']['user_id'] != $this->Session->read('Auth.User.id') && !$this->Session->read('Auth.User.admin')) {
				$this->Session->setFlash(__("You can't edit games you didn't create", true));
				$this->redirect(array('action'=>'view', $id));
			}
		}

		$hosts = $this->Game->User->find('list');//, array('fields'=>array('User.id', 'User.username')));
		$this->set(compact('hosts'));
	}

	public function delete($id = null) {
		//invalid game id
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Game', true));
			$this->redirect(array('action'=>'index'));
		}

		$game = $this->Game->read(NULL, $id);

		//not authorized to remove the specified game
		if($game['User']['id'] != $this->Session->read('Auth.User.id') && !$this->Session->read('Auth.User.admin')) {
			$this->Session->setFlash(__("You can't remove games you didn't create", true));
			$this->redirect(array('action'=>'index'));
		}

		//fetch attendees, so we can email them about the removal of the game
		App::Import('Model', 'Attendee');
		$Attendee = new Attendee();
		$attendees = $Attendee->find('all', array(
			'conditions' => array(
				'User.send_email' => 1,
				'Attendee.game_id' => $game['Game']['id'],
				'Attendee.user_id !=' => $game['Game']['user_id'],
				'OR' => array(
					'Attendee.attendee_status_id = 1',
					'Attendee.attendee_status_id = 2'
				),
			)
		));

		//good to go, let's remove it
		if ($this->Game->del($id,true)) {
			$this->Session->setFlash(__('Game deleted', true));

			//email users about the deleted game
			$this->_sendDeletedGameEmail($game, $attendees);

			$this->redirect(array('action'=>'index'));
		} else {
			$this->Session->setFlash(__('Failed to remove the game. Try again...', true));
			$this->redirect($this->referrer());
		}
	}

	public function attend() {//$game_id=NULL, $status=NULL, $count=NULL) {
		if($this->data) {
			$game = $this->Game->read(NULL, $this->data['Attendee']['game_id']);
			if($this->data['Attendee']['attendee_status_id'] < 1 || $this->data['Attendee']['attendee_status_id'] > 3) {
				$this->Session->setFlash('Invalid status id');
			} elseif(! $game) {
				$this->Session->setFlash('Invalid game id');
			} else {
				$this->loadModel('Attendee');
				$this->Attendee->unbindModel(array('belongsTo'=>array('AttendeeStatus','Game','User')));
				$this->data['Attendee']['count'] = max(0,$this->data['Attendee']['guests'])+1; //set count to guests + user
				$this->data['Attendee']['user_id'] = $this->Session->read('Auth.User.id');
				if(! $this->Attendee->save($this->data)) {
					$this->Session->setFlash(__('Failed to update your attendance, try again'));
				}
			}

		}
		$this->redirect($this->referrer());
	}

	protected function _sendDeletedGameEmail($game, $attendees) {
		$this->set(compact('game'));

		//email all attendees retrieved
		$okStatus = true;
		foreach($attendees as $attnedee) {
			$this->Email->reset();
			$this->Email->sendAs = 'both';
			$this->Email->template = 'deleted_game';
			$this->Email->subject = "Poker Game Canceled: ".date('l, M d', strtotime($game['Game']['date']));
			$this->Email->from = 'Poker <noreply@poker.joefleming.net>';
			$this->Email->to = "{$attnedee['User']['username']} <{$attnedee['User']['email']}>";
			$this->Email->return = 'joe@joefleming.net';
			if(!$this->Email->send()) {
				$okStatus = false;
			}
		}

		return $okStatus;
	}

	protected function _sendEditGameEmail($oldData, $id) {
		$game = $this->Game->read(NULL, $id);
		if(!$game)
			return false;

		$alerts = array();
		$alertFields = array(
			array('Game'=>'date'), 
			array('Game'=>'time'), 
			array('Game'=>'address'),
			array('Host'=>'username')
		);
		foreach($alertFields as $field) {
			if($game[key($field)][current($field)] != $oldData[key($field)][current($field)])
				$alerts[] = current($field);
		}

		if(count($alerts) > 0) {
			//pass information to the view
			$this->set(compact('game', 'oldData', 'alerts'));

			//email all listed attendees
			$okStatus = true;

			//fetch all other active users
			$users = $this->Game->User->find('all', array(
				'conditions' => array(
					'User.active' => 1,
					'User.send_email' => 1,
					'User.id !=' => $this->Session->read('Auth.User.id'),
				)
			));

			//email each user about the changes
			foreach($users as $user) {
				$this->set(compact('user'));
				$this->Email->reset();
				$this->Email->sendAs = 'both';
				$this->Email->template = 'edited_game';
				$this->Email->subject = "Poker Game " . ucwords(implode(', ', $alerts)) . " Changed: " 
					. date('l, F d', strtotime($game['Game']['date']));
				$this->Email->from = 'Poker <noreply@poker.joefleming.net>';
				$this->Email->to = "{$user['User']['username']} <{$user['User']['email']}>";
				$this->Email->return = 'joe@joefleming.net';
				if(!$this->Email->send()) {
					$okStatus = false;
				}
			}


			return $okStatus;
		}

		return true;
	}

	protected function _sendNewGameEmail($game_id) {
		$game = $this->Game->read(NULL, $game_id);
		if(!$game)
			return false;

		//fetch all active users
		$users = $this->Game->User->find('all', array(
			'conditions' => array(
				'User.active' => 1,
				'User.send_email' => 1,
				'User.id !=' => $game['Game']['user_id']
			)
		));

		$this->set(compact('game'));

		//email all users retrieved
		$okStatus = true;
		foreach($users as $user) {
			$this->set('user', $user);
			$this->Email->reset();
			$this->Email->sendAs = 'both';
			$this->Email->template = 'new_game';
			$this->Email->subject = "New Poker Game: ".date('l, F d', strtotime($game['Game']['date']));
			$this->Email->from = 'Poker <noreply@poker.joefleming.net>';
			$this->Email->to = "{$user['User']['username']} <{$user['User']['email']}>";
			$this->Email->return = 'joe@joefleming.net';
			if(!$this->Email->send()) {
				$okStatus = false;
			}
		}

		return $okStatus;
	}

}
?>
