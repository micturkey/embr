<?php
function req($url, $data=false, $cert=false) {
	$c = curl_init();
	curl_setopt_array($c, array(
		CURLOPT_URL => $url,
		CURLOPT_HTTPHEADER => array( 'User-Agent: Mozilla/5.0 (Windows NT 5.1; U; zh-cn; rv:1.8.1) Gecko/20091102 Firefox/3.5.5' ),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_FRESH_CONNECT => true,
		CURLOPT_DNS_USE_GLOBAL_CACHE => true
	));
	if (!strpos($url, 'https')) {
		if ($cert) {
			curl_setopt_array($c, array(
				CURLOPT_SSL_VERIFYPEER => true,
				CURLOPT_SSL_VERIFYHOST => 2,
				CURLOPT_CAINFO, getcwd().$cert
			));
		} else {
			curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
		}
	}
	if ($data) {
		curl_setopt_array($c, array(
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $data
		));
	}
	$r = curl_exec($c);
	curl_close($c);
	return $r;
}
?>