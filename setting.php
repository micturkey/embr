<?php
	include ('lib/twitese.php');
	$title = "Settings";
	include ('inc/header.php');	
	if (!loginStatus()) header('location: login.php');	
?>
<script src="js/colorpicker.js"></script>
<script src="js/setting.js"></script>
<link rel="stylesheet" href="css/colorpicker.css" />
<div id="statuses" class="column round-left">
	<div id="setting">
<?php 
	$settingType = isset($_GET['t'])? $_GET['t'] : 1;
	if (isset($_POST['name'])) {
		$t = getTwitter();
		$args = array();
		$args['name'] = $_POST['name'];
		$args['url'] = $_POST['url'];
		$args['location'] = $_POST['location'];
		$args['description'] = $_POST['description'];
		$result = $t->updateProfile($args);
		if ($result) echo "<div id=\"otherTip\">Your profile has been updated!</div>";
		else echo "<div id=\"otherTip\">Update failed. Please try again.</div>";
	}
?>
<div id="setting_nav">
<?php
	switch($settingType){
		case 'profile':
?>
			<span class="subnavLink"><a href="setting.php">Customize</a></span><span class="subnavNormal">Edit Profile</span><span class="subnavLink"><a href="setting.php?t=advanced">Advanced</a></span>
<?php			
			break;
		case 'advanced':
?>
			<span class="subnavLink"><a href="setting.php">Customize</a></span><span class="subnavLink"><a href="setting.php?t=profile">Edit Profile</a></span><span class="subnavNormal">Advanced</span>
<?php		
			break;
		default:
?>
			<span class="subnavNormal">Customize</span><span class="subnavLink"><a href="setting.php?t=profile">Edit Profile</a></span><span class="subnavLink"><a href="setting.php?t=advanced">Advanced</a></span>
<?php	
	} // end switch
