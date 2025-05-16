<?php
declare(ticks=4);

$GLOBALS['counter'] = 0;

function tickHandler()
{
	$GLOBALS['counter']++;	
}

register_tick_function('tickHandler');

$ticks = 0;
while ($GLOBALS['counter'] < 1000) {
	echo "one\n";
	echo "two\n";
	$ticks+=3;	
}
echo "$ticks ticks\n";
echo $GLOBALS['counter'] . " calls to tickHandler\n";
