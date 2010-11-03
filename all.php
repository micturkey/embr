<?php 
	include_once('lib/twitese.php');
	$title = 'Updates';
	include_once('inc/header.php');
	include_once('ajax/timeline_format.php');
	if (!loginStatus()) header('location: login.php');

?>
<script src="js/all.js"></script>
<style>.timeline li {border-bottom:1px solid #EFEFEF;border-top:none !important}</style>

<div id="statuses" class="column round-left">
	<?php include('inc/sentForm.php') ?>
			<div id="allNav">
			<a class="allBtn allHighLight" id="allTimelineBtn" href="javascript:void(0);">Updates</a>
			<a class="allBtn" id="allRepliesBtn" href="#">Replies</a>
			<a class="allBtn" id="allMessageBtn" href="#">Messages</a>
		</div>
<?php
	$statuses = $t->friendsTimeline();
	$retweetes = $t->retweeted_to_me();
	if ($statuses === false) {
		header('location: error.php');exit();
	}
	$empty = count($statuses) == 0? true: false;
	if ($empty) {
		echo "<div id=\"empty\">No tweet to display</div>";
	} else {
		$output = '<ol class="timeline" id="allTimeline">';
		if(count($retweetes) > 0){
			$statuses = sort_timeline($statuses, $retweetes);
		}
		$MAX_STATUSES = 20;
		$status_count = 0;
		foreach ($statuses as $status) {
			if($status_count++ >= $MAX_STATUSES){
				break;
			}
			if(isset($status->retweeted_status)){
				$output .= format_retweet($status);
			}else{
				$output .= format_timeline($status, $t->username);
			}
		}

		$output .= "</ol>";

		echo $output;
	}

	$statuses = $t->replies();
	if ($statuses === false) {
		header('location: error.php');exit();
	}
	$empty = count($statuses) == 0? true: false;
	if ($empty) {
		echo "<div id=\"empty\">No tweet to display</div>";
	} else {
		$output = '<ol class="timeline" id="allReplies">';

		foreach ($statuses as $status) {
			$output .= format_timeline($status, $t->username);
		}

		$output .= "</ol>";

		echo $output;
	}


	$messages = $t->directMessages();
	if ($messages === false) {
		header('location: error.php');exit();
	}
	$empty = count($messages) == 0? true: false;
	if ($empty) {
		echo "";
	} else {
		$output = '<ol class="timeline" id="allMessage">';

		foreach ($messages as $message) {
			$name = $message->sender_screen_name;
			$imgurl = getAvatar($message->sender->profile_image_url);
			$date = formatDate($message->created_at);
			$text = formatText($message->text);

			$output .= "<li>
				<span class=\"status_author\">".initShortcutMenu($message->sender)."
				<a href=\"user.php?id=$name\" target=\"_blank\"><img src=\"$imgurl\" title=\"$name\" /></a>
				</span>
				<span class=\"status_body\">
				<span class=\"status_id\">$message->id</span>
				<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$name\">$name</a><span class=\"tweet\"> $text </span></span>";
			$output .= recoverShortens($text);
			$output .= "<span class=\"actions\"><a class=\"msg_replie_btn\" href=\"message.php?id=$name\">Reply</a><a class=\"msg_delete_btn\" href=\"a_del.php?id=$message->id&t=m\">Delete</a></span>
				<span class=\"status_info\">
				<span class=\"date\">$date</span>
				</span>
				</span>
				</li>";
		}

		$output .= "</ol>";
		echo $output;
	}
?>
</div>

<?php 
	include ('inc/sidebar.php');
?>

<?php 
	include ('inc/footer.php');
?>
