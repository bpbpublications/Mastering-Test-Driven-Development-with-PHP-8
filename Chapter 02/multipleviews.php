<?php
$type = $_GET['type'] ?? 'text/html';
$type = $type == 'json' ? 'application/json' : $type; 
header('Content-Type:' . $type);
echo '{"message" :  "How do you see this content now?"}';
