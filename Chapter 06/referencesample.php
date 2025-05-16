<?php
$value = 42;
$copyOfValue = $value;
$referenceOfValue = &$value;
$value = 13;
echo "\$value = $value\n";
echo "\$copyOfValue = $copyOfValue\n";
echo "\$referenceOfValue = $referenceOfValue\n";
