<?php
require 'vendor/autoload.php';
$code = $_GET['code'] ?? 0;
$author = getAuthorByCode($code);
?>
<!doctype html>
<html>
<head>
<title>Librarian</title>
</head>
<body>
<h1>Author</h1>
<form method="post" action="author.save.php">
<label for="first_name">First name:</label>
<input type="text" name="first_name" value="<?=$author->firstName?>"><br/>
<label for="middle_name">Middle name:</label>
<input type="text" name="middle_name" value="<?=$author->middleName?>"><br/>
<label for="last_name">Last name:</label>
<input type="text" name="last_name" value="<?=$author->lastName?>"><br/>
<input type="hidden" name="code" value="<?=$code?>"><br/>
<input type="submit" value="save">
</form>    
</body>    
</html>
