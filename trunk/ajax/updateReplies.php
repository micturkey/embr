<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	include_once('../lib/timeline_format.php');
	$t = getTwitter();
	if ( isset($_GET['since_id']) ) {

		$statuses = $t->replies(false, $_GET['since_id']);

		$empty = count($statuses) == 0? true: false;

		if ($empty) {
			echo "empty";
		} else {
			$output = "";
			foreach($statuses as $status) {
				$output .= format_timeline($status, $t->username);
			}
			$output .= '<div class="new"></div>';
			echo $output;
		}

	} else {
		echo 'error';
	}

?>
