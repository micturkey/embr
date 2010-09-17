<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include_once("../lib/twitese.php");
	if(!isset($_GET['target'])){
		echo "error";
		exit;
	}
	$target = trim($_GET['target']);
	$t = getTwitter();
	$source = isset($_GET['source']) ? trim($_GET['source']) : $t->username;
	if($target == '' || $source == ''){
		echo "error";
		exit;
	}
	$analysis = getRelationship($target, $source);
	switch($analysis){
		case 1:
			$result = '<h3 style="color:#00805F;text-shadow:1px 1px #FFFFFF;">They\'re following each other. How sweet!</h3>';
			break;
		case 2:
			$result = '<h3 style="margin-top: 80px; text-shadow: 1px 1px rgb(255, 255, 255); color: rgb(0, 128, 95);">
						<a href="user.php?id='.$source.'">@'.$source.'</a> is following <a href="user.php?id='.$target.'">@'.$target.'</a>
						</h3>
					   <h3 style="margin-top: 5px; color: rgb(131, 22, 31); text-shadow: 1px 1px rgb(255, 255, 255);">
					   <a href="user.php?id='.$target.'">@'.$target.'</a> is NOT following <a href="user.php?id='.$source.'">@'.$source.'</a>
						</h3>';
			break;
		case 3:
			$result = '<h3 style="margin-top: 80px; text-shadow: 1px 1px rgb(255, 255, 255); color: rgb(0, 128, 95);">
						<a href="user.php?id='.$target.'">@'.$target.'</a> is following <a href="user.php?id='.$source.'">@'.$source.'</a>
						</h3>
					   <h3 style="margin-top: 5px; color: rgb(131, 22, 31); text-shadow: 1px 1px rgb(255, 255, 255);">
					   <a href="user.php?id='.$source.'">@'.$source.'</a> is NOT following <a href="user.php?id='.$target.'">@'.$target.'</a>
						</h3>';
			break;
		case 4:
			$result = '<h3 style="color: rgb(131, 22, 31); text-shadow: 1px 1px rgb(255, 255, 255);">
						<a href="user.php?id='.$source.'">@'.$source.'</a> is blocking <a href="user.php?id='.$target.'">@'.$target.'</a>
						</h3>';
			break;
		case 9:
			$result = '<h3 style="color:#666666;text-shadow: 1px 1px rgb(255, 255, 255);">It seems that they don\'t know each other!</h3>';
			break;
	}
	$html = $result;
	echo $html;
?>