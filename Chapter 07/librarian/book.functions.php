<?php
use Librarian\Util\Config;
use Librarian\Model\Filesystem\AuthorPlainText;
use Librarian\Model\Filesystem\AuthorCSV;
use Librarian\Model\Filesystem\AuthorJSON;
const AUTHOR_ROW_LENGTH = 65;
const BOOK_ROW_LENGTH = 89;

require_once 'vendor/autoload.php';
require_once 'txt.functions.php';
require_once 'csv.functions.php';
require_once 'json.functions.php';
require_once 'database.functions.php';
require_once 'collection.functions.php';

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
    $storageFormat = Config::get('storage_format');
    $suffix = ['rdb' => 'Database', 'ddb' => 'Collection'];
    if ($storageFormat == 'rdb' || $storageFormat == 'ddb'){
        $call = 'saveAuthorIn' . $suffix[$storageFormat];
        return $call($lastName, $middleName, $firstName);
    }
    $suffix = ['txt' => 'PlainText', 'csv' => 'CSV', 'json' => 'JSON'];
    $driverName = 'Librarian\Model\Filesystem\Author' . $suffix[$storageFormat];
    $authorDriver = new $driverName();
    $saved = $authorDriver->save($lastName, $middleName, $firstName);
    return $saved;
}

function listAuthorsInTable()
{
    $storageFormat = Config::get('storage_format');
    $authors = [];
    $suffix = ['rdb' => 'Database', 'ddb' => 'Collection'];
    if ($storageFormat == 'rdb' || $storageFormat == 'ddb'){
        $call = 'readAuthorsIn' . $suffix[$storageFormat];
        $authors = $call();
    }
    $suffix = ['txt' => 'PlainText', 'csv' => 'CSV', 'json' => 'JSON'];
    if ($storageFormat == 'txt' || $storageFormat == 'csv' || $storageFormat == 'json'){
        prepareFile('author');
        $driverName = 'Librarian\Model\Filesystem\Author' . $suffix[$storageFormat];
        $authorDriver = new $driverName();
        $authors = $authorDriver->readAll();
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

function listAuthorsForSelect($code)
{
    $storageFormat = Config::get('storage_format');
    $authors = [];
    $suffix = ['rdb' => 'Database', 'ddb' => 'Collection'];
    if ($storageFormat == 'rdb' || $storageFormat == 'ddb'){
        $call = 'readAuthorsIn' . $suffix[$storageFormat];
        $authors = $call($lastName, $middleName, $firstName);
    }
    $suffix = ['txt' => 'PlainText', 'csv' => 'CSV', 'json' => 'JSON'];
    if ($storageFormat == 'txt' || $storageFormat == 'csv' || $storageFormat == 'json'){
        prepareFile('author');
        $driverName = 'Librarian\Model\Filesystem\Author' . $suffix[$storageFormat];
        $authorDriver = new $driverName();
        $authors = $authorDriver->readAll();
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
    $storageFormat = Config::get('storage_format');

    $author = [];
    $suffix = ['rdb' => 'Database', 'ddb' => 'Collection'];
    if ($storageFormat == 'rdb' || $storageFormat == 'ddb'){
        $call = 'readAuthorIn' . $suffix[$storageFormat] . 'ByCode';
        $author = $call($code);
    }
    $suffix = ['txt' => 'PlainText', 'csv' => 'CSV', 'json' => 'JSON'];
    if ($storageFormat == 'txt' || $storageFormat == 'csv' || $storageFormat == 'json'){
        prepareFile('author');
        $driverName = 'Librarian\Model\Filesystem\Author' . $suffix[$storageFormat];
        $authorDriver = new $driverName();
        $author = $authorDriver->readByCode($code);
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
    $storageFormat = Config::get('storage_format');

    $data = [
        'last_name' => $lastName,
        'middle_name' => $middleName,
        'first_name' => $firstName
    ];
    $saved = false;
    $suffix = ['rdb' => 'Database', 'ddb' => 'Collection'];
    if ($storageFormat == 'rdb' || $storageFormat == 'ddb'){
        $call = 'updateAuthorIn' . $suffix[$storageFormat];
        return $call($code, $data);
    }
    $suffix = ['txt' => 'PlainText', 'csv' => 'CSV', 'json' => 'JSON'];
    prepareFile('author');
    $driverName = 'Librarian\Model\Filesystem\Author' . $suffix[$storageFormat];
    $authorDriver = new $driverName();
    return $authorDriver->update($code, $data);
}

function deleteAuthor($code)
{
    $storageFormat = Config::get('storage_format');

    $suffix = ['rdb' => 'Database', 'ddb' => 'Collection'];
    if ($storageFormat == 'rdb' || $storageFormat == 'ddb'){
        $call = 'deleteAuthorIn' . $suffix[$storageFormat];
        return $call($code);
    }
    $suffix = ['txt' => 'PlainText', 'csv' => 'CSV', 'json' => 'JSON'];
    prepareFile('author');
    $driverName = 'Librarian\Model\Filesystem\Author' . $suffix[$storageFormat];
    $authorDriver = new $driverName();
    return $authorDriver->delete($code);
}

//book
function saveBook($title, $authorCode)
{
    $storageFormat = Config::get('storage_format');

    $suffix = ['rdb' => 'Database', 'ddb' => 'Collection'];
    if ($storageFormat == 'rdb' || $storageFormat == 'ddb'){
        $call = 'saveBookIn' . $suffix[$storageFormat];
        return $call($title, $authorCode);
    }
    $suffix = ['txt' => 'PlainText', 'csv' => 'CSV', 'json' => 'JSON'];
    prepareFile('author');
    $driverName = 'Librarian\Model\Filesystem\Book' . $suffix[$storageFormat];            
    $bookDriver = new $driverName();
    return $bookDriver->save($title, $authorCode);
}

function listBooksInTable()
{
    $storageFormat = Config::get('storage_format');
    $books = [];
    $suffix = ['rdb' => 'Database', 'ddb' => 'Collection'];
    if ($storageFormat == 'rdb' || $storageFormat == 'ddb'){
        $call = 'readBooksIn' . $suffix[$storageFormat];
        $books = $call();
    }
    $suffix = ['txt' => 'PlainText', 'csv' => 'CSV', 'json' => 'JSON'];
    if ($storageFormat == 'txt' || $storageFormat == 'csv' || $storageFormat == 'json'){
        prepareFile('book');
        $driverName = 'Librarian\Model\Filesystem\Book' . $suffix[$storageFormat];            
        $bookDriver = new $driverName();
        $books = $bookDriver->readAll();
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
    $storageFormat = Config::get('storage_format');
    $suffix = ['rdb' => 'Database', 'ddb' => 'Collection'];
    if ($storageFormat == 'rdb' || $storageFormat == 'ddb'){
        $call = 'readBookIn' . $suffix[$storageFormat] . 'ByCode';
        $book = $call($code);
    }
    $suffix = ['txt' => 'PlainText', 'csv' => 'CSV', 'json' => 'JSON'];
    if ($storageFormat == 'txt' || $storageFormat == 'csv' || $storageFormat == 'json'){
        prepareFile('author');
        prepareFile('book');
        $driverName = 'Librarian\Model\Filesystem\Book' . $suffix[$storageFormat];            
        $bookDriver = new $driverName();
        $book = $bookDriver->readByCode($code);
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
    $storageFormat = Config::get('storage_format');

    $data = [
        'title' => $title,
        'author_code' => $authorCode
    ];
    $suffix = ['rdb' => 'Database', 'ddb' => 'Collection'];
    if ($storageFormat == 'rdb' || $storageFormat == 'ddb'){
        $call = 'updateBookIn' . $suffix[$storageFormat];
        return $call($code, $data);
    }
    $suffix = ['txt' => 'PlainText', 'csv' => 'CSV', 'json' => 'JSON'];
    prepareFile('author');
    prepareFile('book');            
    $driverName = 'Librarian\Model\Filesystem\Book' . $suffix[$storageFormat];            
    $bookDriver = new $driverName();
    return $bookDriver->update($code, $data);
}

function deleteBook($code)
{
    $storageFormat = Config::get('storage_format');

    $suffix = ['rdb' => 'Database', 'ddb' => 'Collection'];
    if ($storageFormat == 'rdb' || $storageFormat == 'ddb'){
        $call = 'deleteBookIn' . $suffix[$storageFormat];
        return $call($code);
    }
    $suffix = ['txt' => 'PlainText', 'csv' => 'CSV', 'json' => 'JSON'];
    prepareFile('author');
    prepareFile('book');            
    $driverName = 'Librarian\Model\Filesystem\Book' . $suffix[$storageFormat];            
    $bookDriver = new $driverName();
    return $bookDriver->delete($code);
}

function getPathForFile(string $entity)
{
    $storageFormat = Config::get('storage_format');
    $path = '';
    switch($storageFormat){
        case 'txt':
            $path = Config::get($entity . '_plaintext_filepath');
            break;
        case 'csv':
            $path = Config::get($entity . '_csv_filepath');
            break;
        case 'json':
            $path = Config::get($entity . '_json_filepath');
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
    $storageFormat = Config::get('storage_format');

    $path = '';
    switch($storageFormat){
        case 'txt':
        case 'csv':
        case 'json':
            unlink(getPathForFile($entity));
            break;
        case 'rdb':
            truncateTable($entity . 's');
            break;
        case 'ddb':
            dropCollection($entity . 's');            
    }
}

