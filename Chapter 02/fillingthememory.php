<?php
$i = 0;
while (true) {
	$newVariable = "variable$i";
	$$newVariable = $i;
	$i++;
	echo 'Allocated memory: ' . memory_get_usage() . " bytes\n";  
}
