<?php
$letter = $argv[1] ?? 'x';

if ($letter == 'a'){
	echo 'archive';
} else {
	if ($letter == 'b') {
		echo 'brief';
	} else {
		if ($letter == 'c') {
			echo 'create';
		} else {
			if ($letter == 'd') {
				echo 'delete';
			} else {
				if ($letter == 'e') {
					echo 'extract';
				} else {
					if ($letter == 'f') {
						echo 'format';
					} else {
						echo 'command not found';
					}
				}
			}
		}
	}
}					
echo "\n";	
