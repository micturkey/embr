<?php
include('common.php');
echo req('https://twitter.com/users/'.$_GET['t'].'_available?'.$_GET['t'].'='.urlencode($_GET['v']));
?>
