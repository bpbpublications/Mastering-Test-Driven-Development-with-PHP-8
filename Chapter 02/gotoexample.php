<?php
for ($i=0;$i<1000;$i++){
	$j=0;
	while ($j<1000){
		echo "i = $i and j = $j\n";
		if ($i == 42){
			goto endgame;
		}
		$j++;
	}
}
endgame:
echo "That's all folks!\n";

