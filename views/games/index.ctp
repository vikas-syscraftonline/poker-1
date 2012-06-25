<div class="games index">
<h2><?php __('Upcoming Games');?></h2>
<table cellpadding="0" cellspacing="0" id="gamelist">
<tr>
	<th class='date'><?php echo $paginator->sort('date');?></th>
	<th class='time'><?php echo $paginator->sort('time');?></th>
	<th class='address'><?php echo $paginator->sort('address');?></th>
	<th class='host'><?php echo $paginator->sort('host_id');?></th>
	<th class='notes'><?php echo $paginator->sort('notes');?></th>
	<th class='count'><?php echo __('Yes'); ?></th>
	<th class='comments'><?php echo $html->image('comment.png'); ?></th>
	<th class='actions'><?php __('Actions');?></th>
</tr>
<?php if(count($games)==0): ?>
	<tr class='empty'><td colspan='8'>There are no games scheduled. <?php e($paginator->link('Create one', array('action'=>'add'))); ?></td></tr>
<?php endif; ?>
<?php //debug($games); ?>
<?php
$i = 0;
$showOld = false;
foreach ($games as $game):
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}

	$class = null;
	if(strtotime($game['Game']['date']) < strtotime(date('Y-m-d')) && !$showOld) {
		$showOld = true;
?>
		<tr class='empty'>
			<td colspan="8">Old Games...</td>
		</tr>
<?php
	}
?>
	<tr<?php echo $class;?>>
		<td class='date'>
			<?php 	$date = date('D, M d', strtotime($game['Game']['date']));
				$year = date('Y', strtotime($game['Game']['date']));
				if($year > date('Y'))
					$date .= ' '.$year;

				echo $paginator->link($date, array('action'=>'view', $game['Game']['id']));
			?>

			
		</td>
		<td class='time'>
			<?php echo date('g:i A', strtotime($game['Game']['time'])); ?>
		</td>
		<td class='address'>
			<?php echo (strtotime($game['Game']['date']) < strtotime(date('Y-m-d'))) ? 'n/a' : $game['Game']['address']; ?>
			<?php //if(!empty($game['Game']['address'])) echo ' -- '.$paginator->link('MAP', 'http://maps.google.com/maps?q='.$game['Game']['address'], array('target'=>'_blank')); ?>
		</td>
		<td class='host'>
			<?php echo $paginator->link($text->truncate($game['Host']['username'], 10), array('controller' => 'users', 'action' => 'view', $game['Host']['id'])); ?>
		</td>
		<td class='notes'>
			<?php 
				#echo substr(strip_tags($game['Game']['notes']),0,30); 
				#if(strlen(strip_tags($game['Game']['notes'])) > 30) 
				#	echo '... '.$paginator->link('more', array('action'=>'view', $game['Game']['id']));
				echo $text->truncate(strip_tags($game['Game']['notes']), 35, array('exact'=>false, 'html'=>true)).' '.
					$paginator->link('more', array('action'=>'view', $game['Game']['id']));
			?>
		</td>
		<td class='count' title="<?php echo (count($game['Attendee'])==1) ? count($game['Attendee'])." Person" : count($game['Attendee'])." People"; ?> Attending">
			<?php 
				$yesCount = 0;
				foreach($game['Attendee'] as $attendee) {
					if($attendee['attendee_status_id']==1)
						$yesCount+=$attendee['count'];
				}
				echo $yesCount;
			?>
		</td>
		<td class='comments' title="<?php echo count($game['Comment']); ?> Comment<?php if(count($game['Comment'])!=1) echo "s"; ?>">
			<?php echo $paginator->link(count($game['Comment']), array('action'=>'view', $game['Game']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $paginator->link( $html->image('/img/view.png',array('alt'=>'View','title'=>'View')), array('action' => 'view', $game['Game']['id']), array('escape' => false)); ?>
			<?php if($game['Game']['user_id'] == $session->read('Auth.User.id') || $session->read('Auth.User.admin')==1): ?>
			<?php echo $paginator->link($html->image('/img/edit.png',array('alt'=>'Edit','title'=>'Edit')), array('action' => 'edit', $game['Game']['id']), array('escape' => false)); ?>
			<?php echo $paginator->link($html->image('/img/delete.png',array('alt'=>'Delete','title'=>'Delete')), array('action' => 'delete', $game['Game']['id']), array('escape' => false), sprintf(__('Are you sure you want to delete the game on %s?', true), $game['Game']['date'])); ?>
			<?php endif; ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>


<div class="paging">
	<?php $paginator->options(array('url' => $this->passedArgs)); ?>
	<?php //echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
</div>


<div>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %start% to %end% of %count%', true)
));
?></div>

<div>
<?php 
	if(isset($this->params['named']['games']) && $this->params['named']['games'] == 'all') {
		echo $paginator->link('Hide past games', array('action'=>'index', 'games'=>'new'));
	} else {
		echo $paginator->link('Show past games', array('action'=>'index', 'games'=>'all'));
	}
 ?>
</div>
