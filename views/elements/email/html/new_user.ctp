<h1>New User Registration</h1>

<p>A new user has registered for poker.</p>

<ul>
<li>User: <?php e($user['User']['username']); ?></li>
<li>Email: <?php e($user['User']['email']); ?></li>
</ul>

<p>To activate this account, <?php e($html->link('click here', 'http://'.$_SERVER['SERVER_NAME'].$html->url(array('controller'=>'users', 'action'=>'activate', $user['User']['id'])))); ?></p>
