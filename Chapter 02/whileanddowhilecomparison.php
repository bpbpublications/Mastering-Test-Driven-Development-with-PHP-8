<?php

$sum = 5000; $iteractions = 0;

do {
	$sum+=101;
	$iteractions++;
} while ($sum < 5000);
echo "\$sum is $sum after $iteractions iteractions\n";

$sum = 5000; $iteractions = 0;

while ($sum < 5000) {
	$sum+=101;
	$iteractions++;
}
echo "\$sum is $sum after $iteractions iteractions\n";
