<?php 
	if(!isset($_SESSION)){
		session_start();
	}
	$time = $_SERVER['REQUEST_TIME']-300;
	setcookie('oauth_token',"",$time);
	setcookie('oauth_token_secret',"",$time);
	setcookie('user_id',"",$time);
	setcookie('twitese_name',"",$time);
	setcookie('friends_count',"",$time);
	setcookie('statuses_count',"",$time);
	setcookie('followers_count',"",$time);
	setcookie('imgurl',"",$time);
	setcookie('name',"",$time);
	setcookie('listed_count',"",$time);
	setcookie('recover',"",$time);
	setcookie('homeInterval',"",$time);
	setcookie('updatesInterval',"",$time);
	setcookie('proxify',"",$time);	
	session_destroy();
	header('location: login.php');
?>
