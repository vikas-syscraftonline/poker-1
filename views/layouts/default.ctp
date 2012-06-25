<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php echo $html->charset(); ?>
	<title>
		Poker - <?php echo $title_for_layout; ?>
	</title>
	<meta name="description" content="Site Description" />
	<meta name="keywords" content="keywords" />

	<?php
		echo $html->meta('icon');

		echo $html->css('global', 'stylesheet', array('media'=>'all'));

		//auto-load relevant styles based on their filenames
		if (is_file(APP.WEBROOT_DIR.DS."css".DS.$this->params["controller"].".css")) {
			echo $html->css($this->params["controller"], 'stylesheet', array('media'=>'all'));
		}

		if (is_file(APP.WEBROOT_DIR.DS."css".DS.$this->params['controller'].DS.$this->params['action'].".css")) {
			echo $html->css($this->params["controller"].DS.$this->params["action"], 'stylesheet', array('media'=>'all'));
		}

		//include mootools
		echo $html->script('mootools-1.2.4');
		echo $html->script('global');

		//auto-load relevant scripts based on their filenames
		//first, check for and include any script file that matches the controller name
		if (is_file(APP.WEBROOT_DIR.DS."js".DS.$this->params["controller"].".js")) {
			echo $html->script($this->params["controller"], 'stylesheet', array('media'=>'all'));
		}
		//next, check for and include any script files in a folder matching the controller name that have filenames matching the action
		if (is_file(APP.WEBROOT_DIR.DS."js".DS.$this->params['controller'].DS.$this->params['action'].".js")) {
			echo $html->script($this->params["controller"].DS.$this->params["action"]);
		}

		//load any other scripts included in the executed page
		echo $scripts_for_layout;

	?>
	<!--[if lte IE 6]>
	<script type='text/javascript'> window.addEvent('domready', function(){ $('oldiesucks').setStyle('display','block'); }); </script>
	<![endif]-->

</head>
<body>

<div id="oldiesucks">
	Upgrade <a href="http://www.microsoft.com/windows/internet-explorer/default.aspx">Internet Explorer</a> or make the switch to <a href="http://getfirefox.com">Firefox</a>, <a href="http://www.google.com/chrome">Chrome</a> or <a href="http://www.apple.com/safari/">Safari</a> if you want this site to look right.
</div>

<div id="header">
<?php if($session->read('Auth.User')): ?>
<div id="navmenu">
<ul>
	<li><?php echo $html->link('Games', array('controller' => 'games', 'action' => 'index')); ?></li>
	<li><?php echo $html->link(__('Add Game',true), array('controller'=>'games', 'action'=>'add')); ?></li>
	<?php if($session->read('Auth.User.admin')==1): ?>
		<li><?php echo $html->link(__('Users', true), array('controller'=>'users', 'action' => 'index')); ?></li>
	<?php endif; ?>
</ul>
</div>
<div id="usermenu">
<ul>
	<li><?php e($html->link(__('My Account',true), array('controller'=>'users', 'action'=>'account'))); ?></li>
	<li><?php e($html->link(__('Log Out',true), array('controller'=>'users', 'action'=>'logout'))); ?></li>
</ul>
</div>
<?php endif; ?>
</div>


<div id="contentBody"><div id="content">
<?php  
	echo $session->flash('error');
	echo $session->flash();
	echo $session->flash('auth');
?>

<?php echo $content_for_layout; ?>

<?php if(count($news)>0): ?>
<div class="news index">
        <h2>News</h2>
        <?php foreach($news as $entry): ?>
                <div class='heading'>
                        <span class='title'><?php echo $entry['News']['title']; ?></span>
                        <span class='date'><?php echo date('F d, g:ia', strtotime($entry['News']['created'])); ?></span>
                </div>
                        <div class='text'><?php echo $entry['News']['news']; ?></div>
        <?php endforeach; ?>
</div>
<?php endif; ?>
</div> </div>


<div id="footer">
<!--No rights reserved and no copyright since I stole all these images and icons anyway.-->
&nbsp;
</div>

<?php echo "<p>".$this->element('sql_dump')."</p>"; //sql log dump ?>

</body>
</html>
