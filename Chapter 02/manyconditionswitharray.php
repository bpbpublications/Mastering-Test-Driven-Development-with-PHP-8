<?php
$letter = $argv[1] ?? 'x';

$commands = [
	'a' => 'archive',
	'b' => 'brief',
	'c' => 'create',
	'd' => 'delete',
	'e' => 'extract',
	'f' => 'format',
	'x' => 'command not found'
];

echo $commands[$letter];
echo "\n";	