?>
</div>
<?php
	switch($settingType){
		case 'profile':
			$user = getTwitter()->veverify();
?>
			<form id="setting_form" action="setting.php?t=profile" method="post">
				<table id="setting_table">
				<tr>
				<td class="setting_title">Name：</td>
				<td><input class="setting_input" type="text" name="name" value="<?php echo isset($user->name) ? $user->name : ''?>" /></td>
				</tr>
				<tr>
				<td class="setting_title">URL：</td>
				<td><input class="setting_input" type="text" name="url" value="<?php echo isset($user->url) ? $user->url : '' ?>" /></td>
				</tr>
				<tr>
				<td class="setting_title">Location：</td>
				<td><input class="setting_input" type="text" name="location" value="<?php echo isset($user->location) ? $user->location : '' ?>" /></td>
				</tr>
				<tr>
				<td class="setting_title">Bio：</td>
				<td><textarea id="setting_text" name="description"><?php echo isset($user->description) ? $user->description : '' ?></textarea><small style="margin-left:5px;vertical-align: top;">*Max 160 chars</small></td>
				</tr>
				<tr>
				<td colspan="2"><input type="submit" class="btn" style="margin-left:62px !important" id="save_button" value="Save"></input></td>
				</tr>
				</table>
				</form>
<?php
			break;
		case 'advanced':
		
?>
		<form id="style_form" action="setting.php?t=advanced" method="post">
			<fieldset class="settings">

			<legend>Proxify</legend>

			<input id="proxify" type="checkbox" />
			<label>Proxify the twitter avatar</label>
			<br />

			</fieldset>
			
			<table>
			<tr>
			<td colspan="2">
			<input type="submit" class="btn" id="save_button" value="Save" />
			</td>
			</tr>
			</table>
		</form>

<?php
			break;
		default:
			if ( isset($_POST['myCSS']) ) {
				try {
					saveStyle($_POST['myCSS'], $_POST['fontsize'], $_POST['bodyBg']);
					echo "<div id=\"otherTip\">Your styles have been updated!</div>";
				} catch (Exception $e) {
					echo "<div id=\"otherTip\">Update failed. Please try again.</div>";
				}
			}
			if ( isset($_GET['reset']) ) {
				resetStyle();
				echo "<div id=\"otherTip\">Your styles have been reseted!</div>";
			}
			if(isset($_POST['updatesInterval'])){
				setcookie('updatesInterval', $_POST['updatesInterval'], $_SERVER['REQUEST_TIME']+3600*24*365, '/');
				setcookie('intervalChanged', 'true', $_SERVER['REQUEST_TIME']+3600*24*365, '/');
			}
			if(isset($_POST['homeInterval'])){
				setcookie('homeInterval', $_POST['updatesInterval'], $_SERVER['REQUEST_TIME']+3600*24*365, '/');
				setcookie('intervalChanged', 'true', $_SERVER['REQUEST_TIME']+3600*24*365, '/');
			}
?>
		<form id="style_form" action="setting.php" method="post">

			<fieldset class="settings">

			<legend>Enhancements</legend>

			<input id="show_pic" type="checkbox" checked="checked" />
			<label>Enable Auto Images Preview</label>
			<small>(Supports mainstream image hostings)</small>

			<br /><br />

			<input id="mediaPreSelect" type="checkbox" />
			<label>Enable Auto Medias Preview</label>
			<small>(Supports Xiami and Tudou)</small><br />

			</fieldset>

			<fieldset class="settings">

			<legend>Auto Refresh Interval</legend>

			<label>Home Page</label>
			<select id="homeInterval" name="homeInterval" value="<?php echo getCookie('homeInterval')?>">
				<option value="0.5">30 sec</option>
				<option value="1" selected="selected">1 min(Default)</option>
				<option value="3">3 min</option>
				<option value="5">5 min</option>
				<option value="10">10 min</option>
				<option value="0">Never</option>
			</select>
			&nbsp;&nbsp;
			<label>Updates Page</label>
			<select id="updatesInterval" name="updatesInterval" value="<?php echo getCookie('updatesInterval')?>">
				<option value="0.5">30 sec</option>
				<option value="1">1 min</option>
				<option value="3" selected="selected">3 min(Default)</option>
				<option value="5">5 min</option>
				<option value="10">10 min</option>
				<option value="0">Never</option>
			</select>

			</fieldset>

			<fieldset class="settings">

			<legend>UI Preferences</legend>

			<label>Custom Themes</label>
			<select id="styleSelect"><option value="n/a">Choose one...</option></select>
			<small>(We have awesome themes for you!)</small>

			<br /><br />

			<label>Background Color</label>
			<input class="bg_input" type="text" id="bodyBg" name="bodyBg" value="<?php echo getColor("bodyBg","") ?>" />
			<small>(Choose your favorite color here)</small>

			<br /><br />

			<label>Font Size</label>
			<select id="fontsize" name="fontsize" value="<?php echo getCookie('fontsize')?>">
				<option value="12px">Small</option>
				<option value="13px" selected="selected">Middle(Default)</option>
				<option value="14px">Large</option>
				<option value="15px">Extra Large</option>
			</select>
			<small>(Set the font size)</small>

			<br /><br />		

			<label>Customize CSS</label>
			<small>(You can put your own CSS hack here, or your Twitter style code)</small>
			<br />
			<label>Tips:</label>
			<small>You must use <a href="http://i.zou.lu/csstidy/" target="_blank" title="Powered by Showfom">CSSTidy</a> to compress your stylesheet.</small>
			<br />
			<textarea type="text" id="myCSS" name="myCSS" value="" /><?php echo getColor("myCSS","") ?></textarea>

			</fieldset>

			<table>
			<tr>
			<td colspan="2">
			<input type="submit" class="btn" id="save_button" value="Save" />
			<a id="reset_link" href="setting.php?reset=true" title="You will lose all customized settings!">Reset to default</a>
			</td>
			</tr>
			</table>

		</form>
<?php
	} // end switch
?>

	</div>
</div>

<?php 
	include ('inc/sidebar.php');
?>

<?php 
	include ('inc/footer.php');
?>
