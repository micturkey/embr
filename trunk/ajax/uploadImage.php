<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	if (isset($_FILES['image'])) {
		$image = "@{$_FILES['image']['tmp_name']};type={$_FILES['image']['type']};filename={$_FILES['image']['name']}";
		switch($_GET['do']) {
			case 'image':
			//$image = $_FILES["image"]['tmp_name'];
			$result = imageUpload($image);
			if (isset($result->url)) {
				echo '{"result": "success" , "url" : "' . $result->url . '"}';
			} else {
				echo '{"result": "error"}';
			}
			break;
			case 'profile':
			$t = getTwitter();
			$result = $t->updateProfileImage($image);
			if ($result->http_code == 200) {
				echo '{"result": "success"}';
			} else {
				echo '{"result": "error"}';
			}
			break;
		}
	}
?>
