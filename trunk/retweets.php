<?php
	include_once('lib/twitese.php');
	$title = "Retweets";
	include_once('inc/header.php');
	include_once('ajax/timeline_format.php');
	if (!loginStatus()) header('location: login.php');

	$type = 'retweets';
	$retweetsType = isset($_GET['type']) ? $_GET['type'] : 'to';
	$page = isset($_GET['page']) ? $_GET['page'] : false;
	$count = isset($_GET['count']) ? $_GET['count'] : 20;
	$since_id = isset($_GET['since']) ? $_GET['since'] : false;
	$max_id = isset($_GET['maxid']) ? $_GET['maxid'] : false;

	$t = getTwitter();
	$retweets_to_me_class = '';
	$retweeted_by_me_class = '';
	$retweeted_of_me_class = '';
	$retweets;
	switch($retweetsType){
		case "by":
			$retweets_to_me_class = 'subnavLink';
			$retweeted_by_me_class = 'subnavNormal';
			$retweeted_of_me_class = 'subnavLink';
			$retweets = $t->retweeted_by_me($page, $count, $since_id, $max_id);
			break;
		case "mine":
			$retweets_to_me_class = 'subnavLink';
			$retweeted_by_me_class = 'subnavLink';
			$retweeted_of_me_class = 'subnavNormal';
			$retweets = $t->retweets_of_me($page, $count, $since_id, $max_id);
			break;
		default:
			$retweets_to_me_class = 'subnavNormal';
			$retweeted_by_me_class = 'subnavLink';
			$retweeted_of_me_class = 'subnavLink';
			$retweets = $t->retweeted_to_me($page, $count, $since_id, $max_id);
	}
	echo '<div id="statuses" class="column round-left">';
	include_once('inc/sentForm.php');
	$html = '<script src="js/btns.js"></script>
	<style>
	.big-retweet-icon{display:none}
	.timeline li {border-bottom:1px solid #EFEFEF;border-top:none !important}
	</style>';
	$html .= "<div id='subnav'>
		<a href='retweets.php?type=to'><span class='$retweets_to_me_class'>Retweets by others</span></a>
		<a href='retweets.php?type=by'><span class='$retweeted_by_me_class'>Retweets by you</span></a>
		<a href='retweets.php?type=mine'><span class='$retweeted_of_me_class'>Your tweets, retweeted</span></a>
		</div>";
	$html .='<div class="clear"></div>';
	$empty = count($retweets) == 0? true: false;
	if ($empty) {
		$html .= "<div id=\"empty\">No retweets to display.</div>";
	} else {
		$html .= '<ol class="timeline">';
		if($retweetsType == 'mine'){
			foreach($retweets as $retweet){
				$html .= format_retweet_of_me($retweet);
			}
		}elseif($retweetsType == 'by'){
			foreach($retweets as $retweet){
				$html .= format_retweet($retweet, true);
			}
		}else{
			foreach($retweets as $retweet){
				$html .= format_retweet($retweet);
			}
		}
		$html .= '</ol><div id="pagination">';
	}
	echo $html;
	include_once('inc/sidebar.php');
	include_once('inc/footer.php');
?>
