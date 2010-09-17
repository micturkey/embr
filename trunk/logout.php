<?php 
	include ('lib/twitese.php');
	
	if(!isset($_SESSION)){
		session_start();
	}
	
	$time = mktime(12,0,0,1, 1, 1990);
	SetCookie('oauth_token',"",$time);
	SetCookie('oauth_token_secret',"",$time);
	SetCookie('user_id',"",$time);
	SetCookie('twitese_name',"",$time);
	SetCookie('twitese_pw',"",$time);
	SetCookie('friends_count',"",$time);
	SetCookie('statuses_count',"",$time);
	SetCookie('followers_count',"",$time);
	SetCookie('imgurl',"",$time);
	SetCookie('name',"",$time);
	SetCookie('listed_count',"",$time);
	SetCookie('recover',"",$time);
	SetCookie('homeInterval',"",$time);
	SetCookie('updatesInterval',"",$time);
	SetCookie('proxify',"",$time);
	
	session_destroy();
	header('location: login.php');
?>
