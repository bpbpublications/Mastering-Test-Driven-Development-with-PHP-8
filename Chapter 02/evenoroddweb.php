<!doctype html>
<html>
<body>
<p>
<?php
$r = rand(0,2147483647);

if ($r % 2 == 0) {
?>
<span style="color:blue">	
<?php
	echo "$r is even\n";
} else {
?>
<span style="color:red">
<?php
	echo "$r is odd\n";
}
?>
</span>
</p>
</body>
</html>
