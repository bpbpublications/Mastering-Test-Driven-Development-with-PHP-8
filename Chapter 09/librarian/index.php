<?php
require 'vendor/autoload.php';

$c = isset($_GET['c']) ? $_GET['c'] : 'index';
$action = isset($_GET['a']) ? $_GET['a'] : 'index';

$controller = 'Librarian\Controller\\' . ucfirst($c);

(new $controller())->run($action);