<?php
$letter = $argv[1] ?? 'x';

switch ($letter){
	case 'a':
		echo 'archive'; break;
	case 'b':
		echo 'brief'; break;
	case 'c':
		echo 'create'; break;
	case 'd':
		echo 'delete'; break;
	case 'e':
		echo 'extract'; break;
	case 'f':
		echo 'format'; break;
	default:
		echo 'command not found';
}
echo "\n";	
