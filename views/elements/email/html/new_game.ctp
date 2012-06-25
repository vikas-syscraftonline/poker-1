<h1>New Poker Game</h1>

<ul>
<li>Host: <?php e($game['User']['username']); ?></li>
<li>Date: <?php e(date('l, M d', strtotime($game['Game']['date']))); ?></li>
<li>Time: <?php e(date('h:i A', strtotime($game['Game']['time']))); ?></li>
<li>Address: <?php e($game['Game']['address']); ?></li>
</ul>

<p><?php e(nl2br($game['Game']['notes'])); ?></p>

<p>To view or RSVP for this game, <?php e($html->link('click here', 'http://'.$_SERVER['SERVER_NAME'].$html->url(array('controller'=>'games', 'action'=>'view', $game['Game']['id'])))); ?></p>

<p>To change your email settings, <?php e($html->link('click here', 'http://'.$_SERVER['SERVER_NAME'].$html->url(array('controller'=>'users', 'action'=>'edit', $user['User']['id'])))); ?></p>
