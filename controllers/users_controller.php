<?php
class UsersController extends AppController {

	public $name = 'Users';
	public $helpers = array('Html', 'Form');
	public $components = array('Auth','Email');
	public $paginate = array(
                'order' => 'User.username ASC',
                'limit' => 20,
        );

	function beforeFilter() {
		parent::beforeFilter();
                $this->Auth->deny();
		$this->Auth->allow('login','logout','register','success');
                $this->Auth->loginRedirect = $this->referrer();
		//$this->Auth->authError = 'Please log in to access your account';
		//Security::setHash('md5');
        }

        function login() {
		#$this->Auth->authError = 'Please log in to access your account';

                $this->set('pageReferrer', $this->referrer());
		if($this->Auth->user()) { //user is logged in...
			if (!empty($this->data)) { //user just submitted form to log in
				$this->_doLogin($this->data);
			}
			$this->redirect($this->Auth->redirect()); //redirect user to the post-login page
		}

		$this->set('title_for_layout', 'User Login');
        }

	function _doLogin($data) {
		if($this->Auth->login($data)) {
			$user = serialize(array(
				'username'=>$data['User']['username'],
				'password'=>$data['User']['password'],
				)
			);
			$this->Cookie->write('User.profile', $user);
			return true;
		}
		return false;
	}

	function register() {
		$this->set('title_for_layout', 'User Regiestration');

		if($this->Auth->user()) {
			$this->redirect(array('/'));
		}

                $this->set('pageReferrer', $this->referrer());
		if (!empty($this->data)) { //user just submitted form to log in
			if($this->data['User']['passwrd'] != $this->data['User']['passwrd2']) { //check that passwords match
				$this->Session->setFlash('Passwords do not match, please try again.');
			} else { //if they do, continue
				//hash passwords
				$this->data['User']['password'] = $this->data['User']['passwrd']; //set the password for hashing
				$this->data = $this->Auth->hashPasswords($this->data);
				//clear the password inputs in the form so they are never rendered
				unset($this->data['User']['passwrd']);
				unset($this->data['User']['passwrd2']);
				if($user=$this->User->save($this->data)) { //if account is created, log user in, report and redirect
					/*if($this->_doLogin($this->data)) {
						$this->Session->setFlash('Your account has been created. Thank you!');
						$this->redirect($this->Auth->redirect());
					} else {
						$this->Session->setFlash('Your account was created but you could not be logged in. Please try logging in.');
						$this->redirect(array('action'=>'login'));
					}*/

					//send email about new users, with link to activation
					#if(! $this->__sendRegisteredEmail($this->User->id));
					#	$this->Session->setFlash('Email failed to send');
					$this->__sendRegisteredEmail($this->User->id);

					$this->redirect(array('action'=>'success'));
				} else {
					$this->Session->setFlash('There was a problem creating your account. Please check your input and try again.');
				}
			}
		}
	}

	function __sendRegisteredEmail($id) {
		$user = $this->User->read(NULL, $id);

		$this->Email->subject = "New User Registration: {$user['User']['username']}";
		$this->Email->from = 'Poker <noreply@poker>';
		$this->Email->to = 'Admin <admin@email>';

		$this->Email->template = 'new_user';
		$this->Email->sendAs = 'both';
		$this->set(compact('user'));

		//$this->Email->delivery = 'mail';
		return $this->Email->send();
	}

	function success() {
		if(strstr($this->referrer(), 'login')===false && strstr($this->referrer(), 'register')===false)
			$this->redirect(array('action'=>'login'));
	}

	function activate($id=NULL) {
		$user = $this->User->read(NULL, $id);
		if(!$user) {
			$this->Session->setFlash('Invalid account id');
		}
		elseif($user['User']['active']==1) {
			$this->Session->setFlash('That account is already active');
		}
		else {
			$user['User']['active'] = 1;
			if(!$this->User->save($user)) {
				$this->Session->setFlash("Failed to activate {$user['User']['username']}");
			} else {
				$this->Session->setFlash("User '{$user['User']['username']}' is now active");
				$this->__sendActivatedEmail($id);
			}
		}

		$this->redirect('/');
	}

	function __sendActivatedEmail($id) {
		$user = $this->User->read(NULL, $id);

		$this->Email->subject = "Account Activated at http://".$_SERVER['SERVER_NAME'];
		$this->Email->from = 'Poker <noreply@poker.joefleming.net>';
		$this->Email->to = "{$user['User']['username']} <{$user['User']['email']}>";

		$this->Email->template = 'account_activated';
		$this->Email->sendAs = 'both';
		$this->set(compact('user'));

		//$this->Email->delivery = 'mail';
		return $this->Email->send();
	}

	function logout() {
		$this->Session->setFlash('You have been logged out of your account');
		$this->Cookie->delete('User.profile');
		$this->redirect($this->Auth->logout());
	}

	function index() {
		$this->User->recursive = 0;
		$users = $this->paginate('User');
		$this->set(compact('users'));
	}

	function view($id=NULL) {
		$user = $this->User->read(NULL, $id);
		$this->set(compact('user'));
	}

	function edit($id=NULL) {
		if($this->data) { //form submitted
			//if the password is blank, don't save it!
			if($this->data['User']['password']==$this->Auth->password(''))
				unset($this->data['User']['password']);

			//prevent users from setting active and admin flags
			if(! $this->Session->read('Auth.User.admin')) {
				unset($this->data['User']['active']);
				unset($this->data['User']['admin']);
			}

			if(! $this->User->save($this->data)) {
				$this->Session->setFlash('Failed to save changes');
			} else {
				$this->Session->setFlash('User updated');
				if($this->Session->read('Auth.User.admin')==1)
					$this->redirect(array('action'=>'index'));
				else
					$this->redirect(array('action'=>'view', $this->data['User']['id']));
			}
		} else {
			$this->data = $this->User->read(NULL, $id);
			if(! $this->data) {
				$this->Session->setFlash('Invalid User');
				$this->redirect('/');
			}
			$this->data['User']['password'] = '';
		}
	}

	function delete($id) {
		if (!$id) {
                        $this->Session->setFlash(__('Invalid id for User', true));
                        $this->redirect(array('action'=>'index'));
                }
                if ($this->User->del($id)) {
                        $this->Session->setFlash(__('User deleted', true));
                        $this->redirect(array('action'=>'index'));
                }
	}

	function account() {
		$this->redirect(array('action'=>'edit', $this->Session->read('Auth.User.id')));
	}

	function isAuthorized() {
		$restricted = array('index', 'edit', 'delete', 'activate');
		if( $this->action == 'edit' && ($this->Session->read('Auth.User.admin')==1 || $this->Session->read('Auth.User.id')==$this->passedArgs[0]) ) {
			return true;
		} elseif( $this->Session->read('Auth.User.admin')!=1 && in_array($this->action, $restricted) ) {
			return false;
		}

		return true;
	}

}
?>
