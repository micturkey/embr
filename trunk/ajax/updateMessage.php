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
				$date = formatDate($message->created_at);
				$text = formatText($message->text);
				$output = "<li>";
				$output .= "<span class=\"status_author\">".initShortcutMenu($message->sender)."
					<a href=\"user.php?id=$name\" target=\"_blank\"><img src=\"$imgurl\" title=\"$name\" /></a>
					</span>
					<span class=\"status_body\">
					<span class=\"status_id\">$message->id </span>
					<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$name\">$name</a> $text </span>";
				if ($shorturl = unshortUrl($text)) $output .= "<span class=\"unshorturl\"><p>URL</p><a href=\"$shorturl\" target=\"_blank\" rel=\"noreferrer\">$shorturl</a></span>";
				$output .= "<span class=\"actions\"><a class=\"msg_replie_btn\" href=\"message.php?id=$name\">Reply</a><a class=\"msg_delete_btn\" href=\"a_del.php?id=$message->id&t=m\">Delete</a></span>
					<span class=\"status_info\">
					<span class=\"date\">$date</span>
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
