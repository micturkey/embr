<?php
	include ('lib/twitese.php');
	$title = "Replies";
	include ('inc/header.php');
	
	if (!loginStatus()) header('location: login.php');
?>

<script type="text/javascript">
	$(function(){
		formFunc();
		$(".rt_btn").live("click", function(e){
			e.preventDefault();
			onRT($(this));
		});
		
		$(".replie_btn").live("click", function(e){
			e.preventDefault();
			onReplie($(this), e);
		});
		
		$(".favor_btn").live("click", function(e){
			e.preventDefault();
			onFavor($(this));
		});
		
		$("#submit_btn").click(function(e){
		updateStatus();
		e.preventDefault();
		});
	});
</script>

<div id="statuses" class="column round-left">

	<?php include('inc/sentForm.php')?>
	
	<?php 
		$t = getTwitter();
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
	
		$statuses = $t->replies($p);
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
							<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$user->screen_name\">$user->screen_name</a> <span class=\"tweet\">$text</span> </span>";
						$output .= recoverShortens($text);
				$output .= "<span class=\"actions\">
								<a class=\"replie_btn\" href=\"a_reply.php?id=$status->id\">回复</a><a class=\"rt_btn\" href=\"a_rt.php?id=$status->id\">回推</a><a class=\"favor_btn\" href=\"a_favor.php?id=$status->id\">收藏</a></span>
				<span class=\"status_info\">";
				if ($status->in_reply_to_status_id) $output .= "<span class=\"in_reply_to\"> <a class=\"ajax_reply\" href=\"ajax/status.php?id=$status->in_reply_to_status_id&uid=$user->id \">in reply to $status->in_reply_to_screen_name</a></span>";
				$output .= "				
								<span class=\"source\">via $status->source</span>
								<span class=\"date\"><a href=\"https://twitter.com/$user->screen_name/status/$status->id\" target=\"_blank\">$date</a></span>
						    </span>
						</span>
					</li>
				";
			}
			
			$output .= "</ol><div id=\"pagination\">";
			
			if ($p >1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"replies.php?p=" . ($p-1) . "\">Back</a>";
			if (!$empty) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"replies.php?p=" . ($p+1) . "\">Next</a>";
			
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
