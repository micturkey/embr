<?php
	ob_start();
	if(!isset($_SESSION)){
		session_start();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="rabr, open source, php, twitter, oauth, disinfeqt" />
<meta name="description" content="Revolutionary Interface for Twitter" />
<link rel="shortcut icon" href="img/favicon.ico" />
<link type="text/css" id="css" href="css/main.css?ver=2010041607" rel="stylesheet" />
<title>Rabr / <?php echo $title ?></title>
<?php 
	$myCSS = isset($_COOKIE["myCSS"]) ? $_COOKIE["myCSS"] : "";
	$old_css = "ul.sidebar-menu li.active a";
	$new_css = "ul.sidebar-menu a.active";
	$myCSS = str_replace($old_css,$new_css,$myCSS);
	$fontsize = isset($_COOKIE["fontsize"]) ? $_COOKIE["fontsize"] : "13px";
	$ad_display = "block";
	$bodyBg = isset($_COOKIE["bodyBg"]) ? $_COOKIE["bodyBg"] : "";
?>
<style type="text/css">
<?php echo $myCSS ?>
a:active, a:focus {outline:none}
body {font-size:<?php echo $fontsize ?> !important;background-color:<?php echo $bodyBg ?>}
#plz_dont_block_us {display: <?php echo $ad_display ?>}
</style>
<script type="text/javascript" src="js/jquery.js?ver=1.4.2"></script>
<script type="text/javascript" src="js/jquery.plugins.js?ver=2010033101"></script>
<script type="text/javascript" src="js/mediaPreview.js?ver=2010041501"></script>
<script type="text/javascript" src="js/public.js?ver=2010041604"></script>
</head>
<body>
<div id="shortcutTip" style="display:none"></div>
	<div id="header">
		<div class="wrapper">
			<a href="index.php"><img id="logo" style="float:left" src="img/logo.png" /></a>
			<ul id="nav" class="round">
				<li><a href="index.php">Home</a></li>
				<li><a href="profile.php">Profile</a></li>
				<li><a href="browse.php">Public</a></li>
				<li><a href="setting.php">Settings</a></li>
				<li><a href="logout.php">Logout</a></li>			
			</ul>
		</div>
	</div>
	<div id="content">
		<div class="wrapper">
			<div class="content-bubble-arrow"></div>
				<table cellspacing="0" class="columns">
					<tbody>
						<tr>
							<td id="left" class="column round-left">