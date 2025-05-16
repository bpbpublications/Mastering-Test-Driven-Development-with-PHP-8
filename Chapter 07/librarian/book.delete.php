<?php
require 'vendor/autoload.php';
$code = $_GET['code'] ?? 0;
$message = 'The record has not been deleted';
if ($code == 0){
    $message = 'It cannot delete what does not exist';
}
if ($code <> 0 && deleteBook($code)) {
    $message = 'Record deleted successfully!';
}
header('location: book.message.php?message=' . base64_encode($message)); 
