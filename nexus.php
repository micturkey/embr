<?php
	include ('lib/twitese.php');
	$title = "Nexus";
	include ('inc/header.php');	
	if (!loginStatus()) header('location: login.php');	
?>
<style>
#analyze {
display: block; 
float: left; 
width: 200px; 
margin-left: 145px; 
margin-top: 20px; 
height: 40px;
background-color:#FFFFFF;
background-image:url("../img/more.gif");
background-position:left top;
background-repeat:repeat-x;
border-color:#DDDDDD #AAAAAA #AAAAAA #DDDDDD;
border-style:solid;
border-width:1px;
color:#666666;
display:block;
font-family:Tahoma,Arial,sans-serif;
font-size:14px;
font-weight:700;
letter-spacing:1px;
line-height:1.5em;
margin-bottom:6px;
outline-style:none;
outline-width:medium;
text-align:center;
text-shadow:1px 1px 1px #FFFFFF;
}
#analyze:hover {
background-position:left -78px;
border:1px solid #BBBBBB;
text-decoration:none;
}
#analyze:active {
background-position:left -38px;
color:#666666;
}
#analyze.loading  {
background-color:transparent;
background-image:url("../img/ajax.gif");
background-position:50% 50%;
background-repeat:no-repeat;
border:medium none;
cursor:default !important;
}
#analyze::-moz-focus-inner {
border:0 none;}
#users  {
box-shadow:0 2px 10px #333333; 
-webkit-box-shadow: 0 2px 10px #333333;
-moz-box-shadow: 0 2px 10px #333333;
background:url("../img/bg-front.gif") repeat-x scroll 0 bottom transparent;
display:block;
float:left;
margin-bottom:10px;
margin-left:25px;
margin-top:25px;
padding:15px 15px 20px 0;
width:480px;
}
#users h2 {
background:url("../img/nexus_logo.png") no-repeat scroll 0 0 transparent;
color:#666666;
height:44px;
margin:0 0 0 15px;
text-indent:-9999em;
width:117px;
}
#descr {display: block; float: left; clear: both; text-shadow: 1px 1px rgb(238, 238, 238); margin: 10px 0 15px 15px; color: rgb(51, 51, 51);font-size:12px}
.at_sym_1 {display: block; float: left; vertical-align: middle; clear: both; margin-top: 7px; font-size: 14px; margin-left: 55px; color: rgb(51, 51, 51);}
.at_sym_2 {display: block; float: left; margin-left: 15px; vertical-align: middle; font-size: 14px; margin-top: 7px; color: rgb(51, 51, 51);}
.and_sym {display: block; float: left; margin-left: 15px; vertical-align: middle; font-weight: bold; font-size: 14px; margin-top: 7px; color: rgb(102, 102, 102);}
.user_input {
border:1px solid #A7A6AA;
color:#666666;
display:block;
float:left;
font-family:tahoma;
font-size:14px;
font-weight:bold;
letter-spacing:1px;
margin-left:5px;
margin-top:3px;
padding:5px;
vertical-align:top;
width:130px;
}
#result {
display:block;
float:left;
width:460px;
height:200px;
margin-left:20px;
margin-top:10px;
}
.intro  {
background:url("../img/nexus_intro.png") no-repeat scroll 0 0 transparent;
}
.sect {
box-shadow:0px 0px 5px #c2c2c2; 
-webkit-box-shadow: 0px 0px 5px #c2c2c2;
-moz-box-shadow: 0px 0px 5px #c2c2c2;
display: block; 
float: left; 
height: 0px; 
margin-left: 15px; 
color: #EEEEEE; 
width: 464px; 
margin-top: 10px;
}
#result h3  {
color:red;
display:block;
font-size:20px;
margin-top:90px;
margin-left:0 !important;
text-align:center;
}
</style>
<script src="js/nexus.js"></script>
<div id="statuses" class="column round-left">
<div class="round" id="users">
<h2>
<span>Nexus</span>
</h2>
<hr class="sect" />
<p id="result" class="intro"></p>
<p id="descr">Rabr Nexus is a tool helps you analyze relationships between you or the others.</p>
<label class="at_sym_1">@</label>
<input type="text" id="user_1" name="user_1" class="user_input" onfocus="this.select()" onmouseover="this.focus()" value="<?php if (isset($_GET['target'])) echo $_GET['target'] ?>" />
<label class="and_sym">and</label>
<label class="at_sym_2">@</label>
<input type="text" id="user_2" name="user_2" class="user_input" onfocus="this.select()" onmouseover="this.focus()" value="<?php if (isset($_GET['source'])) echo $_GET['source'] ?>"/>
<input type="submit" id="analyze" class="round" value="Analyze" />
</div>
</div>

<?php 
	include ('inc/sidebar.php');
?>

<?php 
	include ('inc/footer.php');
?>
