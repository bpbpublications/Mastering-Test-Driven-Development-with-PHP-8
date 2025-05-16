<?php
$letter = $argv[1] ?? 'x';

echo match($letter) {
	'a' => 'archive',
	'b' => 'brief',
	'c' => 'create',
	'd' => 'delete',
	'e' => 'extract',
	'f' => 'format',
	default => 'command not found'
};
echo "\n";	
