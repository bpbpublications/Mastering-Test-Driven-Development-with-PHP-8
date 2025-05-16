<!doctype html>
<html>
<head>
<title>Librarian</title>
</head>
<body>
<p><?=base64_decode($_GET['message'] ?? 'no message')?></p>
<a href="author.list.php">Authors</a>
</body>
</html>
