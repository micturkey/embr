<?php
	define('API_URL', 'http://api.twitter.com/1.1');
	
	// Config
	define('SECURE_KEY', 'YOU SHOULD CHANGE IT'); //You should NOT leave it blank
	define('BASE_URL','http://embr.in'); //Where your embr is hosted, i.e. http://bugbug.tk
	define("CONSUMER_KEY", "TEItTaPASySnYxziOyIdag");
	define("CONSUMER_SECRET", "xJEoWvBumpqgiiBuviWTa7GT8KCvP7Kv3n0hixhJaZY");
	
	//Extra Auth
	define('BASIC_AUTH', false); // if you set basic_auth true, u MUST set up the following BASIC_AUTH_USER and BASIC_AUTH_PW, and you d better reset your secure_key
	define('BASIC_AUTH_USER', 'your_basic_auth_user');
	define('BASIC_AUTH_PW', 'your_basic_auth_password');

	define('ID_AUTH',false); // if you set id_auth true, u MUST set up the following AUTH_ID list
	$AUTH_ID = array('username1','username2','username3','......');

	//Optional Information
	define('SITE_OWNER', 'TWITTER'); //Your Twitter ID  
	define('BLOG_SITE',''); //blog_site
?>
