<h1>There was a change to an existing poker game</h1>

<p>The changes are highlighed below:</p>

<ul>
<li<?php if(in_array('username', $alerts)) e(' style="font-weight:bold"'); ?>>Host: <?php e($game['User']['username']); ?></li>
<li<?php if(in_array('date', $alerts)) e(' style="font-weight:bold"'); ?>>Date: <?php e(date('l, F d, Y', strtotime($game['Game']['date']))); ?></li>
<li<?php if(in_array('time', $alerts)) e(' style="font-weight:bold"'); ?>>Time: <?php e(date('h:i A', strtotime($game['Game']['time']))); ?></li>
<li<?php if(in_array('address', $alerts)) e(' style="font-weight:bold"'); ?>>Address: <?php e($game['Game']['address']); ?></li>
</ul>

<p>Notes:<br /><?php e(nl2br($game['Game']['notes'])); ?></p>

<p>To update your RSVP for this game, <?php e($html->link('Click here', 'http://'.$_SERVER['SERVER_NAME'].$html->url(array('controller'=>'games', 'action'=>'view', $game['Game']['id'])))); ?></p>

<p>To change your email settings, <?php e($html->link('Click here', 'http://'.$_SERVER['SERVER_NAME'].$html->url(array('controller'=>'users', 'action'=>'edit', $user['User']['id'])))); ?></p>
