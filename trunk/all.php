<?php 
	include_once('lib/twitese.php');
	$title = 'Updates';
	include_once('inc/header.php');
	include_once('ajax/timeline_format.php');
	if (!loginStatus()) header('location: login.php');

?>
<script type="text/javascript" src="js/all.js?ver=2010041101"></script>
<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript" src="js/formfunc.js?ver=2010041501"></script>

<div id="statuses" class="column round-left">

	<h2>What's happening?</h2>
	<span id="tip"><b>140</b></span>

	<form enctype="multipart/form-data" action="ajax/uploadPhoto.php" method="post" id="photoArea">
	<span style="font-weight: bold;">Upload Image</span>
	<p>Powered by Img.ly</p>
	<input type="file" name="image" id="imageFile"/> 
	<input type="submit" id="imageUploadSubmit" class="btn" value="Upload"/>
	<a href="#" onclick="$('#photoArea').slideToggle(300)" title="Close" class="close"></a>
	</form>

<form id="filterArea">
<span style="font-weight: bold;">Filter Timeline</span>
<p>Seperate keywords with comma. [eg: twitter,hello] Also usernames <b>without</b> @</p> 
<input type="text" id="iptFilter" name="iptFilter" class="filter_input"/>
<input type="submit" style="vertical-align: top; padding: 5px; margin: 9px 3px 0pt 6px;" id="filterSubmit" class="btn" value="Update">
<input type="submit" style="padding: 5px; vertical-align: top; margin-top: 9px;" id="filterReset" class="btn" value="Reset">
<input type="submit" style="padding: 5px; vertical-align: top; margin: 9px 0pt 0pt 3px;" id="filterHide" class="btn" value="Hide @">
<a class="close" title="Close" onclick="$('#filterArea').slideToggle(300)" href="#"></a>
</form>

<form id="symArea">
<div id="symbols">
<?php include ('inc/symbols.php');?>
</div>
<a class="close" title="Close" onclick="$('#symArea').slideToggle(300)" href="#"></a>
</form>

<form id="transArea">
<span style="font-weight: bold; display: block; margin-bottom: 5px;">Translation Settings</span>
<p>Translate tweets into
<select name="langs" style="border: 1px solid rgb(170, 170, 170); padding: 1px 2px;">
<option value="ar">Arabic</option>
<option value="zh-CN">简体中文</option>
<option value="zh-TW">繁體中文</option>
<option value="da">Danish</option>
<option value="nl">Dutch</option>
<option value="en">English</option>
<option value="fi">Finnish</option>
<option value="fr">French</option>
<option value="de">German</option>
<option value="el">Greek</option>
<option value="hu">Hungarian</option>
<option value="is">Icelandic</option>
<option value="it">Italian</option>
<option value="ja">Japanese</option>
<option value="ko">Korean</option>
<option value="lt">Lithuanian</option>
<option value="no">Norwegian</option>
<option value="pl">Polish</option>
<option value="pt">Portuguese</option>
<option value="ru">Russian</option>
<option value="es">Spanish</option>
<option value="sv">Swedish</option>
<option value="th">Thai</option>
</select>
</p>
<p>Translate my tweets into <select name="myLangs" style="border: 1px solid rgb(170, 170, 170); margin-top: 5px; padding: 1px 2px;">
<option value="ar">Arabic</option>
<option value="zh-CN">简体中文</option>
<option value="zh-TW">繁體中文</option>
<option value="da">Danish</option>
<option value="nl">Dutch</option>
<option value="en">English</option>
<option value="fi">Finnish</option>
<option value="fr">French</option>
<option value="de">German</option>
<option value="el">Greek</option>
<option value="hu">Hungarian</option>
<option value="is">Icelandic</option>
<option value="it">Italian</option>
<option value="ja">Japanese</option>
<option value="ko">Korean</option>
<option value="lt">Lithuanian</option>
<option value="no">Norwegian</option>
<option value="pl">Polish</option>
<option value="pt">Portuguese</option>
<option value="ru">Russian</option>
<option value="es">Spanish</option>
<option value="sv">Swedish</option>
<option value="th">Thai</option>
</select>
<input type="button" value="Translate" class="btn" id="translateMy" style="vertical-align: middle; padding: 3px 8px; margin-top: -3px;">
</p>
<a href="#" onclick="$('#transArea').slideToggle(300)" title="Close" style="right:25px;top:5px;" class="close"></a>
</form>

	<form action="index.php" method="post">
		<a id="transRecover">Restore</a>
		<textarea name="status" id="textbox"></textarea>
		<input type="hidden" id="in_reply_to" name="in_reply_to" value="0" />

	<div id="func_set">
	
	<a class="func_btn" href="javascript:shortUrlDisplay();" title="Shorten URL" style="background-position:-238px -113px">Shorten URL</a>
	
	<a class="func_btn" href="javascript:shortenTweet();" title="Shorten Tweet" style="background-position:-222px -48px;">Shorten Tweet</a>
	
	<a id="transBtn" title="Translation Settings" class="func_btn" style="background-position:-110px -80px;">Translate</a>
	
	<a title="Upload Image" id="photoBtn" class="func_btn" style="background-position: -207px -128px;">Image</a>
	
	<a id="filterBtn" title="Filter Timeline" class="func_btn" style="background-position:-174px -112px;">Filter</a>
	
	<a title="Sogou Cloud IME" href="javascript:void((function(){var%20n=navigator.userAgent.toLowerCase();ie=n.indexOf('msie')!=-1?1:0;if(document.documentMode)ie=0;charset='';if(ie)charset=document.charset;src=ie&amp;&amp;charset=='utf-8'?'http://web.pinyin.sogou.com/web_ime/init2_utf8.php':'http://web.pinyin.sogou.com/web_ime/init2.php';element=document.createElement('script');element.setAttribute('src',src);document.body.appendChild(element);})())" onclick="updateSentTip('Loading...', 5000, 'ing')" class="func_btn" style="background-position: -62px -112px;">Sogou</a>
	
	<a id="symbolBtn" title="Symbols and smileys" class="func_btn" style="background-position: -206px -113px;">Symbols</a>

	<a id="restoreBtn" style="background-position: 2px -64px;" class="func_btn" title="Restore previous tweet">Restore</a>
	
	<a id="autoBtn" title="Auto refresh control" class="func_btn pause">Pause</a>
	
	<a id="clearBtn" style="background-position: 3px -176px;" class="func_btn" title="Sweep Timeline" class="func_btn">Sweep</a>

	<a id="refreshBtn" title="Refresh the timeline" class="func_btn" style="background-position: -62px -80px;">Refresh</a>
	</div>

