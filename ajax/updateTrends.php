<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	$t = getTwitter();
	$trends = $t->trends();

	if (count($trends) == 0) {
		echo "empty";
	}else{
		$html = '';
		foreach ($trends->trends as $trend) {
			$li = '
				<li>
				<a href="search.php?q='.rawurlencode($trend->name).'">'.$trend->name.'</a>
				</li>
				';
			$html .= $li;
		}
		echo $html;
	}
?>
