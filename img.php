<?php
function geExtname($url) 
{ 
$path=parse_url($url); 
$str=explode('.',$path['path']); 
return $str[1]; 
} 

if(isset($_GET['imgurl']))
{
	$url = $_GET['imgurl'];

	$ch = curl_init($url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
	$ret = curl_exec($ch);
	$Httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if($Httpcode == '200')
	{
	$img_header = "Content-type: image/".geExtname($url);
	Header($img_header);
	echo $ret; //���ͼ��
	}
	else
	{
		echo '��ȡͼƬ����!����Http����Ϊ:'.$Httpcode;
	}
}
?>