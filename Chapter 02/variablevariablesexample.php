<?php
for($i=0;$i<10;$i++){
	$name = 'twopower' . $i;
	$$name = pow(2,$i);
	echo "$name = {$$name} \n";
}
