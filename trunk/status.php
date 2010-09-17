<?php 
	if(!isset($_SESSION)){
		session_start();
	}
	include ('lib/twitese.php');
	$title = "Tweet";

	if (!loginStatus()) header('location: login.php');

	$t = getTwitter();
	if ( isset($_GET['id']) ) {
		$statusid = $_GET['id'];
		$status = $t->showStatus($statusid);
		if (!$status) {
			header('location: error.php');
		}
		$user = $status->user;
		$date = formatDate($status->created_at);
		$text = formatText($status->text);
	} else {
		header('location: error.php');
	}

?>
<?php ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="embr, open source, php, twitter, oauth, disinfeqt, JLHwung" />
<meta name="description" content="Multi-functional Interface for Twitter" />
<link rel="shortcut icon" href="img/favicon.ico" />
<link type="text/css" id="css" href="css/main.css" rel="stylesheet" />
<title>Embr / <?php echo $title ?></title>
<?php 
	$myCSS = getColor("myCSS","");
	$old_css = "ul.sidebar-menu li.active a";
	$new_css = "ul.sidebar-menu a.active";
	$myCSS = str_replace($old_css,$new_css,$myCSS);
	$fontsize = getColor("fontsize","13px");
?>
<style type="text/css">
<?php echo $myCSS ?>
a:active, a:focus {outline:none}
body {font-size:<?php echo $fontsize ?> !important}
#header {
margin:1em auto;
text-align:right;
width:600px;
}
#content {
margin:1em auto;
width:600px;
}
.wrapper {
margin:1em auto;
position:relative;
width:600px;
}
#statuses{
background-color:#FFFFFF;
float:left;
padding:10px;
width:580px;
}
.timeline li:hover, .rank_list li:hover {
background-color:transparent !important;
}
.timeline, .ajax_timeline {
border-bottom:1px solid #FFF !important;
border-top:1px solid #FFF !important;
}
.timeline li, .ajax_timeline li {
border-bottom:1px solid #FFF !important;
border-top:1px solid #FFF !important;
}
.status_body {
display:block;
font-size:2em;
line-height:30px;
margin-left:58px;
overflow:hidden;
position:relative;
}
.timeline li {
cursor:default;
margin:0px;
overflow:hidden;
padding:10px;
position:relative;
}
.status_author, .rank_img {
left:10px;
position:absolute;
top:15px;
width:50px;
}
</style>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/mediaPreview.js"></script>
<script type="text/javascript" src="js/public.js"></script>
</head>

<body>
	<div id="header">
		<div class="wrapper">
			<a href="index.php"><img id="logo" style="float:left" src="img/logo.png" /></a>
			<ul id="nav" class="round">
				<li><a href="index.php">Home</a></li>
				<li><a href="profile.php">Profile</a></li>
				<li><a href="browse.php">Public</a></li>
				<li><a href="setting.php">Settings</a></li>
				<li><a href="logout.php">Logout</a></li>			
			</ul>
		</div>
	</div>
	<div id="content">
		<div class="wrapper">
			<div class="content-bubble-arrow"></div>
			<table cellspacing="0" class="columns">
		  <tbody>
			<tr>
			  <td id="left" class="round">
<div id="statuses" class="round">
		<div class="clear"></div>
		<ol class="timeline">
				<li>
						<span class="status_author">
								<a href="user.php?id=<?php echo $user->screen_name ?>" target="_blank"><img src="<?php echo getAvatar($user->profile_image_url); ?>" /></a>
						</span>
						<span class="status_body">
							<span class="status_id"><?php echo $statusid ?></span>
							<span class="status_word"><a class="user_name" href="user.php?id=<?php echo $user->screen_name ?>"><?php echo $user->screen_name ?></a> <span class="tweet"><?php echo $text ?></span></span>
							<span class="status_info">
										<?php if ($status->in_reply_to_status_id) {?><span class="in_reply_to"> <a href="status.php?id=<?php echo $status->in_reply_to_status_id ?>">in reply to <?php echo $status->in_reply_to_screen_name?></a></span> <?php }?>
										<span class="source">from <?php echo $status->source ?></span>
										<span class="date"><a href="status.php?id=<?php echo $statusid ?>" target="_blank"><?php echo $date ?></a></span>
							</span>
						</span>
				</li>
		</ol>
</div>
			</tr>
		  </tbody>
		</table>
		<div class="clear"></div>
			<div id="footer" class="round">
			<ul>
			<li>&copy; 2010 disinfeqt</li>
			<li><a href="about.php" title="About Rabr">About</a></li>
			<li><a href="http://blog.zdxia.com/" title="zdx Purified" target="_blank">Blog</a></li>
			<li><a href="http://code.google.com/p/twitese/" target="_blank" title="Rabr is proundly powered by the Open Source project - Twitese">Twitese</a></li>
			<li><a href="http://code.google.com/p/rabr/" target="_blank">Open Source</a></li>
			</ul>
			</div>
		</div>
	</div>
</body>
	<script type="text/javascript">
	var username = $(".user_name").html();
	var tweet = $(".tweet").text();
	if (tweet.length > 30) {
		tweet = tweet.substr(0,30) + " ...";
	}
	document.title =document.title.replace(/Tweet/, username + ": " + tweet);
	</script>
</html>
<?php ob_end_flush(); ?>
