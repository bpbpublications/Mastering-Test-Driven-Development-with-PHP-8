<?php
require 'vendor/autoload.php';
prepareFile('author');
?>
<!doctype html>
<html>
<head>
<title>Librarian</title>
</head>
<body>
<h1>Authors</h1>
<a href="author.edit.php">Add an author</a>
<table>
<thead>
<tr>
<th>code</th>
<th>name</th>
<th>action</th>
</tr>
</thead>
<tbody>
<?=listAuthorsInTable()?>    
</tbody>            
</table>
<a href="index.php">Homepage</a>
</body>
</html>    
