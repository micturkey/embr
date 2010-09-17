<?php
$online_log="rabr_online_users.txt";
$timeout=300;
$entries=file($online_log);
$temp=array();
for($i=0;$i<count($entries);$i++){
$entry=explode(",",trim($entries[$i]));
if(($entry[0]!=getenv('REMOTE_ADDR'))&&($entry[1]>$_SERVER['REQUEST_TIME'])){
array_push($temp,$entry[0].",".$entry[1]."\n");
}
}
array_push($temp,getenv('REMOTE_ADDR').",".($_SERVER['REQUEST_TIME']+($timeout))."\n");
$users_online=count($temp);
$entries=implode("",$temp);
$fp=fopen($online_log,"w");
flock($fp,LOCK_EX);
fputs($fp,$entries);
flock($fp,LOCK_UN);
fclose($fp);
if($users_online==1){
echo "Just you online!";
}else{
echo $users_online."  Users Online";
}
?>