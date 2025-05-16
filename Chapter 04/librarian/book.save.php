<?php
require 'vendor/autoload.php';
$code = $_POST['code'] ?? 0;
$title = $_POST['title'] ?? null;
$authorCode = $_POST['author_code'] ?? null;
$message = 'The record has not been recorded';
if ($title == null || $authorCode == null){
    $message = 'No data, no recording';
}
if ($code == 0 && saveBook($title,$authorCode)) {
    $message = 'Record saved successfully!';
}
if ($code <> 0 && updateBook($code,$title,$authorCode)) {
    $message = 'Record updated successfully!';
}
header('location: book.message.php?message=' . base64_encode($message)); 
