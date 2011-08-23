<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	include_once('timeline_format.php');
	$t = getTwitter();
	if ( isset($_GET['since_id']) ) {

		$messages = $t->directMessages(false, $_GET['since_id']);

		$empty = count($messages) == 0? true: false;

		if ($empty) {
			echo "empty";
		} else {
			foreach ($messages as $message) {
				$name = $message->sender_screen_name;
				$imgurl = getAvatar($message->sender->profile_image_url);
				$date = strtotime($message->created_at);
				$url_recover = '';
				$text = formatEntities(&$message->entities,$message->text,&$url_recover);
				$output = "<li>";
				$output .= "<span class=\"status_author\">
					<a href=\"user.php?id=$name\" target=\"_blank\"><img src=\"$imgurl\" title=\"Click for more...\" /></a>
					</span>
					<span class=\"status_body\">
					<span class=\"status_id\">$message->id </span>
					<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$name\">$name</a> $text </span>";
				$output .= $url_recover;
				$output .= "<span class=\"actions\"><a class=\"msg_replie_btn\" href=\"message.php?id=$name\">Reply</a><a class=\"msg_delete_btn\" href=\"a_del.php?id=$message->id&t=m\">Delete</a></span>
					<span class=\"status_info\">
					<span class=\"date\" id=\"$date\">".date('Y-m-d H:i:s', $date)."</span>
					</span>
					</span>
					</li>";
			}
			$output .= "<div class=\"new\"></div>";
			echo $output;
		}
	} else {
		echo 'error';
	}

?>
