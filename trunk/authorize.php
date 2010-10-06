<?php
//create by  @miajiao & @dcshi mod by @JLHwung

	include ('lib/twitese.php');
	$url = 'https://twitter.com/oauth/authorize';

	$authenticity_token = $_POST['authenticity_token'];
	$oauth_token = $_POST['oauth_token'];
	$username = $_GET['username'];
	$password = urldecode(decrypt($_GET['password']));
	$data = array(
		'session[username_or_email]' => $username,
		'session[password]' => $password,
		'authenticity_token' => $authenticity_token,
		'oauth_token' => $oauth_token,
	);
	echo processCurl($url, http_build_query($data) );
?>