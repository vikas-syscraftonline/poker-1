<div class="games view">
	<table id="gametable"><tr>
	<td valign="top" id="details">
		<h2><?php  __('Game Details'); ?></h2>

		<?php if($game['Game']['user_id']==$session->read('Auth.User.id') || $session->read('Auth.User.admin')==1): ?>
		<?php echo $html->link(__(' Edit',true), array('action' => 'edit', $game['Game']['id']),array('class'=>'button'),null,false); ?>
		<?php echo $html->link(__('Delete', true), array('action' => 'delete', $game['Game']['id']),array('class'=>'button'), __('Are you sure you want to delete this game?', true),false); ?>
		<?php endif; ?>

		<dl><?php $i = 0; $class = ' class="altrow"';?>

			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Host'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $html->link($game['Host']['username'], array('controller' => 'users', 'action' => 'view', $game['Host']['id'])); ?>
				&nbsp;
			</dd>

			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Date'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo date('l, F n, Y', strtotime($game['Game']['date'])); ?> at <?php echo date('h:i A', strtotime($game['Game']['time'])); ?>
				&nbsp;
			</dd>

			<?php 
			//if the address is defined, pass to JS and show the address section
			if(strtotime($game['Game']['date']) >= strtotime(date('Y-m-d')) && !empty($game['Game']['address'])): 
				$html->scriptBlock("var gameAddress = '{$game['Game']['address']}';", array('inline'=>false));
			?>
				<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Address'); ?></dt>
				<dd<?php if ($i++ % 2 == 0) echo $class;?>>
					<?php echo $game['Game']['address'];
						//echo ' -- '.
						//$html->link('MAP', 'http://maps.google.com/maps?q='.$game['Game']['address'], array('target'=>'_blank'));
					?>
					<div id="map_canvas">&nbsp;</div>
					&nbsp;
				</dd>
			<?php endif; ?>

			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Notes'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo nl2br($game['Game']['notes']); ?>
				&nbsp;
			</dd>

			<?php if($session->read('Auth.User.admin')==1): ?>

			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $html->link($game['User']['username'], array('controller' => 'users', 'action' => 'view', $game['User']['id'])); ?>
				&nbsp;
			</dd>

			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $game['Game']['created']; ?>
				&nbsp;
			</dd>

			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $game['Game']['modified']; ?>
				&nbsp;
			</dd>

			<?php endif; ?>
		</dl>
	</td>
	<td valign="top" id="info">
		<div id="attendance">
		<h2><?php echo __('Attendance'); ?></h2>
		<?php 
			echo $form->create('Attendee', array('url'=>array('controller'=>'games', 'action'=>'attend'))); 
			//echo $form->hidden('Attendee.user_id', array('value'=>$session->read('Auth.User.id')));
			echo $form->hidden('Attendee.game_id', array('value'=>$game['Game']['id']));
		?>
		<div>
			Will you be there? 
			<?php 
				$yeses = $maybes = $noes = array();
				$yesesCount = $maybesCount = $noesCount = 0;
				$userAttendanceStatus = NULL;
				foreach($attendees as $attendee) {
					$count = ($attendee['Attendee']['count'] > 1) ? ' (+'.($attendee['Attendee']['count']-1).')' : '';
					switch($attendee['Attendee']['attendee_status_id']){
						case 1: 
							$yeses[$attendee['User']['id']] = $attendee['User']['username'].$count; 
							$yesesCount += $attendee['Attendee']['count'];
							break;
						case 2:
							$maybes[$attendee['User']['id']] = $attendee['User']['username'].$count; 
							$maybesCount += $attendee['Attendee']['count'];
							break;
						case 3: 
							$noes[$attendee['User']['id']] = $attendee['User']['username'].$count; 
							$noesCount += $attendee['Attendee']['count'];
							break;
					}
					if($attendee['Attendee']['user_id']==$session->read('Auth.User.id')) {
						$userAttendanceStatus = $attendee['Attendee']['attendee_status_id'];
						$userGuestCount = $attendee['Attendee']['count']-1;
						echo $form->hidden('Attendee.id', array('value'=>$attendee['Attendee']['id']));
					}
				}

				$statii = array();
				/*
				foreach($attendeeStatuses as $attendee_status_id=>$status) {
					if($userAttendanceStatus==$attendee_status_id)
						$statii[] = $status;
					else
						$statii[] = $html->link($status, array('controller'=>'games', 'action'=>'attend', $game['Game']['id'], $attendee_status_id));
				}
				*/
				echo "<p>";
				echo $form->radio('Attendee.attendee_status_id', $attendeeStatuses, array('legend'=>false, 'value'=>$userAttendanceStatus));
				echo $form->input('Attendee.guests', array('label'=>'Additional Guests: ', 'value'=>$userGuestCount));
				echo "</p>";
				echo implode(' / ', $statii);
			?>
		</div>
		<?php echo $form->end('Update'); ?>
		<dl>
			<!-- Show attendance -->
			<?php //debug($attendees); ?>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Attending ('.$yesesCount.')'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php if(count($yeses)==0): ?><span class='empty'>Nobody yet...</span><?php endif; ?>
				<?php echo implode(', ', $yeses); ?>
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Might Attend ('.$maybesCount.')'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php if(count($maybes)==0): ?><span class='empty'>Nobody yet...</span><?php endif; ?>
				<?php echo implode(', ', $maybes); ?>
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Not Attending ('.$noesCount.')'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php if(count($noes)==0): ?><span class='empty'>Nobody yet...</span><?php endif; ?>
				<?php echo implode(', ', $noes); ?>
			</dd>

		</dl>
		</div>
		<div id="comments">
			<h2><?php echo __('Comments'); ?></h2>
			<?php if(count($comments)==0): ?><div class="empty">No Comments</div><?php endif; ?>
			<dl>
			<?php foreach($comments as $comment): ?>
				<dt<?php if ($i % 2 == 0) echo $class;?>> 
					<div class='date'><?php echo date('M d', strtotime($comment['Comment']['created']))." - ".date('h:ia', strtotime($comment['Comment']['created'])); ?>
					<?php if($comment['Comment']['user_id']==$session->read('Auth.User.id') || $session->read('Auth.User.admin')==1)
						echo " [".$html->link('delete', array('controller'=>'comments','action'=>'delete',$comment['Comment']['id']),null,'Are you sure you want to delete that comment?')."]"
				?>
					</div>
					<div class='name'><?php echo $comment['User']['username']; ?></div> 
				</dt>
				<dd<?php if ($i++ % 2 == 0) echo $class;?>>
					<?php echo $comment['Comment']['comment']; ?>
					&nbsp;
				</dd>
			<?php endforeach; ?>
			<dl>

			<div id="comment_form">
				<?php echo $form->create('Comment', array('action'=>'add')); ?>
				<?php echo $form->hidden('Comment.game_id', array('value'=>$game['Game']['id'])); ?>
				<?php echo $form->input('Comment.comment', array('label'=>false,'div'=>false)); ?>
				<?php echo $form->end(array('label'=>'Add Comment')); ?>
			</div>
		</div>
	</tr></table>
</div>
