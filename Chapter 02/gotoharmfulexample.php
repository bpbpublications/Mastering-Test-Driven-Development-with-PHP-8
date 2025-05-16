<?php
for ($i=0;$i<1000;$i++){
backtogame:
	$j=0;
	while ($j<1000){
		echo "i = $i and j = $j\n";
		$j++;
	}
}
goto backtogame;
echo "That's all folks!\n";

