<!doctype html>
<html>
<head>
<title>Librarian</title>
</head>
<body>
<p><?=base64_decode($_GET['message'] ?? 'no message')?></p>
<a href="book.list.php">Books</a>
</body>
</html>
