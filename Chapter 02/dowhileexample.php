<?php

$sum = 0;
$iteractions = 0;

do {
	$sum+=101;
	$iteractions++;
} while ($sum < 5000);
echo "\$sum is $sum after $iteractions iteractions\n";
