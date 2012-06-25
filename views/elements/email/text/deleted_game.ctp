Poker Game Canceled
-------------------

The following game was canceled.

Host: <?php e($game['User']['username']); ?>

Date: <?php e(date('l, M d', strtotime($game['Game']['date']))); ?>

Time: <?php e(date('h:i A', strtotime($game['Game']['time']))); ?>

Address: <?php e($game['Game']['address']); ?>


Update your plans accordingly
