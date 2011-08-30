<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	include_once('../lib/timeline_format.php');
	$t = getTwitter();
	if ( isset($_GET['since_id']) ) {

		$messages = $t->directMessages(false, $_GET['since_id']);

		$empty = count($messages) == 0? true: false;

		if ($empty) {
			echo "empty";
		} else {
			$output = '';
			foreach ($messages as $message) {
				$output .= format_message($message);
			}
			$output .= "<div class=\"new\"></div>";
			echo $output;
		}
	} else {
		echo 'error';
	}

?>
