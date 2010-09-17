<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	if (isset($_FILES["image"])) {
		$image = $_FILES["image"]['tmp_name'];
		$result = imageUpload($image);
		if (isset($result->url)) {
			echo '{"result": "success" , "url" : "' . $result->url . '"}';
		} else {
			echo '{"result": "error"}';
		}
	}

?>
