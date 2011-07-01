<?php

	include ('lib/twitese.php');
	$url = 'https://mobile.twitter.com/signup';
	if(isset($_POST['oauth_signup_client[fullname]']) 
		&& isset($_POST['oauth_signup_client[screen_name]'])
		&& isset($_POST['oauth_signup_client[email]'])
		&& isset($_POST['oauth_signup_client[password]'])
		&& isset($_POST['captcha_method'])
		&& isset($_POST['captcha_challenge_field'])
		&& isset($_POST['captcha_response_field'])
	) {
		echo processCurl($url, http_build_query($_POST));
	} else {
		$raw = processCurl($url);
		echo str_replace('<img src=\'','<img src=\'img.php?imgurl=https://mobile.twitter.com',$raw);
	}