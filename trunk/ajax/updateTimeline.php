<?php
	if(!isset($_SESSION)){
		session_start();
	}
	function getTimeline(){
		include ('../lib/twitese.php');
		include('timeline_format.php');
		$t = getTwitter();
		if ( isset($_GET['since_id']) ) {

			$statuses = $t->friendsTimeline(false, $_GET['since_id']);
			$retweetes = $t->retweeted_to_me(false, false, $_GET['since_id']);
			if(count($retweetes) > 0){
				$statuses = sort_timeline($statuses, $retweetes);
			}

			$count = count($statuses);
			$html = "";
			if ($count <= 0) {
				echo "empty";
			}
			else
			{
				foreach ($statuses as $status)
				{
					if($status->id < $_GET['since_id'])
					{
						break;
					}
					
					if(($status->user->screen_name == $t->username ) && (strpos($status->source, "api") !== false || strpos($status->source, "rabr") !== false)){
						$count -= 1;
						continue;
					}
					elseif ( isset($status->retweeted_status) )
					{
						if ( ($t->username == $status->retweeted_status->user->screen_name) && (strpos($status->source, "api") != false || strpos($status->source, "rabr") !== false) )
						{
							$count -= 1;
							continue;
						}
					}
					if(isset($status->retweeted_status)){
						$html .= format_retweet($status);
					}else{
						$html .= format_timeline($status, $t->username);
					}
				}
				if($count == 1){
					$tweetCounter = "$count unread tweet";
				}else{
					$tweetCounter = "$count unread tweets";
				}
				$html .= '<div class="new">'.$tweetCounter.'</div>';
				echo $html;
			}
		} else {
			echo 'error';
		}
	}

	// force exit timeout script
	//$deadline = $_SERVER['REQUEST_TIME'] + MAX_EXECUTION_TIME;
	/*
	function checkTimeout(){
		if($_SERVER['REQUEST_TIME'] < $GLOBALS['timeline']){
			return;
		}
		echo "error";
		exit;
	}
	register_tick_function("checkTimeout");
	declare(ticks = 1){
	 */
	getTimeline();
	//}
?>
