<?php

function benchmark($f, $n = 100000){
	$start = microtime(true);
	for($i = 0; $i < $n; $i++){
		$f();
	}
	$end = microtime(true);
	return $end - $start;
}

?>