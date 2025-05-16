<?php
$uri = $_SERVER['REQUEST_URI'];
$parts = explode('/',$uri);
if ($parts[1] == ''){
    return false;
}
$fileName = explode('?',$parts[1])[0];
if ($fileName !== 'index.php'){
    header("HTTP/1.1 404 Not Found");
    echo "404 Not Found";
    exit;
}
return false;