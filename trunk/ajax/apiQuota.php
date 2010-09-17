<?php 
	include ('../lib/twitese.php');
	if(!isset($_SESSION)){
		session_start();
	}
	$t = getTwitter();
	$limit = $t->ratelimit();
	$reset = intval((strtotime($limit->reset_time) - time())/60);
	$remaining = $limit->remaining_hits < 0 ? 0 : $limit->remaining_hits;
	header('Content-Type: text/plain');
	echo "{ limit: $remaining, reset: $reset }";
?>
