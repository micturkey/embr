<?php
include('common.php');
header('Content-Type: image/jpeg');
echo req('https://api-secure.recaptcha.net/image?c='.$_GET['c']);
?>