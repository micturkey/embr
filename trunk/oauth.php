<?php
	/* Start session and load lib */
	if(!isset($_SESSION)){
		session_start();
	}
	include_once('lib/twitese.php');

	if (isset($_REQUEST['oauth_token'])) {
		if($_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
			$_SESSION['oauth_status'] = 'oldtoken';
			session_destroy();
			header('Location: login.php?oauth=old');
		}else{
			/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

			/* Request access tokens from twitter */
			$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

			/* Save the access tokens. Normally these would be saved in a database for future use. */
			$_SESSION['access_token'] = $access_token;

			/* Remove no longer needed request tokens */
			unset($_SESSION['oauth_token']);
			unset($_SESSION['oauth_token_secret']);

			/* If HTTP response is 200 continue otherwise send to connect page to retry */
			if (200 == $connection->http_code) {
				/* The user has been verified and the access tokens can be saved for future use */
				$_SESSION['login_status'] = 'verified';
				$t = getTwitter();
				$user = $t->veverify();
				/* And set new cookies */
				$time = $_SERVER['REQUEST_TIME']+3600*24*365;
				setEncryptCookie('oauth_token', $access_token['oauth_token'], $time, '/');
				setEncryptCookie('oauth_token_secret', $access_token['oauth_token_secret'], $time, '/');
				setEncryptCookie('user_id', $access_token['user_id'], $time, '/');
				setEncryptCookie('twitese_name', $t->screen_name, $time, '/');
				refreshProfile();
				if(!isset($_COOKIE['showpic'])){
					setcookie('showpic', 'true', $time, '/');
				}
				if(!isset($_COOKIE['mediaPre'])){
					setcookie('mediaPre', 'true', $time, '/');
				}
				if(!isset($_COOKIE['loginPage'])) {
					header('Location: index.php');
				} else {
					$scheme = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") ? 'http' : 'https';
					$port = $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '';
					$login_page = $scheme . '://' . $_SERVER['HTTP_HOST'] . $port . $_COOKIE['loginPage'];
					header('Location: '. $login_page);
				}
			} else {
				session_destroy();
				header('Location: login.php?oauth=error');
			}
		}
	}else{
		/* Create TwitterOAuth object and get request token */
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
		
		/* Get callback URL */
		$scheme = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") ? 'http' : 'https';
		$port = $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '';
		$oauth_callback = $scheme . '://' . $_SERVER['HTTP_HOST'] . $port . $_SERVER['REQUEST_URI'];
	
		/* Get request token */
		$request_token = $connection->getRequestToken($oauth_callback);

		/* Save request token to session */
		$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

		/* If last connection fails don't display authorization link */
		switch ($connection->http_code) {
			case 200:
				
				$time = $_SERVER['REQUEST_TIME']+3600*24*365;
				$url = $connection->getAuthorizeURL($token);
				if ( isset($_POST['proxify']) ) { 
					$old = processCurl($url);
					$search_contents ='https://twitter.com/oauth/authenticate';
					$replace_contents = 'authenticate.php';
		
					//Retrieve oauth_token and authenticity_token
					preg_match_all('/value="(.*?)"/i', $old, $m);

					$username = $_POST['username'];
					$password = $_POST['password'];
					$data = array(
						'session[username_or_email]' => $username,
						'session[password]' => $password,
						'authenticity_token' => $m[1][0],
						'oauth_token' => $m[1][1],
					);
					$url = 'https://twitter.com/oauth/authenticate';

					$oldInput = processCurl($url, http_build_query($data) );
					if ( !$oldInput) {
						header('location: error.php');exit();
					}

					$search_contents ='https://twitter.com/oauth/authorize';
					if ($password) {
						$password = urlencode(encrypt($password));
					}
					$replace_contents = 'authorize.php?username='.$username.'&password='.$password;
					$new = str_replace($search_contents,$replace_contents,$oldInput);
					$replace_contents = 'authenticate.php';
					$new = str_replace($url,$replace_contents,$new);
					$new = str_replace('html { display:none; }','.error{display:none;}',$new);
					$new = preg_replace('/https?:\/\/\w+([0-9])\.twimg\.com/i','https://s3.amazonaws.com/twitter_production',$new);
  				echo $new;
				} //OAuth Proxy End
				else {
					header('Location: ' . $url); 
				}
				break;
			default:
				header('Location: error.php?t=1');exit();
				break;
		}
	}
?>
