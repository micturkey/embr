<?php
	/**
	 * @Authot leeio(http://leeiio.me)
	 * @Version 0.1
	 * @Description:转换地址为短地址
	 */
	if(!isset($_SESSION)){
		session_start();
	}
	$urls = array();
	$get_long_urls = array();
	$get_api = 'http://zi.mu/api.php?format=simple&action=shorturl&url=';
	//$get_api = 'http://is.gd/api.php?longurl=';
	//$get_api = 'http://aa.cx/api.php?url=';
	//http://api.bit.ly/shorten?version=2.0.1&login=disinfeqt&apiKey=R_19b002e03a72522c492b453238be5f82&longUrl=
	$long_urls = substr($_POST['long_urls'],0,-1);
	$urls = explode("|",$long_urls);
	$short_urls = "";
	$long_urls_len = count($urls);

	for($i=0;$i<$long_urls_len;$i++){
		$curl = curl_init();
		$url = $get_api.rawurlencode($urls[$i]);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		curl_close($curl);

		$short_urls .= $data.'|'.$urls[$i].'^';
	}
	echo substr($short_urls,0,-1);
?>
