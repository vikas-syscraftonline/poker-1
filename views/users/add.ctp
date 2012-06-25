<div class="users form">
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php __('Add User');?></legend>
	<?php
		echo $form->input('username');
		echo $form->input('password');
		echo $form->input('phone');
		echo $form->input('email');
		echo $form->input('active');
		echo $form->input('admin');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Users', true), array('action' => 'index'));?></li>
		<li><?php echo $html->link(__('List Games', true), array('controller' => 'games', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Game', true), array('controller' => 'games', 'action' => 'add')); ?> </li>
	</ul>
</div>
