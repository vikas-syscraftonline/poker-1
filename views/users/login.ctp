<table id="login"><tr><td style="width: 50%" valign="top">

<h2>Login</h2>
<?php	echo $form->create('User', array('action' => 'login')); ?>
	<input type="hidden" name="pageReferrer" value="<?php echo $pageReferrer; ?>" />
<table id="loginTable">
<tr><td class='label'>Username:</td><td class='input'><?php echo $form->input('username',array('label'=>false, 'div'=>false)); ?></td></tr>
<tr><td class='label'>Password:</td><td class='input'><?php echo $form->input('password',array('label'=>false, 'div'=>false)); ?></td></tr>
</table>
<?php echo $form->end('Login'); ?>

</td><td style="width: 50%" valign="top">

<?php e($this->Element('user_register')); ?>

</td></tr></table>
