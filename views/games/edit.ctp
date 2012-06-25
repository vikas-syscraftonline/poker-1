<div class="games form">
<?php echo $form->create('Game');?>
	<?php echo $form->hidden('id', array('value'=>$this->data['Game']['id'])); ?>
        <h2><?php __('Edit Game');?></h2>
	<?php echo $html->link(__('View',true), array('action' => 'view', $form->value('Game.id')), array('class'=>'button')); ?>
	<?php echo $html->link(__('Delete', true), array('action' => 'delete', $form->value('Game.id')), array('class'=>'button'), sprintf(__('Are you sure you want to delete the %s game?', true), $form->value('Game.date'))); ?>
        <table>
        <tr><td class='label'>Host</td><td><?php echo $form->input('host_id',array('label'=>false,'div'=>false)); ?></td></tr>
        <tr><td class='label'>Date</td><td><?php echo $form->input('date',array('label'=>false,'div'=>false)); ?></td></tr>
        <tr><td class='label'>Time</td><td><?php echo $form->input('time',array('label'=>false,'div'=>false)); ?></td></tr>
        <tr><td class='label'>Address</td><td><?php echo $form->input('address',array('label'=>false,'div'=>false)); ?></td></tr>
	<tr><td class='label'>Notes</td><td><?php echo $form->input('notes',array('label'=>false,'div'=>false)); ?></td></tr>
        </table>
	<?php echo $html->link('Cancel', array('action'=>'view',$this->data['Game']['id']), array('class'=>'button'),'Discard all chandes?'); ?>
<?php echo $form->end(array('label'=>'Submit','div'=>false));?>
</div>
