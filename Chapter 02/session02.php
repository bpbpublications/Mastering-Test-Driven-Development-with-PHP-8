<?php
session_start();
$boxContent = $_SESSION['box'];
?>
The box has a <?=$boxContent?>!
