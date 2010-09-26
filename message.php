<?php
	include_once('lib/twitese.php');
	$title = 'Direct Messages';
	include_once('inc/header.php');
	include_once('ajax/timeline_format.php');
	
	if (!loginStatus()) header('location: login.php');
?>

<!--<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/message.js"></script>-->
<style type="text/css">.timeline li {border-bottom:1px solid #EFEFEF;border-top:none !important}</style>

<?php 
	$isSentPage = isset($_GET['t'])? true : false;
?>
<div id="statuses" class="column round-left">

	<?php if ( isset($_GET['id']) ) { ?>
	<h2>To <input type="text" style="border: 1px solid rgb(167, 166, 170); margin: 0px 0px 6px; padding: 2px; height: 14px; width: 120px; font-size: 13px;" name="sent_id" id="sent_id" value="<?php echo $_GET['id'] ?>"/></h2>
	<?php	} else { ?>
	<h2>To <input type="text" style="border: 1px solid rgb(167, 166, 170); margin: 0px 0px 6px; padding: 2px; height: 14px; width: 120px; font-size: 13px;" name="sent_id" id="sent_id" /></h2>
	<?php	} ?>
	<?php include('inc/sentForm.php')?>
	
	<div id="subnav">
	<?php if ($isSentPage) {?>
       	<span class="subnavLink"><a href="message.php">Inbox</a></span><span class="subnavNormal">Sent</span>
	<?php } else {?>
       	<span class="subnavNormal">Inbox</span><span class="subnavLink"><a href="message.php?t=sent">Sent</a></span>
	<?php } ?>
    </div>
	
	<?php 
		$t = getTwitter();
		
		if ( isset($_POST['sent_id']) && isset($_POST['message']) ) {
			
			if (trim($_POST['message']) == '') {
				setUpdateCookie('empty');
			} else {
				$result = $t->sendDirectMessage(trim($_POST['sent_id']), $_POST['message']);
				if ($result) setUpdateCookie('success');
				else setUpdateCookie('error');
			}
			
			header('location: message.php?t=sent');
		}
		
		if (getUpdateCookie()) {
			switch (getUpdateCookie()) {
				case 'success':
					echo "<div id=\"otherTip\">You message has been sent.</div>";
					break;
				case 'empty':
					echo "<div id=\"otherTip\">You cannot send an empty message!</div>";
					break;
				case 'error':
					echo "<div id=\"otherTip\">Send message failed, please try again.</div>";
					break;
				default:
					break;
			}
		}
	?>
	<!--
	<form action="message.php" method="post">
	<?php if ( isset($_GET['id']) ) { ?>
	<h2>To <input type="text" style="border: 1px solid rgb(167, 166, 170); margin: 0px 0px 5px; padding: 2px; height: 16px; width: 150px; font-size: 13px;" name="sent_id" id="sent_id" value="<?php echo $_GET['id'] ?>"/></h2>
	<?php	} else { ?>
	<h2>To <input type="text" style="border: 1px solid rgb(167, 166, 170); margin: 0px 0px 5px; padding: 2px; height: 16px; width: 150px; font-size: 13px;" name="sent_id" id="sent_id" /></h2>
	<?php	} ?>
	<span id="tip"><b>140</b></span>
		<textarea name="message" id="textbox"></textarea>
		<input type="submit" id="submit_btn" value="send" />
	</form>
	<div class="clear"></div>
	-->
	
	
	<?php 
		$t = getTwitter();
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
	
		if ($isSentPage) {
			$messages = $t->sentDirectMessage($p);
		} else {
			$messages = $t->directMessages($p);
		}
		if ($messages === false) {
			header('location: error.php');
		} 
		$empty = count($messages) == 0? true: false;
		if ($empty) {
			echo "<div id=\"empty\">No tweets to display.</div>";
		} else {
			$output = '<ol class="timeline" id="allTimeline">';
			
			foreach ($messages as $message) {
				$name = $message->sender_screen_name;
				$imgurl = getAvatar($message->sender->profile_image_url);
				$date = formatDate($message->created_at);
				$text = formatText($message->text);
				
				$output .= "
					<li>
						<span class=\"status_author\">".initShortcutMenu($message->sender)."
							<a href=\"user.php?id=$name\" target=\"_blank\"><img src=\"$imgurl\" title=\"$name\" /></a>
						</span>
						<span class=\"status_body\">
							<span class=\"status_id\">$message->id </span>
							<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$name\">$name </a> $text </span>
							<span class=\"actions\">
				";
				/*
				if (!$isSentPage) {
					$output .= "<a class=\"msg_replie_btn\" href=\"message.php?id=$name\">回复</a><a class=\"delete_btn\" href=\"a_del.php?id=$message->id&t=m\">删除</a>";
				} else {
					$output .= "<a class=\"delete_btn\" href=\"a_del.php?id=$message->id&t=m\">删除</a>";
				}
				*/
				if (!$isSentPage) {
					$output .= "<a class=\"msg_replie_btn\" href=\"#\">reply</a><a class=\"msg_delete_btn\" href=\"#\">delete</a>";
				} else {
					$output .= "<a class=\"msg_delete_btn\" href=\"#\">delete</a>";
				}
				$output .="</span><span class=\"status_info\">
								<span class=\"date\">$date</span>
						    </span>
						</span>
					</li>
				";
			}
			
			$output .= "</ol><div id=\"pagination\">";
			
			
			if ($isSentPage) {
				if ($p >1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"message.php?t=sent&p=" . ($p-1) . "\">Back</a>";
				if (!$empty) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"message.php?t=sent&p=" . ($p+1) . "\">Next</a>";
			} else {
				if ($p >1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"message.php?p=" . ($p-1) . "\">Back</a>";
				if (!$empty) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"message.php?p=" . ($p+1) . "\">Next</a>";
			}
			
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
