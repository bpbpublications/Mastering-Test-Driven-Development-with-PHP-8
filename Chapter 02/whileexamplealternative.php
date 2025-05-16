<?php

$sum = 0;
$iteractions = 0;

while ($sum < 5000):
	$sum+=101;
	$iteractions++;
endwhile;
echo "\$sum is $sum after $iteractions iteractions\n";
