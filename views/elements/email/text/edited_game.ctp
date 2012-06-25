There was a change to a poker game you RSVP'd for. The changes are highlighed below:

Host: <?php if(in_array('username', $alerts)) e('*** '); e($game['User']['username']); ?>

Date: <?php if(in_array('date', $alerts)) e('*** '); e(date('l, F d, Y', strtotime($game['Game']['date']))); ?>

Time: <?php if(in_array('time', $alerts)) e('*** '); e(date('h:i A', strtotime($game['Game']['time']))); ?>

Address: <?php if(in_array('address', $alerts)) e('*** '); e($game['Game']['address']); ?>


Notes:
<?php e($game['Game']['notes']); ?>


To update your RSVP for this game, visit <?php e('http://'.$_SERVER['SERVER_NAME'].$html->url(array('controller'=>'games', 'action'=>'view', $game['Game']['id']))); ?>

To change your email settings, visit <?php e('http://'.$_SERVER['SERVER_NAME'].$html->url(array('controller'=>'users', 'action'=>'edit', $user['User']['id']))); ?>
