<?php 
class CommentsController extends AppController {
	public $name = 'Comments';

	public function add() {
		if(!$this->data || !$this->data['Comment']['game_id']) //no from data or missing game id
			$this->redirect(array('controller'=>'games', 'action'=>'index'));

		$this->data['Comment']['user_id'] = $this->Session->read('Auth.User.id');
		$this->sanitizeString($this->data['Comment']['comment'], '');
		if($this->Comment->save($this->data)) {
			$this->Session->setFlash('Comment added');
		} else {
			$this->Session->setFlash('Failed to save your comment. Please try again.');
		}
		$this->redirect($this->referrer());
	}

	public function delete($id) {
		$comment = $this->Comment->read(NULL, $id);
		if(!$comment) {
			$this->Session->setFlash("Invalid comment");
			$this->redirect($this->referrer());
		}

		if(!$this->Session->read('Auth.User.admin')==1 && $comment['Comment']['user_id']!=$this->Session->read('Auth.User.id')) {
			$this->Session->setFlash("You can't remove a comment that isn't yours");
			$this->redirect($this->referrer());
		}

		if(!$this->Comment->delete()) {
			$this->Session->setFlash('Failed to delete comment. Please try again.');
		} else {
			$this->Session->setFlash('Comment deleted');
		}
		$this->redirect($this->referrer());
	}
}
 ?>
