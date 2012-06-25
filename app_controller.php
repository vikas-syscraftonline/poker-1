<?php

class AppController extends Controller {
	public $components = array('Session', 'Cookie', 'Auth', 'RequestHandler');
	public $helpers = array('Session', 'Paginator', 'Text', 'Html', 'Form');

	public function beforeFilter() {
		/****
		*	Configure the global site cookie
		****/
		$this->Cookie->name = 'site';
		$this->Cookie->time =  1209600; //seconds to live; 1209600 seconds = 14 days

		/*
		*	Configure the Auth component, used for managing user logins and site access
		*	NOTE: to use the component, include it in your controller and execute parent::beforeFilter()
		*/
		#if (is_object($this->Auth) && method_exists($this->Auth, 'Startup')) {
		if(array_key_exists('Auth', $this->components)) {
			$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
			$this->Auth->loginRedirect = array('/');
			$this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
			$this->Auth->autoRedirect = false; //prevent redirect on login and logout (we'll handle it)
			//$this->Auth->authError = 'Please log in';

			//$this->Auth->allow();
			$this->Auth->authorize = 'controller';
			$this->Auth->userModel = 'User'; //use the Users model for users
			$this->Auth->authenticate = ClassRegistry::init('User'); //user the Users model to create the hash
			$this->Auth->fields = array( 
				'username' => 'username', //the Users log in with emails, so tell Auth to use the email field
				'password' => 'password'
				);
			$this->Auth->userScope = array('User.active'=>1);

			//if auth loses the user session, but the profile lives in the cookie, read cookie and log users back in
			if(! $this->Auth->user() && $this->Cookie->read('User.profile')) {
				//unserialize the cookie string and use it to log in
				$user = unserialize($this->Cookie->read('User.profile'));
				$this->Auth->login($user);
			}
		}

		// always pass the pageReferrer
		$this->set('pageReferrer', $this->referrer());

		//fetch news
		$this->_getSiteNews();
	}

	//executed after the controller action, but before the view is rendered to the user
	public function beforeRender() {
	}

	//executed after the view is rendered to the user
	public function afterFilter() {
	}

	//Auth function to test if the user is authorized
	public function isAuthorized() {
		return true;
	}


/**
*	General functionality
*
*	These function provide various site utilities and don't belong to any one category of functionality
**/
	/**
	*	alias for the mis-spelled referer() method
	*
	*	function takes submitted form data and session data into account when fetching referrer
	**/
	public function referrer() {
		$referrer = $this->referer();
		if (isset($_POST['pageReferrer'])) { //override via POST
			$referrer = $_POST['pageReferrer'];
		} elseif($this->Session->read('pageReferrer')) { //override via Session
			$referrer = $this->Session->read('pageReferrer');
		}

		return $referrer;
	}

	public function sanitizeString(&$string, $allow='<strong><b><em><i><s>') {
		$string = strip_tags($string, $allow);
	}

/*
*	Site tools
*/
	protected function _getSiteNews() {
                $this->loadModel('News');
                $this->set('news', $this->News->find('all', array(
                        'conditions' =>         array(
                                'News.created >=' => date('Y-m-d', strtotime('2 days ago'))
                        ),
                        'limit' => 3,
                        'order' => 'News.created DESC'
                )));
	}
}

?>
