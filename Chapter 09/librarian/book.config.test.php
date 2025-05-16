<?php
return [
    'author_plaintext_filepath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'authors.test.txt',
    'author_csv_filepath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'authors.test.csv',
    'author_json_filepath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'authors.test.json',
    'book_plaintext_filepath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'books.test.txt',
    'book_csv_filepath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'books.test.csv',
    'book_json_filepath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'books.test.json',
    'storage_format' => 'txt',
    'db' => [
        'dsn' => 'mysql:dbname=librarian_test;host=localhost',
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'mysql',
        'database' => 'librarian'
    ]
];
