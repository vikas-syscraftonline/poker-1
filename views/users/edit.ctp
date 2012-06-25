<div class="users form">
<h2>Edit User</h2>
<?php echo $form->create('User',array('action'=>'edit'));?>
	<?php
		echo $form->hidden('pageReferrer', array('value'=>$pageReferrer));
		echo $form->input('id');
		echo $form->input('username');
		echo $form->input('password');
		echo $form->input('phone');
		echo $form->input('email');
		echo $form->input('send_email');
		if($session->read('Auth.User.admin')==1):
		echo $form->input('active');
		echo $form->input('admin');
		endif;
	?>
	<?php echo $html->link('Cancel', $pageReferrer, array('class'=>'button'),'Discard all changes?'); ?>
<?php echo $form->end(array('label'=>'Submit','div'=>false));?>
</div>
<div class="actions">
	<ul>
		<?php if($session->read('Auth.User.admin')==1): ?>
		<li><?php echo $html->link(__('Delete', true), array('action' => 'delete', $form->value('User.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('User.id'))); ?></li>
		<li><?php echo $html->link(__('List Users', true), array('action' => 'index'));?></li>
		<?php endif; ?>
		<li><?php echo $html->link(__('Back to User View', true), array('controller' => 'users', 'action'=>'view', $this->data['User']['id'])); ?></li>
		<li><?php echo $html->link(__('Back to Games List', true), array('controller' => 'games', 'action' => 'index')); ?> </li>
	</ul>
</div>
