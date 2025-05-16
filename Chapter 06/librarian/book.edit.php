<?php
require 'vendor/autoload.php';
$code = $_GET['code'] ?? 0;
$book = getBookByCode($code);
?>
<!doctype html>
<html>
<head>
<title>Librarian</title>
</head>
<body>
<h1>Book</h1>
<form method="post" action="book.save.php">
<label for="title">Title:</label>
<input type="text" name="title" value="<?=$book['title']?>"><br/>
<label for="author_code">Author:</label>
<select name="author_code">
<?=listBooksForSelect($book['author_code'] ?? 0)?>
</select><br/>
<input type="hidden" name="code" value="<?=$code?>"><br/>
<input type="submit" value="save">
</form>    
</body>    
</html>
