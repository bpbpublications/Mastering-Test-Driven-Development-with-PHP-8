<?php
$weakButDynamic = 1;
echo 'First, $weakButDynamic is ' . gettype($weakButDynamic) . "\n";
$weakButDynamic = 1.618;
echo 'Now, $weakButDynamic is ' . gettype($weakButDynamic) . "\n";
$weakButDynamic = "some text";
echo 'Now, $weakButDynamic is ' . gettype($weakButDynamic) . "\n";
$weakButDynamic = false;
echo 'Now, $weakButDynamic is ' . gettype($weakButDynamic) . "\n";
$weakButDynamic = null;
echo 'Now, $weakButDynamic is ' . gettype($weakButDynamic) . "\n";
$weakButDynamic = 2;
echo 'Now, $weakButDynamic is ' . gettype($weakButDynamic) . " again\n"; 
