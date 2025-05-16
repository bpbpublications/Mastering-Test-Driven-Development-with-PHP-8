<?php
require 'vendor/autoload.php';
prepareFile('book');
?>
<!doctype html>
<html>
<head>
<title>Librarian</title>
</head>
<body>
<h1>Books</h1>
<a href="book.edit.php">Add a book</a>
<table>
<thead>
<tr>
<th>code</th>
<th>title</th>
<th>author</th>
<th>action</th>
</tr>
</thead>
<tbody>
<?=listBooksInTable()?>    
</tbody>
</table>
<a href="index.php">Homepage</a>
</body>
</html>    
