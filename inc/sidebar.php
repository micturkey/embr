<?php 
$t = getTwitter();
?>
<td class="column round-right" id="side_base">
<table>
<tr>
<td>
<div id="side" class="round-right">
	<div id="sideinfo">
		<a href="profile.php"><img id="sideimg" src="<?php echo getCookie("imgurl")?>" /></a>
		<span id="sideid"><span id="side_name"><?php echo getEncryptCookie('twitese_name')?></span><a href="#" id="profileRefresh" title="refresh your profile"><img src="img/refresh.png" /></a></span>
		<a href="profile.php"><span id="me_tweets"><span id="update_count"><?php echo getCookie('statuses_count')?></span> tweets</span></a>
	</div>
	<?php if (strrpos($_SERVER['PHP_SELF'], 'profile')) {
	$user = $t->showUser();
?>
	<ul id="user_info_profile">
		<li><span>Name</span> <?php echo $user->name ?></li>
		<?php if ($user->location) echo "<li><span>Location</span> $user->location</li>"; ?>
		<?php if (($user->url) and (strlen($user->url)>20)) echo '<li><span>Web</span> <a href="' .$user->url. '" target="_blank">' .substr($user->url, 0, 20). '...</a></li>'; else if (($user->url) and (strlen($user->url)<=20)) echo '<li><span>Web</span> <a href="' .$user->url. '" target="_blank">' .$user->url. '</a></li>';?>
		<?php if ($user->description) echo "<li><span>Bio</span> $user->description</li>"; ?>
		</ul>
	<?php }?>
	<ul id="user_stats">
		<li>
			<a href="friends.php">
				<span class="count"><?php echo getCookie('friends_count')?></span>
				<span class="label">following</span>
			</a>
		</li>
		<li>
			<a href="followers.php">
				<span class="count"><?php echo getCookie('followers_count')?></span>
				<span class="label">followers</span>
			</a>
		</li>
		<li>
			<a href="lists.php">
				<span class="count"><?php $listed_count = getCookie('listed_count'); echo ($listed_count > 0 ? $listed_count : 0);?></span>
				<span class="label">listed</span>
			</a>
		</li>
	</ul>
	
		<li>
		<DIV id='profile' class='section'> 
		<p id="sidebarTip" class='promotion round' style='cursor:pointer'> 
			<a class="definition">
			<?php 
			switch(mt_rand(0,2)) { 
				case 2: ?>
		<strong>Realtime Refresh</strong>
		<em>v.</em>
		Now you can refresh your profile whenever you like!<br>Click for more details.<span id="indicator">[+]</span>
		</a>
		<a id="sidebarTip_more" style="display: none;">
			See the circle behind your username? Try to click it!	
			</a>
				<?php break;
				case 1: ?>
		<strong>User Di&middot;rect View</strong>
		<em>n.</em>
		Now you can view the user page of your interested more incentively. <br>Click for more details.<span id="indicator">[+]</span>
		</a>
		<a id="sidebarTip_more" style="display: none;">
			take @<?php echo SITE_OWNER ?> for example, you can visit his/her page via <?php echo BASE_URL.'/'.SITE_OWNER ?>			
			</a>
				<?php break;
				default: ?>
		<strong>Short&middot;cuts</strong>
		<em>n.</em>
		Use shortcuts in Embr. <br>Click for more details.<span id="indicator">[+]</span>
		</a>
		<a id="sidebarTip_more" style="display: none;">
		<strong>Shortcuts available now:</strong><br>
		C / U - Update<br>
		T - Go to top<br>
		B - Go to bottom<br>
		R - Refresh<br>
		S - Search
		</a>
		<?php break; 
	 } ?>
		</p>
		</DIV>
		</li>
		
	<div class="clear"></div>
	<ul id="primary_nav" class="sidebar-menu">
	<li id="updates_tab"><a class="in-page-link" href="all.php"><span>Updates</span></a></li>
	<li id="replies_tab"><a class="in-page-link" href="replies.php"><span>@<?php echo is_null(getEncryptCookie('twitese_name')) ? $t->screen_name : getEncryptCookie('twitese_name'); ?></span></a></li>
	<li id="msgs_tab"><a class="in-page-link" href="message.php"><span>Direct Messages</span></a></li>
	<li id="lists_tab"><a class="in-page-link" href="lists.php"><span>Lists</span></a></li>
	<li id="favs_tab"><a class="in-page-link" href="favor.php"><span>Favorites</span></a></li>
	<li id="retweets_tab"><a class="in-page-link" href="retweets.php"><span>Retweets</span></a></li>
	</ul>
	<?php include ('sidepost.php') ?>
</div>
</td>
</tr>
</table>