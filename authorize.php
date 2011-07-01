<?php

	include ('lib/twitese.php');
	$url = 'https://twitter.com/oauth/authorize';
	echo processCurl($url, http_build_query($_POST));
?>