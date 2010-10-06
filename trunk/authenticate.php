<?php
//create by  @miajiao & @dcshi,mod by @JLHwung
	include ('lib/twitese.php');

	$url = 'https://twitter.com/oauth/authenticate';
	$authenticity_token = $_POST['authenticity_token'];
	$oauth_token = $_POST['oauth_token'];
	$username = $_POST['session']['username_or_email'];
	$password = $_POST['session']['password'];

	$data = array(
		'session[username_or_email]' => $username,
		'session[password]' => $password,
		'authenticity_token' => $authenticity_token,
		'oauth_token' => $oauth_token,
	);

	$oldInput = processCurl($url, http_build_query($data) );
	$search_contents ='https://twitter.com/oauth/authorize';
	if ($password) {
		$password = urlencode(encrypt($password));
	}
	$replace_contents = 'authorize.php?username='.$username.'&password='.$password;
	echo str_replace($search_contents,$replace_contents,$oldInput);
?>