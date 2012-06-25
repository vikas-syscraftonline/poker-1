<h1>Poker Game Canceled</h1>

<p>The following game was canceled.</p>

<ul>
<li>Host: <?php e($game['User']['username']); ?></li>
<li>Date: <?php e(date('l, M d', strtotime($game['Game']['date']))); ?></li>
<li>Time: <?php e(date('h:i A', strtotime($game['Game']['time']))); ?></li>
<li>Address: <?php e($game['Game']['address']); ?></li>
</ul>

<p>Update your plans accordingly</p>
