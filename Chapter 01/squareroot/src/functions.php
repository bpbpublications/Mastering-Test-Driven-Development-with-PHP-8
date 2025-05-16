<?php
function square_root($number, $iterations = 100)
{
	$p = $number;
	$n = $iterations;

	$x = ((1 + $p)/2);

	for ($i=1;$i<$n;$i++){
		$x = ((($p/$x) + $x)/2);
	}
	return $x;
}
