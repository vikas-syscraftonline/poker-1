<h1>TESTS!</h1>
<?php
	$text = serialize(array('email'=>'joe@selectitaly.com', 'password'=>Security::hash('password'), 'admin'=>1));
	$key = Configure::read('Security.salt');
	$cipher = Security::cipher($text, $key);
	$decipher = Security::cipher($cipher, $key);
?>

	<p><?php e("Encrypt: ({$text}/{$key}) = {$cipher}"); ?></p>
	<p><?php e("Decrypt: {$decipher}"); ?></p>
	<?php pr(unserialize($decipher)); ?>
	<p><?php e(Configure::read('Security.salt')); ?></p>
