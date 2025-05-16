<?php
const AUTHOR_ROW_LENGTH = 65;
const BOOK_ROW_LENGTH = 89;

require_once 'txt.functions.php';
require_once 'csv.functions.php';
require_once 'json.functions.php';
require_once 'database.functions.php';

/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
function getConfig()
{
    $config = require 'book.config.php';
    return $config;
}

function formatField($field, int $length)
{
    return str_pad($field, $length, ' ', STR_PAD_LEFT);
}

//author
function saveAuthor($lastName, $middleName, $firstName)
{
    $storageFormat = getConfig()['storage_format'];

    $saved = false;
    switch($storageFormat){
        case 'txt':
            $saved = saveAuthorInPlainText($lastName, $middleName, $firstName);
            break;
        case 'csv':
            $saved = saveAuthorInCSV($lastName, $middleName, $firstName);
            break;
        case 'json':
            $saved = saveAuthorInJSON($lastName, $middleName, $firstName);
            break;
        case 'rdb':
            $saved = saveAuthorInDatabase($lastName, $middleName, $firstName);    
    }
    return $saved;
}

function listAuthorsInTable()
{
    $storageFormat = getConfig()['storage_format'];
    $authors = [];
    switch($storageFormat){
        case 'txt':
            prepareFile('author');
            $authors = readAuthorsInPlainText();
            break;
        case 'csv':
            prepareFile('author');
            $authors = readAuthorsInCSV();
            break;
        case 'json':
            prepareFile('author');
            $authors = readAuthorsInJSON();
            break;
        case 'rdb':
            $authors = readAuthorsInDatabase();            
    }
    $html = '';
    foreach($authors as $author){
        $html.='<tr>';
        $html.='<td><a href="author.edit.php?code=' . $author['code'] . '">' . $author['code'] . '</a><td>';
        $html.="<td>{$author['first_name']} {$author['middle_name']} {$author['last_name']}<td>";
        $html.='<td><a href="author.delete.php?code=' . $author['code'] . '">remove</a><td>';
        $html.='</tr>';
    }
    return $html;
}

function listBooksForSelect($code)
{
    $storageFormat = getConfig()['storage_format'];
    $authors = [];
    switch($storageFormat){
        case 'txt':
            $authors = readAuthorsInPlainText();
            break;
        case 'csv':
            $authors = readAuthorsInCSV();
            break;
        case 'json':
            $authors = readAuthorsInJSON();
            break;
        case 'rdb':
            $authors = readAuthorsInDatabase();
    }
    $html = '';
    foreach($authors as $author){
        $authorName = $author['first_name'] . ' ' . $author['middle_name'] . ' ' . $author['last_name'];
        $html.='<option value="' . $author['code'] . '"' . ($author['code'] == $code ? ' selected ' : '') . '>' . $authorName . '</option>';
    }
    return $html;
}

function getAuthorByCode($code)
{
    $storageFormat = getConfig()['storage_format'];

    $author = [];
    switch($storageFormat){
        case 'txt':
            prepareFile('author');
            $author = readAuthorInPlainTextByCode($code);
            break;
        case 'csv':
            prepareFile('author');
            $author = readAuthorInCSVByCode($code);
            break;
        case 'json':
            prepareFile('author');
            $author = readAuthorInJSONByCode($code);
            break;
        case 'rdb':
            $author = readAuthorInDatabaseByCode($code);
    }
    if (empty($author)){
        $author = [
            'first_name' => '',
            'middle_name' => '',
            'last_name' => ''
        ];
    }
    return $author;
}

function updateAuthor($code, $lastName, $middleName, $firstName)
{
    $storageFormat = getConfig()['storage_format'];

    $data = [
        'last_name' => $lastName,
        'middle_name' => $middleName,
        'first_name' => $firstName
    ];

    $saved = false;
    switch($storageFormat){
        case 'txt':
            $saved = updateAuthorInPlainText($code, $data);
            break;
        case 'csv':
            $saved = updateAuthorInCSV($code, $data);
            break;
        case 'json':
            $saved = updateAuthorInJSON($code, $data);
            break;
        case 'rdb':
            $saved = updateAuthorInDatabase($code, $data);
    }
    return $saved;
}

function deleteAuthor($code)
{
    $storageFormat = getConfig()['storage_format'];

    $deleted = false;
    switch($storageFormat){
        case 'txt':
            $deleted = deleteAuthorInPlainText($code);
            break;
        case 'csv':
            $deleted = deleteAuthorInCSV($code);
            break;
        case 'json':
            $deleted = deleteAuthorInJSON($code);
            break;
        case 'rdb':
            $deleted = deleteAuthorInDatabase($code);
    }
    return $deleted;
}

