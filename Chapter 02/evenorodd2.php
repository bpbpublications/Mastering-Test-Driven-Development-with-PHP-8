<?php
$r = rand(0,2147483647);

$kind = 'odd';

if ($r % 2 == 0) {
	$kind = 'even';
}
echo "$r is $kind\n";

