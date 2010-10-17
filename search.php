<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('lib/twitese.php');
	$title = "Search";
	include ('inc/header.php');

	function getSearch($query, $page){
		GLOBAL $output;
		$t = getTwitter();
		$MAX_TWEETS = 20;
		$statuses = $t->search($query, $page, $MAX_TWEETS);

		//if ($statuses === false) {
		//	header('location: error.php');exit();
		//}
		$resultCount = count($statuses->results);
		if ($resultCount <= 0) {
			echo "<div id=\"empty\">No tweet to display.</div>";
		} else {
			include_once('ajax/timeline_format.php');
			$output = '<ol class="timeline" id="allTimeline">';
			foreach ($statuses->results as $status) {
				$date = formatDate($status->created_at);
				$text = formatText($status->text);

				$output .= "
					<li>
					<span class=\"status_author\">
					<a href=\"user.php?id=$status->from_user\" target=\"_blank\"><img src=\"".getAvatar($status->profile_image_url)."\" title=\"$status->from_user\" /></a>
					</span>
					<span class=\"status_body\">
					<span class=\"status_id\">$status->id</span>
					<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$status->from_user\">$status->from_user</a> <span class=\"tweet\">$text</span> </span>";
				$output .= recoverShortens($text);
				$output .="<span class=\"actions\">
					<a class=\"replie_btn\" href=\"a_reply.php?id=$status->id\">回复</a><a class=\"rt_btn\" href=\"a_rt.php?id=$status->id\">回推</a>
					<a class=\"retw_btn\" title=\"New Retweet\" href=\"javascript:void(0);\">New Retweet</a>
					<a class=\"favor_btn\" href=\"a_favor.php?id=$status->id\">收藏</a></span><span class=\"status_info\">";
				$output .=	"<span class=\"source\">via ".html_entity_decode($status->source)."</span>
					<span class=\"date\"><a href=\"status.php?id=$status->id\" title=\"".date('Y-m-d H:i:s', strtotime($status->created_at))."\" target=\"_blank\">$date</a></span>
					</span>
					</span>
					</li>
					";
			}

			$output .= "</ol><div id=\"pagination\">";

			if ($page > 1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"search.php?q=".urlencode($query)."&p=" . ($page - 1) . "\">Back</a>";
			if ($resultCount == $MAX_TWEETS) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"search.php?q=".urlencode($query)."&p=" . ($page + 1) . "\">Next</a>";
			$output .= "</div>";
		}
	}

	if (!loginStatus()) header('location: login.php');
?>
<style type="text/css">#trend_entries{display:block}</style>
<script type="text/javascript" src="js/search.js"></script>
<div id="statuses" class="column round-left">

	<form action="search.php" method="get" id="search_form">
		<input type="text" name="q" id="query" value="<?php echo $_GET['q'] ?>" />
		<input type="submit" class="more round" style="width: 103px; margin-left: 10px; display: block; float: left; height: 34px; font-family: tahoma; color: rgb(51, 51, 51);" value="Search">
	</form>

	<!-- Google Custom Search Element 
	<div id="cse" style="width:100%;">Loading</div>
	<script src="http://www.google.com/jsapi" type="text/javascript"></script>
	<script type="text/javascript">
	google.load('search', '1');
	google.setOnLoadCallback(function(){
		new google.search.CustomSearchControl('011932393486939676874:dgglbf75l0o').draw('cse');
	  }, true);
	</script>
-->	
<?php 
	$t = getTwitter();
	$p = 1;
	if (isset($_GET['p'])) {
		$p = (int) $_GET['p'];
		if ($p <= 0) $p = 1;
	}
	$output = '';
	if (isset($_GET['q'])) {
		$q = $_GET['q'];
		getSearch($q, $p);
	}
	echo $output;
?>
</div>

<?php 
	include ('inc/sidebar.php');
?>

<?php 
	include ('inc/footer.php');
?>
