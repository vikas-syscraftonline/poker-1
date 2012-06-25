<h2>Register</h2>
<?php	echo $form->create('User', array('url'=>'/users/register')); ?>
<input type="hidden" name="pageReferrer" value="<?php echo $pageReferrer; ?>" />
<table id="loginTable">
<tr><td class='label'>Username:</td><td class='input'><?php echo $form->input('username',array('label'=>false, 'div'=>false)); ?></td></tr>
<tr><td class='label'>Password:</td><td class='input'><?php echo $form->input('passwrd',array('type'=>'password','label'=>false, 'div'=>false)); ?></td></tr>
<tr><td class='label'>Verfiy Password:</td><td class='input'><?php echo $form->input('passwrd2',array('type'=>'password','label'=>false, 'div'=>false)); ?></td></tr>
<tr><td class='label'>Email:</td><td class='input'><?php echo $form->input('User.email',array('label'=>false, 'div'=>false)); ?></td></tr>
</table>
<?php echo $form->end('Sign Up'); ?>
