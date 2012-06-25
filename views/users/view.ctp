<div class="users view">
<h2><?php  __('User');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Username'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['username']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Phone'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php if(!empty($user['User']['phone'])) echo substr($user['User']['phone'],0,3).'-'.substr($user['User']['phone'],3,3).'-'.substr($user['User']['phone'],6); else echo "N/A"; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Email'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $html->link($user['User']['email'], "mailto:{$user['User']['email']}?subject=Poker message from ".$user['User']['username']); ?>
			&nbsp;
		</dd>
	</dl>
</div>

<?php //pr($user); ?>

<div class="actions">
	<ul>
<?php if($session->read('Auth.User.admin')==1): ?>
		<li><?php echo $html->link(__('Edit User', true), array('action' => 'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete User', true), array('action' => 'delete', $user['User']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $user['User']['id'])); ?> </li>
		<li><?php echo $html->link(__('List Users', true), array('action' => 'index')); ?> </li>
<?php elseif($user['User']['id'] == $session->read('Auth.User.id')): ?>
		<li><?php echo $html->link(__('Edit Your Account', true), array('action' => 'edit', $user['User']['id'])); ?> </li>
<?php endif; ?>
		<li><?php echo $html->link(__('Back to Games List', true), array('controller' => 'games', 'action' => 'index')); ?> </li>
	</ul>
</div>
