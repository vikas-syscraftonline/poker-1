New Poker Game
--------------

Host: <?php e($game['User']['username']); ?>

Date: <?php e(date('l, M d', strtotime($game['Game']['date']))); ?>

Time: <?php e(date('h:i A', strtotime($game['Game']['time']))); ?>

Address: <?php e($game['Game']['address']); ?>


<?php e($game['Game']['notes']); ?>


To view or RSVP for this game, visit <?php e('http://'.$_SERVER['SERVER_NAME'].$html->url(array('controller'=>'games', 'action'=>'view', $game['Game']['id']))); ?>

To change your email settings, visit <?php e('http://'.$_SERVER['SERVER_NAME'].$html->url(array('controller'=>'users', 'action'=>'edit', $user['User']['id']))); ?>
