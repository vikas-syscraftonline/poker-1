New User Registration

A new user has registered for poker.

User: <?php e($user['User']['username']); ?>

Email: <?php e($user['User']['email']); ?>


To activate this account, visit <?php echo 'http://'.$_SERVER['SERVER_NAME'].$html->url(array('controller'=>'users', 'action'=>'activate', $user['User']['id'])); ?>