<?php 
	$t = getTwitter();
	$current_user = $t == null ? false : $t->veverify();
	if ($current_user === false) {
		header('location: error.php');exit();
	} 
	$empty = count($current_user) == 0? true: false;
	if ($empty) {
		echo "<div id=\"currently\">
			<span id=\"full_status\" title=\"Click to view the full tweet\"><strong >Latest:</strong></span>
			<span id=\"latest_status\">
			<span id=\"latest_text\">
			<span class=\"status-text\">What's shaking?</span>
			<span class=\"full-text\" style=\"display:none\">What's shaking?</span>
			<span class=\"entry-meta\" id=\"latest_meta\"></span>
			<span class=\"entry-meta\" id=\"full_meta\"></span>
			</span>
			</span>
			</div>";
	} else {
		$status = $current_user->status;
		refreshProfile($t, $current_user);
		$date = formatDate($status->created_at);
		$text = formatText($status->text);
		$output = "
			<div id=\"currently\">
			<span id=\"full_status\"><strong>Latest:</strong></span>
			<span id=\"latest_status\">
			<span id=\"latest_text\">
			<span class=\"status-text\">" . $text . "</span>
			<span class=\"full-text\" style=\"display:none\">" . $text . "</span>
			<span class=\"entry-meta\" id=\"latest_meta\"><a href=\"status.php?id=$status->id\" target=\"_blank\">" . $date . "</a></span>
			<span class=\"entry-meta\" id=\"full_meta\" style=\"display:none\"><a href=\"status.php?id=$status->id\" target=\"_blank\">" . $date . "</a></span>
			</span>
			</span>
			</div>				
			";
		echo $output;
	}
?>
		<div id="tweeting_controls">
		<a class="a-btn a-btn-m btn-disabled" id="tweeting_button" tabindex="2" href="#"><span>Tweet</span></a>
	</div>
<!--<input type="submit" id="submit_btn" value="update" />-->
		<div id="allNav">
			<a class="allBtn allHighLight" id="allTimelineBtn" href="javascript:void(0);">Updates</a>
			<a class="allBtn" id="allRepliesBtn" href="javascript:void(0);">Replies</a>
			<a class="allBtn" id="allMessageBtn" href="javascript:void(0);">Messages</a>
		</div>


	</form>

	<div class="clear"></div>
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
