<?php

$sum = 0;
$iteractions = 0;

while ($sum < 5000) {
	$sum+=101;
	$iteractions++;
}
echo "\$sum is $sum after $iteractions iteractions\n";
