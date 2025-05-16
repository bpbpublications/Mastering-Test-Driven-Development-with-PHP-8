<?php
return [
    'author_plaintext_filepath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'authors.txt',
    'author_csv_filepath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'authors.csv',
    'author_json_filepath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'authors.json',
    'book_plaintext_filepath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'books.txt',
    'book_csv_filepath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'books.csv',
    'book_json_filepath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'books.json',
    'storage_format' => 'txt',
    'db' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'librarian'
    ]
];
