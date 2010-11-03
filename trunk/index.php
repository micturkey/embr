<?php
	include ('lib/twitese.php');
	$title = 'Home';
	include ('inc/header.php');

	if (!loginStatus()) header('location: login.php');

	$t = getTwitter();

	if (isset($_POST['status']) && isset($_POST['in_reply_to']))
	{
		if (trim($_POST['status']) == '')
		{
			setUpdateCookie('empty');
		}
		else
		{
			$result = $t->update($_POST['status'], $_POST['in_reply_to']);
			if ($result)
			{
				setUpdateCookie('success');

				$user = $result->user;
				$time = $_SERVER['REQUEST_TIME']+3600*24*365;
				if ($user)
				{
					setcookie('friends_count', $user->friends_count, $time, '/');
					setcookie('statuses_count', $user->statuses_count, $time, '/');
					setcookie('followers_count', $user->followers_count, $time, '/');
					setcookie('imgurl', getAvatar($user->profile_image_url), $time, '/');
					setcookie('name', $user->name, $time, '/');
				}
			}
			else
			{
				setUpdateCookie('error');
			}
		}
		header('location: index.php');
	}
?>
<script src="js/home.js"></script>
<div id="statuses" class="column round-left">
<?php
  include('inc/sentForm.php'); 
  
	$p = 1;
	if (isset($_GET['p']))
	{
		$p = (int) $_GET['p'];
		if ($p <= 0) $p = 1;
	}

	$statuses = $t->friendsTimeline($p);
	$retweetes = $t->retweeted_to_me($p);
	if ($statuses == false)
	{
		header('location: error.php');exit();
	} 
	$empty = count($statuses) == 0 ? true: false;
	if ($empty)
	{
		echo "<div id=\"empty\">No tweet to display.</div>";
	}
	else
	{
		echo '<ol class="timeline" id="allTimeline">';

		include('ajax/timeline_format.php');
		if(count($retweetes) > 0)
		{
			$statuses = sort_timeline($statuses, $retweetes);
		}
		$MAX_STATUSES = 20;
		$status_count = 0;
		foreach ($statuses as $status)
		{
			if(++$status_count >= $MAX_STATUSES)
			{
				break;
			}
			if(isset($status->retweeted_status))
			{
				echo format_retweet($status);
			}
			else
			{
				echo format_timeline($status, $t->username);
			}
		}

		echo "</ol><div id=\"pagination\">";

		if ($p >1) echo "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"index.php?p=" . ($p-1) . "\">Back</a>";
		if (!$empty) echo "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"index.php?p=" . ($p+1) . "\">Next</a>";
	}
?>
</div>
</div>
<?php 
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
