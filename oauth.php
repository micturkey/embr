<?php
	/* Start session and load lib */
	if(!isset($_SESSION)){
		session_start();
	}
	require_once('lib/twitese.php');

	if (isset($_REQUEST['oauth_token'])) {
		if($_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
			$_SESSION['oauth_status'] = 'oldtoken';
			session_destroy();
			header('Location: ./login.php?oauth=old');
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
				setEncryptCookie("oauth_token", $access_token['oauth_token'], $time, "/");
				setEncryptCookie("oauth_token_secret", $access_token['oauth_token_secret'], $time, "/");
				setEncryptCookie("user_id", $access_token['user_id'], $time, "/");
				setEncryptCookie('twitese_name', $t->screen_name, $time, '/');
				refreshProfile();
				if(!isset($_COOKIE['showpic_cookie'])){
					setcookie('showpic_cookie', 'true', $time, '/');
				}
				if(!isset($_COOKIE['mediaPre'])){
					setcookie('mediaPre', 'true', $time, '/');
				}
				if(!isset($_COOKIE['login_page'])) {
					header('Location: ./index.php');
				} else {
					header('Location: ./'.getcookie('login_page'));
				}
			} else {
				session_destroy();
				header('Location: ./login.php?oauth=error');
			}
		}
	}else{
		/* Create TwitterOAuth object and get request token */
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

		/* Get request token */
		$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

		/* Save request token to session */
		$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

		/* If last connection fails don't display authorization link */
		switch ($connection->http_code) {
			case 200:
				/* Build authorize URL */

				
				$time = $_SERVER['REQUEST_TIME']+3600*24*365;
				if ( isset($_POST['proxify']) ) {
					$url = TWIOLP_API.'/oauth_proxy/'.$connection->getAuthorizeURL($token);
					if(!isset($_COOKIE['proxify'])) setcookie('proxify', 'true', $time, '/');
				}
				else {
					$url = $connection->getAuthorizeURL($token);
					if(!isset($_COOKIE['proxify'])) setcookie('proxify', 'false', $time, '/');
				}
				header('Location: ' . $url); 
				break;
			default:
				echo 'Could not connect to Twitter. Refresh the page or try again later.';
				break;
		}
	}
?>
