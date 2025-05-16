<?php
$r = rand(0,2147483647);

if ($r % 2 == 0) {
	echo "$r is even\n";
} else {
	echo "$r is odd\n";
}