//book
function saveBook($title, $authorCode)
{
    $storageFormat = getConfig()['storage_format'];

    $saved = false;
    switch($storageFormat){
        case 'txt':
            $saved = saveBookInPlainText($title, $authorCode);
            break;
        case 'csv':
            $saved = saveBookInCSV($title, $authorCode);
            break;
        case 'json':
            $saved = saveBookInJSON($title, $authorCode);
            break;
        case 'rdb':
            $saved = saveBookInDatabase($title, $authorCode);

    }
    return $saved;
}

function listBooksInTable()
{
    $storageFormat = getConfig()['storage_format'];
    $books = [];
    switch($storageFormat){
        case 'txt':
            prepareFile('book');            
            $books = readBooksInPlainText();
            break;
        case 'csv':
            prepareFile('book');            
            $books = readBooksInCSV();
            break;
        case 'json':
            prepareFile('book');            
            $books = readBooksInJSON();
            break;
        case 'rdb':
            $books = readBooksInDatabase();
    }
    $html = '';
    foreach($books as $book){
        $html.='<tr>';
        $html.='<td><a href="book.edit.php?code=' . $book['code'] . '">' . $book['code'] . '</a><td>';
        $html.="<td>{$book['title']}<td>";
        $author = getAuthorByCode($book['author_code']);
        $authorName = $author['first_name'] . ' ' . $author['middle_name'] . ' ' . $author['last_name'];
        $html.='<td>' . $authorName . '<td>';
        $html.='<td><a href="book.delete.php?code=' . $book['code'] . '">remove</a><td>';
        $html.='</tr>';
    }
    return $html;
}

function getBookByCode($code)
{
    $storageFormat = getConfig()['storage_format'];

    $book = [];
    switch($storageFormat){
        case 'txt':
            prepareFile('author');
            prepareFile('book');
            $book = readBookInPlainTextByCode($code);
            break;
        case 'csv':
            prepareFile('author');
            prepareFile('book');            
            $book = readBookInCSVByCode($code);
            break;
        case 'json':
            prepareFile('author');
            prepareFile('book');            
            $book = readBookInJSONByCode($code);
            break;
        case 'rdb':
            $book = readBookInDatabaseByCode($code);            
    }
    if (empty($book)){
        $book = [
            'title' => '',
            'author_code' => 0
        ];
    }
    return $book;
}

function updateBook($code, $title, $authorCode)
{
    $storageFormat = getConfig()['storage_format'];

    $data = [
        'title' => $title,
        'author_code' => $authorCode
    ];

    $saved = false;
    switch($storageFormat){
        case 'txt':
            $saved = updateBookInPlainText($code, $data);
            break;
        case 'csv':
            $saved = updateBookInCSV($code, $data);
            break;
        case 'json':
            $saved = updateBookInJSON($code, $data);
            break;
        case 'rdb':
            $saved = updateBookInDatabase($code, $data);
    }
    return $saved;
}

function deleteBook($code)
{
    $storageFormat = getConfig()['storage_format'];

    $deleted = false;
    switch($storageFormat){
        case 'txt':
            $deleted = deleteBookInPlainText($code);
            break;
        case 'csv':
            $deleted = deleteBookInCSV($code);
            break;
        case 'json':
            $deleted = deleteBookInJSON($code);
            break;
        case 'rdb':
            $deleted = deleteBookInDatabase($code);
    }
    return $deleted;
}

function getPathForFile(string $entity)
{
    $storageFormat = getConfig()['storage_format'];

    $path = '';
    switch($storageFormat){
        case 'txt':
            $path = getConfig()[$entity . '_plaintext_filepath'];
            break;
        case 'csv':
            $path = getConfig()[$entity . '_csv_filepath'];
            break;
        case 'json':
            $path = getConfig()[$entity . '_json_filepath'];
    }
    return $path;
}

function prepareFile(string $entity)
{
    $path = getPathForFile($entity);
    if (!file_exists($path)){
        $handle = fopen($path,'w');
        fclose($handle);
    }
}

function replaceConfigFileContent(string $search, string $replace)
{
    $configPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'book.config.php';
    $content = file_get_contents($configPath);
    $content = str_replace($search, $replace, $content);
    file_put_contents($configPath, $content);
}

function clearEntity(string $entity)
{
    $storageFormat = getConfig()['storage_format'];

    $path = '';
    switch($storageFormat){
        case 'txt':
        case 'csv':
        case 'json':
            unlink(getPathForFile($entity));
            break;
        case 'rdb':
            truncateTable($entity . 's');
    }
}

