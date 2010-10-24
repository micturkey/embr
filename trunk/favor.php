<?php 
	include ('lib/twitese.php');
	$title = "My Favorites";
	include ('inc/header.php');
	if (!loginStatus()) header('location: login.php');
?>

<script type="text/javascript" src="js/favor.js"></script>

<div id="statuses" class="column round-left">

	<?php include('inc/sentForm.php')?>

<?php 
	$t = getTwitter();
	$p = 1;
	if (isset($_GET['p'])) {
		$p = (int) $_GET['p'];
		if ($p <= 0) $p = 1;
	}

	$statuses = $t->getFavorites($p);
	if ($statuses === false) {
		header('location: error.php');exit();
	} 
	$empty = count($statuses) == 0? true: false;
	if ($empty) {
		echo "<div id=\"empty\">No tweet to display.</div>";
	} else {
		$output = '<ol class="timeline" id="allTimeline">';

		foreach ($statuses as $status) {
			
			$user = $status->user;
			$date = formatDate($status->created_at);
			$text = formatText($status->text);

			$output .= "
				<li>
				<span class=\"status_author\">
				<a href=\"user.php?id=$user->screen_name\" target=\"_blank\"><img src=\"".getAvatar($user->profile_image_url)."\" title=\"$user->screen_name\" /></a>
				</span>
				<span class=\"status_body\">
				<span class=\"status_id\">$status->id</span>
				<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$user->screen_name\">$user->screen_name</a><span class=\"tweet\"> $text </span></span>";
			$output .= recoverShortens($text);
			$output .="<span class=\"actions\">
				<a class=\"replie_btn\" href=\"#\">Reply</a>
				<a class=\"rt_btn\" href=\"#\">Retweet</a>
				<a class=\"retw_btn\" title=\"New Retweet\" href=\"#\">New Retweet</a>
				<a class=\"trans_btn\" title=\"Translate\" href=\"#\">Translate</a>
				<a class=\"delete_btn\" href=\"#\">Delete</a></span><span class=\"status_info\">
				";
			if ($status->in_reply_to_status_id) $output .= "<span class=\"in_reply_to\"> <a href=\"/status.php?id=$status->in_reply_to_status_id \">in reply to $status->in_reply_to_screen_name</a></span>";
			$output .= "				
				<span class=\"source\">via $status->source</span>
				<span class=\"date\"><a href=\"/status.php?id=$status->id\" target=\"_blank\">$date</a></span>
				</span>
				</span>
				</li>
				";
				
		}

		$output .= "</ol><div id=\"pagination\">";

		if ($p >1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"favor.php?p=" . ($p-1) . "\">Back</a>";
		if (!$empty) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"favor.php?p=" . ($p+1) . "\">Next</a>";

		$output .= "</div>";

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
