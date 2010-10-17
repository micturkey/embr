<?php
	include ('lib/twitese.php');
	$title = "Ranking";
	include ('inc/header.php');
	
	if (!loginStatus()) header('location: login.php');
?>

<script type="text/javascript" src="js/profile.js"></script>

<div id="statuses" class="column round-left">

	<h2>Rankings</h2>
	<div class="clear"></div>
	
	<?php 
		$t = getTwitter();
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
		$num = 20*($p-1);
	
		$users = $t->rank($p);
		if ($users === false) {
			header('location: error.php');exit();
		} 
		$empty = count($users) == 0? true: false;
		if ($empty) {
			echo "<div id=\"empty\">No tweet to display.</div>";
		} else {
			$output = '<ol class="rank_list">';
			
			foreach ($users as $user) {
				$num++;	
				$output .= "
				<li>
					<span class=\"rank_img\"><a href=\"user.php?id=$user->screen_name\"><img src=\"$user->profile_img_url\" /></a></span>
					<div class=\"rank_content\">
						<span class=\"rank_num\">No. $num <span class=\"rank_name\"><a href=\"user.php?id=$user->screen_name\">$user->name</a></span><span class=\"rank_screenname\"> ($user->screen_name)</span></span>
						<span class=\"rank_count\">Followers：$user->followers_count 　Friends：$user->friends_count 　Tweets：$user->statuses_count</span>
						<span class=\"rank_description\">Bio：$user->description</span>
					</div>
				</li>
				";
			}
			
			$output .= "</ol><div id=\"pagination\">";
			
			if ($p >1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"rank.php?p=" . ($p-1) . "\">Back</a>";
			if (!$empty) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"rank.php?p=" . ($p+1) . "\">Next</a>";
			
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
