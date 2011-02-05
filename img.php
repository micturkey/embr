<?php
if(isset($_GET['imgurl']))
{
	$url = $_GET['imgurl'];

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,TRUE); //301&302
	$ret = curl_exec($ch);
	$Httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$Hsize = curl_getinfo($ch,CURLINFO_HEADER_SIZE);
	curl_close($ch);
	if($Httpcode == '200')
	{
		$header = substr($ret,0,$Hsize);
		$pat = '/(Content-Type:\s?image\/\w+)/i';
		$matchRet = preg_match_all($pat,$header,$m);
		if($matchRet)
		{
			$header = $m[0][0];
			$ret = substr($ret,$Hsize);
			Header($header);
			echo $ret;
		}
		else
		{
			echo 'image not found';
		}
	}
	else
	{
		echo 'image loading error, code: '.$Httpcode;
	}
}
?>