<div class="games form">
<?php echo $form->create('Game');?>
	<?php echo $form->hidden('user_id', array('value'=>$session->read('Auth.User.id'))); ?>
	<h2><?php __('Add Game');?></h2>
	<table>
	<tr><td class='label'>Host</td><td><?php echo $form->input('host_id',array('selected'=>$session->read('Auth.User.id'),'label'=>false,'div'=>false)); ?></td></tr>
	<tr><td class='label'>Date</td><td><?php echo $form->input('date',array('label'=>false,'div'=>false)); ?></td></tr>
	<tr><td class='label'>Time</td><td><?php echo $form->input('time',array('selected'=>'19:00:00','label'=>false,'div'=>false)); ?></td></tr>
	<tr><td class='label'>Address</td><td><?php echo $form->input('address',array('label'=>false,'div'=>false)); ?></td></tr>
	<tr><td class='label'>Notes</td><td><?php echo $form->input('notes',array('label'=>false,'div'=>false)); ?></td></tr>
	</table>
	<?php echo $html->link('Cancel', array('action'=>'index'), array('class'=>'button'),'Discard all data?'); ?>
<?php echo $form->end(array('label'=>'Create Game','div'=>false));?>
</div>
