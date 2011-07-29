<?php 
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	$t = getTwitter();
	$user = $t->veverify();
	if (!isset($user->error) && isset($user->name)) {
		$time = $_SERVER['REQUEST_TIME']+3600*24*365;
		setcookie('friends_count', $user->friends_count, $time, '/');
		setcookie('statuses_count', $user->statuses_count, $time, '/');
		setcookie('followers_count', $user->followers_count, $time, '/');
		setcookie('imgurl', getAvatar($user->profile_image_url), $time, '/');
		setcookie('name', $user->screen_name, $time, '/');
		setcookie('listed_count', GetListed($t), $time, '/');
		echo '{"result": "success"}';
		/*
		$callback = array(
			'friends' => $user->friends_count,
			'followers' => $user->followers_count,
			'statuses' => $user->statuses_count,
			'listed' => GetListed($t),
			'imgurl' => getAvatar($user->profile_image_url),
			'name' => $user->screen_name,
		);
		echo json_encode($callback);
		*/	
	} else {
		echo '{"result": "error"}';
	}
	
?>