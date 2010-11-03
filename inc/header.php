<?php
	ob_start();
	if(!isset($_SESSION)){
		session_start();
	}
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="embr, open source, php, twitter, oauth" />
<meta name="description" content="Vivid Interface for Twitter" />
<meta name="author" content="disinfeqt, JLHwung" />
<link rel="icon" href="img/favicon.ico" />
<link id="css" href="css/main.css" rel="stylesheet" />
<title>Embr / <?php echo $title ?></title>
<?php 
	$myCSS = getColor("myCSS","");
	$old_css = "ul.sidebar-menu li.active a";
	$new_css = "ul.sidebar-menu a.active";
	$myCSS = str_replace($old_css,$new_css,$myCSS);
	$fontsize = getColor("fontsize","13px");
	$ad_display = getColor("ad_display","block");
	$bodyBg = getColor("bodyBg","");
?>
<style type="text/css">
<?php echo $myCSS ?>
a:active, a:focus {outline:none}
body {font-size:<?php echo $fontsize ?> !important;background-color:<?php echo $bodyBg ?>}
</style>
<script src="js/jquery.js"></script>
<script src="js/mediaPreview.js"></script>
<script src="js/public.js"></script>
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