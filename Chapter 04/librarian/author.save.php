<?php
require 'vendor/autoload.php';
$code = $_POST['code'] ?? 0;
$firstName = $_POST['first_name'] ?? null;
$middleName = $_POST['middle_name'] ?? null;
$lastName = $_POST['last_name'] ?? null;
$message = 'The record has not been recorded';
if ($firstName == null || $middleName == null || $lastName == null){
    $message = 'No data, no recording';
}
if ($code == 0 && saveAuthor($lastName,$middleName,$firstName)) {
    $message = 'Record saved successfully!';
}
if ($code <> 0 && updateAuthor($code,$lastName,$middleName,$firstName)) {
    $message = 'Record updated successfully!';
}
header('location: author.message.php?message=' . base64_encode($message)); 
