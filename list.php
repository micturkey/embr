<?php 
	include ('lib/twitese.php');
	$title = "@{$_GET['id']}";
	include ('inc/header.php');
	//include('ajax/timeline_format.php');
	if (!loginStatus()) header('location: login.php');
?>

<script src="js/list.js"></script>

<div id="statuses">
	<?php 
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
		
		$id = isset($_GET['id'])? $_GET['id'] : false;
		$t = getTwitter();
		$statuses = $t->listStatus($id, $p);
		$listInfo = $t->listInfo($id);
		if ($statuses === false) {
			header('location: error.php');exit();
		} 
		
		$isFollower = false;
		//$isFollower = $t->isFollowedList($id);
		$empty = count($statuses) == 0? true: false;
		if ($empty) {
			echo "<div id=\"empty\">No Tweet To Display</div>";
		} else {
	?>
	
		
	<div id="info_head">
		<a href="https://twitter.com/<?php echo $userid ?>"><img id="info_headimg" src="<?php echo getAvatar($listInfo->user->profile_image_url); ?>" /></a>
		<div id="info_name"><?php echo $id?></div>
		<div id="info_relation">
		<?php if ($isFollower) {?>
			<a id="list_block_btn" class="info_btn_hover" href="#">Unfollow</a>
		<?php } else { ?>
			<a id="list_follow_btn" class="info_btn" href="#">Follow</a>
		<?php } ?>
			<a id="list_send_btn" class="info_btn" href="#">Tweet</a>
			<a class="info_btn" href="list_followers.php?id=<?php echo $id?>">Followers (<?php echo $listInfo->subscriber_count?>)</a>
			<a class="info_btn" href="list_members.php?id=<?php echo $id?>">Members (<?php echo $listInfo->member_count?>)</a>
		</div>
	</div>
	<div class="clear"></div>
	
	<?php 
		
			$output = '<ol class="timeline" id="allTimeline">';
			
			foreach ($statuses as $status) {
				//format_timeline($status,$t->username);
				
				$user = $status->user;
				$date = formatDate($status->created_at);
				$text = formatText($status->text);
				
				$output .= "
					<li>
						<span class=\"status_author\">
							<a href=\"user.php?id=$user->screen_name\" target=\"_blank\"><img src=\"".getAvatar($user->profile_image_url)."\" title=\"$user->screen_name\" /></a>
						</span>
						<span class=\"status_body\">
							<span class=\"status_id\">$status->id_str</span>
							<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$user->screen_name\">$user->screen_name</a> $text </span>";
				$output .= recoverShortens($text);
				$output .= "<span class=\"actions\">
							<a class=\"replie_btn\" title=\"Reply\" href=\"#\">Reply</a>
							<a class=\"rt_btn\" title=\"Retweet\" href=\"#\">Retweet</a>
							<a class=\"retw_btn\" title=\"New Retweet\" href=\"#\">New Retweet</a>
							<a class=\"favor_btn\" title=\"Favorite\" href=\"#\">Fav</a>";
				if ($user->screen_name == $t->username) $output .= "<a class=\"delete_btn\" title=\"Delete\" href=\"#\">Delete</a>";
				$output .= "</span><span class=\"status_info\">";
				if ($status->in_reply_to_status_id_str) $output .= "<span class=\"in_reply_to\"> <a class=\"ajax_reply\" href=\"ajax/status.php?id=$status->in_reply_to_status_id_str&uid=$user->id \">in reply to $status->in_reply_to_screen_name</a></span>";
				$output .= "<span class=\"source\">via $status->source</span>
							<span class=\"date\"><a href=\"status.php?id=$status->id_str\" target=\"_blank\">$date</a></span>
						    </span>
						</span>
					</li>
				";
				
			}
			
			$output .= "</ol><div id=\"pagination\">";
			
			if ($p >1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"list.php?id=$id&p=" . ($p-1) . "\">Back</a>";
			if (!$empty) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"list.php?id=$id&p=" . ($p+1) . "\">Next</a>";
			
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
